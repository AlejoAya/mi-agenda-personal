<?php
require_once __DIR__ . '/includes/functions.php'; auth_required();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Método no permitido.'); }
verify_csrf(); require_once __DIR__ . '/config/database.php';
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Identificador inválido.'); }
$stmt = $pdo->prepare('DELETE FROM contactos WHERE id = ? AND id_usuario = ?');
$stmt->execute([$id, $_SESSION['user_id']]); redirect('index.php');
