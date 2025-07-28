<?php
// users.php - Endpoints para usuarios (solo GET básico)
if ($method === 'GET') {
    $stmt = $pdo->query('SELECT user_id, email, name, role FROM Users');
    $users = $stmt->fetchAll();
    echo json_encode($users);
    exit;
}

// Registro de usuario
if ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['email'], $data['name'], $data['password'])) {
        echo json_encode(['error' => 'Faltan datos requeridos']);
        http_response_code(400);
        exit;
    }
    $email = $data['email'];
    $name = $data['name'];
    $password = hash('sha256', $data['password']);
    // Verificar si el email ya existe
    $stmt = $pdo->prepare('SELECT user_id FROM Users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['error' => 'El email ya está registrado']);
        http_response_code(409);
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO Users (email, name, password) VALUES (?, ?, ?)');
    $stmt->execute([$email, $name, $password]);
    $new_user_id = $pdo->lastInsertId();

    // Insertar progreso inicial en PlayerProgress para todos los temas y niveles
    // Obtener todos los topic_id
    $topics = $pdo->query('SELECT topic_id FROM Topics')->fetchAll(PDO::FETCH_COLUMN);
    // Obtener todos los level_id
    $levels = $pdo->query('SELECT level_id FROM Levels')->fetchAll(PDO::FETCH_COLUMN);
    // Insertar para cada combinación
    $pp_stmt = $pdo->prepare('INSERT INTO PlayerProgress (user_id, topic_id, level_id, points_obtained, percentage_advance, start_time, end_time) VALUES (?, ?, ?, 0, 0, NULL, NULL)');
    foreach ($topics as $topic_id) {
        foreach ($levels as $level_id) {
            $pp_stmt->execute([$new_user_id, $topic_id, $level_id]);
        }
    }
    echo json_encode(['success' => true, 'user_id' => $new_user_id]);
    exit;
}

// Login de usuario
if ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['email'], $data['password'])) {
        echo json_encode(['error' => 'Faltan datos requeridos']);
        http_response_code(400);
        exit;
    }
    $email = $data['email'];
    $password = hash('sha256', $data['password']);

    // Actualizar datos de usuario (PUT)
    if ($method === 'PUT') {
        parse_str(file_get_contents('php://input'), $put_vars);
        $data = json_decode(json_encode($put_vars), true);
        $user_id = $_GET['user_id'] ?? null;
        if (!$user_id) {
            echo json_encode(['error' => 'Falta user_id en la URL']);
            http_response_code(400);
            exit;
        }
        // Solo permitir actualizar email, name, password, role
        $fields = [];
        $params = [];
        if (isset($data['email'])) {
            $fields[] = 'email = ?';
            $params[] = $data['email'];
        }
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = $data['name'];
        }
        if (isset($data['password'])) {
            $fields[] = 'password = ?';
            $params[] = hash('sha256', $data['password']);
        }
        if (isset($data['role'])) {
            $fields[] = 'role = ?';
            $params[] = $data['role'];
        }
        if (empty($fields)) {
            echo json_encode(['error' => 'No hay datos para actualizar']);
            http_response_code(400);
            exit;
        }
        $params[] = $user_id;
        $sql = 'UPDATE Users SET ' . implode(', ', $fields) . ' WHERE user_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        echo json_encode(['success' => true]);
        exit;
    }

    // Preparar y ejecutar el login correctamente
    $stmt = $pdo->prepare('SELECT user_id, email, name, role FROM Users WHERE email = ? AND password = ?');
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();
    if ($user) {
        session_start();
        $_SESSION['quizUser'] = $user;
        setcookie('quizUser', json_encode($user), time() + 3600, '/');
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['error' => 'Credenciales incorrectas']);
        http_response_code(401);
    }
    exit;
}

echo json_encode(['error' => 'Método no permitido']);
http_response_code(405);
