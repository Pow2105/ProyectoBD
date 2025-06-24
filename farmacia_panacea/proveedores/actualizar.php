<?php
require_once("../db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $conn->query("UPDATE proveedores SET nombre_empresa='$nombre', contacto_nombre='$contacto', contacto_telefono='$telefono', direccion='$direccion' WHERE id_proveedor=$id");
    header("Location: consultar_proveedores.php");
    exit;
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $datos = $conn->query("SELECT * FROM proveedores WHERE id_proveedor = $id")->fetch_assoc();
} else {
    echo "<p class='text-red-600 text-center'>ID de proveedor no proporcionado.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Actualizar Proveedor</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-6">
<div class="max-w-xl mx-auto">
  <h2 class="text-2xl font-bold mb-4 text-indigo-700">Editar Proveedor</h2>
  <form action="" method="post" class="space-y-4">
    <input type="hidden" name="id" value="<?php echo $datos['id_proveedor']; ?>">
    <div><label>Nombre:</label><input type="text" name="nombre" value="<?php echo $datos['nombre_empresa']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Contacto:</label><input type="text" name="contacto" value="<?php echo $datos['contacto_nombre']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Teléfono:</label><input type="text" name="telefono" value="<?php echo $datos['contacto_telefono']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Dirección:</label><input type="text" name="direccion" value="<?php echo $datos['direccion']; ?>" class="w-full border px-3 py-2"></div>
    <div class="flex justify-between items-center">
      <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Actualizar</button>
      <a href="consultar_proveedores.php" class="text-sm text-indigo-600 hover:underline">Volver</a>
    </div>
  </form>
</div>
</body>
</html>

