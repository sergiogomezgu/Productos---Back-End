<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Isla Transfers</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        h1 { color: #0056b3; }
        .user-nav { background-color: #f0f0f0; padding: 10px; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>Bienvenido a Isla Transfers</h1>

    <div class="user-nav">
        <?php
        // Comprueba si la variable de sesión 'user_id' existe
        if (isset($_SESSION['user_id'])) {
            // --- Si la sesión SÍ existe ---
            echo "<p>Hola, <strong>" . htmlspecialchars($_SESSION['user_name']) . "</strong></p>";
            echo '<a href="index.php?page=logout">Cerrar Sesión</a>';
            
            // Aquí mostramos los menús según el rol
            if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'administrador') {
                 echo ' | <a href="index.php?page=admin">Panel de Administración</a>';
            } else {
                 echo ' | <a href="index.php?page=user">Mi Panel de Usuario</a>';
            }

        } else {
            // --- Si la sesión NO existe ---
            echo '<a href="index.php?page=login">Ir a Login</a> | ';
            echo '<a href="index.php?page=register">Ir a Registro</a>';
        }
        ?>
    </div>

</body>
</html>