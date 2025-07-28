<?php
// add_topic.php
session_start();
require_once __DIR__ . '/../src/db.php';
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic_name = trim($_POST['topic_name'] ?? '');
    if ($topic_name === '') {
        $error = 'El nombre del tema es obligatorio.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO Topics (topic_name) VALUES (?)');
        if ($stmt->execute([$topic_name])) {
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = 'Error al agregar el tema. ¿Ya existe?';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Tema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-header bg-morado text-white">Agregar nuevo tema</div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="topic_name" class="form-label">Nombre del tema</label>
                    <input type="text" class="form-control" id="topic_name" name="topic_name" required>
                </div>
                <button type="submit" class="btn btn-success">Agregar</button>
                <a href="admin_dashboard.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
