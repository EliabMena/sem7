<?php
// edit_user.php
session_start();
require_once __DIR__ . '/../src/db.php';
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    header('Location: admin_dashboard.php');
    exit;
}
$stmt = $pdo->prepare('SELECT * FROM Users WHERE user_id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    header('Location: admin_dashboard.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'player';
    if ($name && $email) {
        $stmt = $pdo->prepare('UPDATE Users SET name = ?, email = ?, role = ? WHERE user_id = ?');
        $stmt->execute([$name, $email, $role, $user_id]);
        header('Location: admin_dashboard.php');
        exit;
    }
    $error = 'Todos los campos son obligatorios.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Editar usuario</h2>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-select">
                <option value="player" <?= $user['role']==='player'?'selected':'' ?>>Jugador</option>
                <option value="operative" <?= $user['role']==='operative'?'selected':'' ?>>Operativo</option>
                <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
