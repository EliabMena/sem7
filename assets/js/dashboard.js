// dashboard.js
// Mostrar nombre de usuario y cargar temas dinámicamente

document.addEventListener('DOMContentLoaded', () => {
    // Obtener usuario del localStorage
    const user = JSON.parse(localStorage.getItem('quizUser'));
    const userName = document.getElementById('userName');
    if (user && user.name) {
        if (userName) {
            userName.textContent = user.name;
        }
    } else {
        window.location.href = 'login.php';
        return;
    }

    // Cerrar sesión
    document.getElementById('logoutBtn').onclick = () => {
        localStorage.removeItem('quizUser');
        window.location.href = 'login.php';
    };

    // Cargar temas desde la API (solo mostrar si hay progreso)
    // Este bloque fue eliminado para evitar duplicados sin barras de progreso
});
