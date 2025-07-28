// login.js
const loginForm = document.getElementById('loginForm');
const loginMsg = document.getElementById('loginMsg');

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    loginMsg.textContent = '';
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const res = await fetch('../src/api.php?resource=users&action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    });
    const data = await res.json();
    if (data.success) {
        loginMsg.textContent = '¡Bienvenido, ' + data.user.name + '!';
        loginMsg.className = 'text-success mt-3';
        localStorage.setItem('quizUser', JSON.stringify(data.user));
        setTimeout(() => window.location.href = 'dashboard.php', 1000);
    } else {
        loginMsg.textContent = data.error || 'Error al iniciar sesión';
        loginMsg.className = 'text-danger mt-3';
    }
});
