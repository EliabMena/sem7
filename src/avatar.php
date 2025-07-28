<?php
// avatar.php - Devuelve la URL del avatar activo de un usuario
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'Falta user_id']);
    http_response_code(400);
    exit;
}
$stmt = $pdo->prepare('SELECT image_url FROM Avatars WHERE user_id = ? AND is_active = 1 ORDER BY avatar_id DESC LIMIT 1');
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row && $row['image_url']) {
    // Siempre devolver la ruta absoluta accesible desde el navegador
    $filename = basename($row['image_url']);
    $avatar_url = '/Semestral/assets/img/users/' . $filename;
    echo json_encode(['avatar_url' => $avatar_url]);
} else {
    $default_avatar = 'https://cdn.jsdelivr.net/gh/twbs/icons@1.10.0/icons/person-circle.svg';
    echo json_encode(['avatar_url' => $default_avatar]);
}
