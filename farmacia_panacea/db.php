<?php
date_default_timezone_set('America/Bogota');
$host = "localhost"; // o 127.0.0.1
$usuario = "root";
$contrasena = ""; // Por defecto en XAMPP está vacío
$base_de_datos = "farmacia_panacea";

$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Opcional: Establecer conjunto de caracteres a UTF-8
$conn->set_charset("utf8");
?>
