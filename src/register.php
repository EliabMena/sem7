<?php
require_once __DIR__ . '/Sanitizer.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$name = Sanitizer::sanitizeString($data['name'] ?? '');
$email = Sanitizer::sanitizeEmail($data['email'] ?? '');
$password = Sanitizer::sanitizePassword($data['password'] ?? '');

if (!Sanitizer::validateRequired($name) || !Sanitizer::validateRequired($email) || !Sanitizer::validateRequired($password)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
}
// Validar email
if ($email === '') {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido.']);
    exit;
}
// Verificar si el usuario ya existe
$stmt = $pdo->prepare('SELECT user_id FROM Users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'El correo ya está registrado.']);
    exit;
}
// Hash de la contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)');
$role = 'operative'; // Por defecto, los registros aquí son operativos
if ($stmt->execute([$name, $email, $hash, $role])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar usuario.']);
}
