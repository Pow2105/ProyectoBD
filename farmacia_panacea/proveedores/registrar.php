<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Registrar Proveedor</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-50 p-6">
<div class="max-w-xl mx-auto">
<h2 class="text-2xl font-bold text-indigo-700 mb-4">Nuevo Proveedor</h2>
<form action="procesar_registro.php" method="post" class="space-y-4">
  <div><label>Nombre Empresa:</label><input type="text" name="empresa" class="w-full border px-3 py-2"></div>
  <div><label>Dirección:</label><input type="text" name="direccion" class="w-full border px-3 py-2"></div>
  <div><label>Nombre Contacto:</label><input type="text" name="contacto" class="w-full border px-3 py-2"></div>
  <div><label>Teléfono:</label><input type="text" name="telefono" class="w-full border px-3 py-2"></div>
  <div><label>Email:</label><input type="email" name="email" class="w-full border px-3 py-2"></div>
  <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Registrar</button>
</form>
</div>
</body>
</html>