<?php
require_once "db.php"; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Recibir y sanitizar datos
        $rif = trim($_POST['rif']);
        $razonsoc = trim($_POST['razonsoc']);
        $tel = trim($_POST['telefono']);
        $dir = trim($_POST['direccion']);
        $persona = trim($_POST['persona']);
        $hoy = date("Y-m-d H:i:s");

        // Validación de datos
        if (empty($rif) || empty($razonsoc) || empty($tel) || empty($dir) || empty($persona)) {
            echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
            exit;
        }

        // Insertar en la base de datos con sentencias preparadas
        $stmt = $conexion->prepare("INSERT INTO proveedores (rif, razonsoc, telefono, direccion, contacto, status, fechacre) 
                                    VALUES (:rif, :razonsoc, :tel, :dir, :persona, 'ACTIVO', :hoy)");

        $stmt->bindParam(':rif', $rif, PDO::PARAM_STR);
        $stmt->bindParam(':razonsoc', $razonsoc, PDO::PARAM_STR);
        $stmt->bindParam(':tel', $tel, PDO::PARAM_STR);
        $stmt->bindParam(':dir', $dir, PDO::PARAM_STR);
        $stmt->bindParam(':persona', $persona, PDO::PARAM_STR);
        $stmt->bindParam(':hoy', $hoy, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<script>alert('Los datos fueron guardados con éxito.'); window.location.href = 'proveedores01.php';</script>";
        } else {
            echo "<p>Error al guardar los datos.</p>";
        }

    } catch (PDOException $e) {
        die("Error en la consulta: " . $e->getMessage());
    }
} else {
    echo "<p>Método de acceso no permitido.</p>";
}
?>