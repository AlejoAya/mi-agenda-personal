<?php
require_once __DIR__ . '/includes/functions.php'; auth_required();
require_once __DIR__ . '/config/database.php';
$categories = $pdo->query('SELECT id, nombre FROM categorias ORDER BY id')->fetchAll();
$values = ['nombre'=>'','telefono'=>'','correo'=>'','ciudad'=>'','id_categoria'=>'','favorito'=>'0']; $errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $values = contact_values(); $errors = contact_errors($values);
    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad, id_categoria, favorito) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $values['nombre'], $values['telefono'], $values['correo'], $values['ciudad'], (int)$values['id_categoria'], (int)$values['favorito']]); redirect('index.php');
    }
}
$pageTitle = 'Nuevo contacto'; $buttonText = 'Guardar contacto'; include __DIR__ . '/includes/header.php'; include __DIR__ . '/includes/contact_form.php'; include __DIR__ . '/includes/footer.php';
