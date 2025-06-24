<?php
require_once("../db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empresa = $_POST["empresa"];
    $direccion = $_POST["direccion"];
    $contacto = $_POST["contacto"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $sql = "CALL sp_insert_proveedor(?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $empresa, $direccion, $contacto, $telefono, $email);
    if ($stmt->execute()) { echo "Proveedor registrado exitosamente."; }
    else { echo "Error: " . $stmt->error; }
    $stmt->close(); $conn->close();
}
?>