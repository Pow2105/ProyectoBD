<?php
require_once("../db.php");
date_default_timezone_set('America/Bogota');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $categoria = $_POST["categoria"];
    $precio = $_POST["precio_venta"];
    $stock = $_POST["stock"];
    $fecha = $_POST["fecha_registro"];

    $sql = "CALL sp_insert_producto(?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdis", $nombre, $descripcion, $categoria, $precio, $stock, $fecha);

    if ($stmt->execute()) {
        echo "<div class='bg-green-100 text-green-800 p-4 mt-4 rounded max-w-md mx-auto text-center'>Producto registrado exitosamente.</div>";
    } else {
        echo "<div class='bg-red-100 text-red-800 p-4 mt-4 rounded max-w-md mx-auto text-center'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
}
?>