<?php
require_once("../db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $rol = $_POST['rol'];
    $conn->query("UPDATE empleados SET nombre='$nombre', apellido='$apellido', rol='$rol' WHERE id_empleado=$id");
    header("Location: consultar.php");
    exit;
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $datos = $conn->query("SELECT * FROM empleados WHERE id_empleado = $id")->fetch_assoc();
} else {
    echo "<p class='text-red-600 text-center'>ID de empleado no proporcionado.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Actualizar Empleado</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-100 p-6">
<div class="max-w-xl mx-auto">
  <h2 class="text-2xl font-bold mb-4">Editar Empleado</h2>
  <form action="" method="post" class="space-y-4">
    <input type="hidden" name="id" value="<?php echo $datos['id_empleado']; ?>">
    <div><label>Nombre:</label><input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Apellido:</label><input type="text" name="apellido" value="<?php echo $datos['apellido']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Rol:</label><input type="text" name="rol" value="<?php echo $datos['rol']; ?>" class="w-full border px-3 py-2"></div>
    <div class="flex justify-between items-center">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
      <a href="consultar.php" class="text-sm text-blue-600 hover:underline">Volver</a>
    </div>
  </form>
</div>
</body>
</html>