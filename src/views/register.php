<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-R">
    <title>Registro - Isla Transfers</title>
    <style>
        body { font-family: Arial, sans-serif; display: grid; justify-content: center; margin-top: 50px; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 300px; padding: 8px; }
    </style>
</head>
<body>

    <h2>Registro de Nuevo Usuario</h2>

    <form action="index.php?page=register&action=submit" method="POST">
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select id="tipo_usuario" name="tipo_usuario">
                <option value="particular">Particular</option>
                <option value="corporativo">Corporativo (Hotel)</option>
            </select>
        </div>
        <button type="submit">Registrarse</button>
    </form>

</body>
</html>