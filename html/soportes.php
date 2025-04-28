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

// Clasificador ML
function detectarTipoDocumento($archivoPdfTmp)
{
    $cmd = escapeshellcmd("/opt/aftp-ml/env/bin/python3 /opt/aftp-ml/clasificador.py " . $archivoPdfTmp);
    $output = shell_exec($cmd);
    if (!$output) return null;

    if (strpos($output, "Clasificado como: FACTURA") !== false) return 'factura';
    if (strpos($output, "Clasificado como: GASTO") !== false) return 'gasto';
    return null;
}

// Variables
$numero = $tipoSeleccionado = $pdf_tmp = $tipoDetectado = null;
$id_retencion = 0;

// Proceso POST
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

    if ($tipoDetectado && $tipoDetectado !== $tipoSeleccionado) {
        echo "<script>
            alert('❌ El clasificador detectó: $tipoDetectado, pero seleccionaste: $tipoSeleccionado. Corrige antes de continuar.');
            window.location.href = 'soportes.php';
        </script>";
        exit;
    }

    $check = $pdo->prepare("SELECT COUNT(*) FROM soportes_factura WHERE numero_factura = ?");
    $check->execute([$numero]);
    if ($check->fetchColumn() > 0) {
        echo "<script>alert('Ya existe un soporte para este documento.'); window.location.href='soportes.php';</script>";
        exit;
    }

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

    $total = $pdo->query("SELECT COUNT(*) FROM soportes_factura")->fetchColumn();
    $indiceNum = ceil(($total + 1) / 10);
    $indice = 'A' . $indiceNum;
    $destino = "/data/soportes/{$numero}.pdf";

    if (!move_uploaded_file($pdf_tmp, $destino)) {
        echo "<script>alert('Error al mover el archivo.'); window.location.href='soportes.php';</script>";
        exit;
    }

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
  <link rel="stylesheet" href="css/style.css"> <!-- Usamos el mismo estilo -->
</head>
<style>
/* Ocultar el input file original */
input[type="file"] {
  display: none;
}

/* Estilo para el label que actúa como botón */
.label-file {
  display: inline-block;
  width: 100%;
  padding: 12px;
  background: linear-gradient(135deg, #78D1F9, #2090CD);
  color: white;
  font-weight: bold;
  text-align: center;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.3s ease;
  font-size: 16px;
}

.label-file:hover {
  background: #2090CD;
}
</style>
<body>

<div class="container">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Cargar Soporte</h2>
  </div>

  <div style="margin-top: 20px; text-align: left;">
    <a href="index.php" class="btn">← Menú Principal</a>
  </div>

  <form class="login-form" method="post" enctype="multipart/form-data" style="margin-top: 20px;">
    <h3 style="text-align: center;">Formulario de Soportes</h3>

    <div class="form-group">
      <label for="numero_factura">Número de Documento (Factura o Gasto)</label>
      <select name="numero_factura" required>
        <option value="">-- Seleccione --</option>
        <?php
        $stmt = $pdo->query("
            SELECT f.numero_factura FROM facturas f
            WHERE NOT EXISTS (
                SELECT 1 FROM soportes_factura sf
                WHERE CONVERT(sf.numero_factura USING utf8mb3) COLLATE utf8mb3_general_ci =
                      CONVERT(f.numero_factura USING utf8mb3) COLLATE utf8mb3_general_ci
                      AND sf.tipo_documento = 'factura'
            )
            ORDER BY f.fecha_creacion DESC
        ");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $val = $row['numero_factura'] . '|factura';
            echo "<option value='$val'>" . htmlspecialchars($row['numero_factura']) . " (Factura)</option>";
        }

        $stmt2 = $pdo->query("
            SELECT g.codigo FROM gastos g
            WHERE NOT EXISTS (
                SELECT 1 FROM soportes_factura sf
                WHERE CONVERT(sf.numero_factura USING utf8mb3) COLLATE utf8mb3_general_ci =
                      CONVERT(g.codigo USING utf8mb3) COLLATE utf8mb3_general_ci
                      AND sf.tipo_documento = 'gasto'
            )
            ORDER BY g.fecha_emision DESC
        ");
        foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $val = $row['codigo'] . '|gasto';
            echo "<option value='$val'>" . htmlspecialchars($row['codigo']) . " (Gasto)</option>";
        }
        ?>
      </select>
    </div>

    <div class="form-group">
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
    </div>

    <div class="form-group">
        <label for="archivo_pdf">Archivo PDF</label><br>
        
        <label for="archivo_pdf" class="label-file">Seleccionar Archivo PDF</label>
        <input type="file" name="archivo_pdf" id="archivo_pdf" accept=".pdf" required>

        <!-- Aquí aparecerá el nombre del archivo -->
        <small id="nombre-archivo" style="display:block; margin-top:8px; color:#555;"></small>
    </div>


    <button type="submit" class="btn">Cargar Soporte</button>

  </form>

</div>
    <script>
        document.getElementById('archivo_pdf').addEventListener('change', function() {
            const nombreArchivo = this.files.length > 0 ? this.files[0].name : 'Ningún archivo seleccionado';
            document.getElementById('nombre-archivo').textContent = nombreArchivo;
        });
    </script>
</body>
</html>