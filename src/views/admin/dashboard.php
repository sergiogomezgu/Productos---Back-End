<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #d9534f; } /* Color rojo para admin */
        nav { background: #f4f4f4; padding: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; }
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

    <div class="content">
        <h2>Bienvenido al panel</h2>
        <p>Este es el panel principal de administración. Próximamente aquí verás el calendario de reservas.</p>
    </div>

</body>
</html>