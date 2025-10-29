<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Isla Transfers</title>
    <style>
        body { font-family: Arial, sans-serif; display: grid; justify-content: center; margin-top: 50px; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 300px; padding: 8px; }
        .error { color: red; }
    </style>
</head>
<body>

    <h2>Iniciar Sesión</h2>

    <form action="index.php?page=login&action=submit" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Entrar</button>
    </form>

</body>
</html>