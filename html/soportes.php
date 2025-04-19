<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_regenerate_id(true);

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

// Procesar envío
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero_factura'])) {
    list($numero, $tipo) = explode('|', $_POST['numero_factura']);
    $id_retencion = intval($_POST['retencion']);

    // Verificar duplicado
    $check = $pdo->prepare("SELECT COUNT(*) FROM soportes_factura WHERE numero_factura = ?");
    $check->execute([$numero]);
    if ($check->fetchColumn() > 0) {
        echo "<script>alert('Ya existe un soporte para este documento.'); location.href='soportes.php';</script>";
        exit;
    }

    // Obtener datos según tipo
    if ($tipo === 'factura') {
        $stmt = $pdo->prepare("SELECT f.concepto AS descripcion, s.razon_social AS sucursal, f.fecha_emision
                               FROM facturas f
                               JOIN sucursal s ON f.cod_cia = s.cod_cia
                               WHERE f.numero_factura = ?");
    } else {
        $stmt = $pdo->prepare("SELECT g.concepto_gasto AS descripcion, s.razon_social AS sucursal, g.fecha_emision
                               FROM gastos g
                               JOIN sucursal s ON g.cod_cia = s.cod_cia
                               WHERE g.codigo = ?");
    }

    $stmt->execute([$numero]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$info) {
        echo "<script>alert('Documento no encontrado.');</script>";
    } elseif (!isset($_FILES['archivo_pdf']) || $_FILES['archivo_pdf']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Error al subir el archivo.');</script>";
    } else {
        $ext = strtolower(pathinfo($_FILES['archivo_pdf']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            echo "<script>alert('Solo se permiten archivos PDF.');</script>";
        } else {
            $pdf_tmp = $_FILES['archivo_pdf']['tmp_name'];
            $total = $pdo->query("SELECT COUNT(*) FROM soportes_factura")->fetchColumn();
            $indice = 'A' . ceil(($total + 1) / 10);
            $destino = "/data/soportes/{$numero}.pdf";

            if (!move_uploaded_file($pdf_tmp, $destino)) {
                echo "<script>alert('No se pudo mover el archivo.');</script>";
            } else {
                $insert = $pdo->prepare("INSERT INTO soportes_factura
                    (numero_factura, descripcion, sucursal, fecha_emision, id_retencion, ruta_archivo, indice_archivo, tipo_documento)
                    VALUES (:num, :desc, :suc, :fecha, :ret, :ruta, :indice, :tipo)");
                $insert->execute([
                    ':num' => $numero,
                    ':desc' => $info['descripcion'],
                    ':suc' => $info['sucursal'],
                    ':fecha' => $info['fecha_emision'],
                    ':ret' => $id_retencion,
                    ':ruta' => $destino,
                    ':indice' => $indice,
                    ':tipo' => $tipo
                ]);
                echo "<script>alert('Soporte cargado con éxito.'); location.href='soportes.php';</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargar Soporte</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; text-align: center; padding: 20px; }
        .container { background: white; max-width: 600px; margin: auto; padding: 20px;
                     border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { background: #007bff; color: white; padding: 15px; border-radius: 8px 8px 0 0; }
        label { display: block; margin-top: 10px; font-weight: bold; text-align: left; }
        select, input[type="file"] {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-top: 5px;
        }
        button {
            margin-top: 15px; background: #28a745; color: white; padding: 10px;
            border: none; width: 100%; border-radius: 5px; cursor: pointer;
        }
        .menu { text-align: right; margin-bottom: 15px; }
        .menu a {
            background: #007bff; color: white; text-decoration: none; padding: 8px 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="menu"><a href="index.php">⬅ Menú Principal</a></div>
    <h2>Cargar Soporte</h2>

    <form method="post" enctype="multipart/form-data">
        <label for="numero_factura">Número de Documento (Factura o Gasto)</label>
        <select name="numero_factura" required>
            <option value="">-- Seleccione --</option>

            <?php
            // Facturas sin soporte
            $sql = "
                SELECT f.numero_factura 
                FROM facturas f
                WHERE NOT EXISTS (
                    SELECT 1 FROM soportes_factura sf
                    WHERE CONVERT(sf.numero_factura USING utf8mb3) COLLATE utf8mb3_general_ci =
                          CONVERT(f.numero_factura USING utf8mb3) COLLATE utf8mb3_general_ci
                          AND sf.tipo_documento = 'factura'
                )
                ORDER BY f.fecha_creacion DESC
            ";
            $stmt = $pdo->query($sql);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $val = $row['numero_factura'] . '|factura';
                echo "<option value='$val'>" . htmlspecialchars($row['numero_factura']) . " (Factura)</option>";
            }

            // Gastos sin soporte (por código)
            $sql2 = "
                SELECT g.codigo 
                FROM gastos g
                WHERE NOT EXISTS (
                    SELECT 1 FROM soportes_factura sf
                    WHERE CONVERT(sf.numero_factura USING utf8mb3) COLLATE utf8mb3_general_ci =
                          CONVERT(g.codigo USING utf8mb3) COLLATE utf8mb3_general_ci
                          AND sf.tipo_documento = 'gasto'
                )
                ORDER BY g.fecha_emision DESC
            ";
            $stmt2 = $pdo->query($sql2);
            foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $val = $row['codigo'] . '|gasto';
                echo "<option value='$val'>" . htmlspecialchars($row['codigo']) . " (Gasto)</option>";
            }
            ?>
        </select>

        <label>Política de Retención</label>
        <select name="retencion" required>
            <option value="">-- Seleccione una política --</option>
            <?php
            $stmt = $pdo->query("SELECT id_retencion, nombre_politica FROM politicas_retencion");
            while ($row = $stmt->fetch()) {
                echo "<option value='{$row['id_retencion']}'>" . htmlspecialchars($row['nombre_politica']) . "</option>";
            }
            ?>
        </select>

        <label>Archivo PDF</label>
        <input type="file" name="archivo_pdf" accept=".pdf" required>

        <button type="submit">📄 Cargar Soporte</button>
    </form>
</div>

</body>
</html>