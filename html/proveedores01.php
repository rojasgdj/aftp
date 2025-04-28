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
  <title>Gestión de Proveedores - Sistema AFTP</title>

  <link rel="stylesheet" href="css/style.css"> <!-- Usando tu style.css ya mejorado -->
</head>

<body>

<div class="container">

  <!-- Título -->
  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Gestión de Proveedores</h2>
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
      <i data-feather="plus-circle"></i> Nuevo Proveedor
    </button>
    <button class="btn" onclick="openTab('listado')">
      <i data-feather="list"></i> Listado
    </button>
  </div>

  <!-- Formulario Nuevo Proveedor -->
  <div id="crear" class="content active" style="margin-top: 20px;">
    <form class="login-form" method="post" action="proveedores01valida.php">
      <h3 style="text-align: center;">Registrar Proveedor</h3>

      <div class="form-group">
        <label for="nit">NIT</label>
        <input type="text" name="nit" id="nit" maxlength="20" required>
      </div>

      <div class="form-group">
        <label for="razon_social">Razón Social</label>
        <input type="text" name="razon_social" id="razon_social" maxlength="100" required>
      </div>

      <div class="form-group">
        <label for="direccion_fiscal">Dirección Fiscal</label>
        <textarea name="direccion_fiscal" id="direccion_fiscal" rows="2" required></textarea>
      </div>

      <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" maxlength="20" required>
      </div>

      <div class="form-group">
        <label for="contacto">Persona de Contacto</label>
        <input type="text" name="contacto" id="contacto" maxlength="50" required>
      </div>

      <button type="submit" class="btn">Registrar Proveedor</button>
    </form>
  </div>

  <!-- Listado de Proveedores -->
  <div id="listado" class="content" style="display: none; margin-top: 20px;">
    <h3 style="text-align: center;">Listado de Proveedores</h3>

    <div style="overflow-x: auto; margin-top: 20px;">
      <?php
      try {
          $stmt = $pdo->query("SELECT cod_proveedor, nit, razon_social, telefono, contacto, status, fecha_creacion FROM proveedores ORDER BY fecha_creacion DESC");

          if ($stmt->rowCount() > 0) {
              echo "<table>";
              echo "<thead><tr><th>Código</th><th>NIT</th><th>Razón Social</th><th>Teléfono</th><th>Contacto</th><th>Estado</th><th>Fecha Creación</th></tr></thead><tbody>";

              while ($reg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($reg['cod_proveedor']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['nit']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['razon_social']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['telefono']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['contacto']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['status']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['fecha_creacion']) . "</td>";
                  echo "</tr>";
              }

              echo "</tbody></table>";
          } else {
              echo "<p>No hay proveedores registrados.</p>";
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

<!-- Script pestañas -->
<script>
function openTab(tabId) {
    document.querySelectorAll('.content').forEach(tab => {
        tab.style.display = 'none';
        tab.classList.remove('active');
    });
    document.getElementById(tabId).style.display = 'block';
    document.getElementById(tabId).classList.add('active');
}
window.onload = function() {
    openTab('crear'); // Mostrar el formulario al cargar
};
</script>

</body>
</html>