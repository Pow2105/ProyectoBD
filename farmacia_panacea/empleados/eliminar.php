<?php
require_once("../db.php");
$id = $_GET['id'];
$conn->query("DELETE FROM empleados WHERE id_empleado = $id");
header("Location: consultar.php");
?>