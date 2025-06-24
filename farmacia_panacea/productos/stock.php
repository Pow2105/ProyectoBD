<?php
require_once("../db.php");
$resultado = $conn->query("SELECT * FROM vw_stock_actual");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Stock Actual</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-4xl mx-auto">
  <h1 class="text-3xl font-bold text-green-700 mb-6">Stock Actual de Productos</h1>
  <table class="table-auto w-full border">
    <thead class="bg-green-100">
      <tr>
        <th class="px-3 py-2">ID</th>
        <th class="px-3 py-2">Nombre</th>
        <th class="px-3 py-2">Stock</th>
      </tr>
    </thead>
    <tbody>
      <?php while($fila = $resultado->fetch_assoc()) { ?>
      <tr class="border-t">
        <td class="px-3 py-2"><?php echo $fila['id_producto']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['nombre']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['stock_actual']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>