<?php
// Importante: le decimos que use el archivo del Modelo 'User'
require_once 'models/User.php';

class AuthController {

    // --- FUNCIÓN DE REGISTRO (Corregida) ---
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once 'views/register.php';
            return;
        }

        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->nombre = $_POST['nombre'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];
        $user->tipo_usuario = $_POST['tipo_usuario'];

        if($user->register()) {
            echo "¡Registro exitoso! Serás redirigido al login.";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php?page=login';
                    }, 3000); // 3 segundos
                  </script>";
            exit;
        } else {
            echo "Error en el registro. Inténtalo de nuevo.";
        }
    }

    // --- FUNCIÓN DE LOGIN (Nueva y Corregida) ---
    public function login() {
        // Si el método NO es POST, solo mostramos el formulario
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once 'views/login.php';
            return;
        }

        // --- Si es POST, procesamos el login ---
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        // Asignar email y password (sin hashear)
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        // Intentar hacer login (el Modelo se encarga)
        $user_data = $user->login();

        if($user_data) {
            // ¡ÉXITO! Guardamos los datos del usuario en la sesión
            $_SESSION['user_id'] = $user_data['id_viajero'];
            $_SESSION['user_name'] = $user_data['nombre'];
            $_SESSION['user_role'] = $user_data['tipo_usuario'];

            // Redirigir al panel de control (que aún no hemos hecho)
            echo "¡Login correcto! Redirigiendo al panel...";
            // Usamos JavaScript para evitar el error de 'headers'
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php?page=home';
                    }, 2000); // 2 segundos
                  </script>";
            exit;

        } else {
            
            echo "Error: Email o contraseña incorrectos.";
            // Volver a cargar la vista de login para que lo intente de nuevo
            require_once 'views/login.php';
        }
    }

} // <-- ESTA ES LA LLAVE FINAL DE LA CLASE
?>