<?php
// questions.php - Endpoints para preguntas
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $topic_id = $_GET['topic_id'] ?? null;
    $level_id = $_GET['level_id'] ?? null;
    $sql = 'SELECT * FROM Questions WHERE 1=1';
    $params = [];
    if ($topic_id) {
        $sql .= ' AND topic_id = ?';
        $params[] = $topic_id;
    }
    if ($level_id) {
        $sql .= ' AND level_id = ?';
        $params[] = $level_id;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Obtener respuestas para cada pregunta
    foreach ($questions as &$q) {
        $stmtA = $pdo->prepare('SELECT answer_id, answer_text, is_correct FROM Answers WHERE question_id = ?');
        $stmtA->execute([$q['question_id']]);
        $q['answers'] = $stmtA->fetchAll(PDO::FETCH_ASSOC);
    }
    echo json_encode($questions);
    exit;
}

// Crear pregunta (POST)
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['topic_id'], $data['level_id'], $data['question_text'], $data['question_type'], $data['answers']) || !is_array($data['answers'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan datos requeridos o answers no es un array']);
        exit;
    }
    $topic_id = $data['topic_id'];
    $level_id = $data['level_id'];
    $question_text = $data['question_text'];
    $question_type = $data['question_type'];
    $qr_code = $data['qr_code'] ?? null;
    // Insertar pregunta
    $stmt = $pdo->prepare('INSERT INTO Questions (topic_id, level_id, question_text, question_type, qr_code) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$topic_id, $level_id, $question_text, $question_type, $qr_code]);
    $question_id = $pdo->lastInsertId();
    // Insertar respuestas
    $inserted = 0;
    foreach ($data['answers'] as $ans) {
        if (!isset($ans['answer_text'], $ans['is_correct'])) continue;
        $stmtA = $pdo->prepare('INSERT INTO Answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)');
        $stmtA->execute([$question_id, $ans['answer_text'], $ans['is_correct'] ? 1 : 0]);
        $inserted++;
    }
    echo json_encode(['success' => true, 'question_id' => $question_id, 'answers_inserted' => $inserted]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);
