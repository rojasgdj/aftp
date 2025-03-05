<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Validación de Datos</title>
</head>
<body>

<?php
require 'db.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos y limpiar entrada
    $rif = htmlspecialchars(trim($_POST['rif']));
    $razonsoc = htmlspecialchars(trim($_POST['razonsoc']));
    $tel = htmlspecialchars(trim($_POST['telefono']));
    $dir = htmlspecialchars(trim($_POST['direccion']));
    $persona = htmlspecialchars(trim($_POST['persona']));
    $hoy = date("Ymd");

    try {
        // Preparar consulta para evitar inyección SQL
        $stmt = $pdo->prepare("INSERT INTO clientes (rif, razonsoc, telefono, direccion, contacto, status, fechacre) 
                               VALUES (:rif, :razonsoc, :telefono, :direccion, :contacto, 'ACTIVO', :fechacre)");

        // Ejecutar consulta con parámetros
        if ($stmt->execute([
            ':rif' => $rif,
            ':razonsoc' => $razonsoc,
            ':telefono' => $tel,
            ':direccion' => $dir,
            ':contacto' => $persona,
            ':fechacre' => $hoy
        ])) {
            echo "<script>
                    alert('Los datos fueron guardados con éxito.');
                    window.location.href = 'clientes01.php';
                  </script>";
        } else {
            echo "<p>Error al guardar los datos.</p>";
        }

    } catch (PDOException $e) {
        echo "<p>Error en la conexión: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>Método de acceso no permitido.</p>";
}
?>

</body>
</html>