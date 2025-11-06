<?php
session_start(); // Inicia la sesión en todas las páginas

// Incluir la configuración de la base de datos
require_once 'config/Database.php';

// Determinar qué controlador cargar
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    case 'register':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        // Comprueba si se está enviando el formulario (POST) o solo viéndolo (GET)
        $action = isset($_GET['action']) ? $_GET['action'] : 'show';
        if ($action == 'submit') {
            $controller->register(); // Esto procesará el POST
        } else {
            $controller->register(); // Esto mostrará la vista
        }
        break;

    case 'login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        // Comprobamos si se envía el formulario (POST) o solo se muestra (GET)
        $action = isset($_GET['action']) ? $_GET['action'] : 'show';
        if ($action == 'submit') {
            $controller->login(); // Procesará el POST
        } else {
            $controller->login(); // Mostrará la vista
        }
        break;


    case 'admin':
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();

        // Miramos si se pide una acción específica (ej: crear, editar)
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';

        // Esta es una forma avanzada de llamar a la función que toca
        // (Si $action = 'index', llama a $controller->index())
        // (Si $action = 'create_booking', llamará a $controller->create_booking())
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            echo "Error: Acción no encontrada en el AdminController.";
        }
        break;


    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    default:
        echo "Error 404 - Página no encontrada";
        break;
}
?>