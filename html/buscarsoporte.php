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
  <title>Buscar Soporte</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Buscar Soporte</h2>
  </div>

  <div style="margin-top: 20px; text-align: left;">
    <a href="index.php" class="btn">← Menú Principal</a>
  </div>

  <form class="login-form" method="post" style="margin-top: 20px;">
    <h3 style="text-align: center;">Buscar por Número de Factura</h3>

    <div class="form-group">
      <input type="text" name="numero_factura" placeholder="Ingrese número de factura" required style="text-align:center;">
    </div>

    <button type="submit" class="btn">Buscar</button>
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
          echo "<div style='overflow-x:auto; margin-top:30px;'>
                  <table style='width:100%; border-collapse: collapse;'>
                    <tbody>
                      <tr><th style='text-align:left;'>Número de Factura</th><td>" . htmlspecialchars($soporte['numero_factura']) . "</td></tr>
                      <tr><th style='text-align:left;'>Descripción</th><td>" . htmlspecialchars($soporte['descripcion']) . "</td></tr>
                      <tr><th style='text-align:left;'>Sucursal</th><td>" . htmlspecialchars($soporte['sucursal']) . "</td></tr>
                      <tr><th style='text-align:left;'>Fecha Emisión</th><td>" . htmlspecialchars($soporte['fecha_emision']) . "</td></tr>
                      <tr><th style='text-align:left;'>Política de Retención</th><td>" . htmlspecialchars($soporte['nombre_politica']) . "</td></tr>
                      <tr><th style='text-align:left;'>Índice Asignado</th><td>" . htmlspecialchars($soporte['indice_archivo']) . "</td></tr>
                      <tr><th style='text-align:left;'>Archivo PDF</th>
                          <td><a class='btn' href='descargar.php?file=" . urlencode(basename($soporte['ruta_archivo'])) . "' target='_blank'>⬇ Descargar</a></td>
                      </tr>
                    </tbody>
                  </table>
                </div>";
      } else {
          echo "<p style='margin-top:20px; color:red; font-weight:bold;'>❌ No se encontró soporte para la factura ingresada.</p>";
      }
  }
  ?>

</div>

</body>
</html>