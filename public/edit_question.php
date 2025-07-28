<?php
// edit_question.php
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
$stmt = $pdo->prepare('SELECT * FROM Questions WHERE question_id = ?');
$stmt->execute([$question_id]);
$preg = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$preg) {
    header('Location: questions_crud.php');
    exit;
}
$temas = $pdo->query('SELECT * FROM Topics')->fetchAll(PDO::FETCH_ASSOC);
$niveles = $pdo->query('SELECT * FROM Levels')->fetchAll(PDO::FETCH_ASSOC);
$answers = $pdo->prepare('SELECT * FROM Answers WHERE question_id = ?');
$answers->execute([$question_id]);
$answers = $answers->fetchAll(PDO::FETCH_ASSOC);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = trim($_POST['question_text'] ?? '');
    $topic_id = $_POST['topic_id'] ?? '';
    $level_id = $_POST['level_id'] ?? '';
    $question_type = $_POST['question_type'] ?? 'multiple_choice';
    $difficulty = $_POST['difficulty'] ?? 'easy';
    if ($question_text === '' || !$topic_id || !$level_id) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $stmt = $pdo->prepare('UPDATE Questions SET topic_id=?, level_id=?, question_text=?, question_type=?, difficulty=? WHERE question_id=?');
        if ($stmt->execute([$topic_id, $level_id, $question_text, $question_type, $difficulty, $question_id])) {
            // Actualizar respuestas si es multiple_choice
            if ($question_type === 'multiple_choice') {
                $pdo->prepare('DELETE FROM Answers WHERE question_id=?')->execute([$question_id]);
                $answersArr = $_POST['answers'] ?? [];
                $correct = $_POST['correct'] ?? -1;
                foreach ($answersArr as $i => $ans) {
                    $is_correct = ($i == $correct) ? 1 : 0;
                    $stmtA = $pdo->prepare('INSERT INTO Answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)');
                    $stmtA->execute([$question_id, $ans, $is_correct]);
                }
            } else {
                $pdo->prepare('DELETE FROM Answers WHERE question_id=?')->execute([$question_id]);
            }
            header('Location: questions_crud.php');
            exit;
        } else {
            $error = 'Error al actualizar la pregunta.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Pregunta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function toggleAnswers() {
        var type = document.getElementById('question_type').value;
        document.getElementById('answersSection').style.display = (type === 'multiple_choice') ? 'block' : 'none';
    }
    </script>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-header bg-morado text-white">Editar pregunta</div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Texto de la pregunta</label>
                    <input type="text" class="form-control" name="question_text" value="<?= htmlspecialchars($preg['question_text']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tema</label>
                    <select class="form-select" name="topic_id" required>
                        <?php foreach ($temas as $t): ?>
                            <option value="<?= $t['topic_id'] ?>" <?= $preg['topic_id'] == $t['topic_id'] ? 'selected' : '' ?>><?= htmlspecialchars($t['topic_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nivel</label>
                    <select class="form-select" name="level_id" required>
                        <?php foreach ($niveles as $n): ?>
                            <option value="<?= $n['level_id'] ?>" <?= $preg['level_id'] == $n['level_id'] ? 'selected' : '' ?>><?= htmlspecialchars($n['level_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de pregunta</label>
                    <select class="form-select" name="question_type" id="question_type" onchange="toggleAnswers()">
                        <option value="multiple_choice" <?= $preg['question_type'] == 'multiple_choice' ? 'selected' : '' ?>>Opción múltiple</option>
                        <option value="true_false" <?= $preg['question_type'] == 'true_false' ? 'selected' : '' ?>>Verdadero/Falso</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Dificultad</label>
                    <select class="form-select" name="difficulty">
                        <option value="easy" <?= $preg['difficulty'] == 'easy' ? 'selected' : '' ?>>Fácil</option>
                        <option value="medium" <?= $preg['difficulty'] == 'medium' ? 'selected' : '' ?>>Media</option>
                        <option value="hard" <?= $preg['difficulty'] == 'hard' ? 'selected' : '' ?>>Difícil</option>
                    </select>
                </div>
                <div class="mb-3" id="answersSection">
                    <label class="form-label">Respuestas (marca la correcta)</label>
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="radio" name="correct" value="<?= $i ?>" <?= (isset($answers[$i]) && $answers[$i]['is_correct']) ? 'checked' : '' ?>>
                            </div>
                            <input type="text" class="form-control" name="answers[]" value="<?= isset($answers[$i]) ? htmlspecialchars($answers[$i]['answer_text']) : '' ?>">
                        </div>
                    <?php endfor; ?>
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="questions_crud.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
<script>toggleAnswers();</script>
</body>
</html>
