<?php
require_once("../db.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM proveedores WHERE id_proveedor = $id");
}
header("Location: consultar_proveedores.php");
exit;
?>