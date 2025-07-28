<?php
// answers.php - Endpoints para respuestas
if ($method === 'GET') {
    $question_id = $_GET['question_id'] ?? null;
    if (!$question_id) {
        echo json_encode(['error' => 'Falta question_id']);
        http_response_code(400);
        exit;
    }
    $stmt = $pdo->prepare('SELECT answer_id, answer_text, is_correct FROM Answers WHERE question_id = ?');
    $stmt->execute([$question_id]);
    $answers = $stmt->fetchAll();
    echo json_encode($answers);
    exit;
}
echo json_encode(['error' => 'Método no permitido']);
http_response_code(405);
