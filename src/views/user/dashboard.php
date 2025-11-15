<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Panel de Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #337ab7; } /* Color azul para usuario */
        nav { background: #f4f4f4; padding: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; }
    </style>
</head>
<body>

    <h1>Mi Panel de Usuario</h1>
    <p>Hola, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>.</p>

    <nav>
        <a href="index.php?page=user&action=my_bookings">Ver Mis Reservas</a> |
        <a href="index.php?page=home">Volver a la Home</a> |
        <a href="index.php?page=logout">Cerrar Sesión</a>
    </nav>

    <div class="content">
        <h2>Mis Reservas</h2>
        
        <style>
            .reservas-lista { list-style: none; padding: 0; }
            .reserva-item { background: #f9f9f9; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; }
            .reserva-localizador { font-weight: bold; font-size: 1.2em; color: #337ab7; } /* Azul */
        </style>
        
        <ul class="reservas-lista">
            <?php
            // Esta variable $stmt solo existirá si la acción es "my_bookings"
            if (isset($stmt) && $stmt->rowCount() > 0) {
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                    echo "<li class='reserva-item'>";
                    echo "<span class='reserva-localizador'>Localizador: $localizador</span>";
                    
                    if (!empty($fecha_entrada)) {
                        echo "<p><strong>Llegada:</strong> $fecha_entrada a las $hora_entrada</p>";
                    }
                    if (!empty($fecha_vuelo_salida)) {
                         echo "<p><strong>Salida:</strong> $fecha_vuelo_salida</p>";
                    }
                    
                    echo "<p><strong>Hotel ID:</strong> $id_hotel</p>";
                    echo "<p><strong>Viajeros:</strong> $num_viajeros</p>";
                    
                    echo "<hr>";
                    echo '<a href="index.php?page=user&action=cancel_booking&id=' . $id_reserva . '" onclick="return confirm(\'¿Estás seguro de que quieres cancelar esta reserva?\');" style="color: red;">Cancelar Reserva</a>';

                    
                    echo "</li>";
                }
                
            } elseif (isset($stmt)) {
                // Si $stmt existe pero no tiene filas
                echo "<li>Aún no tienes ninguna reserva.</li>";
            } else {
                // Si $stmt no existe (es la página 'index' normal)
                 echo "<p>Bienvenido a tu panel. Haz clic en 'Ver Mis Reservas' para ver tu historial.</p>";
            }
            ?>
        </ul>
        
    </div>
    </body>
</html>