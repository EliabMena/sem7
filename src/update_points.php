<?php
// update_points.php - Actualiza los puntos del usuario al finalizar el juego
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';
session_start();

// Obtener user_id desde sesión o cookie
// Permitir user_id directo en el body para pruebas/desarrollo
$data = json_decode(file_get_contents('php://input'), true);
$user_id = null;
if (isset($data['user_id'])) {
    $user_id = (int)$data['user_id'];
} elseif (isset($_SESSION['quizUser']) && isset($_SESSION['quizUser']['user_id'])) {
    $user_id = (int)$_SESSION['quizUser']['user_id'];
} elseif (isset($_COOKIE['quizUser'])) {
    $cookie = json_decode($_COOKIE['quizUser'], true);
    if (isset($cookie['user_id'])) {
        $user_id = (int)$cookie['user_id'];
    }
}
if (!$user_id) {
    echo json_encode(['error' => 'No autenticado']);
    http_response_code(401);
    exit;
}

// Recibir puntos y detalles
$puntos = isset($data['points']) ? (int)$data['points'] : 0;
$topic_id = isset($data['topic_id']) ? (int)$data['topic_id'] : null;
$level_id = isset($data['level_id']) ? (int)$data['level_id'] : null;
if ($puntos <= 0 || !$topic_id || !$level_id || !$user_id) {
    echo json_encode(['error' => 'Datos incompletos']);
    http_response_code(400);
    exit;
}

// Insertar o actualizar PlayerProgress

// Obtener total de preguntas para calcular porcentaje avance
$q = $pdo->prepare('SELECT COUNT(*) FROM Questions WHERE topic_id = ? AND level_id = ?');
$q->execute([$topic_id, $level_id]);
$total_preguntas = (int)$q->fetchColumn();
$porcentaje = $total_preguntas > 0 ? min(100, round(($puntos / ($total_preguntas * 10)) * 100, 2)) : 0;

// Buscar el registro existente usando player_progress_id
$stmt = $pdo->prepare('SELECT player_progress_id, points_obtained FROM PlayerProgress WHERE user_id = ? AND topic_id = ? AND level_id = ?');
$stmt->execute([$user_id, $topic_id, $level_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $nuevo = $row['points_obtained'] + $puntos;
    $stmt2 = $pdo->prepare('UPDATE PlayerProgress SET points_obtained = ?, percentage_advance = ?, end_time = NOW() WHERE player_progress_id = ?');
    $stmt2->execute([$nuevo, $porcentaje, $row['player_progress_id']]);
} else {
    $stmt2 = $pdo->prepare('INSERT INTO PlayerProgress (user_id, topic_id, level_id, points_obtained, percentage_advance, start_time, end_time) VALUES (?, ?, ?, ?, ?, NOW(), NOW())');
    $stmt2->execute([$user_id, $topic_id, $level_id, $puntos, $porcentaje]);
}
echo json_encode(['success' => true, 'points_added' => $puntos, 'percentage' => $porcentaje]);
