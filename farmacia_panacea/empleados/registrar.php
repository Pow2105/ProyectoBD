<?php
require_once("../db.php");
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = $_POST["nombre"];
  $apellido = $_POST["apellido"];
  $rol = $_POST["rol"];
  $salario = $_POST["salario"];
  $fecha = $_POST["fecha_contratacion"];
  $descripcion = $_POST["descripcion"];

  $sql = "CALL sp_insert_empleado_simple(?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssiss", $nombre, $apellido, $rol, $salario, $fecha, $descripcion);

  if ($stmt->execute()) {
    $mensaje = "<div id='noti' class='bg-green-100 text-green-800 px-4 py-3 rounded mb-4'>Empleado registrado exitosamente.</div>";
    echo "<script>setTimeout(() => document.getElementById('form-empleado').reset(), 100);</script>";
  } else {
    $mensaje = "<div class='bg-red-100 text-red-800 px-4 py-3 rounded mb-4'>Error: " . $stmt->error . "</div>";
  }
  $stmt->close();
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Empleado</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    setTimeout(() => {
      const noti = document.getElementById('noti');
      if (noti) noti.remove();
    }, 4000);
  </script>
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-blue-700 mb-4">Registrar Nuevo Empleado</h2>
    <?php echo $mensaje; ?>
    <form id="form-empleado" method="post" class="space-y-4">
      <div>
        <label class="block font-medium">Nombre:</label>
        <input type="text" name="nombre" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Apellido:</label>
        <input type="text" name="apellido" class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Rol:</label>
        <input type="text" name="rol" class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Salario:</label>
        <input type="number" step="0.01" name="salario" class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Fecha de Contratación:</label>
        <input type="date" name="fecha_contratacion" class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Descripción:</label>
        <textarea name="descripcion" class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Registrar</button>
        <a href="../index.php" class="text-sm text-blue-600 hover:underline">Volver al inicio</a>
      </div>
    </form>
  </div>
</body>
</html>


