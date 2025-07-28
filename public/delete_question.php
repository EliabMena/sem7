<?php
// delete_question.php
session_start();
require_once __DIR__ . '/../src/db.php';
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$question_id = $_GET['question_id'] ?? null;
if (!$question_id) {
    header('Location: questions_crud.php');
    exit;
}
$pdo->prepare('DELETE FROM Answers WHERE question_id=?')->execute([$question_id]);
$pdo->prepare('DELETE FROM Questions WHERE question_id=?')->execute([$question_id]);
header('Location: questions_crud.php');
exit;
