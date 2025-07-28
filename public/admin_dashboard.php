<?php
// admin_dashboard.php
session_start();
require_once __DIR__ . '/../src/db.php';

// Verifica que el usuario esté logueado y sea admin
if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener todos los usuarios (excepto el admin actual)
$stmt = $pdo->prepare('SELECT user_id, name, email, role FROM Users WHERE user_id != ?');
$stmt->execute([$_SESSION['quizUser']['user_id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - QuizApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-morado shadow-sm">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
          <img src="../assets/img/logo.png" alt="Logo" width="40" height="40" class="rounded-circle bg-white shadow-sm">
          <span class="fw-bold">Quiz App - Admin</span>
        </a>
        <div class="ms-auto">
          <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
      </div>
    </nav>
    <div class="container py-4">
        <h2 class="fw-bold text-morado mb-4">Panel de Administración</h2>
        <div class="mb-4">
            <a href="questions_crud.php" class="btn btn-outline-primary btn-lg">Gestionar preguntas</a>
            <a href="export_players_report.php" class="btn btn-success btn-lg ms-2">Descargar reporte Excel</a>
        </div>
        <div class="card mb-4">
            <div class="card-header bg-morado text-white">Usuarios registrados</div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['user_id'] ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <a href="edit_user.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="delete_user.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="add_user.php" class="btn btn-success">Agregar nuevo usuario</a>
            </div>
        </div>

        <!-- CRUD de Temas -->
        <div class="card mb-4">
            <div class="card-header bg-morado text-white">Temas y Niveles</div>
            <div class="card-body">
                <?php
                // Obtener temas y niveles
                $temas = $pdo->query('SELECT * FROM Topics')->fetchAll(PDO::FETCH_ASSOC);
                $niveles = $pdo->query('SELECT * FROM Levels')->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($temas as $tema): ?>
                        <tr>
                            <td><?= isset($tema['topic_id']) ? $tema['topic_id'] : '' ?></td>
                            <td><?= isset($tema['topic_name']) && $tema['topic_name'] !== null ? htmlspecialchars($tema['topic_name']) : '' ?></td>
                            <td>
                                <a href="edit_topic.php?topic_id=<?= isset($tema['topic_id']) ? $tema['topic_id'] : '' ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="delete_topic.php?topic_id=<?= isset($tema['topic_id']) ? $tema['topic_id'] : '' ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este tema?')">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="add_topic.php" class="btn btn-success">Agregar nuevo tema</a>
                <hr>
                <h6 class="fw-bold">Niveles existentes:</h6>
                <ul>
                  <?php foreach ($niveles as $nivel): ?>
                    <li><?= htmlspecialchars($nivel['level_name']) ?> (ID: <?= $nivel['level_id'] ?>)</li>
                  <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <a href="dashboard.php" class="btn btn-secondary">Ir al dashboard de usuario</a>
    </div>
</body>
</html>
