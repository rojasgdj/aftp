<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Gastos - Sistema AFTP</title>

  <link rel="stylesheet" href="css/style.css"> <!-- Tu style.css principal -->
  <script src="js/feather.min.js"></script> <!-- Feather Icons -->
</head>

<body>

<div class="container">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Gestión de Gastos</h2>
  </div>

  <div style="margin-top: 20px; text-align: left;">
    <a href="index.php" class="btn">
      <i data-feather="arrow-left"></i> Menú Inicio
    </a>
  </div>

  <div class="tab-container">
    <button class="btn" onclick="openTab('crear')">
      <i data-feather="plus-circle"></i> Nuevo Gasto
    </button>
    <button class="btn" onclick="openTab('listado')">
      <i data-feather="file-text"></i> Listado
    </button>
  </div>

  <!-- Formulario de Nuevo Gasto -->
  <div id="crear" class="content active" style="margin-top: 20px;">
    <form class="login-form" method="post" action="gastos01valida.php">
      <h3 style="text-align: center;">Registrar Gasto</h3>

      <div class="form-group">
        <label for="codigo">Código</label>
        <input type="text" name="codigo" id="codigo" required>
      </div>

      <div class="form-group">
        <label for="factura">Número de Factura</label>
        <input type="text" name="factura" id="factura" required>
      </div>

      <div class="form-group">
        <label for="concepto_gasto">Concepto</label>
        <textarea name="concepto_gasto" id="concepto_gasto" rows="3" required></textarea>
      </div>

      <div class="form-group">
        <label for="fecha_emision">Fecha de Gasto</label>
        <input type="date" name="fecha_emision" id="fecha_emision" required>
      </div>

      <div class="form-group">
        <label for="cod_proveedor">Proveedor</label>
        <select name="cod_proveedor" id="cod_proveedor" required>
          <option value="">Seleccione un proveedor</option>
          <?php
          $stmt = $pdo->query("SELECT cod_proveedor, razon_social FROM proveedores ORDER BY razon_social ASC");
          while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='" . htmlspecialchars($prov['cod_proveedor']) . "'>" . htmlspecialchars($prov['razon_social']) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="cod_cia">Sucursal</label>
        <select name="cod_cia" id="cod_cia" required>
          <option value="">Seleccione una sucursal</option>
          <?php
          $stmt = $pdo->query("SELECT cod_cia, razon_social FROM sucursal ORDER BY razon_social ASC");
          while ($suc = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='" . htmlspecialchars($suc['cod_cia']) . "'>" . htmlspecialchars($suc['razon_social']) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="valor_gasto">Monto Bs.</label>
        <input type="number" name="valor_gasto" id="valor_gasto" step="0.01" min="0" required>
      </div>

      <button type="submit" class="btn">Registrar Gasto</button>
    </form>
  </div>

  <!-- Listado de Gastos -->
  <div id="listado" class="content" style="margin-top: 20px;">
    <h3 style="text-align: center;">Listado de Gastos</h3>

    <div style="margin-top: 20px; text-align: right;">
      <button class="btn" onclick="window.print()">
        <i data-feather="printer"></i> Imprimir
      </button>
    </div>

    <div style="overflow-x: auto; margin-top: 20px;">
      <table>
        <thead>
          <tr>
            <th>Código</th>
            <th>Proveedor</th>
            <th>Factura</th>
            <th>Sucursal</th>
            <th>Fecha</th>
            <th>Monto</th>
          </tr>
        </thead>
        <tbody>
          <?php
          try {
              $stmt = $pdo->query("
                  SELECT g.codigo, g.factura, g.fecha_emision, g.valor_gasto,
                         p.razon_social, s.razon_social AS sucursal 
                  FROM gastos g 
                  INNER JOIN proveedores p ON g.cod_proveedor = p.cod_proveedor 
                  INNER JOIN sucursal s ON g.cod_cia = s.cod_cia
                  ORDER BY g.fecha_creacion DESC LIMIT 10
              ");

              while ($gasto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>
                          <td>" . htmlspecialchars($gasto['codigo']) . "</td>
                          <td>" . htmlspecialchars($gasto['razon_social']) . "</td>
                          <td>" . htmlspecialchars($gasto['factura']) . "</td>
                          <td>" . htmlspecialchars($gasto['sucursal']) . "</td>
                          <td>" . htmlspecialchars($gasto['fecha_emision']) . "</td>
                          <td style='text-align: right;'>" . number_format($gasto['valor_gasto'], 2, ',', '.') . "</td>
                        </tr>";
              }
          } catch (PDOException $e) {
              echo "<tr><td colspan='6'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
function openTab(tabId) {
    document.querySelectorAll(".content").forEach(el => {
        el.classList.remove("active");
    });
    document.getElementById(tabId).classList.add("active");
}

window.onload = function() {
    feather.replace(); // Activar Feather Icons al cargar
    openTab('crear');
};
</script>

</body>
</html>