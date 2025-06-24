<?php
require_once("../db.php");
// Usamos el procedimiento que ya habías aprobado
$resultado = $conn->query("CALL sp_consultar_clientes()");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
<div class="max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-blue-700 mb-6">Clientes Registrados</h1>
    
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nombre Completo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Teléfono</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Dirección</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while($c = $resultado->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $c['id_cliente']; ?></td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900"><?php echo htmlspecialchars($c['nombre'] . ' ' . $c['apellido']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($c['telefono']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($c['email']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($c['direccion']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-4">
                        <a href="actualizar.php?id=<?php echo $c['id_cliente']; ?>" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                        <a href="eliminar.php?id=<?php echo $c['id_cliente']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de que quieres eliminar a este cliente?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>