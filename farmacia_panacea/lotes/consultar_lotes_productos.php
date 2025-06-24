<?php
require_once("../db.php");
$resultado = $conn->query("SELECT * FROM lotes_productos ORDER BY fecha_fabricacion DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consultar Lotes de Productos</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-6xl mx-auto">
  <h2 class="text-3xl font-bold text-indigo-700 mb-6">Lotes Registrados</h2>
  <table class="w-full table-auto border">
    <thead class="bg-indigo-100">
      <tr>
        <th class="px-3 py-2">#</th>
        <th class="px-3 py-2">Producto</th>
        <th class="px-3 py-2">Nro Lote</th>
        <th class="px-3 py-2">F. Fabricación</th>
        <th class="px-3 py-2">F. Vencimiento</th>
        <th class="px-3 py-2">Proveedor</th>
        <th class="px-3 py-2">Cantidad</th>
        <th class="px-3 py-2">Precio Total</th>
      </tr>
    </thead>
    <tbody>
      <?php while($fila = $resultado->fetch_assoc()) { ?>
      <tr class="border-t">
        <td class="px-3 py-2"><?php echo $fila['id_lote']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['nombre_producto']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['numero_lote']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['fecha_fabricacion']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['fecha_vencimiento']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['id_proveedor']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['total']; ?></td>
        <td class="px-3 py-2">$<?php echo number_format($fila['precio_total'], 2); ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>



