<?php
session_start();
session_regenerate_id(true);

// Seguridad y control de caché
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proveedores</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            box-sizing: border-box;
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
            font-size: 14px;
            margin-top: 15px;
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
        .fecha {
            margin-top: 15px;
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
    <h2>📋 Reporte de Proveedores</h2>

    <?php
    try {
        $stmt = $pdo->query("
            SELECT cod_proveedor, nit, razon_social, telefono, contacto 
            FROM proveedores 
            ORDER BY fecha_creacion DESC
        ");

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Código</th>
                    <th>RIF / NIT</th>
                    <th>Razón Social</th>
                    <th>Teléfono</th>
                    <th>Contacto</th>
                  </tr>";

            while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($prov['cod_proveedor']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['nit']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['razon_social']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['telefono']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['contacto']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay proveedores registrados.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>

    <p class="fecha">Fecha del reporte: <?= date("d-m-Y H:i:s") ?></p>

    <button class="btn btn-print" onclick="window.print()">🖨️ Imprimir</button>
    <button class="btn btn-back" onclick="window.location.href='index.php'">🏠 Volver al Menú</button>
</div>

</body>
</html>