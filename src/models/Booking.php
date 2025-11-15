<?php
class Booking {
    private $conn;
    private $table_name = "transfer_reservas";

    // Propiedades de la reserva
    public $id_hotel;
    public $id_tipo_reserva;
    public $email_cliente;
    public $fecha_entrada;
    public $hora_entrada;
    public $numero_vuelo_entrada;
    public $origen_vuelo_entrada;
    public $fecha_vuelo_salida;
    public $hora_vuelo_salida;
    public $num_viajeros;
    public $localizador;
    public $fecha_reserva;
    public $fecha_modificacion; // Añadida para la función update
    public $id_reserva; // Añadida para la función update

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODO PARA CREAR UNA RESERVA ---
    public function create() {
        $this->localizador = uniqid('ISLA-');
        $this->fecha_reserva = date('Y-m-d H:i:s');
        $query = "INSERT INTO " . $this->table_name . " (
                        localizador, id_hotel, id_tipo_reserva, email_cliente, 
                        fecha_reserva, fecha_entrada, hora_entrada, 
                        numero_vuelo_entrada, origen_vuelo_entrada, 
                        fecha_vuelo_salida, hora_vuelo_salida, num_viajeros
                      ) VALUES (
                        :localizador, :id_hotel, :id_tipo_reserva, :email_cliente,
                        :fecha_reserva, :fecha_entrada, :hora_entrada,
                        :vuelo_entrada, :origen_entrada,
                        :fecha_salida, :hora_salida, :num_viajeros
                      )";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":localizador", $this->localizador);
        $stmt->bindParam(":id_hotel", $this->id_hotel);
        $stmt->bindParam(":id_tipo_reserva", $this->id_tipo_reserva);
        $stmt->bindParam(":email_cliente", $this->email_cliente);
        $stmt->bindParam(":fecha_reserva", $this->fecha_reserva);
        $stmt->bindParam(":num_viajeros", $this->num_viajeros);
        $stmt->bindParam(":fecha_entrada", $this->fecha_entrada);
        $stmt->bindParam(":hora_entrada", $this->hora_entrada);
        $stmt->bindParam(":vuelo_entrada", $this->numero_vuelo_entrada);
        $stmt->bindParam(":origen_entrada", $this->origen_vuelo_entrada);
        $stmt->bindParam(":fecha_salida", $this->fecha_vuelo_salida);
        $stmt->bindParam(":hora_salida", $this->hora_vuelo_salida);
        if($stmt->execute()) { return true; }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // --- MÉTODO PARA LEER TODAS LAS RESERVAS ---
    public function readAll() {
        $query = "SELECT
                        r.*, 
                        t.Descripción as tipo_reserva_nombre
                    FROM
                        " . $this->table_name . " r
                    LEFT JOIN
                        transfer_tipo_reserva t ON r.id_tipo_reserva = t.id_tipo_reserva
                    ORDER BY
                        r.fecha_entrada ASC, r.hora_entrada ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    // --- MÉTODO PARA BORRAR UNA RESERVA ---
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_reserva = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if($stmt->execute()) { return true; }
        return false;
    }

    // --- MÉTODO PARA LEER UNA SOLA RESERVA ---
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_reserva = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    // --- MÉTODO PARA ACTUALIZAR UNA RESERVA ---
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                SET
                    email_cliente = :email_cliente,
                    id_hotel = :id_hotel,
                    num_viajeros = :num_viajeros,
                    fecha_entrada = :fecha_entrada,
                    hora_entrada = :hora_entrada,
                    numero_vuelo_entrada = :numero_vuelo_entrada,
                    fecha_modificacion = :fecha_modificacion
                WHERE
                    id_reserva = :id_reserva";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email_cliente', $this->email_cliente);
        $stmt->bindParam(':id_hotel', $this->id_hotel);
        $stmt->bindParam(':num_viajeros', $this->num_viajeros);
        $stmt->bindParam(':fecha_entrada', $this->fecha_entrada);
        $stmt->bindParam(':hora_entrada', $this->hora_entrada);
        $stmt->bindParam(':numero_vuelo_entrada', $this->numero_vuelo_entrada);
        $this->fecha_modificacion = date('Y-m-d H:i:s');
        $stmt->bindParam(':fecha_modificacion', $this->fecha_modificacion);
        $stmt->bindParam(':id_reserva', $this->id_reserva);
        if($stmt->execute()) { return true; }
        return false;
    }

    // --- MÉTODO PARA LEER RESERVAS POR EMAIL DE USUARIO ---
    public function readByUserEmail($email) {
        $query = "SELECT
                    r.*, 
                    t.Descripción as tipo_reserva_nombre
                FROM
                    " . $this->table_name . " r
                LEFT JOIN
                    transfer_tipo_reserva t ON r.id_tipo_reserva = t.id_tipo_reserva
                WHERE
                    r.email_cliente = :email
                ORDER BY
                    r.fecha_entrada ASC, r.hora_entrada ASC";

        $stmt = $this->conn->prepare($query);
        // Vinculamos el email
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt;
    }

}
?>