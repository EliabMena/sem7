<?php
// src/update_profile.php
require_once __DIR__ . '/db.php';
session_start();

// Obtener user_id desde sesión o cookie
$user_id = null;
if (isset($_SESSION['quizUser']) && isset($_SESSION['quizUser']['user_id'])) {
  $user_id = (int)$_SESSION['quizUser']['user_id'];
} elseif (isset($_COOKIE['quizUser'])) {
  $cookie = json_decode($_COOKIE['quizUser'], true);
  if (isset($cookie['user_id'])) {
    $user_id = (int)$cookie['user_id'];
  }
}
if (!$user_id) {
  header('Location: ../public/login.php');
  exit;
}

// Validar datos
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
if (!$name || !$email) {
  header('Location: ../public/update_profile.php?error=Datos%20incompletos');
  exit;
}

// Cambiar contraseña si se solicita
if ($current_password || $new_password || $confirm_password) {
  if (!$current_password || !$new_password || !$confirm_password) {
    header('Location: ../public/update_profile.php?error=Debes%20completar%20todos%20los%20campos%20de%20contrase%C3%B1a');
    exit;
  }
  if ($new_password !== $confirm_password) {
    header('Location: ../public/update_profile.php?error=Las%20contrase%C3%B1as%20nuevas%20no%20coinciden');
    exit;
  }
  // Obtener hash actual
  $stmt = $pdo->prepare('SELECT password FROM Users WHERE user_id = ?');
  $stmt->execute([$user_id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $current_hash = hash('sha256', $current_password);
  if (!$row || $current_hash !== $row['password']) {
    header('Location: ../public/update_profile.php?error=Contrase%C3%B1a%20actual%20incorrecta');
    exit;
  }
  // Actualizar contraseña
  $new_hash = hash('sha256', $new_password);
  $stmt = $pdo->prepare('UPDATE Users SET password = ? WHERE user_id = ?');
  $stmt->execute([$new_hash, $user_id]);
}

// Procesar imagen si se subió
$avatar_path = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
  $img_dir = __DIR__ . '/../assets/img/users/';
  if (!is_dir($img_dir)) {
    mkdir($img_dir, 0777, true);
  }
  $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
  $filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
  $dest = $img_dir . $filename;
  if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $dest)) {
    $avatar_path = 'users/' . $filename;
    // Desactivar avatares anteriores
    $pdo->prepare('UPDATE Avatars SET is_active = 0 WHERE user_id = ?')->execute([$user_id]);
    // Insertar nuevo avatar
    $pdo->prepare('INSERT INTO Avatars (user_id, image_url, is_active) VALUES (?, ?, 1)')->execute([$user_id, $avatar_path]);
  }
}

// Actualizar nombre y correo solo si hay cambios reales
$stmt = $pdo->prepare('SELECT name, email FROM Users WHERE user_id = ?');
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row && ($row['name'] !== $name || $row['email'] !== $email)) {
    $stmt = $pdo->prepare('UPDATE Users SET name = ?, email = ? WHERE user_id = ?');
    $success = $stmt->execute([$name, $email, $user_id]);
    if (!$success) {
        header('Location: ../public/update_profile.php?error=No%20se%20pudo%20actualizar%20el%20perfil');
        exit;
    }
}

// Actualizar datos en sesión/cookie
if (isset($_SESSION['quizUser'])) {
  $_SESSION['quizUser']['name'] = $name;
  $_SESSION['quizUser']['email'] = $email;
}
if (isset($_COOKIE['quizUser'])) {
  $cookie = json_decode($_COOKIE['quizUser'], true);
  $cookie['name'] = $name;
  $cookie['email'] = $email;
  setcookie('quizUser', json_encode($cookie), time() + 3600, '/');
}

header('Location: ../public/update_profile.php?success=1');
exit;
