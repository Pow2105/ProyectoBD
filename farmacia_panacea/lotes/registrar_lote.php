<?php
require_once("../db.php");

// Cargar proveedores y empleados para los desplegables
$proveedores = $conn->query("SELECT id_proveedor, nombre_empresa FROM proveedores ORDER BY nombre_empresa");
$empleados = $conn->query("SELECT id_empleado, nombre, apellido FROM empleados ORDER BY nombre");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Datos del Lote
  $nombre_producto = $_POST["nombre_producto"];
  $numero_lote = $_POST["numero_lote"];
  $fecha_fabricacion = $_POST["fecha_fabricacion"];
  $fecha_vencimiento = $_POST["fecha_vencimiento"];
  $id_proveedor = $_POST["id_proveedor"];
  $total_unidades = $_POST["total"];
  $precio_total_lote = $_POST["precio_total"];
  
  // Dato adicional para el gasto
  $id_empleado_compra = $_POST["id_empleado"];

  // Iniciar transacción
  $conn->begin_transaction();

  try {
    // 1. Insertar el lote
    $stmt_lote = $conn->prepare("INSERT INTO lotes_productos (nombre_producto, numero_lote, fecha_fabricacion, fecha_vencimiento, id_proveedor, total, precio_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_lote->bind_param("ssssiid", $nombre_producto, $numero_lote, $fecha_fabricacion, $fecha_vencimiento, $id_proveedor, $total_unidades, $precio_total_lote);
    $stmt_lote->execute();
    $stmt_lote->close();

    // 2. Insertar el gasto asociado a la compra del lote
    $fecha_gasto = date("Y-m-d");
    $categoria_gasto = "Compra de Lote";
    $descripcion_gasto = "Compra de {$total_unidades} unidades de '{$nombre_producto}' (Lote: {$numero_lote})";
    
    $stmt_gasto = $conn->prepare("CALL sp_insert_gasto(?, ?, ?, ?, ?)");
    $stmt_gasto->bind_param("isssd", $id_empleado_compra, $fecha_gasto, $categoria_gasto, $descripcion_gasto, $precio_total_lote);
    $stmt_gasto->execute();
    $stmt_gasto->close();

    // Si todo fue bien, confirmar la transacción
    $conn->commit();
    $mensaje = "<div class='bg-green-100 text-green-800 px-4 py-3 rounded mb-4'>Lote registrado y gasto asociado creado exitosamente.</div>";

  } catch (Exception $e) {
    // Si algo falla, revertir todo
    $conn->rollback();
    $mensaje = "<div class='bg-red-100 text-red-800 px-4 py-3 rounded mb-4'>Error: " . $e->getMessage() . "</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Lote de Producto</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
  <h2 class="text-2xl font-bold text-indigo-700 mb-6">Registrar Compra de Lote</h2>
  <?php echo $mensaje; ?>
  <form method="post" class="space-y-4">
    <div>
      <label class="block font-medium mb-1">Nombre del Producto Comprado:</label>
      <input type="text" name="nombre_producto" class="w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
      <label class="block font-medium mb-1">Número de Lote:</label>
      <input type="text" name="numero_lote" class="w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block font-medium mb-1">Fecha de Fabricación:</label>
            <input type="date" name="fecha_fabricacion" class="w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label class="block font-medium mb-1">Fecha de Vencimiento:</label>
            <input type="date" name="fecha_vencimiento" class="w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
    </div>
    <div>
      <label class="block font-medium mb-1">Proveedor:</label>
      <select name="id_proveedor" class="w-full border-gray-300 rounded-md shadow-sm" required>
        <option disabled selected value="">Seleccione un proveedor</option>
        <?php while ($pr = $proveedores->fetch_assoc()) { ?>
          <option value="<?= $pr['id_proveedor'] ?>"><?= $pr['nombre_empresa'] ?></option>
        <?php } ?>
      </select>
    </div>
     <div>
      <label class="block font-medium mb-1">Empleado que registra la compra:</label>
      <select name="id_empleado" class="w-full border-gray-300 rounded-md shadow-sm" required>
        <option disabled selected value="">Seleccione un empleado</option>
        <?php while ($e = $empleados->fetch_assoc()) { ?>
          <option value="<?= $e['id_empleado'] ?>"><?= $e['nombre'] . ' ' . $e['apellido'] ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block font-medium mb-1">Cantidad Total del Lote:</label>
            <input type="number" name="total" class="w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label class="block font-medium mb-1">Precio Total del Lote ($):</label>
            <input type="number" step="0.01" name="precio_total" class="w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
    </div>
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md w-full hover:bg-indigo-700 font-semibold">Registrar Lote</button>
  </form>
</div>
</body>
</html>