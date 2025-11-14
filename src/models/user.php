<?php
class User {
    private $conn;
    // 1. Nombre de la tabla actualizado
    private $table_name = "transfer_viajeros"; 

    public $nombre;
    public $email;
    public $password;
    public $tipo_usuario;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para crear un nuevo usuario
    public function register() {

        // 2. Sentencia SQL actualizada
        // (Solo insertamos los campos de nuestro formulario de registro)
        $query = "INSERT INTO " . $this->table_name . " (nombre, email, password, tipo_usuario)
                  VALUES (:nombre, :email, :password, :tipo_usuario)";

        // Preparar la sentencia
        $stmt = $this->conn->prepare($query);

        // "Limpiar" los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->tipo_usuario = htmlspecialchars(strip_tags($this->tipo_usuario));

        // Hashear la contraseña antes de guardarla
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular los parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":tipo_usuario", $this->tipo_usuario);

        // Ejecutar la sentencia
        try {
            if($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            // Capturar error (por si el email ya existe, etc.)
            echo "Error al registrar: " . $e->getMessage();
            return false;
        }
        return false;
    }

    // --- MÉTODO DE LOGIN ---
    public function login() {
        // 1. Buscar al usuario por email
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";

        // Preparar la sentencia
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();

        // 2. Comprobar si el usuario existe
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // 3. Si existe, verificar la contraseña
            // Comparamos la contraseña del formulario ($this->password)
            // con la contraseña hasheada de la BBDD ($user['password'])

            if (password_verify($this->password, $user['password'])) {
                // ¡Contraseña correcta!
                // Devolvemos todos los datos del usuario
                return $user;
            }
        }

        // Si el email no existe o la contraseña es incorrecta
        return false;
    }
}
?>