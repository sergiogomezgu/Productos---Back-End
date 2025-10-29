<?php
class Database {
    private $host = 'db';
    private $db_name = 'producto2_db';
    private $username = 'user';
    private $password = 'password';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            die();
        }
        return $this->conn;
    }
}
?>