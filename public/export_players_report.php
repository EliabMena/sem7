<?php
// export_players_report.php
session_start();
require_once __DIR__ . '/../src/db.php';

if (!isset($_SESSION['quizUser']) || $_SESSION['quizUser']['role'] !== 'admin') {
    die('Acceso denegado');
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="reporte_jugadores.xls"');

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Tema</th><th>Nivel</th><th>Puntos</th><th>% Avance</th><th>Inicio</th><th>Fin</th><th>Tiempo (min)</th></tr>";

$sql = "SELECT u.user_id, u.name, u.email, t.topic_name, l.level_name, p.points_obtained, p.percentage_advance, p.start_time, p.end_time
        FROM Users u
        JOIN PlayerProgress p ON u.user_id = p.user_id
        JOIN Topics t ON p.topic_id = t.topic_id
        JOIN Levels l ON p.level_id = l.level_id
        ORDER BY u.user_id, t.topic_name, l.level_id";

$stmt = $pdo->query($sql);
// Definir safe() una sola vez fuera del bucle
function safe($v) { return $v === null ? '' : htmlspecialchars($v); }
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $tiempo = '';
    if (!empty($row['start_time']) && !empty($row['end_time'])) {
        $start = strtotime($row['start_time']);
        $end = strtotime($row['end_time']);
        $tiempo = round(($end - $start) / 60, 2);
    }
    echo "<tr>"
        ."<td>".safe($row['user_id'])."</td>"
        ."<td>".safe($row['name'])."</td>"
        ."<td>".safe($row['email'])."</td>"
        ."<td>".safe($row['topic_name'])."</td>"
        ."<td>".safe($row['level_name'])."</td>"
        ."<td>".safe($row['points_obtained'])."</td>"
        ."<td>".safe($row['percentage_advance'])."</td>"
        ."<td>".safe($row['start_time'])."</td>"
        ."<td>".safe($row['end_time'])."</td>"
        ."<td>".$tiempo."</td>"
        ."</tr>";
}
echo "</table>";
