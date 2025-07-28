<?php
// api.php - Entrada principal de la API
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

$resource = $_GET['resource'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

switch ($resource) {
    case 'topics':
        require_once __DIR__ . '/topics.php';
        break;
    case 'questions':
        require_once __DIR__ . '/questions.php';
        break;
    case 'answers':
        require_once __DIR__ . '/answers.php';
        break;
    case 'users':
        require_once __DIR__ . '/users.php';
        break;
    default:
        echo json_encode(['error' => 'Recurso no encontrado']);
        http_response_code(404);
        break;
}
