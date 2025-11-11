<?php
class Booking {
    private $conn;
    private $table_name = "transfer_reservas";

    // Propiedades de la reserva (las columnas de tu tabla)
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

    // (Campos que generaremos nosotros)
    public $localizador;
    public $fecha_reserva;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODO PARA CREAR UNA RESERVA ---
    public function create() {
        // 1. Generar los datos que faltan
        $this->localizador = uniqid('ISLA-');
        $this->fecha_reserva = date('Y-m-d H:i:s'); // La fecha de hoy

        // 2. La consulta SQL
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

        // 3. "Limpiar" y vincular los datos
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

        // 4. Ejecutar
        if($stmt->execute()) {
            return true; // Éxito
        }

        // Si falla, mostrar el error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // --- MÉTODO PARA LEER TODAS LAS RESERVAS ---
    public function readAll() {
        // Unimos (JOIN) SOLO la tabla de tipo_reserva
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

} // <-- Llave final de la clase
?>