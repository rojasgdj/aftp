<?php
session_start();
session_regenerate_id(true);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Sucursales - Sistema AFTP</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

  <!-- Título -->
  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Gestión de Sucursales</h2>
  </div>

  <!-- Menú -->
  <div style="margin-top: 20px; text-align: left;">
    <a href="index.php" class="btn">
      <i data-feather="arrow-left"></i> Menú Inicio
    </a>
  </div>

  <!-- Botones de Tabs -->
  <div class="tab-container" style="margin: 20px 0;">
    <button class="btn" onclick="openTab('crear')">
      <i data-feather="plus-circle"></i> Nueva Sucursal
    </button>
    <button class="btn" onclick="openTab('listado')">
      <i data-feather="file-text"></i> Listado
    </button>
  </div>

  <!-- Formulario Nueva Sucursal -->
  <div id="crear" class="content active" style="margin-top: 20px;">
    <form class="login-form" method="post" action="cia01valida.php">
      <h3 style="text-align: center;">Registrar Sucursal</h3>

      <div class="form-group">
        <label for="cod_sucursal">Código Sucursal</label>
        <input type="text" name="cod_sucursal" id="cod_sucursal" maxlength="6" required>
      </div>

      <div class="form-group">
        <label for="razon_social">Razón Social</label>
        <input type="text" name="razon_social" id="razon_social" maxlength="100" required>
      </div>

      <div class="form-group">
        <label for="nit">NIT</label>
        <input type="text" name="nit" id="nit" maxlength="20" required>
      </div>

      <div class="form-group">
        <label for="direccion_proveedor">Dirección</label>
        <textarea name="direccion_proveedor" id="direccion_proveedor" rows="2" required></textarea>
      </div>

      <button type="submit" class="btn">Registrar Sucursal</button>
    </form>
  </div>

  <!-- Listado de Sucursales -->
  <div id="listado" class="content" style="margin-top: 20px;">
    <h3 style="text-align: center;">Listado de Sucursales</h3>

    <div style="overflow-x: auto; margin-top: 20px;">
      <?php
      try {
          $stmt = $pdo->query("SELECT cod_sucursal, razon_social, nit FROM sucursal ORDER BY fecha_ingreso DESC");

          if ($stmt->rowCount() > 0) {
              echo "<table>";
              echo "<thead><tr><th>Código</th><th>Razón Social</th><th>NIT</th></tr></thead><tbody>";

              while ($reg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($reg['cod_sucursal']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['razon_social']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['nit']) . "</td>";
                  echo "</tr>";
              }

              echo "</tbody></table>";
          } else {
              echo "<p>No hay sucursales registradas.</p>";
          }
      } catch (PDOException $e) {
          echo "<p>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</p>";
      }
      ?>
    </div>
  </div>

</div>

<!-- Feather Icons -->
<script src="js/feather.min.js"></script>
<script>feather.replace();</script>

<!-- Script Tabs -->
<script>
function openTab(tabId) {
    document.querySelectorAll('.content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById(tabId).classList.add('active');

    if (tabId === 'listado') {
        setTimeout(() => window.scrollTo(0, document.getElementById('listado').offsetTop - 20), 200);
    }
}

window.onload = function() {
    openTab('crear');
};
</script>

</body>
</html>