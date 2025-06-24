<?php
require_once("../db.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_cliente'])) {
  $id_cliente = $_POST['id_cliente'];
  $id_empleado = $_POST['id_empleado'];
  $fecha = $_POST['fecha'];
  $hora = $_POST['hora'];
  $pago = $_POST['tipo_pago'];
  $stmt = $conn->prepare("CALL sp_insert_venta(?, ?, ?, ?, ?)");
  $stmt->bind_param("iisss", $id_cliente, $id_empleado, $fecha, $hora, $pago);
  $stmt->execute();
  $stmt->bind_result($id_venta);
  $stmt->fetch();
  $stmt->close();
} else {
  $id_venta = $_POST['id_venta'] ?? null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Detalles de Venta</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-6">
<div class="max-w-xl mx-auto">
<h2 class="text-xl font-semibold text-yellow-700 mb-4">Agregar Detalle a Venta ID: <?php echo $id_venta; ?></h2>
<form method="post" action="agregar_detalle.php" class="space-y-4">
  <input type="hidden" name="id_venta" value="<?php echo $id_venta; ?>">
  <div><label>ID Producto:</label><input type="number" name="id_producto" class="w-full border px-3 py-2"></div>
  <div><label>Cantidad:</label><input type="number" name="cantidad" class="w-full border px-3 py-2"></div>
  <div><label>Precio Unitario:</label><input type="number" step="0.01" name="precio" class="w-full border px-3 py-2"></div>
  <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Agregar Detalle</button>
</form>
</div>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto'])) {
  $id_producto = $_POST['id_producto'];
  $cantidad = $_POST['cantidad'];
  $precio = $_POST['precio'];
  $stmt = $conn->prepare("CALL sp_insert_detalle_venta(?, ?, ?, ?)");
  $stmt->bind_param("iiid", $id_venta, $id_producto, $cantidad, $precio);
  $stmt->execute();
  echo "<p class='text-green-600 mt-4 text-center'>Detalle agregado exitosamente.</p>";
}
?>