<?php
require_once __DIR__ . '/includes/functions.php';
guest_required(); require_once __DIR__ . '/config/database.php';
$error = ''; $correo = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $correo = strtolower(trim((string)($_POST['correo'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === '') $error = 'Ingresa un correo y una contraseña válidos.';
    else {
        $stmt = $pdo->prepare('SELECT id, nombre, correo, password FROM usuarios WHERE correo = ?');
        $stmt->execute([$correo]); $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) $error = 'Correo o contraseña incorrectos.';
        else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['correo'];
            redirect('index.php');
        }
    }
}
$pageTitle = 'Iniciar sesión'; include __DIR__ . '/includes/header.php'; ?>
<section class="auth card"><div class="eyebrow">BIENVENIDO DE NUEVO</div><h1>Iniciar sesión</h1><p class="muted">Accede de forma segura a tu agenda.</p>
<?php if (isset($_GET['registered'])): ?><div class="alert success">Cuenta creada. Ahora puedes iniciar sesión.</div><?php endif; ?>
<?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post"><?= csrf_field() ?><label>Correo electrónico<input type="email" name="correo" value="<?= e($correo) ?>" maxlength="190" required></label><label>Contraseña<input type="password" name="password" required></label><button class="btn full">Ingresar</button></form><p class="center">¿No tienes cuenta? <a href="register.php">Regístrate</a></p></section>
<?php include __DIR__ . '/includes/footer.php'; ?>
