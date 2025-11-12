<?php
session_start();
include_once '../config/db.php';

if (!isset($_SESSION['viajero_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "No autorizado"]);
    exit();
}

$id_viajero = $_SESSION['viajero_id'];

$sql = "SELECT r.id_reserva, r.fecha_entrada, r.hora_entrada, t.Descripción AS tipo
        FROM transfer_reservas r
        JOIN transfer_tipo_reserva t ON r.id_tipo_reserva = t.id_tipo_reserva
        WHERE r.email_cliente = ?
        ORDER BY r.fecha_entrada";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_viajero);
$stmt->execute();
$result = $stmt->get_result();

$eventos = [];

while ($row = $result->fetch_assoc()) {
    $color = '#3788d8';
    if ($row['tipo'] == 'Aeropuerto → Hotel') $color = '#28a745';
    elseif ($row['tipo'] == 'Hotel → Aeropuerto') $color = '#17a2b8';
    elseif ($row['tipo'] == 'Ida y vuelta') $color = '#ffc107';

    $eventos[] = [
        'title' => $row['tipo'] . ' (ID ' . $row['id_reserva'] . ')',
        'start' => $row['fecha_entrada'] . 'T' . $row['hora_entrada'],
        'color' => $color
    ];
}

header('Content-Type: application/json');
echo json_encode($eventos);
