<?php
require 'db.php'; // Conexión centralizada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Recibir y sanitizar datos
        $codigo         = trim($_POST['codigo']);
        $factura        = trim($_POST['factura']);
        $concepto       = trim($_POST['concepto_gasto']);
        $fecha          = trim($_POST['fecha_emision']); // La fecha ya viene en formato YYYY-MM-DD
        $proveedor      = intval($_POST['cod_proveedor']); // ID del proveedor
        $sucursal       = intval($_POST['cod_cia']); // ID de la sucursal
        $monto          = floatval($_POST['valor_gasto']); // Convertir a número
        $fecha_creacion = date("Y-m-d H:i:s"); // Fecha actual

        // **Validación de datos obligatorios**
        if (empty($codigo) || empty($factura) || empty($concepto) || empty($fecha) || empty($proveedor) || empty($sucursal) || empty($monto)) {
            echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
            exit;
        }

        // **Conversión de fecha si viene en formato dd/mm/yyyy**
        if (strpos($fecha, "/") !== false) {
            $fecha_obj = DateTime::createFromFormat('d/m/Y', $fecha);
            if ($fecha_obj) {
                $fecha = $fecha_obj->format('Y-m-d');
            } else {
                echo "<script>alert('Formato de fecha incorrecto. Use dd/mm/yyyy.'); window.history.back();</script>";
                exit;
            }
        }

        // **Preparar e insertar datos**
        $stmt = $pdo->prepare("
            INSERT INTO gastos (codigo, factura, concepto_gasto, cod_proveedor, fecha_emision, fecha_creacion, valor_gasto, status, cod_cia) 
            VALUES (:codigo, :factura, :concepto, :proveedor, :fecha, :fecha_creacion, :monto, 'ACTIVO', :sucursal)
        ");

        $stmt->execute([
            ':codigo'         => $codigo,
            ':factura'        => $factura,
            ':concepto'       => $concepto,
            ':proveedor'      => $proveedor,
            ':fecha'          => $fecha,
            ':fecha_creacion' => $fecha_creacion,
            ':monto'          => $monto,
            ':sucursal'       => $sucursal
        ]);

        // **Mensaje de éxito y redirección**
        echo "<script>
                alert('Gasto registrado con éxito.');
                window.location.href = 'gastos01.php';
              </script>";

    } catch (PDOException $e) {
        echo "<script>alert('Error en la base de datos: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    } finally {
        $pdo = null; // Cerrar conexión
    }
} else {
    echo "<p>Método de acceso no permitido.</p>";
}
?>