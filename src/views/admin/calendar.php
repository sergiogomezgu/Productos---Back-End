<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #d9534f; }
        nav { background: #f4f4f4; padding: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; }
        
        /* Estilos de la tabla de ejemplo (ya no se usa, pero no molesta) */
        .calendar { width: 100%; border-collapse: collapse; }
        .calendar th, .calendar td { border: 1px solid #ccc; padding: 10px; height: 100px; vertical-align: top; }
        .calendar th { background: #f0f0f0; }
        .day-number { font-weight: bold; }
    </style>
</head>
<body>

    <h1>Panel de Administración</h1>
    <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>.</p>

    <nav>
        <a href="index.php?page=admin&action=calendar">Ver Calendario</a> |
        <a href="index.php?page=admin&action=create_booking">Crear Reserva</a> |
        <a href="index.php?page=home">Volver a la Home</a> |
        <a href="index.php?page=logout">Cerrar Sesión</a>
    </nav>

    <h2>Listado de Reservas</h2>
    <p>Aquí se muestran todas las reservas de la base de datos.</p>

    <style>
        /* Estos estilos son nuevos para la lista */
        .reservas-lista { list-style: none; padding: 0; }
        .reserva-item { background: #f9f9f9; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
        .reserva-localizador { font-weight: bold; font-size: 1.2em; color: #d9534f; }
    </style>

    <ul class="reservas-lista">
        <?php
        // $stmt es la variable que nos pasó el AdminController (Paso 2)
        if ($stmt->rowCount() > 0) {
            // Si hay reservas, las recorremos una por una
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Extraemos los datos para que sea más fácil usarlos
                extract($row);
                
                echo "<li class='reserva-item'>";
                echo "<span class='reserva-localizador'>Localizador: $localizador</span>";
                echo "<p><strong>Cliente:</strong> " . htmlspecialchars($email_cliente) . "</p>";
                
                // Mostramos los datos de llegada (si existen)
                if (!empty($fecha_entrada)) {
                    echo "<p><strong>Llegada:</strong> $fecha_entrada a las $hora_entrada (Vuelo: $numero_vuelo_entrada)</p>";
                }
                // Mostramos los datos de salida (si existen)
                if (!empty($fecha_vuelo_salida)) {
                     echo "<p><strong>Salida:</strong> $fecha_vuelo_salida a las $hora_vuelo_salida</g></p>";
                }
                
                // Usamos ?? para evitar errores si el hotel fue borrado (nombre_hotel será null)
                echo "<p><strong>Hotel ID:</strong> $id_hotel</p>";
                echo "<p><strong>Viajeros:</strong> $num_viajeros</p>";
                echo "</li>";
            }
        } else {
            echo "<li>No se han encontrado reservas.</li>";
        }
        ?>
    </ul>

    </body>
</html>