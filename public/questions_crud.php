<?php
// questions_crud.php
session_start();
require_once __DIR__ . '/../src/db.php';
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
// Obtener preguntas, temas y niveles
$sql = 'SELECT q.question_id, q.question_text, q.question_type, q.difficulty, t.topic_name, l.level_name FROM Questions q
        JOIN Topics t ON q.topic_id = t.topic_id
        JOIN Levels l ON q.level_id = l.level_id
        ORDER BY q.question_id DESC';
$preguntas = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Preguntas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="fw-bold text-morado mb-4">Preguntas</h2>
    <a href="add_question.php" class="btn btn-success mb-3">Agregar nueva pregunta</a>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Texto</th>
                <th>Tema</th>
                <th>Nivel</th>
                <th>Tipo</th>
                <th>Dificultad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($preguntas as $preg): ?>
            <tr>
                <td><?= $preg['question_id'] ?></td>
                <td><?= htmlspecialchars($preg['question_text']) ?></td>
                <td><?= htmlspecialchars($preg['topic_name']) ?></td>
                <td><?= htmlspecialchars($preg['level_name']) ?></td>
                <td><?= htmlspecialchars($preg['question_type']) ?></td>
                <td><?= htmlspecialchars($preg['difficulty']) ?></td>
                <td>
                    <a href="edit_question.php?question_id=<?= $preg['question_id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    <a href="delete_question.php?question_id=<?= $preg['question_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar esta pregunta?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Volver al panel de administración</a>
</div>
</body>
</html>
