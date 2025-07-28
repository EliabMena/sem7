<?php
// src/add_question.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Validar usuario (debe venir en sesión o por id, aquí usamos user_id en POST por simplicidad)
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
$question = trim($_POST['question'] ?? '');
$topic_id = intval($_POST['topic_id'] ?? 0);
$level_id = intval($_POST['level_id'] ?? 0);
$answers = $_POST['answer'] ?? [];
$correct = isset($_POST['correct_answer']) ? intval($_POST['correct_answer']) : null;

if (!$user_id || !$question || !$topic_id || !$level_id || count($answers) < 4 || $correct === null) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
    exit;
}

// Verificar rango del usuario por puntos (Experto >= 1000 puntos)
$stmt = $pdo->prepare('SELECT SUM(points_obtained) as total_points FROM PlayerProgress WHERE user_id = ?');
$stmt->execute([$user_id]);
$row = $stmt->fetch();
$total = $row && $row['total_points'] !== null ? (int)$row['total_points'] : 0;
if ($total < 1000) {
    echo json_encode(['success' => false, 'message' => 'No tienes permiso para añadir preguntas.']);
    exit;
}


// Insertar pregunta (ajustar a columnas reales)
$question_type = 'multiple_choice';
$difficulty = ($level_id == 1) ? 'easy' : (($level_id == 2) ? 'medium' : 'hard');
$stmt = $pdo->prepare('INSERT INTO Questions (topic_id, level_id, question_text, question_type, difficulty) VALUES (?, ?, ?, ?, ?)');
if (!$stmt->execute([$topic_id, $level_id, $question, $question_type, $difficulty])) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar la pregunta.']);
    exit;
}
$question_id = $pdo->lastInsertId();

// Insertar respuestas (ajustar a columnas reales)
$stmt = $pdo->prepare('INSERT INTO Answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)');
for ($i = 0; $i < 4; $i++) {
    $is_correct = ($i == $correct) ? 1 : 0;
    $ans = trim($answers[$i]);
    if ($ans === '') continue;
    $stmt->execute([$question_id, $ans, $is_correct]);
}

echo json_encode(['success' => true]);
