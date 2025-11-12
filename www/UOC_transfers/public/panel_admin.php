<?php
// panel_admin.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../includes/verificar_admin.php';

// En este punto verificar_admin.php ya ha comprobado:
// - que hay sesión con $_SESSION['email']
// - que el email está en la lista de admins
// Cargamos los datos del usuario por si los necesitamos para mostrar nombre, etc.

$email = $_SESSION['email'] ?? null;
$stmt = $conn->prepare("SELECT * FROM transfer_viajeros WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // Si por cualquier motivo el usuario no se encuentra en la BD
    http_response_code(403);
    echo "<p style='color:red;'>⛔ Acceso denegado. Usuario no encontrado.</p>";
    exit();
}

// Obtener hoteles para el selector
$hoteles = $conn->query("SELECT id_hotel, usuario FROM tranfer_hotel");
?>

<?php include_once __DIR__ . '/../includes/header.php'; ?>

<main class="container" style="max-width:900px; margin:40px auto; font-family:sans-serif;">
    <h1 style="text-align:center; color:#0077cc;">Panel de administración</h1>

    <section style="margin-top:40px;">
        <h2>✈️ Crear nueva reserva</h2>
        <form method="POST" action="crear_reserva_admin.php">
            <label>Tipo de reserva:</label>
            <select name="tipo" id="tipo" onchange="mostrarCampos()" required>
                <option value="">-- Selecciona --</option>
                <option value="aeropuerto-hotel">Aeropuerto → Hotel</option>
                <option value="hotel-aeropuerto">Hotel → Aeropuerto</option>
                <option value="ida-vuelta">Ida y vuelta</option>
            </select>

            <div id="aeropuerto-hotel" style="display:none;">
                <h3>Aeropuerto → Hotel</h3>
                <label>Fecha llegada:</label>
                <input type="date" name="fecha_entrada">
                <label>Hora llegada:</label>
                <input type="time" name="hora_entrada">
                <label>Número vuelo:</label>
                <input type="text" name="num_vuelo_entrada">
                <label>Aeropuerto origen:</label>
                <input type="text" name="origen_vuelo">
            </div>

            <div id="hotel-aeropuerto" style="display:none;">
                <h3>Hotel → Aeropuerto</h3>
                <label>Fecha vuelo:</label>
                <input type="date" name="fecha_salida">
                <label>Hora vuelo:</label>
                <input type="time" name="hora_salida">
                <label>Número vuelo:</label>
                <input type="text" name="num_vuelo_salida">
                <label>Hora recogida:</label>
                <input type="time" name="hora_recogida">
            </div>

            <label>Hotel:</label>
            <select name="id_hotel" required>
                <?php while ($h = $hoteles->fetch_assoc()): ?>
                    <option value="<?= $h['id_hotel'] ?>"><?= htmlspecialchars($h['usuario']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Número de viajeros:</label>
            <input type="number" name="num_viajeros" min="1" required>

            <label>Email del cliente:</label>
            <input type="email" name="email_cliente" required>

            <button type="submit" style="margin-top:20px;">Crear reserva</button>
        </form>
    </section>

    <section style="margin-top:40px; text-align:center;">
        <a href="ver_reservas_admin.php" style="margin-right:10px; background:#0077cc; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
            Ver todas las reservas
        </a>
        <a href="calendario_admin.php" style="background:#28a745; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
            Ver calendario
        </a>
    </section>
</main>

<script>
function mostrarCampos() {
    const tipo = document.getElementById('tipo').value;
    document.getElementById('aeropuerto-hotel').style.display = tipo === 'aeropuerto-hotel' || tipo === 'ida-vuelta' ? 'block' : 'none';
    document.getElementById('hotel-aeropuerto').style.display = tipo === 'hotel-aeropuerto' || tipo === 'ida-vuelta' ? 'block' : 'none';
}
</script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
