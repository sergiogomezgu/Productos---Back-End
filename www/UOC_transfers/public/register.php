<?php
session_start();
include_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido1 = trim($_POST['apellido1']);
    $apellido2 = trim($_POST['apellido2']);
    $direccion = trim($_POST['direccion']);
    $codigoPostal = trim($_POST['codigoPostal']);
    $ciudad = trim($_POST['ciudad']);
    $pais = trim($_POST['pais']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // En producción: ¡encriptar!

    // Comprobar si ya existe el email
    $stmt = $conn->prepare("SELECT * FROM transfer_viajeros WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p style='color:red;'>⚠️ Este correo ya está registrado.</p>";
    } else {
        // Insertar nuevo usuario
        $stmt = $conn->prepare("INSERT INTO transfer_viajeros (nombre, apellido1, apellido2, direccion, codigoPostal, ciudad, pais, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nombre, $apellido1, $apellido2, $direccion, $codigoPostal, $ciudad, $pais, $email, $password);

        if ($stmt->execute()) {
            // Guardar ID y email en sesión
            $_SESSION['viajero_id'] = $conn->insert_id;
            $_SESSION['email'] = $email;

            // Redirigir al panel de usuario
            header("Location: panel_usuario.php");
            exit();
        } else {
            echo "<p style='color:red;'>❌ Error al registrar usuario: " . $conn->error . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de usuario</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f0f0f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            width: 320px;
        }
        input, button {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Registro de viajero</h2>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido1" placeholder="Primer apellido" required>
        <input type="text" name="apellido2" placeholder="Segundo apellido" required>
        <input type="text" name="direccion" placeholder="Dirección" required>
        <input type="text" name="codigoPostal" placeholder="Código Postal" required>
        <input type="text" name="ciudad" placeholder="Ciudad" required>
        <input type="text" name="pais" placeholder="País" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
