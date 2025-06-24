<?php
require_once("../db.php");

// --- Lógica de Búsqueda ---
// Obtener parámetros de la URL. Si están vacíos, se envían como NULL al procedimiento.
$fecha = !empty($_GET['fecha']) ? $_GET['fecha'] : NULL;
$cliente_nombre = !empty($_GET['cliente']) ? $_GET['cliente'] : NULL;
$empleado_nombre = !empty($_GET['empleado']) ? $_GET['empleado'] : NULL;

// Preparar y ejecutar la llamada al procedimiento almacenado
$stmt = $conn->prepare("CALL sp_buscar_ventas(?, ?, ?)");
$stmt->bind_param("sss", $fecha, $cliente_nombre, $empleado_nombre);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas Realizadas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
<div class="max-w-7xl mx-auto">
  <h2 class="text-3xl font-bold text-yellow-700 mb-6">Historial de Ventas</h2>

  <form method="GET" action="consultar.php" class="bg-white p-4 rounded-lg shadow-sm mb-6 space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
    <div>
      <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
      <input type="date" name="fecha" id="fecha" value="<?php echo htmlspecialchars($fecha ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
      <label for="cliente" class="block text-sm font-medium text-gray-700">Nombre del Cliente</label>
      <input type="text" name="cliente" id="cliente" value="<?php echo htmlspecialchars($cliente_nombre ?? ''); ?>" placeholder="Ej: Sebastian" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div>
      <label for="empleado" class="block text-sm font-medium text-gray-700">Nombre del Empleado</label>
      <input type="text" name="empleado" id="empleado" value="<?php echo htmlspecialchars($empleado_nombre ?? ''); ?>" placeholder="Ej: Roberto" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>
    <div class="flex space-x-2">
        <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">Buscar</button>
        <a href="consultar.php" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400">Limpiar</a>
    </div>
  </form>

  <div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="w-full table-auto border">
        <thead class="bg-yellow-100">
        <tr>
            <th class="px-4 py-3 text-left">ID</th>
            <th class="px-4 py-3 text-left">Fecha</th>
            <th class="px-4 py-3 text-left">Hora</th>
            <th class="px-4 py-3 text-left">Cliente</th>
            <th class="px-4 py-3 text-left">Empleado</th>
            <th class="px-4 py-3 text-right">Total</th>
            <th class="px-4 py-3 text-center">Pago</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($resultado->num_rows > 0): ?>
            <?php while($v = $resultado->fetch_assoc()): ?>
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3"><?php echo $v['id_venta']; ?></td>
                <td class="px-4 py-3"><?php echo $v['fecha_venta']; ?></td>
                <td class="px-4 py-3"><?php echo $v['hora_venta']; ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($v['cliente']); ?></td>
                <td class="px-4 py-3"><?php echo htmlspecialchars($v['empleado']); ?></td>
                <td class="px-4 py-3 text-right">$<?php echo number_format($v['total_venta'], 2); ?></td>
                <td class="px-4 py-3 text-center"><?php echo htmlspecialchars($v['tipo_pago']); ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr class="border-t">
                <td colspan="7" class="text-center py-10 text-gray-500">No se encontraron ventas que coincidan con los criterios de búsqueda.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
  </div>
</div>
</body>
</html>