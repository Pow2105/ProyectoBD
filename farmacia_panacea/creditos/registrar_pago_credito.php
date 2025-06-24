<?php
require_once("../db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_credito = intval($_POST['id_credito']);
  $monto_pago = floatval($_POST['monto_pago']);
  $metodo = trim($_POST['metodo_pago']);
  $fecha_pago = date("Y-m-d");

  // Llamar al procedimiento almacenado
  $stmt = $conn->prepare("CALL sp_registrar_pago_credito(?, ?, ?, ?)");
  $stmt->bind_param("idss", $id_credito, $monto_pago, $metodo, $fecha_pago);
  $stmt->execute();
  $stmt->close();

  header("Location: consultar_creditos.php");
  exit;
}

$creditos = $conn->query("SELECT c.id_credito_cliente, cl.nombre, cl.apellido, c.monto_credito, c.estado_credito FROM creditos_clientes c JOIN clientes cl ON c.id_cliente = cl.id_cliente WHERE c.estado_credito != 'Pagado'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Pago de Crédito</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-xl mx-auto">
  <h2 class="text-3xl font-bold text-red-700 mb-6">Registrar Cobro de Crédito</h2>
  <form method="post" class="space-y-4 bg-white p-4 rounded shadow">
    <div>
      <label class="block font-medium mb-1">Seleccionar Crédito:</label>
      <select name="id_credito" class="w-full border px-3 py-2 rounded" required>
        <option disabled selected value="">Seleccione</option>
        <?php while($c = $creditos->fetch_assoc()) { ?>
          <option value="<?php echo $c['id_credito_cliente']; ?>">
            <?php echo "#{$c['id_credito_cliente']} - {$c['nombre']} {$c['apellido']} - $" . number_format($c['monto_credito'], 2) . " ({$c['estado_credito']})"; ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div>
      <label class="block font-medium mb-1">Monto del Pago:</label>
      <input type="number" step="0.01" name="monto_pago" class="w-full border px-3 py-2 rounded" required>
    </div>
    <div>
      <label class="block font-medium mb-1">Método de Pago:</label>
      <select name="metodo_pago" class="w-full border px-3 py-2 rounded" required>
        <option disabled selected>Seleccione</option>
        <option value="Efectivo">Efectivo</option>
        <option value="Tarjeta">Tarjeta</option>
        <option value="Transferencia">Transferencia</option>
      </select>
    </div>
    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded w-full">Registrar Pago</button>
  </form>
</div>
</body>
</html>

