<?php
// delete_user.php
session_start();
require_once __DIR__ . '/../src/db.php';
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$user_id = $_GET['user_id'] ?? null;
if ($user_id && $user_id != $_SESSION['quizUser']['user_id']) {
    $stmt = $pdo->prepare('DELETE FROM Users WHERE user_id = ?');
    $stmt->execute([$user_id]);
}
header('Location: admin_dashboard.php');
exit;
