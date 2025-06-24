<?php
require_once("../db.php");

// Consulta para obtener lotes que aún no han sido registrados como productos
// Se usa un LEFT JOIN para encontrar lotes cuyo nombre no existe en la tabla de productos.
$lotes = $conn->query("
    SELECT l.id_lote, l.nombre_producto, l.numero_lote, l.fecha_vencimiento
    FROM lotes_productos l
    LEFT JOIN productos p ON l.nombre_producto = p.nombre
    WHERE p.id_producto IS NULL
    ORDER BY l.nombre_producto
");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_lote = $_POST['id_lote'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio_venta'];

    // Obtenemos la información del lote seleccionado para crear el producto
    $lote_res = $conn->query("SELECT nombre_producto, total FROM lotes_productos WHERE id_lote = $id_lote");
  
    if ($lote_res->num_rows > 0) {
        $lote_data = $lote_res->fetch_assoc();
        $nombre_producto = $lote_data['nombre_producto'];
        $stock_inicial = $lote_data['total']; // El stock inicial es el total del lote
        $fecha_registro = date("Y-m-d");

        // Usamos el procedimiento almacenado sp_insert_producto, que es la mejor práctica
        $stmt = $conn->prepare("CALL sp_insert_producto(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdis", $nombre_producto, $descripcion, $categoria, $precio, $stock_inicial, $fecha_registro);

        if ($stmt->execute()) {
            $mensaje = "<div class='bg-green-100 text-green-800 px-4 py-2 rounded mb-4'>Producto '{$nombre_producto}' registrado exitosamente desde el lote.</div>";
            // Recargamos la lista de lotes para que ya no aparezca el que acabamos de registrar
            $lotes = $conn->query("
                SELECT l.id_lote, l.nombre_producto, l.numero_lote, l.fecha_vencimiento
                FROM lotes_productos l
                LEFT JOIN productos p ON l.nombre_producto = p.nombre
                WHERE p.id_producto IS NULL
                ORDER BY l.nombre_producto
            ");
        } else {
            $mensaje = "<div class='bg-red-100 text-red-800 px-4 py-2 rounded mb-4'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $mensaje = "<div class='bg-red-100 text-red-800 px-4 py-2 rounded mb-4'>Error: El lote seleccionado no es válido.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Producto</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
  <h2 class="text-2xl font-bold text-green-700 mb-4">Registrar Producto desde Lote</h2>
  <?php echo $mensaje; ?>
  <form method="post" class="space-y-4">
    <div>
      <label class="block font-medium mb-1">Lote Disponible (Aún no registrado como producto):</label>
      <select name="id_lote" class="w-full border px-3 py-2 rounded" required>
        <option disabled selected value="">Seleccione un lote de producto</option>
        <?php while($l = $lotes->fetch_assoc()) { ?>
          <option value="<?= $l['id_lote'] ?>">
            <?= "{$l['nombre_producto']} - Lote: {$l['numero_lote']} (Vence: {$l['fecha_vencimiento']})" ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div>
      <label class="block font-medium mb-1">Categoría:</label>
      <input type="text" name="categoria" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div>
      <label class="block font-medium mb-1">Descripción:</label>
      <textarea name="descripcion" class="w-full border px-3 py-2 rounded"></textarea>
    </div>
    <div>
      <label class="block font-medium mb-1">Precio de Venta:</label>
      <input type="number" step="0.01" name="precio_venta" class="w-full border px-3 py-2 rounded" required>
    </div>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full">Registrar Producto</button>
  </form>
</div>
</body>
</html>
