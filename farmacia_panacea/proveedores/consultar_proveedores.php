<?php
require_once("../db.php");
$resultado = $conn->query("SELECT id_proveedor, nombre_empresa, contacto_nombre, contacto_telefono, direccion FROM proveedores ORDER BY nombre_empresa");
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Consultar Proveedores</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-6">
<div class="max-w-6xl mx-auto">
  <h2 class="text-3xl font-bold text-indigo-700 mb-6">Proveedores Registrados</h2>
  <a href="registrar.php" class="mb-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">Registrar Nuevo Proveedor</a>
  <table class="w-full table-auto border">
    <thead class="bg-indigo-100">
      <tr>
        <th class="px-3 py-2">#</th>
        <th class="px-3 py-2">Nombre</th>
        <th class="px-3 py-2">Contacto</th>
        <th class="px-3 py-2">Teléfono</th>
        <th class="px-3 py-2">Dirección</th>
        <th class="px-3 py-2">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while($fila = $resultado->fetch_assoc()) { ?>
      <tr class="border-t">
        <td class="px-3 py-2"><?php echo $fila['id_proveedor']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['nombre_empresa']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['contacto_nombre']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['contacto_telefono']; ?></td>
        <td class="px-3 py-2"><?php echo $fila['direccion']; ?></td>
        <td class="px-3 py-2">
          <a href="actualizar.php?id=<?php echo $fila['id_proveedor']; ?>" class="text-blue-600 hover:underline">Editar</a>
          |
          <a href="eliminar.php?id=<?php echo $fila['id_proveedor']; ?>" class="text-red-600 hover:underline" onclick="return confirm('¿Estás seguro de eliminar este proveedor?');">Eliminar</a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>
