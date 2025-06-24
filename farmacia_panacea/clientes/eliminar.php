<?php
require_once("../db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Llamamos al procedimiento almacenado para eliminar
    $stmt = $conn->prepare("CALL sp_eliminar_cliente(?)");
    $stmt->bind_param("i", $id);
    
    // El try-catch maneja el error si no se puede eliminar (ej. por FK constraint)
    try {
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        // En una aplicación real, aquí podrías registrar el error y mostrar un mensaje amigable.
        // Por ejemplo: header("Location: consultar.php?error=No se pudo eliminar al cliente, tiene ventas asociadas.");
        // Por ahora, simplemente detenemos la ejecución y mostramos un error simple.
        die("<b>Error:</b> No se puede eliminar el cliente. Es probable que tenga un historial de ventas o créditos. <a href='consultar.php'>Volver</a>");
    }
    
    $stmt->close();
}

// Redirigir a la página de consulta si todo salió bien
header("Location: consultar.php");
exit;
?>