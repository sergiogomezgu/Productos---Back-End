<?php
// public/editar_reserva_admin.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../includes/verificar_admin.php';

// Obtener id seguro
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: ver_reservas_admin.php");
    exit();
}

// Obtener la reserva actual
$stmt = $conn->prepare("SELECT * FROM transfer_reservas WHERE id_reserva = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$reserva = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$reserva) {
    echo "<p style='color:red;'>Reserva no encontrada.</p>";
    exit();
}

// Listas para selects
$viajeros = $conn->query("SELECT id_viajero, nombre, apellido1 FROM transfer_viajeros ORDER BY nombre");
$hoteles = $conn->query("SELECT id_hotel, usuario FROM tranfer_hotel ORDER BY usuario");
$tipos = $conn->query("SELECT id_tipo_reserva, Descripción FROM transfer_tipo_reserva ORDER BY Descripción");

// Procesar POST
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $viajero = (int) $_POST['viajero'];
    $hotel = (int) $_POST['hotel'];
    $tipo = (int) $_POST['tipo'];
    $fecha_entrada = $_POST['fecha_entrada'] ?? null;
    $hora_entrada = $_POST['hora_entrada'] ?? null;
    $numero_vuelo_entrada = trim($_POST['numero_vuelo_entrada'] ?? '');
    $origen_vuelo_entrada = trim($_POST['origen_vuelo_entrada'] ?? '');
    $fecha_vuelo_salida = $_POST['fecha_vuelo_salida'] ?? null;
    $hora_vuelo_salida = $_POST['hora_vuelo_salida'] ?? null;
    $num_viajeros = (int) $_POST['num_viajeros'];

    // ✅ Combinar fecha y hora para campo tipo timestamp
    if (!empty($fecha_vuelo_salida) && !empty($hora_vuelo_salida)) {
        $hora_vuelo_salida = $fecha_vuelo_salida . ' ' . $hora_vuelo_salida . ':00';
    } else {
        $hora_vuelo_salida = null;
    }

    if ($viajero <= 0 || $hotel <= 0 || $tipo <= 0) {
        $error = "Viajeros, hotel y tipo son obligatorios.";
    } else {
        $update = $conn->prepare("
            UPDATE transfer_reservas
            SET email_cliente = ?, id_hotel = ?, id_tipo_reserva = ?, fecha_entrada = ?, hora_entrada = ?, numero_vuelo_entrada = ?, origen_vuelo_entrada = ?, fecha_vuelo_salida = ?, hora_vuelo_salida = ?, num_viajeros = ?, fecha_modificacion = NOW()
            WHERE id_reserva = ?
        ");
        $update->bind_param(
            "iiissssssii",
            $viajero,
            $hotel,
            $tipo,
            $fecha_entrada,
            $hora_entrada,
            $numero_vuelo_entrada,
            $origen_vuelo_entrada,
            $fecha_vuelo_salida,
            $hora_vuelo_salida,
            $num_viajeros,
            $id
        );

        if ($update->execute()) {
            $update->close();
            header("Location: ver_reservas_admin.php?msg=actualizado");
            exit();
        } else {
            $error = "Error al actualizar: " . htmlspecialchars($conn->error);
            $update->close();
        }
    }
}

// Formato para mostrar
$fecha_entrada_val = $reserva['fecha_entrada'] ?? '';
$hora_entrada_val = isset($reserva['hora_entrada']) ? substr($reserva['hora_entrada'], 0, 5) : '';
$fecha_vuelo_salida_val = $reserva['fecha_vuelo_salida'] ?? '';
$hora_vuelo_salida_val = isset($reserva['hora_vuelo_salida']) ? substr($reserva['hora_vuelo_salida'], 11, 5) : '';
?>

<?php include_once __DIR__ . '/../includes/header.php'; ?>

<main class="container" style="max-width:800px; margin:40px auto; font-family:sans-serif;">
    <h1>Editar reserva #<?= htmlspecialchars($reserva['id_reserva']) ?></h1>

    <?php if (!empty($error)): ?>
        <div style="color:#b00020; margin-bottom:12px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Viajero (cliente):</label>
        <select name="viajero" required>
            <option value="">-- Selecciona viajero --</option>
            <?php while ($v = $viajeros->fetch_assoc()): ?>
                <option value="<?= $v['id_viajero'] ?>" <?= $v['id_viajero'] == $reserva['email_cliente'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['nombre'] . ' ' . $v['apellido1']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>
        <label>Tipo de reserva:</label>
        <select name="tipo" required>
            <option value="">-- Selecciona tipo --</option>
            <?php while ($t = $tipos->fetch_assoc()): ?>
                <option value="<?= $t['id_tipo_reserva'] ?>" <?= $t['id_tipo_reserva'] == $reserva['id_tipo_reserva'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['Descripción']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>
        <label>Hotel:</label>
        <select name="hotel" required>
            <option value="">-- Selecciona hotel --</option>
            <?php while ($h = $hoteles->fetch_assoc()): ?>
                <option value="<?= $h['id_hotel'] ?>" <?= $h['id_hotel'] == $reserva['id_hotel'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($h['usuario']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>
        <fieldset style="padding:10px; border:1px solid #ddd;">
            <legend>Entrada (aeropuerto → hotel)</legend>
            <label>Fecha llegada:</label>
            <input type="date" name="fecha_entrada" value="<?= htmlspecialchars($fecha_entrada_val) ?>">

            <label>Hora llegada:</label>
            <input type="time" name="hora_entrada" value="<?= htmlspecialchars($hora_entrada_val) ?>">

            <label>Número vuelo (entrada):</label>
            <input type="text" name="numero_vuelo_entrada" value="<?= htmlspecialchars($reserva['numero_vuelo_entrada'] ?? '') ?>">

            <label>Aeropuerto origen (entrada):</label>
            <input type="text" name="origen_vuelo_entrada" value="<?= htmlspecialchars($reserva['origen_vuelo_entrada'] ?? '') ?>">
        </fieldset>

        <br>
        <fieldset style="padding:10px; border:1px solid #ddd;">
            <legend>Salida (hotel → aeropuerto)</legend>
            <label>Fecha vuelo salida:</label>
            <input type="date" name="fecha_vuelo_salida" value="<?= htmlspecialchars($fecha_vuelo_salida_val) ?>">

            <label>Hora vuelo salida:</label>
            <input type="time" name="hora_vuelo_salida" value="<?= htmlspecialchars($hora_vuelo_salida_val) ?>">
        </fieldset>

        <br>
        <label>Número de viajeros:</label>
        <input type="number" name="num_viajeros" value="<?= htmlspecialchars($reserva['num_viajeros']) ?>" min="1" required>

        <br><br>
        <button type="submit" style="padding:10px 18px; background:#0077cc; color:white; border:none; border-radius:6px;">
            Guardar cambios
        </button>
        <a href="ver_reservas_admin.php" style="margin-left:12px; text-decoration:none; color:#0077cc;">Cancelar</a>
    </form>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
