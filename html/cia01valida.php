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
    $codigo = htmlspecialchars(trim($_POST['cod_sucursal'])); // Campo corregido
    $razonsoc = htmlspecialchars(trim($_POST['razon_social'])); // Campo corregido
    $nit = htmlspecialchars(trim($_POST['nit'])); // Campo corregido
    $dir = htmlspecialchars(trim($_POST['direccion_proveedor'])); // Campo corregido
    $hoy = date("Y-m-d"); // Formato correcto para fecha

    try {
        // Preparar consulta para evitar inyección SQL
        $stmt = $pdo->prepare("INSERT INTO sucursal (cod_sucursal, razon_social, nit, direccion_proveedor, fecha_ingreso) 
                               VALUES (:cod_sucursal, :razon_social, :nit, :direccion_proveedor, :fecha_ingreso)");

        // Ejecutar consulta
        if ($stmt->execute([
            ':cod_sucursal' => $codigo,
            ':razon_social' => $razonsoc,
            ':nit' => $nit,
            ':direccion_proveedor' => $dir,
            ':fecha_ingreso' => $hoy
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