<?php
require_once("../db.php");
$resultado = $conn->query("SELECT * FROM vw_proximos_a_vencer");
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Productos por Vencer</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-6">
<div class="max-w-5xl mx-auto">
  <h2 class="text-3xl font-bold text-orange-700 mb-6">Productos Próximos a Vencer</h2>
  <table class="w-full border table-auto">
    <thead class="bg-orange-100">
      <tr>
        <th class="px-4 py-2">Nombre</th>
        <th class="px-4 py-2">Lote</th>
        <th class="px-4 py-2">Fecha Vencimiento</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $resultado->fetch_assoc()) { ?>
      <tr class="border-t">
        <td class="px-4 py-2"><?php echo $row['nombre']; ?></td>
        <td class="px-4 py-2"><?php echo $row['numero_lote']; ?></td>
        <td class="px-4 py-2 text-red-600 font-semibold"><?php echo $row['fecha_vencimiento']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>