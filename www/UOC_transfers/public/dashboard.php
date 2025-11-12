<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel - Isla Transfers</title>
</head>
<body>
<h2>Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?> 👋</h2>
<p>Rol: <?php echo htmlspecialchars($user['rol']); ?></p>

<a href="logout.php">Cerrar sesión</a>
</body>
</html>
