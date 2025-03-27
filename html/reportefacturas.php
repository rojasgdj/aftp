<?php
session_start();
session_regenerate_id(true);

// Seguridad de sesión y control de caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require_once "db.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Facturas Recibidas</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            text-align: center;
        }
        .descripcion {
            text-align: left;
            max-width: 280px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .fecha {
            margin-top: 10px;
            font-size: 13px;
            color: #666;
        }
        .btn {
            display: inline-block;
            margin: 10px 5px 0;
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-print {
            background-color: #007bff;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #28a745;
        }
        .btn-back:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🧾 Reporte de Facturas Recibidas</h2>

    <?php
    try {
        $stmt = $pdo->prepare("
            SELECT f.numero_factura, f.fecha_emision, f.valor_factura, f.fecha_creacion, 
                   p.cod_proveedor, p.razon_social AS proveedor, s.razon_social AS sucursal
            FROM facturas f
            INNER JOIN proveedores p ON f.cod_proveedor = p.cod_proveedor
            INNER JOIN sucursal s ON f.cod_cia = s.cod_cia
            ORDER BY f.fecha_creacion DESC
        ");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Código Proveedor</th>
                    <th>Razón Social</th>
                    <th>N° Factura</th>
                    <th>Fecha Emisión</th>
                    <th>Monto Bs.</th>
                    <th>Sucursal</th>
                  </tr>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['cod_proveedor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['proveedor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numero_factura']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_emision']) . "</td>";
                echo "<td align='right'>" . number_format($row['valor_factura'], 2, ',', '.') . "</td>";
                echo "<td>" . htmlspecialchars($row['sucursal']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No se encontraron facturas registradas.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>

    <p class="fecha">Fecha del reporte: <?= date("d-m-Y H:i:s") ?></p>

    <button class="btn btn-print" onclick="window.print()">🖨️ Imprimir</button>
    <button class="btn btn-back" onclick="location.href='index.php'">🏠 Volver al Menú</button>
</div>

</body>
</html>