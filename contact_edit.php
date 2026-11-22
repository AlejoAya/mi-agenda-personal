<?php
require_once __DIR__ . '/includes/functions.php'; auth_required();
require_once __DIR__ . '/config/database.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(404); exit('Contacto no encontrado.'); }
$stmt = $pdo->prepare('SELECT nombre, telefono, correo, ciudad, id_categoria, favorito FROM contactos WHERE id = ? AND id_usuario = ?');
$stmt->execute([$id, $_SESSION['user_id']]); $values = $stmt->fetch();
if (!$values) { http_response_code(404); exit('Contacto no encontrado.'); }
$categories = $pdo->query('SELECT id, nombre FROM categorias ORDER BY id')->fetchAll();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $values = contact_values(); $errors = contact_errors($values);
    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE contactos SET nombre = ?, telefono = ?, correo = ?, ciudad = ?, id_categoria = ?, favorito = ? WHERE id = ? AND id_usuario = ?');
        $stmt->execute([$values['nombre'], $values['telefono'], $values['correo'], $values['ciudad'], (int)$values['id_categoria'], (int)$values['favorito'], $id, $_SESSION['user_id']]); redirect('index.php');
    }
}
$pageTitle = 'Editar contacto'; $buttonText = 'Guardar cambios'; include __DIR__ . '/includes/header.php'; include __DIR__ . '/includes/contact_form.php'; include __DIR__ . '/includes/footer.php';
