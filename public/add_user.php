<?php
// add_user.php
session_start();
require_once __DIR__ . '/../src/db.php';
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'player';
    if ($name && $email && $password) {
        $hash = hash('sha256', $password);
        $stmt = $pdo->prepare('INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $hash, $role]);
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
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Agregar nuevo usuario</h2>
    <?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-select">
                <option value="player">Jugador</option>
                <option value="operative">Operativo</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Agregar</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
