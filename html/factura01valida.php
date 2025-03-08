<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php'; // Conectar a la base de datos

    try {
        // Recibir y sanitizar datos
        $numero_factura  = trim($_POST['factura']);  
        $concepto        = trim($_POST['concepto']);
        $fecha_emision   = trim($_POST['fecha']);
        $cod_cliente     = intval($_POST['cliente']);
        $valor_factura   = floatval($_POST['monto']);
        $cod_cia         = intval($_POST['cod_cia']); // Recibir la sucursal seleccionada
        $fecha_creacion  = date("Y-m-d H:i:s");

        // Validar campos obligatorios
        if (empty($numero_factura) || empty($concepto) || empty($fecha_emision) || empty($cod_cliente) || empty($valor_factura) || empty($cod_cia)) {
            echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
            exit;
        }

        // Insertar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO facturas 
            (numero_factura, concepto, cod_cliente, fecha_emision, fecha_creacion, valor_factura, status_factura, cod_cia) 
            VALUES (:numero_factura, :concepto, :cod_cliente, :fecha_emision, :fecha_creacion, :valor_factura, 'ACTIVA', :cod_cia)");

        $stmt->execute([
            ':numero_factura' => $numero_factura,
            ':concepto'       => $concepto,
            ':cod_cliente'    => $cod_cliente,
            ':fecha_emision'  => $fecha_emision,
            ':fecha_creacion' => $fecha_creacion,
            ':valor_factura'  => $valor_factura,
            ':cod_cia'        => $cod_cia
        ]);

        echo "<script>
                alert('Factura registrada con éxito.');
                window.location.href = 'factura01.php';
              </script>";

        $pdo = null;
        
    } catch (PDOException $e) {
        echo "<script>alert('Error en la conexión: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
    }
} else {
    echo "<p>Método de acceso no permitido.</p>";
}
?>