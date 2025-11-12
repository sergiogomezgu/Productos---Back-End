<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once '../config/db.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../auth/login.php");
    exit();
}

$email = $_SESSION['email'];

// Obtenemos información del usuario
$stmt_user = $conn->prepare("SELECT * FROM transfer_viajeros WHERE email = ?");
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user = $user_result->fetch_assoc();

$id_reserva = $_GET['id'] ?? null;

if (!$id_reserva) {
    header("Location: panel_usuario.php");
    exit();
}

// Obtener la reserva
$stmt_res = $conn->prepare("SELECT * FROM transfer_reservas WHERE id_reserva = ? AND id_viajero = ?");
$stmt_res->bind_param("ii", $id_reserva, $user['id_viajero']);
$stmt_res->execute();
$reserva_result = $stmt_res->get_result();
$reserva = $reserva_result->fetch_assoc();

if (!$reserva) {
    echo "Reserva no encontrada.";
    exit();
}

// Función para comprobar restricción de 48h
function puede_eliminar($fecha_reserva) {
    $ahora = new DateTime();
    $fecha = new DateTime($fecha_reserva);
    $diff = $ahora->diff($fecha);
    return $diff->days >= 2;
}

if (!puede_eliminar($reserva['fecha_reserva'])) {
    echo "No puedes cancelar esta reserva, quedan menos de 48 horas.";
    exit();
}

// Eliminar reserva
$stmt_del = $conn->prepare("DELETE FROM transfer_reservas WHERE id_reserva=? AND id_viajero=?");
$stmt_del->bind_param("ii", $id_reserva, $user['id_viajero']);
$stmt_del->execute();

header("Location: panel_usuario.php");
exit();
