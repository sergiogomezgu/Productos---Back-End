<?php
// login.php
session_start();
include_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim(strtolower($_POST['email'])) : '';
    $password = $_POST['password'] ?? '';

    // --- 1. Buscar primero en transfer_hotel (corporativos) ---
    $stmt = $conn->prepare("SELECT * FROM tranfer_hotel WHERE usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $hotelResult = $stmt->get_result();

    if ($hotelResult->num_rows === 1) {
        $hotel = $hotelResult->fetch_assoc();
        if ($password === $hotel['password']) {
            $_SESSION['id_hotel'] = $hotel['id_hotel'];   // 👈 guardamos id_hotel
            $_SESSION['usuario']  = $hotel['usuario'];    // nombre de usuario del hotel
            $_SESSION['es_corporativo'] = true;
            header("Location: /UOC_transfers/public/panel_corporativo.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    }

    // --- 2. Si no es hotel, buscar en transfer_viajeros (clientes normales) ---
    $stmt = $conn->prepare("SELECT * FROM transfer_viajeros WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $viajeroResult = $stmt->get_result();

    if ($viajeroResult->num_rows === 1) {
        $user = $viajeroResult->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['viajero_id'] = $user['id_viajero'];
            $_SESSION['viajero_nombre'] = $user['nombre'];
            $_SESSION['email'] = trim(strtolower($user['email']));

            // --- 3. Roles especiales (admins) ---
            $admin_emails = ['admin@isla.com', 'admin@transfers.com'];

            if (in_array($_SESSION['email'], $admin_emails)) {
                header("Location: /UOC_transfers/public/panel_admin.php");
                exit();
            } else {
                header("Location: /UOC_transfers/public/panel.php");
                exit();
            }
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        if (!isset($error)) {
            $error = "No existe ninguna cuenta con ese correo.";
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
    <title>Inicio de sesión</title>
    <style>
        body { font-family: Arial; background-color: #f0f0f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        form { background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); width: 320px; }
        input, button { width: 100%; padding: 8px; margin: 6px 0; box-sizing: border-box; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        h2 { text-align: center; margin: 0 0 10px; }
        .error { color: #b00020; margin-bottom: 10px; text-align:center; }
    </style>
</head>
<body>
    <form method="POST" action="">
        <h2>Iniciar sesión</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
        <p style="text-align:center; margin-top:10px;">
            ¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>
        </p>
    </form>
</body>
</html>
