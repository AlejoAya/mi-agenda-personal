<?php
require_once __DIR__ . '/includes/functions.php';
guest_required();
require_once __DIR__ . '/config/database.php';
$errors = []; $nombre = ''; $correo = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nombre = trim((string)($_POST['nombre'] ?? ''));
    $correo = strtolower(trim((string)($_POST['correo'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm'] ?? '');
    if ($nombre === '' || mb_strlen($nombre) > 120) $errors[] = 'Ingresa un nombre de máximo 120 caracteres.';
    if (strlen($correo) > 190 || !filter_var($correo, FILTER_VALIDATE_EMAIL)) $errors[] = 'Ingresa un correo válido.';
    if (strlen($password) < 8) $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
    if ($password !== $confirm) $errors[] = 'Las contraseñas no coinciden.';
    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE correo = ?'); $stmt->execute([$correo]);
        if ($stmt->fetch()) $errors[] = 'Este correo ya está registrado.';
        else {
            try {
                $stmt = $pdo->prepare('INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)');
                $stmt->execute([$nombre, $correo, password_hash($password, PASSWORD_DEFAULT)]);
                redirect('login.php?registered=1');
            } catch (PDOException $ex) {
                if ($ex->getCode() === '23000') $errors[] = 'Este correo ya está registrado.';
                else { error_log($ex->getMessage()); $errors[] = 'No se pudo completar el registro.'; }
            }
        }
    }
}
$pageTitle = 'Crear cuenta'; include __DIR__ . '/includes/header.php'; ?>
<section class="auth card"><div class="eyebrow">EMPIEZA AQUÍ</div><h1>Crear una cuenta</h1><p class="muted">Organiza tus contactos en un espacio privado.</p>
<?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?>
<form method="post"><?= csrf_field() ?><label>Nombre completo<input name="nombre" maxlength="120" value="<?= e($nombre) ?>" required></label><label>Correo electrónico<input type="email" name="correo" maxlength="190" value="<?= e($correo) ?>" required></label><label>Contraseña<input type="password" name="password" minlength="8" required></label><label>Confirmar contraseña<input type="password" name="confirm" minlength="8" required></label><button class="btn full">Registrarme</button></form><p class="center">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
