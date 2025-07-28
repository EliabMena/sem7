// register.js
const registerForm = document.getElementById('registerForm');
const registerMsg = document.getElementById('registerMsg');

registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    registerMsg.textContent = '';
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const res = await fetch('../src/api.php?resource=users&action=register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password })
    });
    const data = await res.json();
    if (data.success) {
        // Obtener datos del usuario recién creado
        const user = { user_id: data.user_id, name, email, role: 'player' };
        localStorage.setItem('quizUser', JSON.stringify(user));
        registerMsg.textContent = '¡Registro exitoso! Redirigiendo...';
        registerMsg.className = 'text-success mt-3';
        setTimeout(() => window.location.href = 'dashboard.php', 1200);
    } else {
        registerMsg.textContent = data.error || 'Error al registrarse';
        registerMsg.className = 'text-danger mt-3';
    }
});
