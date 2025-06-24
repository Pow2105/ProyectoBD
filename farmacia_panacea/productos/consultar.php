<?php
require_once("../db.php");

$categoria = $_GET['categoria'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$vencimiento = $_GET['vencimiento'] ?? '';
$opcion = $_GET['opcion'] ?? '';

$condiciones = [];
if ($opcion === 'categoria' && !empty($categoria)) $condiciones[] = "categoria LIKE '%$categoria%'";
if ($opcion === 'nombre' && !empty($nombre)) $condiciones[] = "nombre LIKE '%$nombre%'";
if ($opcion === 'vencimiento' && !empty($vencimiento)) $condiciones[] = "fecha_vencimiento = '$vencimiento'";

$sql = "SELECT * FROM productos";
if ($condiciones) {
  $sql .= " WHERE " . implode(" AND ", $condiciones);
}

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consultar Productos</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function mostrarCampo() {
      const seleccion = document.getElementById('opcion').value;
      document.getElementById('campo_categoria').style.display = seleccion === 'categoria' ? 'block' : 'none';
      document.getElementById('campo_nombre').style.display = seleccion === 'nombre' ? 'block' : 'none';
      document.getElementById('campo_vencimiento').style.display = seleccion === 'vencimiento' ? 'block' : 'none';
    }
  </script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-6xl mx-auto">
  <h2 class="text-3xl font-bold text-green-700 mb-4">Listado de Productos</h2>

  <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <select name="opcion" id="opcion" onchange="mostrarCampo()" class="border px-3 py-2 rounded">
      <option value="">Buscar por...</option>
      <option value="categoria" <?php if ($opcion==='categoria') echo 'selected'; ?>>Categoría</option>
      <option value="nombre" <?php if ($opcion==='nombre') echo 'selected'; ?>>Nombre</option>
      <option value="vencimiento" <?php if ($opcion==='vencimiento') echo 'selected'; ?>>Fecha de vencimiento</option>
    </select>

    <input type="text" id="campo_categoria" name="categoria" placeholder="Categoría" value="<?php echo htmlspecialchars($categoria); ?>" class="border px-3 py-2 rounded" style="display:none;">
    <input type="text" id="campo_nombre" name="nombre" placeholder="Nombre del producto" value="<?php echo htmlspecialchars($nombre); ?>" class="border px-3 py-2 rounded" style="display:none;">
    <input type="date" id="campo_vencimiento" name="vencimiento" value="<?php echo htmlspecialchars($vencimiento); ?>" class="border px-3 py-2 rounded" style="display:none;">

    <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded">Buscar</button>
    <a href="consultar.php" class="bg-gray-300 text-black px-4 py-2 rounded text-center inline-block">Limpiar</a>
  </form>

  <script>document.addEventListener('DOMContentLoaded', mostrarCampo);</script>

  <table class="table-auto w-full border">
    <thead class="bg-green-100">
      <tr>
        <th class="px-3 py-2">ID</th>
        <th class="px-3 py-2">Nombre</th>
        <th class="px-3 py-2">Categoría</th>
        <th class="px-3 py-2">Stock</th>
        <th class="px-3 py-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while($p = $resultado->fetch_assoc()) { ?>
      <tr class="border-t">
        <td class="px-3 py-2"><?php echo $p['id_producto']; ?></td>
        <td class="px-3 py-2"><?php echo $p['nombre']; ?></td>
        <td class="px-3 py-2"><?php echo $p['categoria']; ?></td>
        <td class="px-3 py-2"><?php echo $p['stock_actual']; ?></td>
        <td class="px-3 py-2">
          <a href="actualizar.php?id=<?php echo $p['id_producto']; ?>" class="text-blue-600 hover:underline">Editar</a> |
          <a href="eliminar.php?id=<?php echo $p['id_producto']; ?>" class="text-red-600 hover:underline" onclick="return confirm('¿Eliminar producto?');">Eliminar</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="mt-6 text-center">
    <a href="../index.php" class="text-blue-600 hover:underline">← Volver al inicio</a>
  </div>
</div>
</body>
</html>

