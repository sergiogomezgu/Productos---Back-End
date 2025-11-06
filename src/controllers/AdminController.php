<?php
require_once 'models/Booking.php';
class AdminController {

    public function __construct() {
        // 1. PUERTA DE SEGURIDAD
        // Comprueba si el usuario ha iniciado sesión
        if (!isset($_SESSION['user_id'])) {
            echo "Acceso denegado. Debes iniciar sesión.";
            exit;
        }
        // Comprueba si el usuario es 'admin'
        if ($_SESSION['user_role'] !== 'admin') {
            echo "Acceso denegado. No tienes permisos de administrador.";
            exit;
        }
        // Si todo está bien, la página continúa cargándose
    }

    // 2. FUNCIÓN PRINCIPAL
    // Muestra la página principal del panel de admin (el calendario)
    public function index() {
        // Por ahora, solo cargamos una vista simple
        require_once 'views/admin/dashboard.php';
    }

    // Muestra la vista del calendario
    public function calendar() {
        // (Más adelante, aquí buscaremos las reservas en la BBDD)

        // Por ahora, solo cargamos la vista
        require_once 'views/admin/calendar.php';
    }
    // (Aquí arriba está la función calendar() ...)

    // Muestra el formulario para crear una nueva reserva
    public function create_booking() {
        // (Más adelante, aquí cargaremos la lista de hoteles desde la BBDD)

        // Por ahora, solo cargamos la vista del formulario
        require_once 'views/admin/create_booking.php';
    }

    // PROCESA el formulario de creación de reserva
    public function submit_booking() {
        // 1. Conexión a la BBDD
        $database = new Database();
        $db = $database->getConnection();

        // 2. Crear un objeto Booking
        $booking = new Booking($db);

        // 3. Asignar los datos del formulario (el $_POST) al objeto
        $booking->email_cliente = $_POST['email_cliente'];
        $booking->num_viajeros = $_POST['num_viajeros'];

        // --- Traducción de IDs ---

        // PROBLEMA 1: El formulario envía el *nombre* del hotel, pero la BBDD quiere un *ID*.
        // SOLUCIÓN (Temporal): Usaremos el ID que el admin escriba en el campo.
        $booking->id_hotel = $_POST['hotel']; // (Asumimos que el admin escribe el ID del hotel)

        // PROBLEMA 2: El formulario envía "llegada", "salida", etc., pero la BBDD quiere un ID.
        // SOLUCIÓN: Hacemos una "traducción" manual.
        $tipo_reserva_texto = $_POST['tipo_reserva'];
        if ($tipo_reserva_texto == 'llegada') {
            $booking->id_tipo_reserva = 1; // Asumimos 1 = llegada
        } elseif ($tipo_reserva_texto == 'salida') {
            $booking->id_tipo_reserva = 2; // Asumimos 2 = salida
        } else {
            $booking->id_tipo_reserva = 3; // Asumimos 3 = ida_vuelta
        }

        // 4. Asignar datos de trayectos (controlando nulos)
        // (Usamos el 'operador de fusión de null' (??) para evitar errores si el campo está vacío)

        // Datos de Llegada
        $booking->fecha_entrada = $_POST['dia_llegada'] ?? null;
        $booking->hora_entrada = $_POST['hora_llegada'] ?? null;
        $booking->numero_vuelo_entrada = $_POST['vuelo_llegada'] ?? null;
        $booking->origen_vuelo_entrada = $_POST['origen_llegada'] ?? null;

        // Datos de Salida
        $booking->fecha_vuelo_salida = $_POST['dia_salida'] ?? null;
        $booking->hora_vuelo_salida = $_POST['hora_salida'] ?? null;
        // (La DB no tenía 'numero_vuelo_salida', así que lo ignoramos)


        // 5. Intentar crear la reserva
        if($booking->create()) {
            echo "<h1>¡Reserva Creada!</h1>";
            echo "<p>La reserva con localizador <strong>" . $booking->localizador . "</strong> ha sido creada con éxito.</p>";
            echo '<a href="index.php?page=admin">Volver al panel</a>';
        } else {
            echo "<h1>Error al crear la reserva.</h1>";
            echo '<a href="index.php?page=admin&action=create_booking">Volver a intentarlo</a>';
        }
    }
    // (Aquí añadiremos más tarde las funciones para crear, editar y borrar reservas)
}
?>