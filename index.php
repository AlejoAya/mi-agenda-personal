<?php
require_once __DIR__ . '/includes/functions.php'; auth_required();
require_once __DIR__ . '/config/database.php';
$q = trim((string)($_GET['q'] ?? '')); if (mb_strlen($q) > 120) $q = mb_substr($q, 0, 120);
$sort = (string)($_GET['orden'] ?? 'nombre');
$sorts = ['nombre'=>'c.nombre ASC, c.id DESC','ciudad'=>'c.ciudad ASC, c.nombre ASC','recientes'=>'c.fecha_creacion DESC, c.id DESC','antiguos'=>'c.fecha_creacion ASC, c.id ASC'];
if (!isset($sorts[$sort])) $sort = 'nombre';
$onlyFavorites = ($_GET['favoritos'] ?? '') === '1';
$sql = 'SELECT c.id, c.nombre, c.telefono, c.correo, c.ciudad, c.favorito, c.fecha_creacion, cat.nombre AS categoria FROM contactos c JOIN categorias cat ON cat.id = c.id_categoria WHERE c.id_usuario = ?';
$params = [$_SESSION['user_id']];
if ($q !== '') { $sql .= ' AND (c.nombre LIKE ? OR c.correo LIKE ? OR c.ciudad LIKE ?)'; $term = '%' . $q . '%'; array_push($params,$term,$term,$term); }
if ($onlyFavorites) $sql .= ' AND c.favorito = 1';
$sql .= ' ORDER BY ' . $sorts[$sort]; // Lista blanca de columnas permitidas
$stmt = $pdo->prepare($sql); $stmt->execute($params); $contacts = $stmt->fetchAll();
$pageTitle = 'Mis contactos'; include __DIR__ . '/includes/header.php'; ?>
<section class="hero"><div><div class="eyebrow">TU ESPACIO PERSONAL</div><h1>Hola, <?= e($_SESSION['user_name']) ?> 👋</h1><p>Cuenta: <?= e($_SESSION['user_email']) ?></p><p class="muted">Administra tus contactos de forma sencilla y privada.</p></div><a class="btn" href="contact_create.php">+ Nuevo contacto</a></section>
<section class="card"><div class="sectionhead"><h2>Mis contactos</h2><span class="count"><?= count($contacts) ?> registrados</span></div>
<form method="get" class="filters"><label>Buscar por nombre, correo o ciudad<input name="q" value="<?= e($q) ?>" placeholder="Buscar contacto"></label><label>Ordenar por<select name="orden"><option value="nombre" <?= $sort === 'nombre' ? 'selected' : '' ?>>Nombre</option><option value="ciudad" <?= $sort === 'ciudad' ? 'selected' : '' ?>>Ciudad</option><option value="recientes" <?= $sort === 'recientes' ? 'selected' : '' ?>>Más recientes</option><option value="antiguos" <?= $sort === 'antiguos' ? 'selected' : '' ?>>Más antiguos</option></select></label><label class="checkline"><input type="checkbox" name="favoritos" value="1" <?= $onlyFavorites ? 'checked' : '' ?>> ⭐ Solo favoritos</label><button class="btn" type="submit">Aplicar</button><a class="smallbtn" href="index.php">Limpiar</a></form><?php if (!$contacts): ?><div class="empty"><div class="emptyicon">☷</div><h3>Aún no tienes contactos</h3><p>Agrega tu primer contacto para comenzar.</p><a class="btn" href="contact_create.php">Agregar contacto</a></div>
<?php else: ?><div class="tablewrap"><table><thead><tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Ciudad</th><th>Categoría</th><th>Favorito</th><th>Creado</th><th>Acciones</th></tr></thead><tbody><?php foreach ($contacts as $contact): ?><tr><td><strong><?= e($contact['nombre']) ?></strong></td><td><?= e($contact['telefono']) ?></td><td><?= e($contact['correo']) ?></td><td><?= e($contact['ciudad']) ?></td><td><?= e($contact['categoria']) ?></td><td><?= $contact['favorito'] ? '⭐ Sí' : 'No' ?></td><td><?= e(substr($contact['fecha_creacion'], 0, 10)) ?></td><td><div class="actions"><a class="smallbtn" href="contact_edit.php?id=<?= (int)$contact['id'] ?>">Editar</a><form method="post" action="contact_delete.php" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este contacto?');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$contact['id'] ?>"><button class="smallbtn danger" type="submit">Eliminar</button></form></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
