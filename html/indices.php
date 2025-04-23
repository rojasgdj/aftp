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
        .etiqueta-btn {
            float: right;
            background: black;
            color: white;
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 0 10px #000;
            position: relative;
        }
        .modal-content h3 {
            margin-bottom: 10px;
        }
        .close {
            position: absolute;
            right: 10px; top: 10px;
            font-size: 18px;
            cursor: pointer;
        }
        .modal-content input,
        .modal-content textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .modal-content button {
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background: #0056b3;
        }
    </style>
    <script>
        function toggleIndice(id) {
            const cont = document.getElementById(id);
            cont.style.display = cont.style.display === 'none' ? 'block' : 'none';
        }

        function abrirModal(numero) {
            document.getElementById('numero_factura').value = numero;
            document.getElementById('correoModal').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('correoModal').style.display = 'none';
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
                $rangoStmt = $pdo->prepare("SELECT COUNT(*) AS total, MIN(fecha_emision) AS desde, MAX(fecha_emision) AS hasta
                                            FROM soportes_factura WHERE indice_archivo = ?");
                $rangoStmt->execute([$indice]);
                $datos = $rangoStmt->fetch();

                $btnEtiqueta = '';
                if ($datos['total'] >= 10) {
                    $desde = urlencode($datos['desde']);
                    $hasta = urlencode($datos['hasta']);
                    $btnEtiqueta = "<a class='etiqueta-btn' href='etiqueta.php?indice=$indice&desde=$desde&hasta=$hasta' target='_blank'>Etiqueta</a>";
                }

                $id = 'contenido_' . htmlspecialchars($indice);
                echo "<div class='indice-header' onclick=\"toggleIndice('$id')\">
                        📂 Carpeta: <strong>$indice</strong> $btnEtiqueta
                      </div>";
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
                        echo "<td>
                                <a href='descargar.php?file=" . basename($s['ruta_archivo']) . "' target='_blank'>📄 Ver</a><br>
                                <a href='#' onclick=\"abrirModal('" . htmlspecialchars($s['numero_factura']) . "')\">✉️ Enviar</a>
                              </td>";
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

<!-- Modal para envío de correo -->
<div class="modal" id="correoModal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">✖</span>
        <h3>Enviar Soporte</h3>
        <form method="post" action="enviar_soporte.php">
            <input type="hidden" name="numero_factura" id="numero_factura">
            <label>Destinatario:</label>
            <input type="email" name="email" required placeholder="correo@dominio.com">
            <label>Mensaje:</label>
            <textarea name="mensaje" rows="4" placeholder="Mensaje adicional..."></textarea>
            <button type="submit">📧 Enviar Soporte</button>
        </form>
    </div>
</div>

</body>
</html>