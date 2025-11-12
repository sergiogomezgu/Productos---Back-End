<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once '../config/db.php';

// Comprobamos que el usuario es admin
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT rol FROM transfer_viajeros WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['rol'] !== 'admin') {
    echo "No tienes permisos para acceder a esta página.";
    exit();
}

// Obtenemos id de reserva
$id_reserva = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_reserva <= 0) {
    echo "ID de reserva no válido.";
    exit();
}

// Borramos la reserva
$stmt_delete = $conn->prepare("DELETE FROM transfer_reservas WHERE id_reserva = ?");
$stmt_delete->bind_param("i", $id_reserva);

if ($stmt_delete->execute()) {
    $mensaje = "Reserva cancelada correctamente.";
} else {
    $mensaje = "Error al cancelar la reserva: " . $stmt_delete->error;
}

// Redirigimos de nuevo a la lista
header("Location: ver_reservas_admin.php?mensaje=" . urlencode($mensaje));
exit();
