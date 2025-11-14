<?php
// Importamos el modelo de reservas, porque el usuario querrá ver sus propias reservas
require_once 'models/Booking.php';

class UserController {

    public function __construct() {
        // 1. PUERTA DE SEGURIDAD
        // Comprueba si el usuario ha iniciado sesión
        if (!isset($_SESSION['user_id'])) {
            echo "Acceso denegado. Debes iniciar sesión.";
            exit;
        }

        // Comprueba si el usuario es 'particular'
        if ($_SESSION['user_role'] !== 'particular') {
            echo "Acceso denegado. Esta sección es solo para usuarios particulares.";
            exit;
        }
    }

    // 2. FUNCIÓN PRINCIPAL
    // Muestra la página principal del panel de usuario
    public function index() {
        require_once 'views/user/dashboard.php';
    }

    // Muestra las reservas del usuario logueado
    public function my_bookings() {
        // 1. Conexión y Modelo
        $database = new Database();
        $db = $database->getConnection();
        $booking = new Booking($db);

        // 2. Obtener el email del usuario de la SESIÓN
        $user_email = $_SESSION['user_email'];

        // 3. Llamar al método para leer las reservas de ESE email
        $stmt = $booking->readByUserEmail($user_email);

        // 4. Pasar los datos a la vista
        require_once 'views/user/dashboard.php';
    }

    // Procesa la cancelación de una reserva
    public function cancel_booking() {
        // 1. Conexión y Modelo
        $database = new Database();
        $db = $database->getConnection();
        $booking = new Booking($db);

        // 2. Obtener el ID de la reserva de la URL
        $id_a_cancelar = $_GET['id'];

        // 3. Obtener el email del usuario de la SESIÓN
        $email_usuario_logueado = $_SESSION['user_email'];

        // --- ¡COMPROBACIÓN DE SEGURIDAD! ---
        // 4. Verificamos que esta reserva pertenece al usuario logueado

        $reserva = $booking->readOne($id_a_cancelar); // Usamos la función que ya teníamos

        if ($reserva && $reserva['email_cliente'] === $email_usuario_logueado) {
            // El email de la reserva coincide con el de la sesión.
            // El usuario tiene permiso para cancelar.

            if($booking->delete($id_a_cancelar)) { // Reutilizamos la función delete()
                echo "Reserva cancelada con éxito. Redirigiendo...";
            } else {
                echo "Error al cancelar la reserva. Redirigiendo...";
            }

        } else {
            // El usuario está intentando borrar una reserva que no es suya.
            echo "Acceso denegado. No puedes cancelar esta reserva. Redirigiendo...";
        }

        // 5. Redirigir de vuelta a "Mis Reservas"
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php?page=user&action=my_bookings';
                }, 2000); // 2 segundos
              </script>";
        exit;
    }
}
?>