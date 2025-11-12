<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once '../config/db.php';

// Comprobamos que el usuario está logueado
if (!isset($_SESSION['email'])) {
    header("Location: ../public/login.php");
    exit();
}

$email = $_SESSION['email'];

// Obtenemos información del usuario
$stmt_user = $conn->prepare("SELECT * FROM transfer_viajeros WHERE email = ?");
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$user_result = $stmt_user->get_result();
$user = $user_result->fetch_assoc();

if (!$user || !is_array($user)) {
    echo "<p style='color:red;'>❌ Error: no se pudo obtener el usuario correctamente.</p>";
    exit();
}

// Mostrar el ID del usuario logueado (opcional para depuración)
echo "<p>ID del usuario logueado: " . $user['id_viajero'] . "</p>";

// Obtenemos las reservas del usuario
$stmt_res = $conn->prepare("
    SELECT r.*, h.usuario AS nombre_hotel, t.Descripción AS tipo_reserva
    FROM transfer_reservas r
    JOIN tranfer_hotel h ON r.id_hotel = h.id_hotel
    JOIN transfer_tipo_reserva t ON r.id_tipo_reserva = t.id_tipo_reserva
    WHERE r.email_cliente = ?
    ORDER BY r.fecha_reserva ASC
");
$stmt_res->bind_param("i", $user['id_viajero']);
$stmt_res->execute();
$reservas_result = $stmt_res->get_result();

// Función para comprobar restricción de 48 horas
function puede_modificar($fecha_reserva) {
    $ahora = new DateTime();
    $fecha = new DateTime($fecha_reserva);
    $diff = $ahora->diff($fecha);
    return $diff->days >= 2; // mínimo 2 días = 48h
}
?>

<?php include_once '../includes/header.php'; ?>

<main class="container" style="max-width:900px; margin:40px auto; font-family:sans-serif; line-height:1.6;">
    <h1 style="text-align:center; color:#0077cc;">Bienvenido, <?= htmlspecialchars($user['nombre']) ?></h1>
    <p style="text-align:center;">Aquí puedes ver tus reservas y crear nuevas.</p>

    <section style="margin-top:40px;">
        <h2>📋 Mis reservas</h2>
        <?php if ($reservas_result->num_rows > 0): ?>
            <table border="1" cellpadding="10" cellspacing="0" style="width:100%; text-align:left;">
                <thead>
                    <tr style="background:#0077cc; color:white;">
                        <th>ID</th>
                        <th>Hotel</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Viajeros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($res = $reservas_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $res['id_reserva'] ?></td>
                        <td><?= htmlspecialchars($res['nombre_hotel']) ?></td>
                        <td><?= htmlspecialchars($res['tipo_reserva']) ?></td>
                        <td><?= $res['fecha_reserva'] ?></td>
                        <td><?= $res['num_viajeros'] ?></td>
                        <td>
                            <?php if (puede_modificar($res['fecha_reserva'])): ?>
                                <a href="editar_reserva_usuario.php?id=<?= $res['id_reserva'] ?>">Editar</a> |
                                <a href="eliminar_reserva_usuario.php?id=<?= $res['id_reserva'] ?>" onclick="return confirm('¿Estás seguro de cancelar la reserva?')">Cancelar</a>
                            <?php else: ?>
                                <em>No modificable</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes reservas todavía.</p>
        <?php endif; ?>
    </section>

    <section style="margin-top:40px; text-align:center;">
    <a href="crear_reserva.php" style="display:inline-block; background:#00aa55; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; margin-right:10px;">
        Crear nueva reserva
    </a>
    <a href="calendario_usuario.php" style="display:inline-block; background:#0077cc; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
        Ver calendario
    </a>
</section>

</main>

<?php include_once '../includes/footer.php'; ?>
