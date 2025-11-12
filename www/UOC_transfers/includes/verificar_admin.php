<?php
// includes/verificar_admin.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/db.php';

// Redirigir si no hay sesión
if (!isset($_SESSION['email'])) {
    header("Location: ../public/login.php");
    exit();
}

// Lista de emails de administradores (editar aquí para añadir/quitar)
$admin_emails = [
    'admin@transfers.com',
    // 'otroadmin@ejemplo.com'
];

// Normalizar email de sesión y lista para comparación segura
$email_sesion = trim(strtolower($_SESSION['email']));
$admin_emails = array_map(function($e){ return trim(strtolower($e)); }, $admin_emails);

// Comprobar contra la lista local
if (!in_array($email_sesion, $admin_emails, true)) {
    // Como comprobación adicional opcional, verificar que el usuario existe en la BD
    $stmt = $conn->prepare("SELECT id_viajero, email FROM transfer_viajeros WHERE email = ?");
    $stmt->bind_param("s", $email_sesion);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if (!$user || !in_array($email_sesion, $admin_emails, true)) {
        http_response_code(403);
        echo "<p style='color:red; font-weight:bold;'>⛔ Acceso denegado. Esta sección es solo para administradores.</p>";
        exit();
    }
}
