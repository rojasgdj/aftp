<?php
session_start();
session_regenerate_id(true);

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Índices de Soportes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: white;
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        h2 {
            background: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        th {
            background: #007bff;
            color: white;
        }
        .indice-header {
            background: #ffc107;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .indice-tabla {
            display: none;
        }
        .menu {
            text-align: right;
            margin-bottom: 15px;
        }
        .menu a {
            background: #28a745;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .menu a:hover {
            background: #218838;
        }
    </style>
    <script>
        function toggleIndice(id) {
            const cont = document.getElementById(id);
            cont.style.display = cont.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>

<div class="container">
    <div class="menu"><a href="index.php">⬅ Volver al Menú</a></div>
    <h2>Soportes Agrupados por Índice (Carpetas)</h2>

    <?php
    try {
        $stmt = $pdo->query("SELECT DISTINCT indice_archivo FROM soportes_factura ORDER BY indice_archivo");
        $indices = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($indices) {
            foreach ($indices as $indice) {
                $id = 'contenido_' . htmlspecialchars($indice);
                echo "<div class='indice-header' onclick=\"toggleIndice('$id')\">📂 Carpeta: <strong>$indice</strong></div>";
                echo "<div id='$id' class='indice-tabla'>";

                $stmt2 = $pdo->prepare("
                    SELECT sf.numero_factura, sf.descripcion, sf.sucursal, sf.fecha_emision, 
                           sf.ruta_archivo, pr.anios_retencion
                    FROM soportes_factura sf
                    INNER JOIN politicas_retencion pr ON sf.id_retencion = pr.id_retencion
                    WHERE sf.indice_archivo = ?
                    ORDER BY sf.fecha_emision DESC
                ");
                $stmt2->execute([$indice]);
                $soportes = $stmt2->fetchAll();

                if ($soportes) {
                    echo "<table>
                            <tr>
                                <th>Número</th>
                                <th>Descripción</th>
                                <th>Sucursal</th>
                                <th>Fecha Emisión</th>
                                <th>Fecha Destrucción</th>
                                <th>Soporte PDF</th>
                            </tr>";
                    foreach ($soportes as $s) {
                        $fechaEmision = new DateTime($s['fecha_emision']);
                        $fechaDestruccion = (clone $fechaEmision)->modify('+' . $s['anios_retencion'] . ' years');

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($s['numero_factura']) . "</td>";
                        echo "<td>" . htmlspecialchars($s['descripcion']) . "</td>";
                        echo "<td>" . htmlspecialchars($s['sucursal']) . "</td>";
                        echo "<td>" . $fechaEmision->format('Y-m-d') . "</td>";
                        echo "<td>" . $fechaDestruccion->format('Y-m-d') . "</td>";
                        echo "<td><a href='descargar.php?file=" . basename($s['ruta_archivo']) . "' target='_blank'>📄 Ver</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>📭 Sin soportes en esta carpeta.</p>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>No hay soportes registrados aún.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</div>

</body>
</html>