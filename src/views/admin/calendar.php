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

        /* Estilos básicos del calendario */
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
        <a href="index.php?page=logout">Cerrar Sessión</a>
    </nav>

    <h2>Vista de Calendario (Próximamente)</h2>
    <p>Aquí se mostrarán las reservas. Por ahora, es solo un ejemplo visual de una semana.</p>

    <table class="calendar">
        <thead>
            <tr>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
                <th>Domingo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><div class="day-number">1</div></td>
                <td><div class="day-number">2</div></td>
                <td><div class="day-number">3</div></td>
                <td><div class="day-number">4</div></td>
                <td><div class="day-number">5</div></td>
                <td><div class="day-number">6</div></td>
                <td><div class="day-number">7</div></td>
            </tr>
        </tbody>
    </table>

</body>
</html>