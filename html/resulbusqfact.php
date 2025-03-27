<?php
require_once "db.php"; // Conexión a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Facturas Recibidas</title>
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
        // Construcción del WHERE dinámico
        $condiciones = [];
        $parametros = [];

        if (!empty($_REQUEST['factura'])) {
            $condiciones[] = "f.numero_factura = :factura";
            $parametros[':factura'] = $_REQUEST['factura'];
        }

        if (!empty($_REQUEST['fecha'])) {
            $fecha = DateTime::createFromFormat("Y-m-d", $_REQUEST['fecha']);
            if ($fecha) {
                $condiciones[] = "f.fecha_emision = :fecha";
                $parametros[':fecha'] = $fecha->format("Y-m-d");
            }
        }

        if (!empty($_REQUEST['proveedor'])) {
            $condiciones[] = "f.cod_proveedor = :proveedor";
            $parametros[':proveedor'] = $_REQUEST['proveedor'];
        }

        if (empty($condiciones)) {
            echo "<script>alert('Debe colocar al menos un campo de búsqueda.'); window.location.href = 'buscarfactura.php';</script>";
            exit;
        }

        // Consulta SQL
        $query = "SELECT f.numero_factura, f.concepto, f.fecha_emision, f.valor_factura, f.fecha_creacion, 
                         p.razon_social AS proveedor, s.razon_social AS sucursal
                  FROM facturas f
                  INNER JOIN proveedores p ON f.cod_proveedor = p.cod_proveedor
                  INNER JOIN sucursal s ON f.cod_cia = s.cod_cia
                  WHERE " . implode(" AND ", $condiciones);

        $stmt = $pdo->prepare($query);
        $stmt->execute($parametros);

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Proveedor</th>
                    <th>Sucursal</th>
                    <th>Número de Factura</th>
                    <th>Fecha Emisión</th>
                    <th>Descripción</th>
                    <th>Monto Bs.</th>
                  </tr>";

            while ($reg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($reg['proveedor']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['sucursal']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['numero_factura']) . "</td>";
                echo "<td>" . htmlspecialchars($reg['fecha_emision']) . "</td>";
                echo "<td style='text-align:left; max-width: 250px; overflow:hidden; text-overflow: ellipsis; white-space: nowrap;'>"
                    . htmlspecialchars($reg['concepto']) . "</td>";
                echo "<td align='right'>" . number_format($reg['valor_factura'], 2, ',', '.') . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No se encontraron facturas con los criterios ingresados.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>

    <p class="fecha">Fecha de emisión: <?php echo date("d-m-Y H:i:s"); ?></p>

    <button onclick="window.print()">Imprimir</button>
    <p><a href="buscarfactura.php">Regresar</a></p>
</div>

</body>
</html>