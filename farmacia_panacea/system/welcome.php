<?php
// Este archivo genera una página de bienvenida para cada sección

$seccion = $_GET['seccion'] ?? 'desconocida';
$titulo = "Bienvenido a la sección de " . ucfirst($seccion);
$descripcion = "";
$icono_svg = "";

// Definimos el contenido para cada sección
switch ($seccion) {
    case 'clientes':
        $descripcion = "Aquí puedes registrar nuevos clientes y consultar, editar o eliminar los existentes.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63l-6.75 3.375a.75.75 0 01-.676 0l-6.75-3.375a.75.75 0 01-.363-.63V19.125zM15.75 19.125a5.625 5.625 0 0111.25 0v.003l-.001.119a.75.75 0 01-.363.63l-5.25 2.625a.75.75 0 01-.676 0l-5.25-2.625a.75.75 0 01-.363-.63V19.125z" /></svg>';
        break;
    case 'empleados':
        $descripcion = "Gestiona al personal de la farmacia. Registra nuevos empleados y administra sus datos.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path fill-rule="evenodd" d="M7.5 5.25a3 3 0 013-3h3a3 3 0 013 3v.205c.633.085 1.237.24 1.795.462a.75.75 0 01.659.852l-.687 4.126a3 3 0 01-2.933 2.541H8.368a3 3 0 01-2.933-2.541l-.687-4.126a.75.75 0 01.66-.852c.557-.222 1.16-.377 1.795-.462V5.25zM12 10.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4.5 1.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM13.5 15a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" clip-rule="evenodd" /></svg>';
        break;
    case 'proveedores':
        $descripcion = "Administra la información de contacto y los datos de tus proveedores de productos.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" /><path d="M16.5 4.5c0-1.036.84-1.875 1.875-1.875h.375a3 3 0 116 0h.375a1.875 1.875 0 011.875 1.875v13.125a1.875 1.875 0 01-1.875 1.875h-8.25a1.875 1.875 0 01-1.875-1.875V4.5z" /></svg>';
        break;
    case 'productos':
        $descripcion = "Consulta tu inventario, actualiza precios, revisa el stock y registra nuevos productos.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path fill-rule="evenodd" d="M.75 4.5A.75.75 0 011.5 3.75h12a.75.75 0 01.75.75v12a.75.75 0 01-.75.75h-12a.75.75 0 01-.75-.75v-12zM4.5 6.75a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6zm0 3a.75.75 0 000 1.5h6a.75.75 0 000-1.5h-6zm.75 2.25a.75.75 0 01.75-.75h4.5a.75.75 0 010 1.5h-4.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" /><path d="M17.25 3.75a.75.75 0 01.75.75v15a.75.75 0 01-1.5 0V4.5a.75.75 0 01.75-.75zm1.5 0a.75.75 0 01.75.75v15a.75.75 0 01-1.5 0V4.5a.75.75 0 01.75-.75zm1.5 0a.75.75 0 01.75.75v15a.75.75 0 01-1.5 0V4.5a.75.75 0 01.75-.75z" /></svg>';
        break;
    case 'lotes':
        $descripcion = "Registra los lotes de productos nuevos que ingresan a la farmacia y consulta el historial.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path fill-rule="evenodd" d="M.75 4.5A.75.75 0 011.5 3.75h12a.75.75 0 01.75.75v15a.75.75 0 01-1.5 0V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" /><path d="M5.25 3.75A.75.75 0 016 3h12a.75.75 0 01.75.75v16.5a.75.75 0 01-.75.75H6a.75.75 0 01-.75-.75V3.75zM9 6a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 019 6zm3.75-1.5a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zM9 9a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 019 9zm3.75-1.5a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zM9 12a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 019 12zm3.75-1.5a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5z" /></svg>';
        break;
    case 'ventas':
        $descripcion = "Inicia el proceso de una nueva venta o consulta el historial de ventas realizadas.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.46-5.23c.18-.487.18-1.035 0-1.522a3.75 3.75 0 00-3.429-2.571H8.694l-2.558-9.592a.75.75 0 00-.722-.53h-3.11z" /></svg>';
        break;
    case 'creditos':
        $descripcion = "Consulta el estado de los créditos otorgados a clientes y registra los pagos recibidos.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" /><path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75v-.008a.75.75 0 00-.75-.75H18.75z" clip-rule="evenodd" /></svg>';
        break;
    case 'reportes':
        $descripcion = "Visualiza reportes importantes como productos por vencer, resúmenes financieros y más.";
        $icono_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20"><path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z" /></svg>';
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $titulo; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="text-center p-12 bg-white rounded-xl shadow-lg max-w-2xl">
        <div class="text-blue-500 mx-auto mb-6">
            <?php echo $icono_svg; ?>
        </div>
        <h1 class="text-4xl font-bold text-gray-800"><?php echo $titulo; ?></h1>
        <p class="mt-4 text-lg text-gray-600">
            <?php echo $descripcion; ?>
        </p>
        <p class="mt-8 text-sm text-gray-500">
            Utiliza el menú de la izquierda para navegar por las opciones de esta sección.
        </p>
    </div>
</body>
</html>