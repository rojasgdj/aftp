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

// 🧠 Clasificador ML
function detectarTipoDocumento($archivoPdfTmp)
{
    $cmd = escapeshellcmd("/opt/aftp-ml/env/bin/python3 /opt/aftp-ml/clasificador.py " . $archivoPdfTmp);
    $output = shell_exec($cmd);
    if (!$output) return null;

    if (strpos($output, "Clasificado como: FACTURA") !== false) return 'factura';
    if (strpos($output, "Clasificado como: GASTO") !== false) return 'gasto';
    return null;
}

// 📝 Variables
$numero = $tipoSeleccionado = $pdf_tmp = $tipoDetectado = null;
$id_retencion = 0;

// 📥 Proceso POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero_factura'])) {
    list($numero, $tipoSeleccionado) = explode('|', $_POST['numero_factura']);
    $id_retencion = intval($_POST['retencion']);

    if (!isset($_FILES['archivo_pdf']) || $_FILES['archivo_pdf']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Error al subir el archivo.'); window.location.href='soportes.php';</script>";
        exit;
    }

    $ext = strtolower(pathinfo($_FILES['archivo_pdf']['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        echo "<script>alert('Solo se permiten archivos PDF.'); window.location.href='soportes.php';</script>";
        exit;
    }

    $pdf_tmp = $_FILES['archivo_pdf']['tmp_name'];
    $tipoDetectado = detectarTipoDocumento($pdf_tmp);

    // 🔒 Validación estricta
    if ($tipoDetectado && $tipoDetectado !== $tipoSeleccionado) {
        echo "<script>
            alert('❌ El clasificador detectó: $tipoDetectado, pero seleccionaste: $tipoSeleccionado. Corrige antes de continuar.');
            window.location.href = 'soportes.php';
        </script>";
        exit;
    }

    //Verifica duplicado
    $check = $pdo->prepare("SELECT COUNT(*) FROM soportes_factura WHERE numero_factura = ?");
    $check->execute([$numero]);
    if ($check->fetchColumn() > 0) {
        echo "<script>alert('Ya existe un soporte para este documento.'); window.location.href='soportes.php';</script>";
        exit;
    }

    // 📦 Datos del documento
    if ($tipoSeleccionado === 'factura') {
        $stmt = $pdo->prepare("SELECT f.concepto AS descripcion, s.razon_social AS sucursal, f.fecha_emision
                               FROM facturas f JOIN sucursal s ON f.cod_cia = s.cod_cia
                               WHERE f.numero_factura = ?");
    } else {
        $stmt = $pdo->prepare("SELECT g.concepto_gasto AS descripcion, s.razon_social AS sucursal, g.fecha_emision
                               FROM gastos g JOIN sucursal s ON g.cod_cia = s.cod_cia
                               WHERE g.codigo = ?");
    }

    $stmt->execute([$numero]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$info) {
        echo "<script>alert('Documento no encontrado.'); window.location.href='soportes.php';</script>";
        exit;
    }

    // 📁 Calcular índice de carpeta
    $total = $pdo->query("SELECT COUNT(*) FROM soportes_factura")->fetchColumn();
    $indiceNum = ceil(($total + 1) / 10);
    $indice = 'A' . $indiceNum;
    $destino = "/data/soportes/{$numero}.pdf";

    if (!move_uploaded_file($pdf_tmp, $destino)) {
        echo "<script>alert('Error al mover el archivo.'); window.location.href='soportes.php';</script>";
        exit;
    }

    // 🧾 Insertar en BD
    $insert = $pdo->prepare("INSERT INTO soportes_factura
        (numero_factura, descripcion, sucursal, fecha_emision, id_retencion, ruta_archivo, indice_archivo, tipo_documento, tipo_detectado)
        VALUES (:num, :desc, :suc, :fecha, :ret, :ruta, :indice, :tipo, :detectado)");
    $insert->execute([
        ':num' => $numero,
        ':desc' => $info['descripcion'],
        ':suc' => $info['sucursal'],
        ':fecha' => $info['fecha_emision'],
        ':ret' => $id_retencion,
        ':ruta' => $destino,
        ':indice' => $indice,
        ':tipo' => $tipoSeleccionado,
        ':detectado' => $tipoDetectado
    ]);

    // 📦 Verificar si completó la carpeta
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM soportes_factura WHERE indice_archivo = ?");
    $countStmt->execute([$indice]);
    $count = $countStmt->fetchColumn();

    if ($count == 10) {
        $rango = $pdo->prepare("SELECT MIN(fecha_emision) AS desde, MAX(fecha_emision) AS hasta
                                FROM soportes_factura WHERE indice_archivo = ?");
        $rango->execute([$indice]);
        $fechas = $rango->fetch(PDO::FETCH_ASSOC);
        $desde = urlencode($fechas['desde']);
        $hasta = urlencode($fechas['hasta']);

        echo "<script>
            if (confirm('📁 Se completó la carpeta $indice. ¿Deseas imprimir su etiqueta?')) {
                window.open('etiqueta.php?indice=$indice&desde=$desde&hasta=$hasta', '_blank');
            }
            window.location.href = 'soportes.php';
        </script>";
    } else {
        echo "<script>alert('✅ Soporte cargado con éxito.'); window.location.href='soportes.php';</script>";
    }

    exit;
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