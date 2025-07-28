<?php
// progress.php - Devuelve el progreso del usuario por tema y nivel
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['error' => 'Falta user_id']);
    http_response_code(400);
    exit;
}

// Obtener todos los temas
$temasStmt = $pdo->query('SELECT topic_id, topic_name FROM Topics');
$temas = $temasStmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los niveles
$nivelesStmt = $pdo->query('SELECT level_id, level_name FROM Levels ORDER BY level_id ASC');
$niveles = $nivelesStmt->fetchAll(PDO::FETCH_ASSOC);

// Para cada tema y nivel, obtener:
// - total de preguntas
// - respuestas correctas (puntos_obtained en PlayerProgress)
// - porcentaje de avance
$result = [];
foreach ($temas as $tema) {
    $tema_id = $tema['topic_id'];
    $tema_nombre = $tema['topic_name'];
    $niveles_data = [];
    foreach ($niveles as $nivel) {
        $nivel_id = $nivel['level_id'];
        $nivel_nombre = $nivel['level_name'];
        // Total de preguntas
        $qStmt = $pdo->prepare('SELECT COUNT(*) as total FROM Questions WHERE topic_id = ? AND level_id = ?');
        $qStmt->execute([$tema_id, $nivel_id]);
        $qRow = $qStmt->fetch(PDO::FETCH_ASSOC);
        $total_preguntas = $qRow ? (int)$qRow['total'] : 0;
        // Progreso del usuario
        $pStmt = $pdo->prepare('SELECT points_obtained FROM PlayerProgress WHERE user_id = ? AND topic_id = ? AND level_id = ?');
        $pStmt->execute([$user_id, $tema_id, $nivel_id]);
        $pRow = $pStmt->fetch(PDO::FETCH_ASSOC);
        $respuestas_correctas = $pRow ? (int)$pRow['points_obtained'] : 0;
        // Calcular avance como porcentaje real
        $avance = ($total_preguntas > 0) ? round(($respuestas_correctas / $total_preguntas) * 10) : 0;
        $niveles_data[] = [
            'level_id' => $nivel_id,
            'level_name' => $nivel_nombre,
            'correctas' => $respuestas_correctas,
            'total' => $total_preguntas,
            'avance' => $avance
        ];
    }
    $result[] = [
        'topic_id' => $tema_id,
        'topic_name' => $tema_nombre,
        'niveles' => $niveles_data
    ];
}
echo json_encode($result);
