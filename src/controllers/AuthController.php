<?php
// Importante: le decimos que use el archivo del Modelo 'User'
require_once 'models/User.php';

class AuthController {

    // --- FUNCIÓN DE REGISTRO ---
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

    // --- FUNCIÓN DE LOGIN ---
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once 'views/login.php';
            return;
        }

        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        $user_data = $user->login();

        if($user_data) {
            $_SESSION['user_id'] = $user_data['id_viajero'];
            $_SESSION['user_name'] = $user_data['nombre'];
            $_SESSION['user_role'] = $user_data['tipo_usuario'];

            // Guardamos el email para que el panel de usuario pueda encontrar sus reservas
            $_SESSION['user_email'] = $user_data['email']; 

            echo "¡Login correcto! Redirigiendo al panel...";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php?page=home';
                    }, 2000); // 2 segundos
                  </script>";
            exit;

        } else {
            echo "Error: Email o contraseña incorrectos.";
            require_once 'views/login.php';
        }
    }

    // --- FUNCIÓN DE LOGOUT ---
    public function logout() {
        // Quita todas las variables de sesión
        $_SESSION = array();

        // Cierra la sesión
        session_destroy();

        // Redirige a la home
        header("Location: index.php?page=home");
        exit;
    }

}
?>