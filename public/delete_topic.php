<?php
// delete_topic.php
session_start();
require_once __DIR__ . '/../src/db.php';
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$topic_id = $_GET['topic_id'] ?? null;
if (!$topic_id) {
    header('Location: admin_dashboard.php');
    exit;
}
// Eliminar el tema solo si no tiene preguntas asociadas
$stmt = $pdo->prepare('SELECT COUNT(*) FROM Questions WHERE topic_id = ?');
$stmt->execute([$topic_id]);
$hasQuestions = $stmt->fetchColumn() > 0;
if ($hasQuestions) {
    // No eliminar, redirigir con error
    header('Location: admin_dashboard.php?error=tema_con_preguntas');
    exit;
}
$stmt = $pdo->prepare('DELETE FROM Topics WHERE topic_id = ?');
$stmt->execute([$topic_id]);
header('Location: admin_dashboard.php');
exit;
