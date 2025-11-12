<?php
session_start();
include_once __DIR__ . '/../config/db.php';

// Solo usuarios logueados
if (!isset($_SESSION['viajero_id'])) {
    header("Location: /UOC_transfers/public/login.php");
    exit();
}

// Obtener info del usuario
$stmt = $conn->prepare("SELECT * FROM transfer_viajeros WHERE id_viajero = ?");
$stmt->bind_param("i", $_SESSION['viajero_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Traer hoteles y tipos de reserva
$hoteles = $conn->query("SELECT id_hotel, usuario FROM tranfer_hotel");
$tipos = $conn->query("SELECT id_tipo_reserva, Descripción FROM transfer_tipo_reserva");

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_hotel = (int) $_POST['id_hotel'];
    $id_tipo = (int) $_POST['id_tipo_reserva'];
    $fecha_entrada = $_POST['fecha_reserva'];
    $hora_entrada = $_POST['hora_reserva'];
    $num_viajeros = (int) $_POST['num_viajeros'];
    $fecha_modificacion = date('Y-m-d H:i:s');

    // Validación de fecha mínima (48h)
    $fecha_actual = new DateTime();
    $fecha_reserva = new DateTime($fecha_entrada);
    $intervalo = $fecha_actual->diff($fecha_reserva);
    if ($intervalo->days < 2 || $fecha_reserva < $fecha_actual) {
        echo "<p style='color:red;'>❌ La reserva debe realizarse con al menos 48 horas de antelación.</p>";
    } else {
        // Generar localizador único
        $localizador = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        // Valores fijos para simplificar
        $id_destino = $id_hotel;
        $id_vehiculo = 1;
        $numero_vuelo_entrada = 'IB123';
        $origen_vuelo_entrada = 'Aeropuerto';
        $hora_vuelo_salida = date('Y-m-d H:i:s', strtotime($fecha_entrada . ' 12:00:00'));        $fecha_vuelo_salida = date('Y-m-d', strtotime($fecha_entrada . ' +3 days'));

        // Escapar variables
        $localizador = $conn->real_escape_string($localizador);
        $fecha_modificacion = $conn->real_escape_string($fecha_modificacion);
        $fecha_entrada = $conn->real_escape_string($fecha_entrada);
        $hora_entrada = $conn->real_escape_string($hora_entrada);
        $numero_vuelo_entrada = $conn->real_escape_string($numero_vuelo_entrada);
        $origen_vuelo_entrada = $conn->real_escape_string($origen_vuelo_entrada);
        $hora_vuelo_salida = $conn->real_escape_string($hora_vuelo_salida);
        $fecha_vuelo_salida = $conn->real_escape_string($fecha_vuelo_salida);

        $email_cliente = $_SESSION['viajero_id']; // ✅ correcto


        // Crear la consulta SQL
        $sql = "INSERT INTO transfer_reservas
            (localizador, id_hotel, id_tipo_reserva, email_cliente, fecha_reserva, fecha_modificacion,
             id_destino, fecha_entrada, hora_entrada, numero_vuelo_entrada, origen_vuelo_entrada,
             hora_vuelo_salida, fecha_vuelo_salida, num_viajeros, id_vehiculo)
            VALUES
            ('$localizador', $id_hotel, $id_tipo, $email_cliente, '$fecha_modificacion', '$fecha_modificacion',
             $id_destino, '$fecha_entrada', '$hora_entrada', '$numero_vuelo_entrada', '$origen_vuelo_entrada',
             '$hora_vuelo_salida', '$fecha_vuelo_salida', $num_viajeros, $id_vehiculo)";

        if ($conn->query($sql)) {
            echo "<p style='color:green;'>✅ Reserva creada correctamente. Localizador: <strong>$localizador</strong></p>";
        } else {
            echo "<p style='color:red;'>❌ Error al crear la reserva: " . $conn->error . "</p>";
        }
    }
}
?>

<?php include_once '../includes/header.php'; ?>

<main class="container" style="max-width:600px; margin:40px auto;">
    <h1>Crear nueva reserva</h1>
    <form method="POST">
        <label>Hotel:</label>
        <select name="id_hotel" required>
            <?php while($h = $hoteles->fetch_assoc()): ?>
                <option value="<?= $h['id_hotel'] ?>"><?= htmlspecialchars($h['usuario']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Tipo de reserva:</label>
        <select name="id_tipo_reserva" required>
            <?php while($t = $tipos->fetch_assoc()): ?>
                <option value="<?= $t['id_tipo_reserva'] ?>"><?= htmlspecialchars($t['Descripción']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label>Fecha de entrada:</label>
        <input type="date" name="fecha_reserva" required><br><br>

        <label>Hora de entrada:</label>
        <input type="time" name="hora_reserva" required><br><br>

        <label>Número de viajeros:</label>
        <input type="number" name="num_viajeros" min="1" required><br><br>

        <button type="submit">Crear reserva</button>
    </form>
</main>

<?php include_once '../includes/footer.php'; ?>
