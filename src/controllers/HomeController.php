<?php
class HomeController {
    public function index() {
        // Este controlador solo carga una vista
        require_once 'views/home.php';
    }
}
?>