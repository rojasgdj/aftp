<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require 'db.php';

    try {
        // Sanitizar datos
        $numero_factura  = trim($_POST['factura']);
        $concepto        = trim($_POST['concepto']);
        $fecha_emision   = trim($_POST['fecha']);
        $cod_proveedor   = intval($_POST['proveedor']);
        $valor_factura   = floatval($_POST['monto']);
        $cod_cia         = intval($_POST['cod_cia']);
        $fecha_creacion  = date("Y-m-d H:i:s");

        // Validación básica
        if (
            $numero_factura === '' || 
            $concepto === '' || 
            $fecha_emision === '' || 
            $cod_proveedor <= 0 || 
            $valor_factura <= 0 || 
            $cod_cia <= 0
        ) {
            echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
            exit;
        }

        // Insertar en base de datos
        $stmt = $pdo->prepare("
            INSERT INTO facturas (
                numero_factura, concepto, cod_proveedor, fecha_emision, 
                fecha_creacion, valor_factura, status_factura, cod_cia
            ) VALUES (
                :numero_factura, :concepto, :cod_proveedor, :fecha_emision,
                :fecha_creacion, :valor_factura, 'ACTIVA', :cod_cia
            )
        ");

        $stmt->execute([
            ':numero_factura' => $numero_factura,
            ':concepto'       => $concepto,
            ':cod_proveedor'  => $cod_proveedor,
            ':fecha_emision'  => $fecha_emision,
            ':fecha_creacion' => $fecha_creacion,
            ':valor_factura'  => $valor_factura,
            ':cod_cia'        => $cod_cia
        ]);

        echo "<script>
                alert('Factura registrada con éxito.');
                window.location.href = 'factura01.php';
              </script>";
        exit;

    } catch (PDOException $e) {
        echo "<script>alert('Error al registrar la factura: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    echo "<p>Acceso no autorizado.</p>";
}
?>