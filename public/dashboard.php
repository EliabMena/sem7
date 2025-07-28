<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - QuizApp</title>
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
    <!-- Navbar personalizado -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-morado shadow-sm">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#" style="padding-left: 15vh;">
          <img src="../assets/img/logo.png" alt="Logo" width="40" height="40" class="rounded-circle bg-white shadow-sm">
          <span class="fw-bold">Quiz App</span>
        </a>
        <div class="dropdown ms-auto" style="padding-right: 15vh;">
          <button class="btn rounded-circle d-flex align-items-center justify-content-center user-icon-btn p-0" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width:44px;height:44px;background:#fff;border:1.5px solid #a259e6;overflow:hidden;">
            <img id="userAvatarImg" src="https://cdn.jsdelivr.net/gh/twbs/icons@1.10.0/icons/person-circle.svg" alt="Avatar" width="42" height="42" class="rounded-circle" style="object-fit:cover;">
          </button>
          <ul class="dropdown-menu dropdown-menu-end mt-2 user-dropdown-menu" aria-labelledby="userDropdown">
            <li><span class="dropdown-item-text fw-bold" id="userNameDropdown"></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="update_profile.php" id="updateProfileBtn">Actualizar datos</a></li>
            <li id="adminPanelLinkContainer" style="display:none;"><a class="dropdown-item text-primary" href="admin_dashboard.php">Panel de administración</a></li>
            <li><a class="dropdown-item text-danger" href="#" id="logoutBtn">Cerrar sesión</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container py-4">
        <div class="mb-4">
            <h2 class="fw-bold text-morado">¡Bienvenido, <span id="userNameMain"></span>!</h2>
        </div>
        <!-- Card de puntos y título especial del usuario -->
        <div class="row justify-content-center mb-4">
          <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 bg-white d-flex flex-row align-items-center p-4" style="min-height:90px;">
              <div class="flex-fill text-center">
                <div class="fw-bold text-morado" style="font-size:1.2rem;">Puntos</div>
                <div id="userPointsCard" class="fw-bold" style="font-size:2rem;">0</div>
              </div>
              <div class="vr mx-4" style="height:50px;"></div>
              <div class="flex-fill text-center">
                <div class="fw-bold text-morado" style="font-size:1.2rem;">Título especial</div>
                <div id="userAwardCard" class="fw-bold" style="font-size:1.2rem;">Sin título</div>
              </div>
            </div>
          </div>
        </div>
        <h3 class="fw-bold text-morado mb-4">Temas disponibles</h3>
        <div id="temasList" class="row g-4 justify-content-center">
            <!-- Temas dinámicos con progreso -->
        </div>

        <!-- Sección para añadir preguntas (solo para expertos o superior, visible para todos) -->
        <div class="row justify-content-center mt-5">
          <div class="card shadow-sm border-0 bg-white p-4 mx-auto" style="width:90vh;">
                
              <h4 class="fw-bold text-morado mb-3">Añadir nueva pregunta</h4>
              <form id="addQuestionForm">
                <div class="mb-3">
                  <label for="questionText" class="form-label">Pregunta</label>
                  <input type="text" class="form-control" id="questionText" name="question" required disabled>
                </div>
                <div class="mb-3">
                  <label for="questionTopic" class="form-label">Tema</label>
                  <select class="form-select" id="questionTopic" name="topic_id" required disabled>
                    <option value="">Selecciona un tema</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="questionLevel" class="form-label">Nivel</label>
                  <select class="form-select" id="questionLevel" name="level_id" required disabled>
                    <option value="">Selecciona un nivel</option>
                    <option value="1">Principiante</option>
                    <option value="2">Novato</option>
                    <option value="3">Experto</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Respuestas (marca la correcta)</label>
                  <div id="answersContainer">
                    <div class="input-group mb-2">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="correct_answer" value="0" disabled>
                      </div>
                      <input type="text" class="form-control" name="answer[]" placeholder="Respuesta 1" required disabled>
                    </div>
                    <div class="input-group mb-2">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="correct_answer" value="1" disabled>
                      </div>
                      <input type="text" class="form-control" name="answer[]" placeholder="Respuesta 2" required disabled>
                    </div>
                    <div class="input-group mb-2">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="correct_answer" value="2" disabled>
                      </div>
                      <input type="text" class="form-control" name="answer[]" placeholder="Respuesta 3" required disabled>
                    </div>
                    <div class="input-group mb-2">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="correct_answer" value="3" disabled>
                      </div>
                      <input type="text" class="form-control" name="answer[]" placeholder="Respuesta 4" required disabled>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-morado w-100 fw-bold" id="addQuestionBtn" disabled>Añadir pregunta</button>
                <div class="form-text text-danger mt-2 d-none" id="addQuestionMsg"></div>
              </form>
              <div class="alert alert-info mt-3 mb-0" id="addQuestionInfo" style="display:none;"></div>
            </div>
          </div>
        </div>
    </div>
    <style>
      .user-icon-btn {
        transition: box-shadow 0.2s;
      }
      .user-icon-btn:focus, .user-icon-btn:hover {
        box-shadow: 0 0 0 0.2rem #a259e633;
      }
      .user-dropdown-menu {
        display: none;
        margin-top: 8px !important;
      }
      .show .user-dropdown-menu {
        display: block;
      }
    </style>
    <script>
      // Cargar avatar del usuario y mostrarlo en el botón superior
      function loadUserAvatar() {
        if (!quizUser || !quizUser.user_id) return;
        const img = document.getElementById('userAvatarImg');
        // Si ya tenemos avatar_url en localStorage, úsalo primero
        if (quizUser.avatar_url) {
          // Si es una URL remota (http) o CDN, úsala tal cual
          if (quizUser.avatar_url.startsWith('http')) {
            img.src = quizUser.avatar_url;
          } else {
            // Si es solo el nombre del archivo, arma la ruta relativa
            const filename = quizUser.avatar_url.split('/').pop();
            img.src = '../assets/img/users/' + filename;
          }
        }
        // Siempre consulta el backend para refrescar si hay cambios
        fetch(`../src/avatar.php?user_id=${quizUser.user_id}`)
          .then(res => res.json())
          .then(data => {
            if (img && data.avatar_url) {
              if (data.avatar_url.startsWith('http')) {
                img.src = data.avatar_url;
              } else {
                const filename = data.avatar_url.split('/').pop();
                img.src = '../assets/img/users/' + filename;
              }
              // Actualiza localStorage si cambió
              if (!quizUser.avatar_url || quizUser.avatar_url !== data.avatar_url) {
                quizUser.avatar_url = data.avatar_url;
                localStorage.setItem('quizUser', JSON.stringify(quizUser));
              }
            }
          });
      }
      // Habilitar/deshabilitar sección de añadir pregunta según rango
      function setAddQuestionSectionEnabled(enabled) {
        const form = document.getElementById('addQuestionForm');
        Array.from(form.elements).forEach(el => {
          if (el.type !== 'hidden') el.disabled = !enabled;
        });
        document.getElementById('addQuestionBtn').disabled = !enabled;
        if (!enabled) {
          document.getElementById('addQuestionInfo').style.display = 'block';
          document.getElementById('addQuestionInfo').textContent = 'Solo los usuarios con rango Experto o superior pueden añadir preguntas.';
        } else {
          document.getElementById('addQuestionInfo').style.display = 'none';
        }
      }

      // Llenar select de temas en el formulario
      function fillTopicsSelect() {
        const select = document.getElementById('questionTopic');
        temasEstaticos.forEach(t => {
          const opt = document.createElement('option');
          opt.value = t.topic_id;
          opt.textContent = t.name;
          select.appendChild(opt);
        });
      }

      // Al cargar, llenar temas y obtener puntos/rango SOLO UNA VEZ

      function refreshUserPoints() {
        if (quizUser) {
          fetch(`../src/points.php?user_id=${quizUser.user_id}`)
            .then(res => res.json())
            .then(data => {
              let points = typeof data.points === 'number' ? data.points : parseInt(data.points || '0', 10);
              let rank = data.rank;
              if (!rank || rank === 'Desconocido' || rank === '' || rank === null) {
                rank = 'Principiante';
              }
              let award = data.award_name;
              if (!award || award === '' || award === null) {
                award = 'Sin título';
              }
              let quizUserLS = JSON.parse(localStorage.getItem('quizUser')) || {};
              quizUserLS.rank = rank;
              quizUserLS.points = points;
              localStorage.setItem('quizUser', JSON.stringify(quizUserLS));
              const elPoints = document.getElementById('userPoints');
              if (elPoints) elPoints.textContent = points;
              const elPointsCard = document.getElementById('userPointsCard');
              if (elPointsCard) elPointsCard.textContent = points;
              const elRank = document.getElementById('userRank');
              if (elRank) elRank.textContent = rank;
              const elAward = document.getElementById('userAward');
              if (elAward) elAward.textContent = award;
              const elAwardCard = document.getElementById('userAwardCard');
              if (elAwardCard) elAwardCard.textContent = award;
              if (points >= 1000) {
                setAddQuestionSectionEnabled(true);
              }
            })
            .catch(() => {
              const elPoints = document.getElementById('userPoints');
              if (elPoints) elPoints.textContent = '0';
              const elPointsCard = document.getElementById('userPointsCard');
              if (elPointsCard) elPointsCard.textContent = '0';
              const elRank = document.getElementById('userRank');
              if (elRank) elRank.textContent = 'Principiante';
              const elAward = document.getElementById('userAward');
              if (elAward) elAward.textContent = 'Sin título especial';
              const elAwardCard = document.getElementById('userAwardCard');
              if (elAwardCard) elAwardCard.textContent = 'Sin título';
            });
        }
      }

      document.addEventListener('DOMContentLoaded', function() {
        fillTopicsSelect();
        setAddQuestionSectionEnabled(false);
        refreshUserPoints();
        loadUserAvatar();
        // Mostrar enlace al panel admin si el usuario es admin
        if (quizUser && quizUser.role === 'admin') {
          var adminLink = document.getElementById('adminPanelLinkContainer');
          if (adminLink) adminLink.style.display = 'block';
        }
      });

      // Refrescar puntos automáticamente al volver a la pestaña
      document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
          refreshUserPoints();
        }
      });

      // Manejar envío del formulario para añadir pregunta
      document.getElementById('addQuestionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (document.getElementById('addQuestionBtn').disabled) return;
        const form = e.target;
        const data = new FormData(form);
        // Adjuntar user_id
        if (quizUser && quizUser.user_id) {
          data.append('user_id', quizUser.user_id);
        }
        // Validación básica
        const question = data.get('question');
        const topic_id = data.get('topic_id');
        const level_id = data.get('level_id');
        const answers = data.getAll('answer[]');
        const correct = data.get('correct_answer');
        if (!question || !topic_id || !level_id || answers.length < 4 || correct === null) {
          showAddQuestionMsg('Completa todos los campos y marca la respuesta correcta.');
          return;
        }
        // Enviar a la API (debe existir src/add_question.php)
        fetch('../src/add_question.php', {
          method: 'POST',
          body: data
        })
        .then(res => res.json())
        .then(resp => {
          if (resp.success) {
            showAddQuestionMsg('Pregunta añadida correctamente.', true);
            form.reset();
            if (typeof setAlert === 'function') {
              setAlert('¡Pregunta añadida correctamente!', 'success');
            }
          } else {
            showAddQuestionMsg(resp.message || 'Error al añadir pregunta.');
            if (typeof setAlert === 'function') {
              setAlert(resp.message || 'Error al añadir pregunta.', 'danger');
            }
          }
        })
        .catch(() => {
          showAddQuestionMsg('Error de red al añadir pregunta.');
          if (typeof setAlert === 'function') {
            setAlert('Error de red al añadir pregunta.', 'danger');
          }
        });
      });

      function showAddQuestionMsg(msg, success = false) {
        const el = document.getElementById('addQuestionMsg');
        el.textContent = msg;
        el.classList.remove('d-none');
        el.classList.toggle('text-danger', !success);
        el.classList.toggle('text-success', success);
        setTimeout(() => { el.classList.add('d-none'); }, 3500);
      }
      // Mostrar nombre de usuario en navbar, dropdown y saludo principal
      const quizUser = JSON.parse(localStorage.getItem('quizUser'));
      if (quizUser) {
        const elDropdown = document.getElementById('userNameDropdown');
        if (elDropdown) elDropdown.textContent = quizUser.name;
        const elMain = document.getElementById('userNameMain');
        if (elMain) elMain.textContent = quizUser.name;
      }
      // ...eliminado: ahora todo se hace en DOMContentLoaded...
      // Dropdown: abrir/cerrar con click y cerrar al hacer click fuera
      const userDropdownBtn = document.getElementById('userDropdown');
      const dropdownMenu = document.querySelector('.user-dropdown-menu');
      const dropdownParent = userDropdownBtn.parentElement;
      userDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownParent.classList.toggle('show');
        dropdownMenu.classList.toggle('show');
      });
      document.addEventListener('click', (e) => {
        if (!dropdownParent.contains(e.target)) {
          dropdownParent.classList.remove('show');
          dropdownMenu.classList.remove('show');
        }
      });

      // Funcionalidad cerrar sesión
      document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault();
        localStorage.removeItem('quizUser');
        window.location.href = 'login.php';
      });
      // Temas y sus imágenes estáticas
      const temasEstaticos = [
        {
          topic_id: 1,
          name: 'JavaScript',
          description: 'Arte y literatura',
          icon: 'js.png'
        },
        {
          topic_id: 2,
          name: 'PHP',
          description: 'Cultura general',
          icon: 'php.png'
        },
        {
          topic_id: 3,
          name: 'laravel',
          description: 'Ciencia y naturaleza',
          icon: 'laravel.png'
        },
      ];
      const temasList = document.getElementById('temasList');
      temasList.innerHTML = '';

      // Obtener progreso del usuario por tema y nivel
      if (quizUser) {
        fetch(`../src/progress.php?user_id=${quizUser.user_id}`)
          .then(res => res.json())
          .then(progressData => {
            // Obtener todos los niveles posibles (usando el primer tema con niveles, o fallback)
            let allLevels = [];
            if (progressData.length > 0 && progressData[0].niveles) {
              allLevels = progressData[0].niveles.map(n => ({
                level_id: n.level_id,
                level_name: n.level_name
              }));
            }
            temasEstaticos.forEach(topic => {
              // Buscar progreso de este tema
              const temaProgreso = progressData.find(t => t.topic_id == topic.topic_id);
              let nivelesHtml = '';
              // Mostrar todos los niveles aunque no haya progreso
              const nivelesFijos = [
                {level_id: 1, level_name: 'Principiante', total: 25},
                {level_id: 2, level_name: 'Novato', total: 50},
                {level_id: 3, level_name: 'Experto', total: 100}
              ];
              nivelesFijos.forEach(lvl => {
                let nivel = null;
                if (temaProgreso && temaProgreso.niveles) {
                  nivel = temaProgreso.niveles.find(n => n.level_id == lvl.level_id);
                }
                let correctas = nivel && nivel.correctas !== undefined ? nivel.correctas : 0;
                let total = nivel && nivel.total !== undefined ? nivel.total : lvl.total;
                let avance = nivel && nivel.avance !== undefined ? nivel.avance : 0;
                if (nivel === null) {
                  correctas = 0;
                  total = lvl.total;
                  avance = 0;
                }
                // Corregir: los puntos se guardan como 10 por respuesta correcta
                let correctasRespuestas = Math.floor(correctas / 10);
                nivelesHtml += `
                  <div class="mb-1">
                    <span class="fw-semibold">${lvl.level_name}:</span>
                    <span>${correctasRespuestas}/${total} correctas</span>
                    <div class="progress mt-1" style="height: 8px;">
                      <div class="progress-bar bg-morado" role="progressbar" style="width: ${avance}%" aria-valuenow="${avance}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                `;
              });
              const card = document.createElement('div');
              card.className = 'col-12 col-md-8 col-lg-6';
              card.innerHTML = `
                <div class="card shadow-sm mb-3 bg-white border-0 d-flex flex-column align-items-center p-4" style="min-height:260px;">
                  <div class="w-100 d-flex flex-column align-items-center mb-2">
                    <img src="../assets/img/${topic.icon}" alt="${topic.name}" width="80" height="80" class="bg-light rounded-circle mb-2 shadow-sm" style="object-fit:cover;">
                    <h5 class="mb-1 text-center">${topic.name}</h5>
                    <small class="text-muted text-center">${topic.description}</small>
                  </div>
                  <div class="w-100 flex-grow-1">
                    <div class="mt-2">${nivelesHtml}</div>
                  </div>
                  <div class="w-100 d-flex justify-content-center mt-3">
                    <button class="btn btn-morado btn-lg px-5 py-2 fw-bold" style="font-size:1.2rem;" onclick="window.location.href='play.php?topic_id=${topic.topic_id}'">Jugar</button>
                  </div>
                </div>
              `;
              temasList.appendChild(card);
            });
          })
          .catch(() => {
            // Si falla la carga del progreso, no mostrar nada (evita duplicados sin barras)
          });
      }
    </script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/errorHandler.js"></script>
    <script>
    // Alerta flotante reutilizable para feedback de acciones (setAlert)
    window.setAlert = function(msg, type = 'success', duration = 3500) {
      let alertId = 'floatingActionAlertBox';
      let alertBox = document.getElementById(alertId);
      if (!alertBox) {
        alertBox = document.createElement('div');
        alertBox.id = alertId;
        alertBox.style.position = 'fixed';
        alertBox.style.top = '30px';
        alertBox.style.left = '50%';
        alertBox.style.transform = 'translateX(-50%)';
        alertBox.style.minWidth = '320px';
        alertBox.style.maxWidth = '90vw';
        alertBox.style.background = '#fff';
        alertBox.style.color = (type === 'success') ? '#198754' : '#dc3545';
        alertBox.style.border = '1.5px solid ' + ((type === 'success') ? '#198754' : '#dc3545');
        alertBox.style.boxShadow = '0 4px 16px rgba(0,0,0,0.12)';
        alertBox.style.borderRadius = '12px';
        alertBox.style.padding = '18px 28px 14px 28px';
        alertBox.style.fontSize = '1.1rem';
        alertBox.style.fontFamily = 'system-ui, sans-serif';
        alertBox.style.zIndex = '99999';
        alertBox.style.display = 'flex';
        alertBox.style.alignItems = 'center';
        alertBox.style.gap = '12px';
        alertBox.style.pointerEvents = 'auto';
        alertBox.innerHTML = '<span style="font-size:1.5em;">' + (type === 'success' ? '&#x2705;' : '&#9888;&#65039;') + '</span><span id="floatingActionAlertMsg"></span>';
        document.body.appendChild(alertBox);
        alertBox.addEventListener('click', function() {
          alertBox.style.display = 'none';
        });
      } else {
        alertBox.style.display = 'flex';
        alertBox.style.color = (type === 'success') ? '#198754' : '#dc3545';
        alertBox.style.border = '1.5px solid ' + ((type === 'success') ? '#198754' : '#dc3545');
        alertBox.innerHTML = '<span style="font-size:1.5em;">' + (type === 'success' ? '&#x2705;' : '&#9888;&#65039;') + '</span><span id="floatingActionAlertMsg"></span>';
      }
      document.getElementById('floatingActionAlertMsg').textContent = msg;
      clearTimeout(window._floatingActionAlertTimeout);
      window._floatingActionAlertTimeout = setTimeout(function() {
        alertBox.style.display = 'none';
      }, duration);
    };
    </script>
</body>
</html>
