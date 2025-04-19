<?php
session_start();
session_regenerate_id(true);

// Seguridad HTTP
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Autenticación
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
    <title>Buscar Soporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: white;
            max-width: 800px;
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
        }
        form {
            margin-top: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            width: 100%;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            margin-top: 20px;
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
        a.ver {
            color: #007bff;
            text-decoration: underline;
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
</head>
<body>

<div class="container">
    <div class="menu"><a href="index.php">⬅ Menú Principal</a></div>
    <h2>Buscar Soporte por Número de Factura</h2>

    <form method="post">
        <input type="text" name="numero_factura" placeholder="Ingrese número de factura" required>
        <button type="submit">🔎 Buscar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['numero_factura'])) {
        $factura = trim($_POST['numero_factura']);

        $stmt = $pdo->prepare("
            SELECT sf.numero_factura, sf.descripcion, sf.sucursal, sf.fecha_emision,
                   pr.nombre_politica, sf.ruta_archivo, sf.indice_archivo
            FROM soportes_factura sf
            INNER JOIN politicas_retencion pr ON sf.id_retencion = pr.id_retencion
            WHERE sf.numero_factura = ?
        ");
        $stmt->execute([$factura]);
        $soporte = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($soporte) {
            echo "<table>
                    <tr><th>Número de Factura</th><td>" . htmlspecialchars($soporte['numero_factura']) . "</td></tr>
                    <tr><th>Descripción</th><td>" . htmlspecialchars($soporte['descripcion']) . "</td></tr>
                    <tr><th>Sucursal</th><td>" . htmlspecialchars($soporte['sucursal']) . "</td></tr>
                    <tr><th>Fecha de Emisión</th><td>" . htmlspecialchars($soporte['fecha_emision']) . "</td></tr>
                    <tr><th>Política de Retención</th><td>" . htmlspecialchars($soporte['nombre_politica']) . "</td></tr>
                    <tr><th>Índice Asignado</th><td>" . htmlspecialchars($soporte['indice_archivo']) . "</td></tr>
                    <tr><th>Archivo PDF</th>
                        <td><a class='ver' href='descargar.php?file=" . urlencode(basename($soporte['ruta_archivo'])) . "'>⬇ Descargar Soporte</a></td>
                    </tr>
                  </table>";
        } else {
            echo "<p>No se encontró soporte para la factura ingresada.</p>";
        }
    }
    ?>
</div>

</body>
</html>