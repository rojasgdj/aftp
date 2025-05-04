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
  <link rel="stylesheet" href="css/style.css">
  <style>
    .grid-carpetas {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 30px;
      margin-top: 30px;
    }
    .carpeta-box {
      background: #ffffff;
      border-radius: 12px;
      text-align: center;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      transition: 0.3s;
    }
    .carpeta-box:hover {
      transform: translateY(-5px);
    }
    .carpeta-box img {
      width: 70px;
      cursor: pointer;
    }
    .indice-texto {
      font-weight: bold;
      margin-top: 10px;
    }
    .btn-etiqueta {
      background: #2090CD;
      border: none;
      padding: 8px 15px;
      border-radius: 8px;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      font-size: 14px;
      margin-top: 10px;
    }
    .btn-etiqueta:hover {
      background: #007bff;
    }
    .modal, .modal-envio, .modal-visor {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }
    .modal-content, .modal-envio-content {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      position: relative;
      max-height: 90%;
      overflow-y: auto;
    }
    .modal-content {
      width: 90%;
      max-width: 900px;
    }
    .modal-envio-content {
      width: 400px;
      text-align: center;
    }
    .close {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #f00;
    }
    table {
      width: 100%;
      margin-top: 15px;
      border-collapse: collapse;
      font-size: 14px;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ddd;
    }
    th {
      background: linear-gradient(150deg, #78D1F9, #2090CD);
      color: #fff;
    }
    .btn-mini {
      background: #2090CD;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      color: white;
      font-size: 12px;
      font-weight: bold;
      cursor: pointer;
      margin: 2px;
    }
    .btn-mini:hover {
      background: #007bff;
    }
    .modal-envio-content input, .modal-envio-content textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>

<div class="container">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Índices de Soportes</h2>
  </div>

  <div style="margin-top: 20px; text-align: left;">
    <a href="index.php" class="btn">← Volver al Menú</a>
  </div>

  <div class="grid-carpetas">
    <?php
    $stmt = $pdo->query("SELECT DISTINCT indice_archivo FROM soportes_factura ORDER BY indice_archivo");
    $indices = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($indices as $indice):
      $contenidoId = "contenido_" . htmlspecialchars($indice);
      $rango = $pdo->prepare("SELECT COUNT(*) AS total, MIN(fecha_emision) AS desde, MAX(fecha_emision) AS hasta FROM soportes_factura WHERE indice_archivo = ?");
      $rango->execute([$indice]);
      $datos = $rango->fetch();

      $desde = urlencode($datos['desde']);
      $hasta = urlencode($datos['hasta']);
    ?>
      <div class="carpeta-box">
        <img src="img/carpeta.png" alt="Carpeta" onclick="mostrarModal('<?= $contenidoId ?>')">
        <div class="indice-texto"><?= htmlspecialchars($indice) ?></div>
        <?php if ($datos['total'] >= 10): ?>
            <button class="btn-etiqueta" onclick="imprimirEtiqueta('<?= htmlspecialchars($indice) ?>', '<?= $desde ?>', '<?= $hasta ?>')">Etiqueta</button>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php foreach ($indices as $indice):
    $contenidoId = "contenido_" . htmlspecialchars($indice);
  ?>
    <div id="<?= $contenidoId ?>" class="modal">
      <div class="modal-content">
        <span class="close" onclick="cerrarModal('<?= $contenidoId ?>')">&times;</span>
        <h3>📂 Carpeta: <?= htmlspecialchars($indice) ?></h3>
        <table>
          <thead>
            <tr>
              <th>Número</th>
              <th>Descripción</th>
              <th>Sucursal</th>
              <th>Fecha Emisión</th>
              <th>Fecha Destrucción</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt2 = $pdo->prepare("
              SELECT sf.numero_factura, sf.descripcion, sf.sucursal, sf.fecha_emision,
                     pr.anios_retencion, sf.ruta_archivo
              FROM soportes_factura sf
              INNER JOIN politicas_retencion pr ON sf.id_retencion = pr.id_retencion
              WHERE sf.indice_archivo = ?
              ORDER BY sf.fecha_emision DESC
            ");
            $stmt2->execute([$indice]);
            foreach ($stmt2->fetchAll() as $s):
              $fechaEmision = new DateTime($s['fecha_emision']);
              $fechaDestruccion = (clone $fechaEmision)->modify('+' . $s['anios_retencion'] . ' years');
              $archivo = basename($s['ruta_archivo']);
            ?>
              <tr>
                <td><?= htmlspecialchars($s['numero_factura']) ?></td>
                <td><?= htmlspecialchars($s['descripcion']) ?></td>
                <td><?= htmlspecialchars($s['sucursal']) ?></td>
                <td><?= $fechaEmision->format('Y-m-d') ?></td>
                <td><?= $fechaDestruccion->format('Y-m-d') ?></td>
                <td>
                  <button class="btn-mini" onclick="verSoporte('<?= htmlspecialchars($archivo) ?>')">📄 Ver</button>
                  <button class="btn-mini" onclick="abrirModalEnvio('<?= htmlspecialchars($s['numero_factura']) ?>')">✉️ Enviar</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="modal-envio" id="modalEnvio">
    <div class="modal-envio-content">
      <span class="close" onclick="cerrarModalEnvio()">&times;</span>
      <h3>📧 Enviar Soporte</h3>
      <form method="post" action="enviar_soporte.php">
        <input type="hidden" name="numero_factura" id="numero_factura_envio">
        <input type="email" name="correo_destino" placeholder="Correo destinatario" required>
        <textarea name="mensaje" placeholder="Mensaje adicional (opcional)" rows="4"></textarea>
        <button type="submit" class="btn">Enviar</button>
      </form>
    </div>
  </div>

  <!-- Modal Visor PDF -->
  <div id="modalVisor" class="modal" onclick="cerrarModalVisor(event)">
    <div class="modal-content" style="width: 80%; max-width: 800px; height: 90vh; position: relative;">
      <span class="close" onclick="cerrarModalVisor()">&times;</span>
      <h3 style="text-align: center;">📄 Vista de Soporte</h3>
      <iframe id="visorPDF" src="" frameborder="0" style="width: 100%; height: 80%; margin-top: 10px; border-radius: 8px;"></iframe>
      <div style="text-align: center; margin-top: 10px;">
        <a id="btnDescargar" href="#" download class="btn">⬇️ Descargar PDF</a>
      </div>
    </div>
  </div>

</div>

<script>
function mostrarModal(id) {
  document.getElementById(id).style.display = 'flex';
}
function cerrarModal(id) {
  document.getElementById(id).style.display = 'none';
}
function abrirModalEnvio(numero) {
  document.getElementById('numero_factura_envio').value = numero;
  document.getElementById('modalEnvio').style.display = 'flex';
}
function cerrarModalEnvio() {
  document.getElementById('modalEnvio').style.display = 'none';
}
function imprimirEtiqueta(indice, desde, hasta) {
  const url = `etiqueta.php?indice=${indice}&desde=${desde}&hasta=${hasta}`;
  window.open(url, '_blank');
}

function verSoporte(archivo) {
  const visor = document.getElementById('visorPDF');
  const botonDescargar = document.getElementById('btnDescargar');

  visor.src = 'soportes/' + archivo; // Aquí corregimos
  botonDescargar.href = 'soportes/' + archivo; // Aquí corregimos

  document.getElementById('modalVisor').style.display = 'flex';
}

function cerrarModalVisor(event = null) {
  const modal = document.getElementById('modalVisor');
  if (event) {
    const contenido = document.querySelector('#modalVisor .modal-content');
    if (!contenido.contains(event.target)) {
      modal.style.display = 'none';
      document.getElementById('visorPDF').src = '';
    }
  } else {
    modal.style.display = 'none';
    document.getElementById('visorPDF').src = '';
  }
}
</script>

</body>
</html>