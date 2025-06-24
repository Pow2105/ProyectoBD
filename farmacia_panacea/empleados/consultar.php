<?php
require_once("../db.php");
$resultado = $conn->query("SELECT * FROM empleados");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consultar Empleados</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-blue-700 mb-4">Listado de Empleados</h1>
    <table class="table-auto w-full border border-gray-200">
      <thead class="bg-blue-100">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Nombre</th>
          <th class="px-4 py-2">Apellido</th>
          <th class="px-4 py-2">Rol</th>
          <th class="px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while($e = $resultado->fetch_assoc()) { ?>
        <tr class="border-t">
          <td class="px-4 py-2"><?php echo $e['id_empleado']; ?></td>
          <td class="px-4 py-2"><?php echo $e['nombre']; ?></td>
          <td class="px-4 py-2"><?php echo $e['apellido']; ?></td>
          <td class="px-4 py-2"><?php echo $e['rol']; ?></td>
          <td class="px-4 py-2">
            <a href="actualizar.php?id=<?php echo $e['id_empleado']; ?>" class="text-blue-500 hover:underline">Editar</a> |
            <a href="eliminar.php?id=<?php echo $e['id_empleado']; ?>" class="text-red-500 hover:underline" onclick="return confirm('¿Eliminar empleado?');">Eliminar</a>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</body>
</html>