<?php
// topics.php - Endpoints para temas
if ($method === 'GET') {
    $stmt = $pdo->query('SELECT topic_id, topic_name FROM Topics');
    $topics = $stmt->fetchAll();
    echo json_encode($topics);
    exit;
}
echo json_encode(['error' => 'Método no permitido']);
http_response_code(405);
