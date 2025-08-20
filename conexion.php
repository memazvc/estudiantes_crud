<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mi_base_datos";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
