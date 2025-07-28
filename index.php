<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Trivias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <!-- Burbujas decorativas fondo -->
    <div class="burbujas-bg">
      <div class="burbuja burbuja1"></div>
      <div class="burbuja burbuja2"></div>
      <div class="burbuja burbuja3"></div>
      <div class="burbuja burbuja4"></div>
      <div class="burbuja burbuja5"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-morado shadow-sm" style="z-index:2;">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img src="assets/img/logo.png" alt="Logo" width="40" height="40" class="me-2">
          QuizApp
        </a>
        <div class="ms-auto">
          <a href="public/login.php" class="btn btn-outline-light me-2">Iniciar sesión</a>
          <a href="public/register.php" class="btn btn-light text-morado">Registrarse</a>
        </div>
      </div>
    </nav>

    <!-- Logo principal -->
    <div class="container text-center my-5">
      <img src="assets/img/logo.png" alt="Logo Quiz" class="logo-principal mb-4">
      <h1 class="fw-bold text-morado">Bienvenido al Sistema de Trivias</h1>
      <p class="lead">¡Pon a prueba tus conocimientos en diferentes tecnologías!</p>
    </div>

    <!-- Temas disponibles -->
    <div class="container mb-5">
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <div class="d-flex flex-column align-items-center gap-4">
            <div class="card card-tema tema-grande w-100">
              <div class="card-body d-flex flex-column align-items-center text-center">
                <img src="assets/img/php.png" alt="PHP" class="tema-img tema-img-grande mb-3">
                <h5 class="card-title fw-bold">PHP</h5>
                <p class="card-text">Preguntas sobre desarrollo backend con PHP.</p>
                <a href="#" class="btn btn-morado mt-2">Jugar</a>
              </div>
            </div>
            <div class="card card-tema tema-grande w-100">
              <div class="card-body d-flex flex-column align-items-center text-center">
                <img src="assets/img/js.png" alt="JavaScript" class="tema-img tema-img-grande mb-3">
                <h5 class="card-title fw-bold">JavaScript</h5>
                <p class="card-text">Demuestra tus habilidades en JavaScript.</p>
                <a href="#" class="btn btn-morado mt-2">Jugar</a>
              </div>
            </div>
            <div class="card card-tema tema-grande w-100">
              <div class="card-body d-flex flex-column align-items-center text-center">
                <img src="assets/img/laravel.png" alt="Laravel" class="tema-img tema-img-grande mb-3">
                <h5 class="card-title fw-bold">Laravel</h5>
                <p class="card-text">¿Qué tanto sabes de Laravel?</p>
                <a href="#" class="btn btn-morado mt-2">Jugar</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/app.js"></script>
</body>
</html>
