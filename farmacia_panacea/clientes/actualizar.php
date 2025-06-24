<?php
require_once("../db.php");

// Proceso para actualizar llamando al procedimiento almacenado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id_cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    $stmt = $conn->prepare("CALL sp_actualizar_cliente(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $id, $nombre, $apellido, $telefono, $email, $direccion);
    $stmt->execute();
    
    header("Location: consultar.php");
    exit;
}

// Obtener los datos actuales del cliente llamando al procedimiento almacenado
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("CALL sp_obtener_cliente_por_id(?)");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $datos = $stmt->get_result()->fetch_assoc();
} else {
    header("Location: consultar.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
<div class="max-w-xl mx-auto">
  <h2 class="text-2xl font-bold mb-6 text-blue-700">Editar Datos del Cliente</h2>
  <form action="actualizar.php" method="post" class="bg-white p-6 rounded-lg shadow-md space-y-4">
    <input type="hidden" name="id_cliente" value="<?php echo $datos['id_cliente']; ?>">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos['nombre']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Apellido:</label>
        <input type="text" name="apellido" value="<?php echo htmlspecialchars($datos['apellido']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($datos['telefono']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($datos['email']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Dirección:</label>
        <textarea name="direccion" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required><?php echo htmlspecialchars($datos['direccion']); ?></textarea>
    </div>
    <div class="flex justify-end space-x-4">
        <a href="consultar.php" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Cancelar</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Actualizar Cliente</button>
    </div>
  </form>
</div>
</body>
</html>