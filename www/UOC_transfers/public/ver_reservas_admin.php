<?php
// public/ver_reservas_admin.php - versión ajustada a tu esquema
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../includes/verificar_admin.php';

// verificar_admin.php ya garantiza que el usuario en sesión está autorizado como admin
$email_admin = $_SESSION['email'] ?? null;
$stmt = $conn->prepare("SELECT * FROM transfer_viajeros WHERE email = ?");
$stmt->bind_param("s", $email_admin);
$stmt->execute();
$admin_user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$admin_user) {
    http_response_code(403);
    echo "<p style='color:red;'>⛔ Acceso denegado. Usuario administrador no encontrado.</p>";
    exit();
}

// Consulta adaptada según DESCRIBE transfer_reservas
$sql = "SELECT r.id_reserva,
               r.localizador,
               v.nombre AS cliente_nombre,
               COALESCE(v.apellido1, '') AS cliente_apellido,
               h.usuario AS hotel,
               t.Descripción AS tipo_reserva,
               r.fecha_reserva,
               r.fecha_entrada,
               r.hora_entrada,
               r.numero_vuelo_entrada,
               r.origen_vuelo_entrada,
               r.fecha_vuelo_salida,
               r.hora_vuelo_salida,
               r.fecha_modificacion,
               r.num_viajeros
        FROM transfer_reservas r
        LEFT JOIN transfer_viajeros v ON r.email_cliente = v.id_viajero
        LEFT JOIN tranfer_hotel h ON r.id_hotel = h.id_hotel
        LEFT JOIN transfer_tipo_reserva t ON r.id_tipo_reserva = t.id_tipo_reserva
        ORDER BY r.fecha_reserva DESC";

$resultado = $conn->query($sql);
if ($resultado === false) {
    echo "<p style='color:red;'>Error en la consulta: " . htmlspecialchars($conn->error) . "</p>";
    exit();
}
?>

<?php include_once __DIR__ . '/../includes/header.php'; ?>

<main class="container" style="max-width:1000px; margin:40px auto; font-family:sans-serif;">
    <h1>Reservas - Panel Admin</h1>

    <?php if ($resultado->num_rows > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <tr style="background:#0077cc; color:white;">
                <th>ID</th>
                <th>Localizador</th>
                <th>Cliente</th>
                <th>Hotel</th>
                <th>Tipo</th>
                <th>Fecha Entrada</th>
                <th>Hora Entrada</th>
                <th>Vuelo entrada</th>
                <th>Origen entrada</th>
                <th>Fecha vuelo salida</th>
                <th>Hora vuelo salida</th>
                <th>Modificación</th>
                <th>Viajeros</th>
                <th>Acciones</th>
            </tr>
            <?php while($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_reserva']) ?></td>
                    <td><?= htmlspecialchars($row['localizador']) ?></td>
                    <td><?= htmlspecialchars(trim($row['cliente_nombre'] . ' ' . $row['cliente_apellido'])) ?></td>
                    <td><?= htmlspecialchars($row['hotel']) ?></td>
                    <td><?= htmlspecialchars($row['tipo_reserva']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_entrada']) ?></td>
                    <td><?= htmlspecialchars($row['hora_entrada']) ?></td>
                    <td><?= htmlspecialchars($row['numero_vuelo_entrada']) ?></td>
                    <td><?= htmlspecialchars($row['origen_vuelo_entrada']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_vuelo_salida']) ?></td>
                    <td><?= htmlspecialchars($row['hora_vuelo_salida']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_modificacion']) ?></td>
                    <td><?= htmlspecialchars($row['num_viajeros']) ?></td>
                    <td>
                        <a href="editar_reserva_admin.php?id=<?= urlencode($row['id_reserva']) ?>">✏️ Editar</a><br>
                        <a href="eliminar_reserva_admin.php?id=<?= urlencode($row['id_reserva']) ?>" onclick="return confirm('¿Seguro que quieres eliminar esta reserva?')">🗑️ Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No hay reservas aún.</p>
    <?php endif; ?>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
