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

   // Muestra la vista del calendario CON DATOS
    public function calendar() {
        // 1. Conexión y Modelo
        $database = new Database();
        $db = $database->getConnection();
        $booking = new Booking($db);

        // 2. Llamar al método para leer todas las reservas
        $stmt = $booking->readAll();

        // 3. Pasar los datos a la vista
        // (La variable $stmt estará disponible en el archivo de la vista)
        require_once 'views/admin/calendar.php';
    }

    // Muestra el formulario para crear una nueva reserva
    public function create_booking() {
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
        $booking->id_hotel = $_POST['hotel']; // (Asumimos que el admin escribe el ID del hotel)
        
        $tipo_reserva_texto = $_POST['tipo_reserva'];
        if ($tipo_reserva_texto == 'llegada') {
            $booking->id_tipo_reserva = 1; // Asumimos 1 = llegada
        } elseif ($tipo_reserva_texto == 'salida') {
            $booking->id_tipo_reserva = 2; // Asumimos 2 = salida
        } else {
            $booking->id_tipo_reserva = 3; // Asumimos 3 = ida_vuelta
        }

        // 4. Asignar datos de trayectos (controlando nulos)
        $booking->fecha_entrada = $_POST['dia_llegada'] ?? null;
        $booking->hora_entrada = $_POST['hora_llegada'] ?? null;
        $booking->numero_vuelo_entrada = $_POST['vuelo_llegada'] ?? null;
        $booking->origen_vuelo_entrada = $_POST['origen_llegada'] ?? null;
        $booking->fecha_vuelo_salida = $_POST['dia_salida'] ?? null;
        $booking->hora_vuelo_salida = $_POST['hora_salida'] ?? null;

        
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

    // --- ¡NUEVA FUNCIÓN! (Paso 3) ---
    // PROCESA la eliminación de una reserva
    public function delete_booking() {
        // 1. Conexión y Modelo
        $database = new Database();
        $db = $database->getConnection();
        $booking = new Booking($db);

        // 2. Obtener el ID de la URL
        $id_a_borrar = $_GET['id'];

        // 3. Intentar borrar
        if($booking->delete($id_a_borrar)) {
            echo "Reserva borrada con éxito. Redirigiendo...";
        } else {
            echo "Error al borrar la reserva. Redirigiendo...";
        }

        // 4. Redirigir de vuelta al calendario
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php?page=admin&action=calendar';
                }, 2000); // 2 segundos
              </script>";
        exit;
    }

    // MUESTRA el formulario de edición (Paso 4)
    public function edit_booking() {
        // 1. Conexión y Modelo
        $database = new Database();
        $db = $database->getConnection();
        $booking = new Booking($db);

        // 2. Obtener el ID de la URL
        $id_a_editar = $_GET['id'];

        // 3. Buscar los datos de esa reserva
        $data = $booking->readOne($id_a_editar);

        // 4. "Extraer" los datos para que la vista los pueda usar
        // (Esto crea variables como $id_reserva, $email_cliente, etc.)
        extract($data);

        // 5. Cargar la vista del formulario (la que creamos en el Paso 2)
        require_once 'views/admin/edit_booking.php';
    }

    // (Aquí arriba está la función edit_booking()...)

    // PROCESA el formulario de edición (Paso 6)
    public function update_booking() {
        // 1. Conexión y Modelo
        $database = new Database();
        $db = $database->getConnection();
        $booking = new Booking($db);

        // 2. Asignar los datos del formulario POST al objeto
        // (El ID viene del campo <input type="hidden">)
        $booking->id_reserva = $_POST['id_reserva']; 

        $booking->email_cliente = $_POST['email_cliente'];
        $booking->id_hotel = $_POST['hotel'];
        $booking->num_viajeros = $_POST['num_viajeros'];
        $booking->fecha_entrada = $_POST['dia_llegada'] ?? null;
        $booking->hora_entrada = $_POST['hora_llegada'] ?? null;
        $booking->numero_vuelo_entrada = $_POST['vuelo_llegada'] ?? null;

        // 3. Intentar actualizar
        if($booking->update()) {
            echo "Reserva actualizada con éxito. Redirigiendo...";
        } else {
            echo "Error al actualizar la reserva. Redirigiendo...";
        }

        // 4. Redirigir de vuelta al calendario
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php?page=admin&action=calendar';
                }, 2000); // 2 segundos
              </script>";
        exit;
    }

} // <-- Llave final de la clase
?>