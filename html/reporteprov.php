<?php
require_once "db.php"; // Incluir conexión a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proveedores</title>

    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        button:hover {
            background-color: #218838;
        }
        .fecha {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reporte de Proveedores</h2>

    <?php
    try {
        // Consulta de proveedores
        $stmt = $conexion->query("SELECT codprov, rif, razonsoc FROM proveedores ORDER BY fechacre DESC");

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Código</th>
                    <th>RIF</th>
                    <th>Razón Social</th>
                  </tr>";

            while ($reg = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($reg['codprov']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['rif']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['razonsoc']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay proveedores registrados.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error en la consulta: " . $e->getMessage() . "</p>";
    }
    ?>

    <p class="fecha">Fecha de emisión: <?php echo date("d-m-Y H:i:s"); ?></p>

    <button onclick="window.print()">Imprimir</button>
</div>

</body>
</html>