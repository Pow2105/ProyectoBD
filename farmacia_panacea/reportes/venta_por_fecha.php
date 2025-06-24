<?php
require_once("../db.php");
$ventas = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $desde = $_POST['desde'];
  $hasta = $_POST['hasta'];
  $stmt = $conn->prepare("SELECT v.id_venta, v.fecha_venta, c.nombre AS cliente, e.nombre AS empleado, v.total_venta FROM ventas v JOIN clientes c ON v.id_cliente = c.id_cliente JOIN empleados e ON v.id_empleado = e.id_empleado WHERE v.fecha_venta BETWEEN ? AND ? ORDER BY v.fecha_venta ASC");
  $stmt->bind_param("ss", $desde, $hasta);
  $stmt->execute();
  $ventas = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Ventas por Fecha</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
<div class="max-w-4xl mx-auto">
  <h1 class="text-3xl font-bold text-pink-700 mb-6">Ventas por Rango de Fechas</h1>
  <form method="post" class="mb-6 space-y-4">
    <div class="flex flex-col md:flex-row gap-4">
      <div class="flex-1">
        <label class="block font-semibold">Desde:</label>
        <input type="date" name="desde" required class="w-full border px-3 py-2">
      </div>
      <div class="flex-1">
        <label class="block font-semibold">Hasta:</label>
        <input type="date" name="hasta" required class="w-full border px-3 py-2">
      </div>
    </div>
    <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded">Consultar</button>
  </form>

  <?php if (!empty($ventas)) { ?>
    <table class="table-auto w-full border">
      <thead class="bg-pink-100">
        <tr>
          <th class="px-3 py-2">ID Venta</th>
          <th class="px-3 py-2">Fecha</th>
          <th class="px-3 py-2">Cliente</th>
          <th class="px-3 py-2">Empleado</th>
          <th class="px-3 py-2">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php while($v = $ventas->fetch_assoc()) { ?>
        <tr class="border-t">
          <td class="px-3 py-2"><?php echo $v['id_venta']; ?></td>
          <td class="px-3 py-2"><?php echo $v['fecha_venta']; ?></td>
          <td class="px-3 py-2"><?php echo $v['cliente']; ?></td>
          <td class="px-3 py-2"><?php echo $v['empleado']; ?></td>
          <td class="px-3 py-2">$<?php echo number_format($v['total_venta'], 2); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } ?>
</div>
</body>
</html>