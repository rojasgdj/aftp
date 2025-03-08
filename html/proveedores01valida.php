<?php
require_once "db.php"; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Recibir y sanitizar datos
        $nit = trim($_POST['nit']);
        $razon_social = trim($_POST['razon_social']);
        $telefono = trim($_POST['telefono']);
        $direccion_fiscal = trim($_POST['direccion_fiscal']);
        $contacto = trim($_POST['contacto']);
        $hoy = date("Y-m-d H:i:s");

        // Validación de datos
        if (empty($nit) || empty($razon_social) || empty($telefono) || empty($direccion_fiscal) || empty($contacto)) {
            echo "<script>alert('⚠️ Todos los campos son obligatorios.'); window.history.back();</script>";
            exit;
        }

        // Verificar si el NIT ya existe antes de insertar
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM proveedores WHERE nit = :nit");
        $stmt->bindParam(':nit', $nit, PDO::PARAM_STR);
        $stmt->execute();
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            echo "<script>alert('❌ Error: El NIT ingresado ya está registrado.'); window.history.back();</script>";
            exit;
        }

        // Insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO proveedores (nit, razon_social, telefono, direccion_fiscal, contacto, status, fecha_creacion) 
                               VALUES (:nit, :razon_social, :telefono, :direccion_fiscal, :contacto, 'ACTIVO', :hoy)");

        $stmt->bindParam(':nit', $nit, PDO::PARAM_STR);
        $stmt->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':direccion_fiscal', $direccion_fiscal, PDO::PARAM_STR);
        $stmt->bindParam(':contacto', $contacto, PDO::PARAM_STR);
        $stmt->bindParam(':hoy', $hoy, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Redirigir a la interfaz correcta
            header("Location: proveedores01.php");
            exit;
        } else {
            echo "<script>alert('⚠️ Error al guardar los datos.'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('❌ Error en la consulta: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('⚠️ Método de acceso no permitido.'); window.history.back();</script>";
}
?>