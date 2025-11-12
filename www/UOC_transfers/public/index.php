<?php
session_start();

// Redirigir si ya está logueado y NO viene de logout
if (isset($_SESSION['email']) && (!isset($_GET['msg']) || $_GET['msg'] !== 'logout')) {
    $admin_emails = ['admin@isla.com', 'admin@transfers.com']; // añade aquí todos los emails de admin
    $email = trim(strtolower($_SESSION['email']));

    if (in_array($email, $admin_emails)) {
        header("Location: panel_admin.php");
        exit();
    } else {
        header("Location: panel.php");
        exit();
    }
}
?>

<?php include_once '../includes/header.php'; ?>

<main class="container" style="max-width:900px; margin:40px auto; font-family:sans-serif; line-height:1.6;">
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'logout'): ?>
        <div style="background:#dff0d8; color:#3c763d; padding:10px 20px; margin-bottom:20px; border-radius:6px; text-align:center;">
            Has cerrado sesión correctamente.
        </div>
    <?php endif; ?>

    <h1 style="text-align:center; color:#0077cc;">Isla Transfers</h1>
    <p style="text-align:center; font-size:1.1em;">
        Bienvenido/a al sistema de <strong>reserva y gestión de transfers</strong> entre el aeropuerto y los hoteles de la isla.
    </p>

    <section style="margin-top:40px;">
        <h2>🚐 ¿Quiénes somos?</h2>
        <p>
            Somos <strong>Isla Transfers</strong>, una empresa dedicada a realizar traslados de viajeros desde y hacia el aeropuerto.
            Ofrecemos un servicio rápido, seguro y cómodo tanto para clientes particulares como para hoteles que gestionan
            las reservas de sus huéspedes.
        </p>
    </section>

    <section style="margin-top:40px;">
        <h2>📅 Cómo funciona</h2>
        <ol>
            <li>Regístrate o inicia sesión en la plataforma.</li>
            <li>Selecciona el tipo de trayecto: aeropuerto → hotel, hotel → aeropuerto, o ida y vuelta.</li>
            <li>Indica los datos del vuelo, el hotel y el número de viajeros.</li>
            <li>Recibirás un <strong>localizador único</strong> con los detalles de tu reserva por email.</li>
            <li>Recuerda: las reservas deben hacerse con un mínimo de <strong>48 horas</strong> de antelación.</li>
        </ol>
    </section>

    <section style="margin-top:40px;">
        <h2>👥 Tipos de usuario</h2>
        <ul>
            <li><strong>Cliente particular:</strong> puede hacer reservas y consultar sus datos personales.</li>
            <li><strong>Cliente corporativo (hotel):</strong> gestiona reservas desde el panel de administración.</li>
            <li><strong>Administrador:</strong> puede crear, modificar y cancelar cualquier reserva, y ver el calendario completo.</li>
        </ul>
    </section>

    <section style="margin-top:40px; text-align:center;">
        <a href="../public/login.php" style="display:inline-block; background:#0077cc; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; margin-right:10px;">
            Iniciar sesión
        </a>
        <a href="../public/register.php" style="display:inline-block; background:#00aa55; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
            Registrarse
        </a>
    </section>

    <section style="margin-top:60px; text-align:center; font-size:0.9em; color:#666;">
        <p>Los pagos se realizan siempre en efectivo en el momento del trayecto.</p>
        <p>© <?= date('Y'); ?> Isla Transfers — Todos los derechos reservados</p>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>
