<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Validación de Datos</title>
</head>
<body>

<?php
require 'db.php'; // Incluir la conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos y limpiar entrada
    $codigo = htmlspecialchars(trim($_POST['codigo']));
    $razonsoc = htmlspecialchars(trim($_POST['razonsoc']));
    $nit = htmlspecialchars(trim($_POST['rif']));
    $dir = htmlspecialchars(trim($_POST['direccion']));
    $hoy = date("Ymd"); 

    try {
        // Preparar consulta para evitar inyección SQL
        $stmt = $pdo->prepare("INSERT INTO compania (codigo, razonsoc, rif, direccion, fecha) 
                               VALUES (:codigo, :razonsoc, :rif, :direccion, :fecha)");

        // Ejecutar consulta
        if ($stmt->execute([
            ':codigo' => $codigo,
            ':razonsoc' => $razonsoc,
            ':nit' => $nit,
            ':direccion' => $dir,
            ':fecha' => $hoy
        ])) {
            echo "<script>
                    alert('Los datos fueron guardados con éxito.');
                    window.location.href = 'cia01.php';
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