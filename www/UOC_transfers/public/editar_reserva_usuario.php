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
function puede_modificar($fecha_reserva) {
    $ahora = new DateTime();
    $fecha = new DateTime($fecha_reserva);
    $diff = $ahora->diff($fecha);
    return $diff->days >= 2;
}

if (!puede_modificar($reserva['fecha_reserva'])) {
    echo "No puedes modificar esta reserva, quedan menos de 48 horas.";
    exit();
}

// Procesar formulario
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_reserva = $_POST['tipo_reserva'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $num_viajeros = $_POST['num_viajeros'];
    $id_hotel = $_POST['hotel'];

    $stmt_update = $conn->prepare("UPDATE transfer_reservas SET id_tipo_reserva=?, id_hotel=?, fecha_reserva=?, num_viajeros=? WHERE id_reserva=? AND id_viajero=?");
    $stmt_update->bind_param("iisiii", $tipo_reserva, $id_hotel, $fecha, $num_viajeros, $id_reserva, $user['id_viajero']);

    if ($stmt_update->execute()) {
        $mensaje = "Reserva actualizada correctamente.";
        // Recargamos la información de la reserva
        $stmt_res->execute();
        $reserva = $stmt_res->get_result()->fetch_assoc();
    } else {
        $mensaje = "Error al actualizar la reserva: " . $stmt_update->error;
    }
}

// Hoteles y tipos de reserva
$hoteles_result = $conn->query("SELECT * FROM transfer_hotel");
$tipos_result = $conn->query("SELECT * FROM transfer_tipo_reserva");

?>

<?php include_once '../includes/header.php'; ?>

<main class="container" style="max-width:700px; margin:40px auto;">
    <h1 style="text-align:center; color:#0077cc;">Editar reserva</h1>

    <?php if ($mensaje): ?>
        <p style="color:green; font-weight:bold; text-align:center;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" style="margin-top:20px;">
        <label>Tipo de reserva:</label>
        <select name="tipo_reserva" required>
            <?php while ($tipo = $tipos_result->fetch_assoc()): ?>
                <option value="<?= $tipo['id_tipo_reserva'] ?>" <?= $reserva['id_tipo_reserva']==$tipo['id_tipo_reserva']?'selected':'' ?>><?= htmlspecialchars($tipo['tipo_reserva']) ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label>Fecha de vuelo:</label>
        <input type="date" name="fecha" value="<?= $reserva['fecha_reserva'] ?>" required>
        <br><br>

        <label>Hora de vuelo:</label>
        <input type="time" name="hora" value="<?= $reserva['hora'] ?? '' ?>" required>
        <br><br>

        <label>Hotel:</label>
        <select name="hotel" required>
            <?php while ($hotel = $hoteles_result->fetch_assoc()): ?>
                <option value="<?= $hotel['id_hotel'] ?>" <?= $reserva['id_hotel']==$hotel['id_hotel']?'selected':'' ?>><?= htmlspecialchars($hotel['nombre_hotel']) ?></option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label>Número de viajeros:</label>
        <input type="number" name="num_viajeros" min="1" value="<?= $reserva['num_viajeros'] ?>" required>
        <br><br>

        <button type="submit" style="background:#00aa55; color:white; padding:10px 20px; border-radius:8px; border:none;">Actualizar reserva</button>
    </form>

    <p style="margin-top:20px; text-align:center;">
        <a href="panel_usuario.php" style="text-decoration:none; color:#0077cc;">Volver al panel</a>
    </p>
</main>

<?php include_once '../includes/footer.php'; ?>
