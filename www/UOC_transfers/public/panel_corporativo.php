<?php
session_start();
if (!isset($_SESSION['es_corporativo']) || $_SESSION['es_corporativo'] !== true || !isset($_SESSION['id_hotel'])) {
    header("Location: login.php");
    exit();
}

include_once __DIR__ . '/../config/db.php';

// Usamos el id_hotel guardado en la sesión
$id_hotel = $_SESSION['id_hotel'];

$stmt = $conn->prepare("
    SELECT id_reserva, localizador, email_cliente, fecha_reserva, fecha_entrada, hora_entrada, num_viajeros
    FROM transfer_reservas
    WHERE id_hotel = ?
    ORDER BY fecha_reserva ASC
");
$stmt->bind_param("i", $id_hotel);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include_once '../includes/header.php'; ?>

<main class="container" style="max-width:900px; margin:40px auto; font-family:sans-serif; line-height:1.6;">
    <h1 style="text-align:center; color:#0077cc;">Panel Corporativo (Hoteles)</h1>
    <p style="text-align:center;">
        Bienvenido/a <strong><?= htmlspecialchars($_SESSION['usuario'] ?? 'Hotel'); ?></strong>  
        — Gestiona aquí las reservas de tu hotel.
    </p>

    <section style="margin-top:40px;">
        <h2>📅 Reservas del hotel</h2>
        <p>Listado de todas las reservas creadas por tu hotel.</p>

        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; margin-top:20px;">
            <thead style="background:#f0f0f0;">
                <tr>
                    <th>ID Reserva</th>
                    <th>Localizador</th>
                    <th>Email Cliente</th>
                    <th>Fecha Reserva</th>
                    <th>Fecha Entrada</th>
                    <th>Hora Entrada</th>
                    <th>Nº Viajeros</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reserva = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($reserva['id_reserva']); ?></td>
                        <td><?= htmlspecialchars($reserva['localizador']); ?></td>
                        <td><?= htmlspecialchars($reserva['email_cliente']); ?></td>
                        <td><?= htmlspecialchars($reserva['fecha_reserva']); ?></td>
                        <td><?= htmlspecialchars($reserva['fecha_entrada']); ?></td>
                        <td><?= htmlspecialchars($reserva['hora_entrada']); ?></td>
                        <td><?= htmlspecialchars($reserva['num_viajeros']); ?></td>
                        <td>
                            <a href="editar_reserva.php?id=<?= $reserva['id_reserva']; ?>">Editar</a> | 
                            <a href="cancelar_reserva.php?id=<?= $reserva['id_reserva']; ?>">Cancelar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <section style="margin-top:40px;">
        <h2>➕ Crear nueva reserva</h2>
        <p>Formulario para crear reservas en nombre de huéspedes.</p>
        <a href="crear_reserva.php" style="display:inline-block; background:#00aa55; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
            Crear reserva
        </a>
    </section>

    <section style="margin-top:40px;">
        <h2>🏨 Perfil del hotel</h2>
        <p>Gestiona los datos de tu hotel.</p>
        <a href="editar_perfil.php" style="display:inline-block; background:#0077cc; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
            Editar perfil
        </a>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>
