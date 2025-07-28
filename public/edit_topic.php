<?php
// edit_topic.php
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
$stmt = $pdo->prepare('SELECT * FROM Topics WHERE topic_id = ?');
$stmt->execute([$topic_id]);
$tema = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$tema) {
    header('Location: admin_dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic_name = trim($_POST['topic_name'] ?? '');
    if ($topic_name === '') {
        $error = 'El nombre del tema es obligatorio.';
    } else {
        $stmt = $pdo->prepare('UPDATE Topics SET topic_name = ? WHERE topic_id = ?');
        if ($stmt->execute([$topic_name, $topic_id])) {
            header('Location: admin_dashboard.php');
            exit;
        } else {
            $error = 'Error al actualizar el tema. ¿Ya existe ese nombre?';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-header bg-morado text-white">Editar tema</div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="topic_name" class="form-label">Nombre del tema</label>
                    <input type="text" class="form-control" id="topic_name" name="topic_name" value="<?= htmlspecialchars($tema['topic_name']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="admin_dashboard.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
