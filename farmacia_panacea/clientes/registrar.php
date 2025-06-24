<?php
require_once("../db.php");
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = $_POST["nombre"];
  $apellido = $_POST["apellido"];
  $telefono = $_POST["telefono"];
  $email = $_POST["email"];
  $direccion = $_POST["direccion"];

  $stmt = $conn->prepare("INSERT INTO clientes (nombre, apellido, telefono, email, direccion) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $nombre, $apellido, $telefono, $email, $direccion);

  if ($stmt->execute()) {
    $mensaje = "<div id='noti' class='bg-green-100 text-green-800 p-4 mb-4 rounded'>Cliente registrado exitosamente.</div>";
    echo "<script>setTimeout(() => document.getElementById('form-cliente').reset(), 100);</script>";
  } else {
    $mensaje = "<div class='bg-red-100 text-red-800 p-4 mb-4 rounded'>Error al registrar: " . $stmt->error . "</div>";
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Cliente</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    setTimeout(() => {
      const noti = document.getElementById('noti');
      if (noti) noti.remove();
    }, 4000);
  </script>
</head>
<body class="bg-gray-50 p-6">
  <div class="max-w-xl mx-auto">
    <h2 class="text-2xl font-bold text-blue-700 mb-4">Registrar Cliente</h2>
    <?php echo $mensaje; ?>
    <form id="form-cliente" method="post" class="space-y-4">
      <div>
        <label class="block font-medium">Nombre:</label>
        <input type="text" name="nombre" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Apellido:</label>
        <input type="text" name="apellido" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Teléfono:</label>
        <input type="text" name="telefono" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Email:</label>
        <input type="email" name="email" required class="w-full border px-3 py-2 rounded">
      </div>
      <div>
        <label class="block font-medium">Dirección:</label>
        <textarea name="direccion" required class="w-full border px-3 py-2 rounded"></textarea>
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Registrar</button>
        <a href="../index.php" class="text-sm text-blue-600 hover:underline">Volver al inicio</a>
      </div>
    </form>
  </div>
</body>
</html>