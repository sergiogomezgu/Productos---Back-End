<?php
session_start();
include_once '../config/db.php';
include_once '../includes/verificar_admin.php';
// resto del código...

// Función para generar localizador único
function generarLocalizador($longitud = 8) {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $longitud));
}

// Recoger datos del formulario
$tipo = $_POST['tipo'];
$email_cliente = trim($_POST['email_cliente']);
$id_hotel = $_POST['id_hotel'];
$num_viajeros = $_POST['num_viajeros'];
$localizador = generarLocalizador();

// Campos opcionales según tipo
$fecha_entrada = $_POST['fecha_entrada'] ?? null;
$hora_entrada = $_POST['hora_entrada'] ?? null;
$num_vuelo_entrada = $_POST['num_vuelo_entrada'] ?? null;
$origen_vuelo = $_POST['origen_vuelo'] ?? null;

$fecha_salida = $_POST['fecha_salida'] ?? null;
$hora_salida = $_POST['hora_salida'] ?? null;
$num_vuelo_salida = $_POST['num_vuelo_salida'] ?? null;
$hora_recogida = $_POST['hora_recogida'] ?? null;

// Buscar o crear cliente
$stmt = $conn->prepare("SELECT id_viajero FROM transfer_viajeros WHERE email = ?");
$stmt->bind_param("s", $email_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
    $id_cliente = $cliente['id_viajero'];
} else {
    $nombre = "Cliente";
    $apellido1 = "";
    $apellido2 = "";
    $direccion = "";
    $codigoPostal = "";
    $ciudad = "";
    $pais = "";
    $password = "";

    $stmt_insert = $conn->prepare("INSERT INTO transfer_viajeros (nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, email, password, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'particular')");
    $stmt_insert->bind_param("sssssssss", $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email_cliente, $password);
    $stmt_insert->execute();
    $id_cliente = $conn->insert_id;
}

// Obtener ID del tipo de reserva
$mapa_tipos = [
    'aeropuerto-hotel' => 1,
    'hotel-aeropuerto' => 2,
    'ida-vuelta' => 3
];
$id_tipo_reserva = $mapa_tipos[$tipo];

// Insertar reserva
$stmt = $conn->prepare("INSERT INTO transfer_reservas (email_cliente, id_hotel, id_tipo_reserva, fecha_entrada, hora_entrada, num_vuelo_entrada, origen_vuelo, fecha_salida, hora_salida, num_vuelo_salida, hora_recogida, num_viajeros, localizador, fecha_reserva) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("iiissssssssiss", $id_cliente, $id_hotel, $id_tipo_reserva, $fecha_entrada, $hora_entrada, $num_vuelo_entrada, $origen_vuelo, $fecha_salida, $hora_salida, $num_vuelo_salida, $hora_recogida, $num_viajeros, $localizador);
$ok = $stmt->execute();

if ($ok) {
    // Enviar email
    $asunto = "Confirmación de reserva - Localizador $localizador";
    $mensaje = "Hola,\n\nTu reserva ha sido creada con éxito.\n\nLocalizador: $localizador\nTipo: $tipo\nHotel ID: $id_hotel\nNúmero de viajeros: $num_viajeros\n\nGracias por confiar en nosotros.";
    $cabeceras = "From: reservas@transfers.com";

    mail($email_cliente, $asunto, $mensaje, $cabeceras);

    echo "<p style='color:green;'>✅ Reserva creada correctamente. Se ha enviado un email al cliente.</p>";
    echo "<p><a href='panel_admin.php'>Volver al panel</a></p>";
} else {
    echo "<p style='color:red;'>❌ Error al crear la reserva: " . $conn->error . "</p>";
}
?>
