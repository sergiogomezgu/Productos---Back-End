<?php
session_start();
include_once __DIR__ . '/../config/db.php';

// Si no hay sesión, volvemos al login
if (!isset($_SESSION['viajero_id'])) {
    header("Location: /UOC_transfers/public/auth/login.php");
    exit();
}

// Obtenemos datos del usuario
$stmt = $conn->prepare("SELECT * FROM transfer_viajeros WHERE id_viajero = ?");
$stmt->bind_param("i", $_SESSION['viajero_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "Usuario no encontrado";
    exit();
}

// Determinar rol: admin si el email es 'admin@isla.com', usuario normal si no
$rol = ($user['email'] === 'admin@isla.com') ? 'admin' : 'usuario';
?>

<?php include_once '../includes/header.php'; ?>

<main class="container" style="max-width:900px; margin:40px auto; font-family:sans-serif;">
    <h1>Panel de <?= ucfirst($rol) ?></h1>
    <p>Bienvenido/a, <strong><?= htmlspecialchars($user['nombre']) ?></strong>!</p>

    <?php if ($rol === 'usuario'): ?>
        <h2>Tus reservas</h2>
        <?php
        $stmt2 = $conn->prepare("SELECT r.id_reserva, r.fecha_reserva, h.usuario AS hotel, t.Descripción AS tipo_reserva
                                 FROM transfer_reservas r
                                 JOIN tranfer_hotel h ON r.id_hotel = h.id_hotel
                                 JOIN transfer_tipo_reserva t ON r.id_tipo_reserva = t.id_tipo_reserva
                                 WHERE r.email_cliente = ?");
        $stmt2->bind_param("s", $user['email']);
        $stmt2->execute();
        $reservas = $stmt2->get_result();
        ?>
        <?php if ($reservas->num_rows > 0): ?>
            <table border="1" cellpadding="5">
                <tr>
                    <th>ID Reserva</th>
                    <th>Fecha</th>
                    <th>Hotel</th>
                    <th>Tipo de reserva</th>
                </tr>
                <?php while($row = $reservas->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_reserva'] ?></td>
                        <td><?= $row['fecha_reserva'] ?></td>
                        <td><?= htmlspecialchars($row['hotel']) ?></td>
                        <td><?= htmlspecialchars($row['tipo_reserva']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No tienes reservas todavía.</p>
        <?php endif; ?>

        <a href="crear_reserva.php">Crear nueva reserva</a>

    <?php elseif ($rol === 'admin'): ?>
        <h2>Panel de administración</h2>
        <p>Puedes ver todas las reservas, crear, modificar o cancelar reservas.</p>
        <a href="crear_reserva.php">Crear reserva</a>
        <a href="ver_reservas_admin.php">Ver todas las reservas</a>
    <?php endif; ?>
</main>

<?php include_once '../includes/footer.php'; ?>
