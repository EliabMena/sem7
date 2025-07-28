<?php
// add_question.php - Formulario para añadir preguntas (solo para usuarios con nivel Experto o superior)
// Este archivo es una vista, no un endpoint API
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Pregunta - QuizApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm p-4">
                    <h3 class="fw-bold text-morado mb-3">Añadir nueva pregunta</h3>
                    <div id="addQuestionAlert"></div>
                    <form id="addQuestionForm">
                        <div class="mb-3">
                            <label for="topic" class="form-label">Tema</label>
                            <select class="form-select" id="topic" name="topic_id" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Nivel</label>
                            <select class="form-select" id="level" name="level_id" required>
                                <option value="1">Principiante</option>
                                <option value="2">Novato</option>
                                <option value="3">Experto</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="question_text" class="form-label">Pregunta</label>
                            <textarea class="form-control" id="question_text" name="question_text" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de pregunta</label>
                            <select class="form-select" id="question_type" name="question_type" required>
                                <option value="multiple_choice">Opción múltiple</option>
                                <option value="true_false">Verdadero/Falso</option>
                            </select>
                        </div>
                        <div id="answersSection" class="mb-3">
                            <label class="form-label">Respuestas</label>
                            <div id="answersList"></div>
                            <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="addAnswerBtn">Agregar respuesta</button>
                        </div>
                        <button type="submit" class="btn btn-morado w-100 fw-bold">Guardar pregunta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Solo permitir acceso si el usuario es Experto o superior
    const quizUser = JSON.parse(localStorage.getItem('quizUser'));
    function checkRank() {
      fetch('../src/points.php?user_id=' + quizUser.user_id)
        .then(res => res.json())
        .then(data => {
          const rank = data.rank || '';
          if (rank !== 'Experto') {
            document.body.innerHTML = '<div class="container py-5"><div class="alert alert-danger">Solo usuarios con nivel <b>Experto</b> pueden añadir preguntas.</div></div>';
          }
        });
    }
    if (!quizUser) {
      window.location.href = 'login.php';
    } else {
      checkRank();
    }
    // Cargar temas
    fetch('../src/topics.php')
      .then(res => res.json())
      .then(data => {
        const topicSelect = document.getElementById('topic');
        data.forEach(t => {
          const opt = document.createElement('option');
          opt.value = t.topic_id;
          opt.textContent = t.topic_name;
          topicSelect.appendChild(opt);
        });
      });
    // Manejo dinámico de respuestas
    const answersList = document.getElementById('answersList');
    const addAnswerBtn = document.getElementById('addAnswerBtn');
    function addAnswerField() {
      const idx = answersList.children.length;
      const div = document.createElement('div');
      div.className = 'input-group mb-2';
      div.innerHTML = `
        <input type="text" class="form-control" name="answer_text_${idx}" placeholder="Respuesta" required>
        <span class="input-group-text">
          <input type="checkbox" name="is_correct_${idx}" title="Correcta">
        </span>
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentNode.remove()">&times;</button>
      `;
      answersList.appendChild(div);
    }
    addAnswerBtn.addEventListener('click', addAnswerField);
    // Al menos dos respuestas por defecto
    addAnswerField();
    addAnswerField();
    // Enviar formulario
    document.getElementById('addQuestionForm').addEventListener('submit', function(e) {
      e.preventDefault();
      // Construir answers
      const answers = [];
      Array.from(answersList.children).forEach((div, i) => {
        const text = div.querySelector('input[type=text]').value.trim();
        const isCorrect = div.querySelector('input[type=checkbox]').checked;
        if (text) answers.push({ answer_text: text, is_correct: isCorrect });
      });
      if (answers.length < 2) {
        showAlert('Agrega al menos dos respuestas.', 'danger');
        return;
      }
      if (!answers.some(a => a.is_correct)) {
        showAlert('Marca al menos una respuesta como correcta.', 'danger');
        return;
      }
      const payload = {
        topic_id: document.getElementById('topic').value,
        level_id: document.getElementById('level').value,
        question_text: document.getElementById('question_text').value,
        question_type: document.getElementById('question_type').value,
        answers
      };
      fetch('../src/questions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showAlert('Pregunta añadida correctamente.', 'success');
          this.reset();
          answersList.innerHTML = '';
          addAnswerField();
          addAnswerField();
        } else {
          showAlert(data.error || 'Error al añadir pregunta.', 'danger');
        }
      })
      .catch(() => showAlert('Error de red.', 'danger'));
    });
    function showAlert(msg, type) {
      document.getElementById('addQuestionAlert').innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
    }
    </script>
<script src="../assets/js/errorHandler.js"></script>
</body>
</html>
