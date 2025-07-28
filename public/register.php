<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - QuizApp</title>
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
    </div>
    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg position-relative" style="max-width: 400px; width: 100%;">
            <a href="../index.php" class="position-absolute top-0 end-0 m-2 btn btn-light btn-sm rounded-circle shadow-sm" title="Volver al inicio" style="z-index:2;">
                <span style="font-size:1.3rem;line-height:1;">&times;</span>
            </a>
            <div class="text-center mb-3">
                <img src="../assets/img/logo.png" alt="Logo" width="80" class="mb-2">
                <h2 class="fw-bold text-morado">Registro</h2>
            </div>
            <form id="registerForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" required>
                </div>
                <button type="submit" class="btn btn-morado w-100">Registrarse</button>
                <div id="registerMsg" class="mt-3 text-center"></div>
            </form>
        </div>
    </div>
    <script src="../assets/js/register.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    fetch('../src/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password })
    })
    .then(res => res.json())
    .then(data => {
        const msg = document.getElementById('registerMsg');
        if (data.success) {
            msg.className = 'mt-3 text-success';
            msg.textContent = 'Registro exitoso. Ahora puedes iniciar sesión.';
        } else {
            msg.className = 'mt-3 text-danger';
            msg.textContent = data.message || 'Error en el registro.';
        }
    })
    .catch(() => {
        const msg = document.getElementById('registerMsg');
        msg.className = 'mt-3 text-danger';
        msg.textContent = 'Error de red.';
    });
});
</script>
</body>
</html>
