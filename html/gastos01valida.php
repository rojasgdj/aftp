<?php
require 'db.php'; // Usamos la conexión centralizada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Recibir y sanitizar datos
        $codigo = trim($_POST['codigo']);
        $factura = trim($_POST['factura']);
        $concepto = trim($_POST['concepto']);
        $fecha = trim($_POST['fecha']);
        $proveedor = intval($_POST['proveedor']); // Asegurar que sea número
        $monto = floatval($_POST['monto']); // Convertir a número
        $cia = 1;
        $hoy = date("Y-m-d H:i:s");

        // Convertir fecha de dd/mm/yyyy a formato YYYYMMDD
        $fechaems = DateTime::createFromFormat('d/m/Y', $fecha);
        if ($fechaems) {
            $fechaems = $fechaems->format('Ymd');
        } else {
            echo "<script>alert('Formato de fecha incorrecto.'); window.history.back();</script>";
            exit;
        }

        // Validación de datos obligatorios
        if (empty($codigo) || empty($factura) || empty($concepto) || empty($proveedor) || empty($monto)) {
            echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
            exit;
        }

        // Insertar en la base de datos usando sentencias preparadas
        $stmt = $pdo->prepare("
            INSERT INTO gastos (codigo, factura, concepto, codprov, fechaems, fechacre, monto, status, codcia) 
            VALUES (:codigo, :factura, :concepto, :codprov, :fechaems, :fechacre, :monto, 'ACTIVA', :codcia)
        ");

        $stmt->execute([
            ':codigo'   => $codigo,
            ':factura'  => $factura,
            ':concepto' => $concepto,
            ':codprov'  => $proveedor,
            ':fechaems' => $fechaems,
            ':fechacre' => $hoy,
            ':monto'    => $monto,
            ':codcia'   => $cia
        ]);

        // Mensaje de éxito y redirección
        echo "<script>
                alert('Los datos fueron guardados con éxito.');
                window.location.href = 'gastos01.php';
              </script>";
        
    } catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    } finally {
        $pdo = null; // Cerrar conexión
    }
} else {
    echo "<p>Método de acceso no permitido.</p>";
}
?>