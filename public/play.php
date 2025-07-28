<?php
// play.php - Página de juego de trivia
require_once __DIR__ . '/../src/db.php';
session_start();
$quizUser = isset($_SESSION['quizUser']) ? $_SESSION['quizUser'] : null;
if (!$quizUser && isset($_COOKIE['quizUser'])) {
  $quizUser = json_decode($_COOKIE['quizUser'], true);
}
if (!$quizUser) {
  header('Location: login.php');
  exit;
}
$topic_id = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : null;
if (!$topic_id) {
  header('Location: dashboard.php');
  exit;
}
// Obtener nombre del tema
$stmt = $pdo->prepare('SELECT topic_name FROM Topics WHERE topic_id = ?');
$stmt->execute([$topic_id]);
$topic = $stmt->fetchColumn();
if (!$topic) {
  header('Location: dashboard.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jugar - <?= htmlspecialchars($topic) ?> | QuizApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <style>
    body { background: #f8f9fa; }
    .timer { font-size: 1.5rem; font-weight: bold; color: #6f42c1; }
    .question-card { border: 2px solid #6f42c1; border-radius: 18px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .btn-morado { background: #6f42c1; color: #fff; border: none; }
    .btn-morado:hover { background: #563d7c; color: #fff; }
    .answer-btn {
      margin-bottom: 10px;
      border: 2px solid #6f42c1;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(111,66,193,0.10), 0 1.5px 4px rgba(0,0,0,0.06);
      background: #fff;
      color: #6f42c1;
      font-weight: 500;
      transition: box-shadow 0.2s, border-color 0.2s, background 0.2s, color 0.2s;
    }
    .answer-btn:hover:not(:disabled), .answer-btn:focus-visible {
      box-shadow: 0 4px 16px rgba(111,66,193,0.18), 0 2px 8px rgba(0,0,0,0.10);
      border-color: #563d7c;
      background: #f3eaff;
      color: #563d7c;
      cursor: pointer;
    }
    .answer-btn:active {
      background: #e5d4fa;
      color: #fff;
      border-color: #6f42c1;
    }
    .btn-success {
      background: #28a745 !important;
      color: #fff !important;
      border-color: #28a745 !important;
    }
    .btn-danger {
      background: #dc3545 !important;
      color: #fff !important;
      border-color: #dc3545 !important;
    }
  </style>
</head>
<body>
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: #f8f9fa;">
    <div class="row w-100 justify-content-center align-items-center">
      <div class="col-12 col-md-10 col-lg-8 col-xl-6">
        <div class="question-card p-5 bg-white" style="min-height: 420px; min-width: 380px; max-width: 700px; margin:auto;">
          <h3 class="fw-bold text-morado mb-2"><?= htmlspecialchars($topic) ?></h3>
          <div id="gameLevelSelect" class="mb-3">
            <label for="levelSelect" class="form-label">Selecciona tu nivel:</label>
            <select id="levelSelect" class="form-select">
              <option value="1">Principiante</option>
              <option value="2">Novato</option>
              <option value="3">Experto</option>
            </select>
            <button id="startGameBtn" class="btn btn-morado mt-2 w-100">Comenzar</button>
          </div>
          <div id="gameArea" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span id="questionCounter" class="fw-bold"></span>
              <span class="timer" id="timer">60</span>
            </div>
            <div id="questionText" class="mb-3 fw-bold"></div>
            <div id="answersArea"></div>
            <button id="nextBtn" class="btn btn-morado w-100 mt-3" style="display:none;">Siguiente</button>
          </div>
          <div id="gameResult" style="display:none;"></div>
          <a href="dashboard.php" class="btn btn-link mt-3">&larr; Volver al dashboard</a>
        </div>
      </div>
    </div>
  </div>
<script>
const topic_id = <?= (int)$topic_id ?>;
let questions = [];
let current = 0;
let score = 0;
let timer = 60;
let timerInterval = null;
let userLevel = 1;

function shuffle(arr) {
  for (let i = arr.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [arr[i], arr[j]] = [arr[j], arr[i]];
  }
}

document.getElementById('startGameBtn').onclick = async function() {
  userLevel = parseInt(document.getElementById('levelSelect').value);
  const res = await fetch(`../src/questions.php?topic_id=${topic_id}&level_id=${userLevel}`);
  questions = await res.json();
  shuffle(questions);
  questions = questions.slice(0, 10);
  current = 0;
  score = 0;
  document.getElementById('gameLevelSelect').style.display = 'none';
  document.getElementById('gameArea').style.display = '';
  document.getElementById('gameResult').style.display = 'none';
  showQuestion();
};

function showQuestion() {
  if (current >= questions.length) {
    endGame();
    return;
  }
  timer = 60;
  document.getElementById('timer').textContent = timer;
  document.getElementById('questionCounter').textContent = `Pregunta ${current+1} de ${questions.length}`;
  document.getElementById('nextBtn').style.display = 'none';
  const q = questions[current];
  document.getElementById('questionText').textContent = q.question_text;
  // Mostrar respuestas directamente
  const area = document.getElementById('answersArea');
  area.innerHTML = '';
  if (Array.isArray(q.answers)) {
    q.answers.forEach((ans, idx) => {
      const btn = document.createElement('button');
      btn.className = 'btn btn-outline-morado answer-btn w-100';
      btn.textContent = ans.answer_text;
      btn.onclick = () => checkAnswer(ans.is_correct, btn);
      area.appendChild(btn);
    });
  }
  startTimer();
}

function startTimer() {
  clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    timer--;
    document.getElementById('timer').textContent = timer;
    if (timer <= 0) {
      clearInterval(timerInterval);
      document.getElementById('nextBtn').style.display = '';
      disableAnswers();
    }
  }, 1000);
}

function checkAnswer(isCorrect, btn) {
  clearInterval(timerInterval);
  disableAnswers();
  if (isCorrect) {
    btn.classList.add('btn-success');
    score++;
    // Actualizar puntos en localStorage
    let quizUser = localStorage.getItem('quizUser');
    if (quizUser) {
      quizUser = JSON.parse(quizUser);
      quizUser.points = (quizUser.points || 0) + 10;
      localStorage.setItem('quizUser', JSON.stringify(quizUser));
    }
  } else {
    btn.classList.add('btn-danger');
  }
  document.getElementById('nextBtn').style.display = '';
}

function disableAnswers() {
  document.querySelectorAll('.answer-btn').forEach(btn => btn.disabled = true);
}

document.getElementById('nextBtn').onclick = function() {
  current++;
  showQuestion();
};

function endGame() {
  document.getElementById('gameArea').style.display = 'none';
  document.getElementById('gameResult').style.display = '';
  const puntos = score * 10;
  // Actualizar puntos en la base de datos
  // Obtener user_id del localStorage
  let quizUser = localStorage.getItem('quizUser');
  let user_id = null;
  if (quizUser) {
    try {
      user_id = JSON.parse(quizUser).user_id;
    } catch (e) { user_id = null; }
  }
  fetch('../src/update_points.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id: user_id, points: puntos, topic_id: topic_id, level_id: userLevel })
  })
    .then(r => r.json())
    .then(data => {
      // Actualizar puntos en localStorage
      let quizUser = localStorage.getItem('quizUser');
      if (quizUser) {
        quizUser = JSON.parse(quizUser);
        quizUser.points = (quizUser.points || 0) + puntos;
        localStorage.setItem('quizUser', JSON.stringify(quizUser));
      }
    });
  document.getElementById('gameResult').innerHTML = `
    <div class='alert alert-success text-center'>
      <h4>¡Juego terminado!</h4>
      <div style="font-size:2.5rem; color:#ffc107; margin-bottom:10px;">
        <svg xmlns='http://www.w3.org/2000/svg' width='48' height='48' fill='currentColor' class='bi bi-trophy-fill' viewBox='0 0 16 16'>
          <path d='M2 1a1 1 0 0 0-1 1v2a5 5 0 0 0 4 4.9V10a2 2 0 0 0 1 1.73V13H4.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1H10V11.73A2 2 0 0 0 11 10V7.9a5 5 0 0 0 4-4.9V2a1 1 0 0 0-1-1H2Zm1 2v1a4 4 0 0 0 3 3.87V10a1 1 0 0 0 2 0V6.87A4 4 0 0 0 13 4V2H3v1Z'/>
        </svg>
      </div>
      <p>Preguntas correctas: <b>${score} / ${questions.length}</b></p>
      <p>Puntos obtenidos: <b>${puntos}</b></p>
    </div>
  `;
  document.getElementById('gameLevelSelect').style.display = '';
}
</script>
</body>
</html>
