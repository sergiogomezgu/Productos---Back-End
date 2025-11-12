<?php
$servername = "db"; // nombre del servicio MySQL en tu docker-compose
$username = "root";
$password = "example"; // o el que tengas definido en docker-compose.yml
$database = "UOC_transfers";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
