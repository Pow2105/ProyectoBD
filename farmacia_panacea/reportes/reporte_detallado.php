<?php
require_once("../db.php");

// --- Lógica de la página ---
$tipo_entidad = $_GET['tipo_entidad'] ?? '';
$id_entidad = !empty($_GET['id_entidad']) ? (int)$_GET['id_entidad'] : NULL;

$lista_nombres = [];
$resultado_detalle = null;

// Si se seleccionó un TIPO, cargar la lista de nombres
if ($tipo_entidad) {
    $stmt_lista = $conn->prepare("CALL sp_listar_entidades(?)");
    $stmt_lista->bind_param("s", $tipo_entidad);
    $stmt_lista->execute();
    $lista_nombres = $stmt_lista->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_lista->close();
}

// Si se seleccionó un ID, cargar los detalles de esa entidad
if ($tipo_entidad && $id_entidad) {
    // Reutilizamos el procedimiento que ya teníamos para los detalles
    $stmt_detalle = $conn->prepare("CALL sp_consultar_detalles(?, ?)");
    $stmt_detalle->bind_param("si", $tipo_entidad, $id_entidad);
    $stmt_detalle->execute();
    $resultado_detalle = $stmt_detalle->get_result()->fetch_assoc();
    $stmt_detalle->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Detallado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Consultar Información Detallada</h1>

    <form method="GET" action="reporte_detallado.php" id="consulta-form" class="bg-white p-6 rounded-lg shadow-md mb-8 md:flex md:items-end md:space-x-4">
        <div class="flex-grow mb-4 md:mb-0">
            <label for="tipo_entidad" class="block text-sm font-medium text-gray-700">1. Seleccione el Tipo</label>
            <select name="tipo_entidad" id="tipo_entidad" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()" required>
                <option value="">Seleccione...</option>
                <option value="cliente" <?php if($tipo_entidad == 'cliente') echo 'selected'; ?>>Cliente</option>
                <option value="empleado" <?php if($tipo_entidad == 'empleado') echo 'selected'; ?>>Empleado</option>
                <option value="proveedor" <?php if($tipo_entidad == 'proveedor') echo 'selected'; ?>>Proveedor</option>
            </select>
        </div>
        <div class="flex-grow mb-4 md:mb-0">
            <label for="id_entidad" class="block text-sm font-medium text-gray-700">2. Seleccione el Nombre</label>
            <select name="id_entidad" id="id_entidad" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" <?php if(empty($lista_nombres)) echo 'disabled'; ?> required>
                <option value="">-- Seleccione --</option>
                <?php foreach ($lista_nombres as $item): ?>
                    <option value="<?php echo $item['id']; ?>" <?php if($id_entidad == $item['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($item['nombre_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 w-full">Consultar</button>
        </div>
    </form>

    <?php if ($resultado_detalle): ?>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700">
            Detalles de: <span class="text-blue-600"><?php echo htmlspecialchars(end($resultado_detalle)); ?></span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <?php foreach ($resultado_detalle as $columna => $valor): ?>
            <div class="border-b py-2">
                <strong class="text-gray-600 capitalize"><?php echo str_replace('_', ' ', htmlspecialchars($columna)); ?>:</strong>
                <p class="text-gray-800"><?php echo htmlspecialchars($valor); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>