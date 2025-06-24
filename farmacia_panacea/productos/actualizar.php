<?php
require_once("../db.php");
date_default_timezone_set('America/Bogota');
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Proceso de actualización
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $conn->query("UPDATE productos SET nombre='$nombre', categoria='$categoria', precio_venta=$precio, stock_actual=$stock WHERE id_producto=$id");
    header("Location: consultar.php");
    exit;
}

// Mostrar el formulario con los datos actuales
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $datos = $conn->query("SELECT * FROM productos WHERE id_producto = $id")->fetch_assoc();
} else {
    echo "Error: No se proporcionó ID del producto.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizar Producto</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-xl mx-auto">
  <h2 class="text-2xl font-bold mb-4">Editar Producto</h2>
  <form action="" method="post" class="space-y-4">
    <input type="hidden" name="id" value="<?php echo $datos['id_producto']; ?>">
    <div><label>Nombre:</label><input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Categoría:</label><input type="text" name="categoria" value="<?php echo $datos['categoria']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Precio:</label><input type="number" name="precio" step="0.01" value="<?php echo $datos['precio_venta']; ?>" class="w-full border px-3 py-2"></div>
    <div><label>Stock:</label><input type="number" name="stock" value="<?php echo $datos['stock_actual']; ?>" class="w-full border px-3 py-2"></div>
    <div class="flex justify-between items-center">
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Actualizar</button>
      <a href="consultar.php" class="text-sm text-blue-600 hover:underline">Volver</a>
    </div>
  </form>
</div>
</body>
</html>
