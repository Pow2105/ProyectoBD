<?php
require_once("../db.php");
$id = $_GET['id'];
$conn->query("DELETE FROM productos WHERE id_producto = $id");
header("Location: consultar.php");
?>