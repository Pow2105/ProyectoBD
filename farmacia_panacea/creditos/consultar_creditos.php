<?php
require_once("../db.php");
$resultado = $conn->query("
  SELECT 
    creditos_clientes.id_credito_cliente, 
    clientes.nombre, 
    clientes.apellido, 
    ventas.fecha_venta, 
    creditos_clientes.monto_credito, 
    creditos_clientes.monto_restante,
    creditos_clientes.estado_credito, 
    creditos_clientes.fecha_otorgamiento, 
    creditos_clientes.fecha_vencimiento_pago 
  FROM creditos_clientes 
  JOIN clientes ON creditos_clientes.id_cliente = clientes.id_cliente 
  JOIN ventas ON creditos_clientes.id_venta = ventas.id_venta 
  ORDER BY creditos_clientes.fecha_otorgamiento DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consultar Créditos</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-red-700 mb-6">Créditos de Clientes</h2>
    <table class="w-full table-auto border">
      <thead class="bg-red-100">
        <tr>
          <th class="px-3 py-2">#</th>
          <th class="px-3 py-2">Cliente</th>
          <th class="px-3 py-2">Fecha Venta</th>
          <th class="px-3 py-2">Otorgamiento</th>
          <th class="px-3 py-2">Vencimiento</th>
          <th class="px-3 py-2">Monto Total</th>
          <th class="px-3 py-2">Monto Restante</th>
          <th class="px-3 py-2">Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php while($fila = $resultado->fetch_assoc()) { ?>
        <tr class="border-t">
          <td class="px-3 py-2"><?php echo $fila['id_credito_cliente']; ?></td>
          <td class="px-3 py-2"><?php echo $fila['nombre'] . " " . $fila['apellido']; ?></td>
          <td class="px-3 py-2"><?php echo $fila['fecha_venta']; ?></td>
          <td class="px-3 py-2"><?php echo $fila['fecha_otorgamiento']; ?></td>
          <td class="px-3 py-2"><?php echo $fila['fecha_vencimiento_pago']; ?></td>
          <td class="px-3 py-2">$<?php echo number_format($fila['monto_credito'], 2); ?></td>
          <td class="px-3 py-2 text-blue-800 font-medium">
            $<?php echo number_format($fila['monto_restante'], 2); ?>
          </td>
          <td class="px-3 py-2 font-semibold text-<?php 
            echo $fila['estado_credito'] === 'Pagado' ? 'green' : ($fila['estado_credito'] === 'Vencido' ? 'red' : 'yellow'); 
          ?>-700">
            <?php echo $fila['estado_credito']; ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>



