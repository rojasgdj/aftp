<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Validación de Datos</title>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php'; // Conexión centralizada

    try {
        // Recibir y sanitizar datos
        $factura  = intval($_POST['factura']); // Asegurar que sea número
        $concepto = trim($_POST['concepto']);
        $fecha    = trim($_POST['fecha']);
        $cliente  = intval($_POST['cliente']);
        $monto    = floatval($_POST['monto']);
        $cia      = 1;
        $hoy      = date("Y-m-d H:i:s");

        // Convertir fecha de dd/mm/yyyy a YYYY-MM-DD
        $fechaems = DateTime::createFromFormat('d/m/Y', $fecha);
        if ($fechaems) {
            $fechaems = $fechaems->format('Y-m-d');
        } else {
            echo "<script>alert('Formato de fecha incorrecto.'); window.history.back();</script>";
            exit;
        }

        // Validar campos obligatorios
        if (empty($factura) || empty($concepto) || empty($cliente) || empty($monto)) {
            echo "<script>alert('Todos los campos son obligatorios.'); window.history.back();</script>";
            exit;
        }

        // Insertar en la base de datos usando sentencias preparadas
        $stmt = $pdo->prepare("INSERT INTO facturas (numero, concepto, codcli, fechaems, fechacre, monto, status, codcia) 
                               VALUES (:numero, :concepto, :codcli, :fechaems, :fechacre, :monto, 'ACTIVA', :codcia)");

        $stmt->execute([
            ':numero'   => $factura,
            ':concepto' => $concepto,
            ':codcli'   => $cliente,
            ':fechaems' => $fechaems,
            ':fechacre' => $hoy,
            ':monto'    => $monto,
            ':codcia'   => $cia
        ]);

        echo "<script>
                alert('Los datos fueron guardados con éxito.');
                window.location.href = 'factura01.php';
              </script>";

        // Cerrar conexión
        $pdo = null;
        
    } catch (PDOException $e) {
        die("Error en la conexión: " . htmlspecialchars($e->getMessage()));
    }
} else {
    echo "<p>Método de acceso no permitido.</p>";
}
?>

</body>
</html>