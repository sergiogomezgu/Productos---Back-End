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