<?php
// points.php - Devuelve los puntos totales de un usuario
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';


$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'Falta user_id']);
    http_response_code(400);
    exit;
}

// Total de puntos
$stmt = $pdo->prepare('SELECT SUM(points_obtained) as total_points FROM PlayerProgress WHERE user_id = ?');
$stmt->execute([$user_id]);
$row = $stmt->fetch();
$total = $row && $row['total_points'] !== null ? (int)$row['total_points'] : 0;


// Calcular el rango SOLO en base a los puntos
if ($total >= 1000) {
    $rank = 'Experto';
    $level_id = 3;
} elseif ($total >= 500) {
    $rank = 'Novato';
    $level_id = 2;
} else {
    $rank = 'Principiante';
    $level_id = 1;
}

// Premio más alto alcanzado (award_name)
$award_name = null;
if ($level_id !== null && $total > 0) {
    $stmt3 = $pdo->prepare('SELECT award_name FROM Awards WHERE min_points_required <= ? AND level_id <= ? ORDER BY min_points_required DESC, level_id DESC LIMIT 1');
    $stmt3->execute([$total, $level_id]);
    $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
    if ($row3 && $row3['award_name']) {
        $award_name = $row3['award_name'];
    }
}

echo json_encode(['points' => $total, 'rank' => $rank, 'award_name' => $award_name]);
