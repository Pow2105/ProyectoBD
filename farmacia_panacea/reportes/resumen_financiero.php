<?php
require_once("../db.php");

// Obtener totales de ventas y gastos
$ventas_res = $conn->query("SELECT SUM(total_venta) AS total FROM ventas")->fetch_assoc();
$gastos_res = $conn->query("SELECT SUM(monto) AS total FROM gastos")->fetch_assoc();

// Asignar valores, usando 0 si son nulos
$total_ventas = $ventas_res['total'] ?? 0;
$total_gastos = $gastos_res['total'] ?? 0;
$utilidad = $total_ventas - $total_gastos;

// Determinar el color de la utilidad
$utilidad_color_class = $utilidad >= 0 ? 'text-blue-600' : 'text-red-600';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen Financiero</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6 sm:p-10">
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Resumen Financiero</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h2 class="text-sm font-medium text-gray-500 uppercase">Total Ventas</h2>
            <p class="text-3xl font-bold text-green-600 mt-2">
                $<?php echo number_format($total_ventas, 2); ?>
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
            <h2 class="text-sm font-medium text-gray-500 uppercase">Total Gastos</h2>
            <p class="text-3xl font-bold text-red-600 mt-2">
                $<?php echo number_format($total_gastos, 2); ?>
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h2 class="text-sm font-medium text-gray-500 uppercase">Utilidad Neta</h2>
            <p class="text-3xl font-bold <?php echo $utilidad_color_class; ?> mt-2">
                $<?php echo number_format($utilidad, 2); ?>
            </p>
        </div>

    </div>
</div>
</body>
</html>