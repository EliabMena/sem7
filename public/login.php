<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - QuizApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
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
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg position-relative" style="max-width: 400px; width: 100%;">
            <a href="../index.php" class="position-absolute top-0 end-0 m-2 btn btn-light btn-sm rounded-circle shadow-sm" title="Volver al inicio" style="z-index:2;">
                <span style="font-size:1.3rem;line-height:1;">&times;</span>
            </a>
            <div class="text-center mb-3">
                <img src="../assets/img/logo.png" alt="Logo" width="80" class="mb-2">
                <h2 class="fw-bold text-morado">Iniciar Sesión</h2>
            </div>
            <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <button type="submit" class="btn btn-morado w-100">Entrar</button>
                <div id="loginMsg" class="mt-3 text-center"></div>
            </form>
            <div class="mt-3 text-center">
                <a href="register.php" class="text-morado">¿No tienes cuenta? Regístrate</a>
            </div>
        </div>
    </div>
    <script src="../assets/js/login.js"></script>
<script src="../assets/js/errorHandler.js"></script>
</body>
</html>
