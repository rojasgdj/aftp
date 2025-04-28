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
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Facturas - Sistema AFTP</title>

  <link rel="stylesheet" href="css/style.css"> <!-- Usando tu style.css -->
</head>

<body>

<div class="container">

  <!-- Título -->
  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Gestión de Facturas</h2>
  </div>

  <!-- Menú -->
  <div style="margin-top: 20px; text-align: left;">
    <a href="index.php" class="btn">
      <i data-feather="arrow-left"></i> Menú Inicio
    </a>
  </div>

  <!-- Botones Tabs -->
  <div class="tab-container" style="margin: 20px 0;">
    <button class="btn" onclick="openTab('crear')">
      <i data-feather="file-plus"></i> Nueva Factura
    </button>
    <a href="buscarfactura.php?origen=factura01.php" class="btn">
      <i data-feather="search"></i> Buscar Factura
   </a>
    <button class="btn" onclick="openTab('listado')">
      <i data-feather="list"></i> Listado
    </button>
  </div>

  <!-- Formulario Nueva Factura -->
  <div id="crear" class="content active" style="margin-top: 20px;">
    <form class="login-form" method="post" action="factura01valida.php">
      <h3 style="text-align: center;">Registrar Factura</h3>

      <div class="form-group">
        <label for="factura">Número de Factura</label>
        <input type="text" name="factura" id="factura" required>
      </div>

      <div class="form-group">
        <label for="concepto">Concepto</label>
        <textarea name="concepto" id="concepto" rows="2" required></textarea>
      </div>

      <div class="form-group">
        <label for="fecha">Fecha de Emisión</label>
        <input type="date" name="fecha" id="fecha" required>
      </div>

      <div class="form-group">
        <label for="proveedor">Proveedor</label>
        <select name="proveedor" id="proveedor" required>
          <option value="">Seleccione un proveedor</option>
          <?php
          try {
              $stmt = $pdo->query("SELECT cod_proveedor, razon_social FROM proveedores ORDER BY razon_social");
              while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option value='" . htmlspecialchars($prov['cod_proveedor']) . "'>" . htmlspecialchars($prov['razon_social']) . "</option>";
              }
          } catch (PDOException $e) {
              echo "<option value=''>Error al cargar proveedores</option>";
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="monto">Monto Bs.</label>
        <input type="number" name="monto" id="monto" step="0.01" min="0" required>
      </div>

      <div class="form-group">
        <label for="cod_cia">Sucursal</label>
        <select name="cod_cia" id="cod_cia" required>
          <option value="">Seleccione una sucursal</option>
          <?php
          try {
              $stmt = $pdo->query("SELECT cod_cia, razon_social FROM sucursal ORDER BY razon_social");
              while ($suc = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option value='" . htmlspecialchars($suc['cod_cia']) . "'>" . htmlspecialchars($suc['razon_social']) . "</option>";
              }
          } catch (PDOException $e) {
              echo "<option value=''>Error al cargar sucursales</option>";
          }
          ?>
        </select>
      </div>

      <button type="submit" class="btn">Registrar Factura</button>
    </form>
  </div>

  <!-- Listado de Facturas -->
  <div id="listado" class="content" style="margin-top: 20px;">
    <h3 style="text-align: center;">Últimas Facturas Registradas</h3>

    <div style="overflow-x: auto; margin-top: 20px;">
      <?php
      try {
          $stmt = $pdo->prepare("
              SELECT f.numero_factura, f.concepto, f.fecha_emision, f.fecha_creacion,
                     f.valor_factura, p.razon_social AS proveedor, s.razon_social AS sucursal
              FROM facturas f
              INNER JOIN proveedores p ON f.cod_proveedor = p.cod_proveedor
              INNER JOIN sucursal s ON f.cod_cia = s.cod_cia
              ORDER BY f.fecha_creacion DESC LIMIT 10
          ");
          $stmt->execute();
          $facturas = $stmt->fetchAll();

          if ($facturas) {
              echo "<table>";
              echo "<thead><tr>
                      <th>Número</th><th>Proveedor</th><th>Sucursal</th><th>Fecha</th><th>Descripción</th>
                    </tr></thead><tbody>";
              foreach ($facturas as $f) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($f['numero_factura']) . "</td>";
                  echo "<td style='max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;'>" . htmlspecialchars($f['proveedor']) . "</td>";
                  echo "<td>" . htmlspecialchars($f['sucursal']) . "</td>";
                  echo "<td>" . htmlspecialchars($f['fecha_emision']) . "</td>";
                  echo "<td style='max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;'>" . htmlspecialchars($f['concepto']) . "</td>";
                  echo "</tr>";
              }
              echo "</tbody></table>";
          } else {
              echo "<p>No hay facturas registradas.</p>";
          }
      } catch (PDOException $e) {
          echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
      }
      ?>
    </div>
  </div>

</div>

<!-- Feather Icons -->
<script src="js/feather.min.js"></script>
<script>feather.replace();</script>

<!-- Script pestañas -->
<script>
function openTab(tabId) {
    document.querySelectorAll('.content').forEach(tab => {
        tab.classList.remove('active');
        tab.style.display = 'none';
    });
    document.getElementById(tabId).classList.add('active');
    document.getElementById(tabId).style.display = 'block';
}
window.onload = function() {
    openTab('crear');
};
</script>

</body>
</html>