<?php
$host = "fp064_db"; // nombre del contenedor MySQL
$user = "root";
$pass = "rootpass";
$dbname = "UOC_transfers";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}
?>
