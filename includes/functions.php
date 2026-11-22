<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax'
    ]);
    session_start();
}
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $path): never { header('Location: ' . $path); exit; }
function auth_required(): void { if (empty($_SESSION['user_id'])) redirect('login.php'); }
function guest_required(): void { if (!empty($_SESSION['user_id'])) redirect('index.php'); }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void {
    if (!isset($_POST['csrf']) || !hash_equals(csrf_token(), (string)$_POST['csrf'])) {
        http_response_code(403); exit('Solicitud no válida. Actualiza la página e inténtalo nuevamente.');
    }
}
function contact_values(): array {
    return [
        'nombre' => trim((string)($_POST['nombre'] ?? '')),
        'telefono' => trim((string)($_POST['telefono'] ?? '')),
        'correo' => strtolower(trim((string)($_POST['correo'] ?? ''))),
        'ciudad' => trim((string)($_POST['ciudad'] ?? '')),
        'id_categoria' => (string)($_POST['id_categoria'] ?? ''),
        'favorito' => isset($_POST['favorito']) ? '1' : '0',
    ];
}
function contact_errors(array $v): array {
    $errors = [];
    if ($v['nombre'] === '' || mb_strlen($v['nombre']) > 120) $errors[] = 'El nombre es obligatorio (máximo 120 caracteres).';
    if (!preg_match('/^[0-9+() .-]{6,30}$/', $v['telefono'])) $errors[] = 'Ingresa un teléfono válido de 6 a 30 caracteres.';
    if (strlen($v['correo']) > 190 || !filter_var($v['correo'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Ingresa un correo válido.';
    if ($v['ciudad'] === '' || mb_strlen($v['ciudad']) > 100) $errors[] = 'La ciudad es obligatoria (máximo 100 caracteres).';
    if (!ctype_digit($v['id_categoria']) || !in_array((int)$v['id_categoria'], [1,2,3,4,5], true)) $errors[] = 'Selecciona una categoría válida.';
    return $errors;
}
