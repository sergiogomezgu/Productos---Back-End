<?php
// public/eliminar_reserva_admin.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../includes/verificar_admin.php';

// Verificación ya hecha por verificar_admin.php
// Si el usuario no es admin, ya se habrá redirigido o bloqueado

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: ver_reservas_admin.php");
    exit();
}

// Eliminar la reserva
$stmt = $conn->prepare("DELETE FROM transfer_reservas WHERE id_reserva = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Redirigir al panel
header("Location: ver_reservas_admin.php?msg=eliminado");
exit();
