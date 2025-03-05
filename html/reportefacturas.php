<?php
require_once "db.php"; // Incluir conexión a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Facturas Recibidas</title>

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
    <h2>Reporte de Facturas Recibidas</h2>
    
    <?php
    try {
        // Consulta de facturas con INNER JOIN
        $stmt = $conexion->query("SELECT f.numero, f.fechaems, f.monto, f.fechacre, c.codcli, c.razonsoc 
                                  FROM facturas f 
                                  INNER JOIN clientes c ON f.codcli = c.codcli 
                                  ORDER BY f.fechacre DESC");

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Código</th>
                    <th>Razón Social</th>
                    <th>Número de Factura</th>
                    <th>Fecha</th>
                    <th>Monto Bs.</th>
                  </tr>";

            while ($reg = $stmt->fetch()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($reg['codcli']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['razonsoc']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['numero']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['fechaems']) . "</td>";
                echo "<td align='right'>" . number_format($reg['monto'], 2, ',', '.') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay facturas registradas.</p>";
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