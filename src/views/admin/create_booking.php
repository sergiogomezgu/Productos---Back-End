<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Reserva - Admin</title>
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

    <nav>
        <a href="index.php?page=admin&action=calendar">Ver Calendario</a> |
        <a href="index.php?page=admin&action=create_booking">Crear Reserva</a> |
        <a href="index.php?page=home">Volver a la Home</a> |
        <a href="index.php?page=logout">Cerrar Sesión</a>
    </nav>

    <h2>Crear Nueva Reserva</h2>
    <p>Formulario para crear una nueva reserva</p>

    <form action="index.php?page=admin&action=submit_booking" method="POST">

        <h3>Datos del Cliente</h3>
        <div>
            <label for="email_cliente">Email del Cliente:</label>
            <input type="email" id="email_cliente" name="email_cliente" required>
        </div>

        <h3>Detalles del Trayecto</h3>
        <div>
            <label for="tipo_reserva">Tipo de Reserva:</label>
            <select id="tipo_reserva" name="tipo_reserva">
                <option value="llegada">Aeropuerto -> Hotel (Llegada)</option>
                <option value="salida">Hotel -> Aeropuerto (Salida)</option>
                <option value="ida_vuelta">Ida y Vuelta</option>
            </select>
        </div>

        <div id="seccion_llegada">
            <h4>Datos de Llegada (Aeropuerto -> Hotel)</h4>
            <div>
                <label for="dia_llegada">Día de Llegada:</label>
                <input type="date" id="dia_llegada" name="dia_llegada">
            </div>
            <div>
                <label for="hora_llegada">Hora de Llegada:</label>
                <input type="time" id="hora_llegada" name="hora_llegada">
            </div>
            <div>
                <label for="vuelo_llegada">Nº de Vuelo:</label>
                <input type="text" id="vuelo_llegada" name="vuelo_llegada">
            </div>
            <div>
                <label for="origen_llegada">Aeropuerto de Origen:</label>
                <input type="text" id="origen_llegada" name="origen_llegada">
            </div>
        </div>

        <div id="seccion_salida">
            <h4>Datos de Salida (Hotel -> Aeropuerto)</h4>
            <div>
                <label for="dia_salida">Día de Salida:</label>
                <input type="date" id="dia_salida" name="dia_salida">
            </div>
            <div>
                <label for="hora_salida">Hora del Vuelo:</label>
                <input type="time" id="hora_salida" name="hora_salida">
            </div>
            <div>
                <label for="vuelo_salida">Nº de Vuelo:</label>
                <input type="text" id="vuelo_salida" name="vuelo_salida">
            </div>
            <div>
                <label for="recogida_salida">Hora de Recogida:</label>
                <input type="time" id="recogida_salida" name="recogida_salida">
            </div>
        </div>

        <h3>Hotel y Viajeros</h3>
        <div>
            <label for="hotel">Hotel (Destino/Recogida):</label>
            <input type="text" id="hotel" name="hotel" placeholder="Nombre del hotel">
        </div>
         <div>
            <label for="num_viajeros">Número de Viajeros:</label>
            <input type="number" id="num_viajeros" name="num_viajeros" min="1" required>
        </div>

        <button type="submit">Crear Reserva</button>
    </form>

</body>
</html>