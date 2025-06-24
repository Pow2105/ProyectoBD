<?php
require_once("../db.php");

// --- OBTENER DATOS NECESARIOS ---
// Cargar clientes y empleados para los desplegables
$clientes = $conn->query("SELECT id_cliente, nombre, apellido FROM clientes ORDER BY nombre");
$empleados = $conn->query("SELECT id_empleado, nombre, apellido FROM empleados ORDER BY nombre");

// Cargar todos los productos para la funcionalidad de venta
// Se incluyen todos los datos necesarios para el JavaScript
$productos_res = $conn->query("SELECT id_producto, nombre, precio_venta, stock_actual FROM productos WHERE stock_actual > 0 ORDER BY nombre");
$productos_disponibles = [];
while ($row = $productos_res->fetch_assoc()) {
    $productos_disponibles[$row['id_producto']] = $row;
}

// Zona horaria y fecha/hora actual
date_default_timezone_set('America/Bogota');
$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i");
$mensaje = "";

// --- PROCESAMIENTO DEL FORMULARIO ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Iniciar una transacción para asegurar la integridad de los datos
    $conn->begin_transaction();

    try {
        // Datos básicos de la venta
        $id_cliente = $_POST['id_cliente'];
        $id_empleado = $_POST['id_empleado'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $tipo_pago = $_POST['tipo_pago'];
        $productos_venta = $_POST['productos'] ?? [];

        if (empty($productos_venta)) {
            throw new Exception("No se han agregado productos a la venta.");
        }

        // 1. Calcular el total de la venta en el servidor (más seguro)
        $total_venta = 0;
        foreach ($productos_venta as $producto) {
            $total_venta += $producto['cantidad'] * $producto['precio'];
        }

        // 2. Insertar la venta principal en la tabla 'ventas'
        $stmt_venta = $conn->prepare("CALL sp_insert_venta(?, ?, ?, ?, ?, ?)");
        $stmt_venta->bind_param("iisssd", $id_cliente, $id_empleado, $fecha, $hora, $tipo_pago, $total_venta);
        $stmt_venta->execute();
        $id_venta_creada = $conn->insert_id; // Obtener el ID de la venta recién creada
        $stmt_venta->close();

        if ($id_venta_creada == 0) {
            // A veces insert_id no funciona bien con Stored Procedures, obtenemos el ID de forma segura
            $res_id = $conn->query("SELECT MAX(id_venta) as id FROM ventas WHERE id_cliente = $id_cliente AND id_empleado = $id_empleado");
            $id_venta_creada = $res_id->fetch_assoc()['id'];
        }

        // 3. Insertar cada producto en 'detalles_venta'
        $stmt_detalle = $conn->prepare("CALL sp_insert_detalle_venta(?, ?, ?, ?)");
        foreach ($productos_venta as $producto) {
            $id_producto = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio = $producto['precio'];
            $stmt_detalle->bind_param("iiid", $id_venta_creada, $id_producto, $cantidad, $precio);
            $stmt_detalle->execute();
            // El trigger 'trg_descuento_stock_venta' se encargará de reducir el stock
        }
        $stmt_detalle->close();
        
        // 4. Lógica para ventas a crédito
        if ($tipo_pago === 'Crédito') {
            $estado_credito = 'Pendiente';
            $plazo_dias = (int) $_POST['plazo_dias'];
            $fecha_vencimiento = date('Y-m-d', strtotime("$fecha +$plazo_dias days"));
            $stmt_credito = $conn->prepare("INSERT INTO creditos_clientes (id_cliente, id_venta, monto_credito, estado_credito, fecha_otorgamiento, fecha_vencimiento_pago, monto_restante) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt_credito->bind_param("iissssd", $id_cliente, $id_venta_creada, $total_venta, $estado_credito, $fecha, $fecha_vencimiento, $total_venta);
            $stmt_credito->execute();
            $stmt_credito->close();
            $mensaje = "<div class='bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4' role='alert'>Venta a <strong>crédito</strong> (ID: {$id_venta_creada}) registrada correctamente.</div>";
        } else {
            $mensaje = "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4' role='alert'>Venta (ID: {$id_venta_creada}) registrada correctamente.</div>";
        }

        // Si todo fue exitoso, confirmar la transacción
        $conn->commit();

    } catch (Exception $e) {
        // Si algo falla, revertir todos los cambios
        $conn->rollback();
        $mensaje = "<div class='bg-red-100 border-l-4 border-red-500 text-red-700 p-4' role='alert'>Error al registrar la venta: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva Venta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 p-6">
    <div class="container mx-auto max-w-4xl">
        <h2 class="text-3xl font-bold text-yellow-600 mb-6">Registrar Venta</h2>
        <?php echo $mensaje; ?>

        <form action="nueva_venta.php" method="post" class="bg-white p-6 rounded-lg shadow-md space-y-6" id="venta-form">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium text-gray-700">Cliente:</label>
                    <select name="id_cliente" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option disabled selected>Seleccione un cliente</option>
                        <?php while ($c = $clientes->fetch_assoc()) { ?>
                            <option value="<?php echo $c['id_cliente']; ?>">
                                <?php echo $c['nombre'] . " " . $c['apellido']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Empleado:</label>
                    <select name="id_empleado" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option disabled selected>Seleccione un empleado</option>
                        <?php while ($e = $empleados->fetch_assoc()) { ?>
                            <option value="<?php echo $e['id_empleado']; ?>">
                                <?php echo $e['nombre'] . " " . $e['apellido']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <input type="hidden" name="fecha" value="<?php echo $fecha_actual; ?>">
            <input type="hidden" name="hora" value="<?php echo $hora_actual; ?>">

            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalle de la Venta</h3>
                <div class="flex items-end gap-4 bg-gray-50 p-4 rounded-lg">
                    <div class="flex-grow">
                        <label for="producto-select" class="block font-medium text-gray-700">Producto:</label>
                        <select id="producto-select" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Seleccione un producto</option>
                            <?php foreach ($productos_disponibles as $p) {
                                echo "<option value='{$p['id_producto']}'>{$p['nombre']} (Stock: {$p['stock_actual']})</option>";
                            } ?>
                        </select>
                    </div>
                    <div>
                        <label for="cantidad-input" class="block font-medium text-gray-700">Cantidad:</label>
                        <input type="number" id="cantidad-input" min="1" value="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <button type="button" id="add-product-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Agregar</button>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Producto</th>
                            <th class="px-4 py-2 text-left">Cantidad</th>
                            <th class="px-4 py-2 text-left">Precio Unit.</th>
                            <th class="px-4 py-2 text-left">Subtotal</th>
                            <th class="px-4 py-2 text-left">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="detalle-venta-body">
                        </tbody>
                </table>
                 <div id="productos-hidden-inputs"></div>
            </div>

            <div class="border-t border-gray-200 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block font-medium text-gray-700">Tipo de Pago:</label>
                    <select name="tipo_pago" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        <option disabled selected>Seleccione tipo de pago</option>
                        <option value="Contado">Contado</option>
                        <option value="Crédito">Crédito</option>
                    </select>

                    <div id="plazo_credito" style="display:none;" class="mt-4">
                        <label class="block font-medium text-gray-700">Plazo del crédito (días):</label>
                        <select name="plazo_dias" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="7">7 días</option>
                            <option value="15">15 días</option>
                            <option value="30">30 días</option>
                        </select>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-gray-600">Total Venta:</p>
                    <p id="total-venta-display" class="text-3xl font-bold text-gray-900">$0.00</p>
                </div>
            </div>
            
            <div class="text-right border-t border-gray-200 pt-6">
                 <button type="submit" class="bg-yellow-600 text-white px-6 py-3 rounded-md text-lg font-semibold hover:bg-yellow-700">Crear Venta</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- VARIABLES Y CONSTANTES ---
            const productosData = <?php echo json_encode($productos_disponibles); ?>;
            const productoSelect = document.getElementById('producto-select');
            const cantidadInput = document.getElementById('cantidad-input');
            const addProductBtn = document.getElementById('add-product-btn');
            const detalleVentaBody = document.getElementById('detalle-venta-body');
            const totalVentaDisplay = document.getElementById('total-venta-display');
            const productosHiddenInputs = document.getElementById('productos-hidden-inputs');
            const tipoPagoSelect = document.querySelector('select[name="tipo_pago"]');
            const plazoCreditoDiv = document.getElementById('plazo_credito');
            let ventaItems = []; // Array para manejar los productos de la venta

            // --- FUNCIONES ---
            function agregarProducto() {
                const productoId = productoSelect.value;
                const cantidad = parseInt(cantidadInput.value);

                if (!productoId) {
                    alert('Por favor, seleccione un producto.');
                    return;
                }
                if (isNaN(cantidad) || cantidad <= 0) {
                    alert('Por favor, ingrese una cantidad válida.');
                    return;
                }

                const producto = productosData[productoId];

                // Validar si el producto ya está en la lista
                if(ventaItems.some(item => item.id == productoId)){
                    alert('El producto ya ha sido agregado. Puede modificar la cantidad o eliminarlo.');
                    return;
                }

                // Validar stock
                if (cantidad > producto.stock_actual) {
                    alert(`Stock insuficiente. Solo hay ${producto.stock_actual} unidades disponibles.`);
                    return;
                }

                // Agregar item al array de la venta
                ventaItems.push({
                    id: producto.id_producto,
                    nombre: producto.nombre,
                    cantidad: cantidad,
                    precio: parseFloat(producto.precio_venta),
                    stock: producto.stock_actual
                });
                
                renderizarTabla();
            }

            function renderizarTabla() {
                detalleVentaBody.innerHTML = ''; // Limpiar tabla
                productosHiddenInputs.innerHTML = ''; // Limpiar inputs ocultos
                let totalGeneral = 0;
                
                ventaItems.forEach((item, index) => {
                    const subtotal = item.cantidad * item.precio;
                    totalGeneral += subtotal;

                    // Crear fila en la tabla visible
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="border-b px-4 py-2">${item.nombre}</td>
                        <td class="border-b px-4 py-2">${item.cantidad}</td>
                        <td class="border-b px-4 py-2">$${item.precio.toFixed(2)}</td>
                        <td class="border-b px-4 py-2">$${subtotal.toFixed(2)}</td>
                        <td class="border-b px-4 py-2">
                            <button type="button" class="text-red-600 hover:text-red-900" data-index="${index}">Eliminar</button>
                        </td>
                    `;
                    detalleVentaBody.appendChild(tr);

                    // Crear inputs ocultos para enviar con el formulario
                    productosHiddenInputs.innerHTML += `
                        <input type="hidden" name="productos[${index}][id]" value="${item.id}">
                        <input type="hidden" name="productos[${index}][cantidad]" value="${item.cantidad}">
                        <input type="hidden" name="productos[${index}][precio]" value="${item.precio}">
                    `;
                });

                totalVentaDisplay.textContent = `$${totalGeneral.toFixed(2)}`;
            }
            
            function eliminarProducto(index) {
                ventaItems.splice(index, 1); // Eliminar del array
                renderizarTabla();
            }

            // --- EVENT LISTENERS ---
            addProductBtn.addEventListener('click', agregarProducto);

            // Delegación de eventos para los botones de eliminar
            detalleVentaBody.addEventListener('click', function(e) {
                if (e.target && e.target.matches('button[data-index]')) {
                    const index = e.target.getAttribute('data-index');
                    eliminarProducto(index);
                }
            });
            
            // Mostrar/ocultar plazo de crédito
            tipoPagoSelect.addEventListener('change', function () {
                plazoCreditoDiv.style.display = this.value === 'Crédito' ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>