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

// Obtenemos datos de la reserva
$stmt_res = $conn->prepare("
    SELECT * FROM transfer_reservas WHERE id_reserva = ?
");
$stmt_res->bind_param("i", $id_reserva);
$stmt_res->execute();
$reserva_result = $stmt_res->get_result();
$reserva = $reserva_result->fetch_assoc();

if (!$reserva) {
    echo "Reserva no encontrada.";
    exit();
}

// Si se envía formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_hotel = intval($_POST['id_hotel']);
    $id_tipo_reserva = intval($_POST['id_tipo_reserva']);
    $fecha_reserva = $_POST['fecha_reserva'];
    $num_viajeros = intval($_POST['num_viajeros']);

    $stmt_update = $conn->prepare("
        UPDATE transfer_reservas
        SET id_hotel=?, id_tipo_reserva=?, fecha_reserva=?, num_viajeros=?
        WHERE id_reserva=?
    ");
    $stmt_update->bind_param("iisii", $id_hotel, $id_tipo_reserva, $fecha_reserva, $num_viajeros, $id_reserva);
    if ($stmt_update->execute()) {
        $mensaje = "Reserva actualizada correctamente.";
        // Recargamos datos
        $reserva['id_hotel'] = $id_hotel;
        $reserva['id_tipo_reserva'] = $id_tipo_reserva;
        $reserva['fecha_reserva'] = $fecha_reserva;
        $reserva['num_viajeros'] = $num_viajeros;
    } else {
        $mensaje = "Error al actualizar la reserva: " . $stmt_update->error;
    }
}

// Obtenemos hoteles y tipos de reserva
$hoteles = $conn->query("SELECT id_hotel, nombre_hotel FROM transfer_hotel");
$tipos = $conn->query("SELECT id_tipo_reserva, tipo_reserva FROM transfer_tipo_reserva");

?>

<?php include_once '../includes/header.php'; ?>

<main class="container" style="max-width:800px; margin:40px auto;">
    <h1 style="text-align:center; color:#0077cc;">Editar reserva #<?= $id_reserva ?></h1>

    <?php if (!empty($mensaje)): ?>
        <p style="background:#ddffdd; padding:10px; border-radius:5px;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" style="margin-top:20px;">
        <label>Hotel:</label>
        <select name="id_hotel" required>
            <?php while ($h = $hoteles->fetch_assoc()): ?>
                <option value="<?= $h['id_hotel'] ?>" <?= $h['id_hotel']==$reserva['id_hotel']?'selected':'' ?>>
                    <?= htmlspecialchars($h['nombre_hotel']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label>Tipo de reserva:</label>
        <select name="id_tipo_reserva" required>
            <?php while ($t = $tipos->fetch_assoc()): ?>
                <option value="<?= $t['id_tipo_reserva'] ?>" <?= $t['id_tipo_reserva']==$reserva['id_tipo_reserva']?'selected':'' ?>>
                    <?= htmlspecialchars($t['tipo_reserva']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label>Fecha del trayecto:</label>
        <input type="date" name="fecha_reserva" value="<?= $reserva['fecha_reserva'] ?>" required>
        <br><br>

        <label>Número de viajeros:</label>
        <input type="number" name="num_viajeros" min="1" value="<?= $reserva['num_viajeros'] ?>" required>
        <br><br>

        <button type="submit" style="background:#0077cc; color:white; padding:10px 20px; border-radius:5px;">Actualizar reserva</button>
    </form>

    <p style="margin-top:20px;"><a href="ver_reservas_admin.php">Volver a todas las reservas</a></p>
</main>

<?php include_once '../includes/footer.php'; ?>
