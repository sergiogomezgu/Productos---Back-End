<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Reserva - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #d9534f; }
        nav { background: #f4f4f4; padding: 10px; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; }
        form { max-width: 600px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="date"], input[type="time"], input[type="number"], select {
            width: 100%; padding: 8px; box-sizing: border-box;
        }
    </style>
</head>
<body>

    <h1>Panel de Administración</h1>
    <h2>Editar Reserva (Localizador: <?php echo $localizador; ?>)</h2>

    <form action="index.php?page=admin&action=update_booking" method="POST">

        <input type="hidden" name="id_reserva" value="<?php echo $id_reserva; ?>">

        <h3>Datos del Cliente</h3>
        <div>
            <label for="email_cliente">Email del Cliente:</label>
            <input type="email" id="email_cliente" name="email_cliente" value="<?php echo htmlspecialchars($email_cliente); ?>" required>
        </div>

        <h3>Detalles del Trayecto</h3>
        <div>
            <label for="dia_llegada">Día de Llegada:</label>
            <input type="date" id="dia_llegada" name="dia_llegada" value="<?php echo htmlspecialchars($fecha_entrada); ?>">
        </div>
        <div>
            <label for="hora_llegada">Hora de Llegada:</label>
            <input type="time" id="hora_llegada" name="hora_llegada" value="<?php echo htmlspecialchars($hora_entrada); ?>">
        </div>
        <div>
            <label for="vuelo_llegada">Nº de Vuelo:</label>
            <input type="text" id="vuelo_llegada" name="vuelo_llegada" value="<?php echo htmlspecialchars($numero_vuelo_entrada); ?>">
        </div>

         <h3>Hotel y Viajeros</h3>
        <div>
            <label for="hotel">Hotel ID:</label>
            <input type.text" id="hotel" name="hotel" value="<?php echo htmlspecialchars($id_hotel); ?>">
        </div>
         <div>
            <label for="num_viajeros">Número de Viajeros:</label>
            <input type="number" id="num_viajeros" name="num_viajeros" min="1" value="<?php echo htmlspecialchars($num_viajeros); ?>" required>
        </div>

        <button type="submit">Actualizar Reserva</button>
        <a href="index.php?page=admin&action=calendar">Cancelar</a>
    </form>

</body>
</html>