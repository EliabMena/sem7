<?php
require_once __DIR__ . '/../src/db.php';
// update_profile.php
session_start();

$quizUser = isset($_SESSION['quizUser']) ? $_SESSION['quizUser'] : null;
if (!$quizUser && isset($_COOKIE['quizUser'])) {
  $quizUser = json_decode($_COOKIE['quizUser'], true);
}
// Si no hay usuario, intentar obtenerlo desde POST oculto (enviado por JS desde localStorage)
if (!$quizUser && isset($_POST['user_id'])) {
  $stmt = $pdo->prepare('SELECT * FROM Users WHERE user_id = ?');
  $stmt->execute([$_POST['user_id']]);
  $quizUser = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($quizUser) {
    $_SESSION['quizUser'] = $quizUser;
    setcookie('quizUser', json_encode($quizUser), time() + 3600, '/');
  }
}
if (!$quizUser) {
  // Mostrar un formulario oculto para que JS lo rellene y lo envíe automáticamente
  echo '<form id="userRestoreForm" method="POST"><input type="hidden" name="user_id" id="restoreUserId"></form>';
  echo '<script>var quizUser = localStorage.getItem("quizUser");
    if (quizUser) {
      quizUser = JSON.parse(quizUser);
      document.getElementById("restoreUserId").value = quizUser.user_id;
      document.getElementById("userRestoreForm").submit();
    } else {
      window.location.href = "login.php";
    }
  </script>';
  exit;
}
$user_id = $quizUser['user_id'];

$stmt = $pdo->prepare('SELECT u.name, u.email, a.image_url FROM Users u LEFT JOIN Avatars a ON u.user_id = a.user_id AND a.is_active = 1 WHERE u.user_id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$default_avatar = 'https://cdn.jsdelivr.net/gh/twbs/icons@1.10.0/icons/person-circle.svg'; // Ícono gris Bootstrap CDN
$avatar_url = $user && $user['image_url'] ? '../assets/img/users/' . basename($user['image_url']) : $default_avatar;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualizar Perfil - QuizApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center" style="Width: 100%;">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 bg-white p-4 mx-auto" style="max-width:750px; width:90vw; min-width:320px; min-height:60vh;">
          <h3 class="fw-bold text-morado mb-3">Actualizar perfil</h3>
          <form id="updateProfileForm" enctype="multipart/form-data" method="POST" action="../src/update_profile.php">
            <div class="mb-3 text-center">
              <?php if ($user && $user['image_url']) { ?>
                <img src="<?= htmlspecialchars($avatar_url) ?>" alt="Avatar" class="rounded-circle shadow-sm mb-2" width="120" height="120" style="object-fit:cover;">
              <?php } else { ?>
                <span class="d-inline-block rounded-circle shadow-sm mb-2" style="width:120px;height:120px;overflow:hidden;background:#f8f9fa;display:flex;align-items:center;justify-content:center;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="#adb5bd" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                  </svg>
                </span>
              <?php } ?>
            </div>
            <div class="mb-3">
              <label for="profileImage" class="form-label">Imagen de perfil</label>
              <input class="form-control" type="file" id="profileImage" name="profile_image" accept="image/*">
            </div>
            <div class="mb-3">
              <label for="profileName" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="profileName" name="name" value="<?= isset($user['name']) ? htmlspecialchars($user['name']) : '' ?>" required>
            </div>
            <div class="mb-3">
              <label for="profileEmail" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="profileEmail" name="email" value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>" required>
            </div>
            <hr>
            <div class="mb-3">
              <label for="currentPassword" class="form-label">Contraseña actual</label>
              <input type="password" class="form-control" id="currentPassword" name="current_password" autocomplete="current-password">
            </div>
            <div class="mb-3">
              <label for="newPassword" class="form-label">Nueva contraseña</label>
              <input type="password" class="form-control" id="newPassword" name="new_password" autocomplete="new-password">
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirmar nueva contraseña</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirm_password" autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-morado w-100 fw-bold">Actualizar</button>
            <div class="form-text text-danger mt-2 d-none" id="updateProfileMsg"></div>
          </form>
          <div class="mt-3 text-center">  
            <a href="dashboard.php" class="text-morado">&larr; Volver al dashboard</a>
          </div>
        </div>
      </div>
    </div>
  </div>
<script src="../assets/js/errorHandler.js"></script>
<script>
// Función para mostrar alertas visuales en una tarjeta flotante (usa el mismo estilo que errorHandler.js)
function setAlert(msg, type = 'danger', timeout = 4000) {
  let alertBox = document.getElementById('globalErrorBox');
  if (!alertBox) {
    alertBox = document.createElement('div');
    alertBox.id = 'globalErrorBox';
    alertBox.style.position = 'fixed';
    alertBox.style.top = '50%';
    alertBox.style.left = '50%';
    alertBox.style.transform = 'translate(-50%, -50%)';
    alertBox.style.minWidth = '400px';
    alertBox.style.maxWidth = '98vw';
    alertBox.style.background = '#fff';
    alertBox.style.color = (type === 'success') ? '#198754' : (type === 'warning' ? '#ffc107' : '#dc3545');
    alertBox.style.border = '2.5px solid ' + ((type === 'success') ? '#198754' : (type === 'warning' ? '#ffc107' : '#dc3545'));
    alertBox.style.boxShadow = '0 8px 32px rgba(0,0,0,0.18)';
    alertBox.style.borderRadius = '18px';
    alertBox.style.padding = '32px 48px 28px 48px';
    alertBox.style.fontSize = '1.35rem';
    alertBox.style.fontFamily = 'system-ui, sans-serif';
    alertBox.style.zIndex = '99999';
    alertBox.style.display = 'flex';
    alertBox.style.alignItems = 'center';
    alertBox.style.gap = '18px';
    alertBox.style.pointerEvents = 'auto';
    alertBox.innerHTML = '<span style="font-size:2.2em;">&#9888;&#65039;</span><span id="globalErrorMsg"></span>';
    document.body.appendChild(alertBox);
    alertBox.addEventListener('click', function() {
      alertBox.style.display = 'none';
    });
  } else {
    alertBox.style.display = 'flex';
    alertBox.style.color = (type === 'success') ? '#198754' : (type === 'warning' ? '#ffc107' : '#dc3545');
    alertBox.style.border = '2.5px solid ' + ((type === 'success') ? '#198754' : (type === 'warning' ? '#ffc107' : '#dc3545'));
  }
  document.getElementById('globalErrorMsg').textContent = msg;
  clearTimeout(window._globalErrorTimeout);
  if (timeout > 0) {
    window._globalErrorTimeout = setTimeout(function() {
      alertBox.style.display = 'none';
    }, timeout);
  }
}

// Mostrar mensajes de error de PHP
<?php if (isset($_GET['error'])): ?>
  setAlert(decodeURIComponent(<?= json_encode($_GET['error']) ?>), 'danger', 2000);
<?php endif; ?>
<?php if (isset($_GET['success'])): ?>
  setAlert('Perfil actualizado correctamente', 'success', 2000);
<?php endif; ?>
</script>
</body>
<?php if (isset($_GET['success']) && $user && isset($user['name'], $user['email'])): ?>
<script>
  // Actualiza localStorage con los nuevos datos y avatar solo si existen
  var quizUser = localStorage.getItem('quizUser');
  if (quizUser) {
    quizUser = JSON.parse(quizUser);
    quizUser.name = <?= json_encode($user['name']) ?>;
    quizUser.email = <?= json_encode($user['email']) ?>;
    // Obtener avatar actualizado
    fetch('../src/avatar.php?user_id=' + quizUser.user_id)
      .then(res => res.json())
      .then(data => {
        if (data.avatar_url) {
          quizUser.avatar_url = data.avatar_url;
        }
        localStorage.setItem('quizUser', JSON.stringify(quizUser));
      });
  }
</script>
<?php endif; ?>
</html>
