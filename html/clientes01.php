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
  <title>Gestión de Clientes - Sistema AFTP</title>

  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

  <!-- Título -->
  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 70px;">
    <h2>Gestión de Clientes</h2>
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
      <i data-feather="plus-circle"></i> Nuevo Cliente
    </button>
    <button class="btn" onclick="openTab('listado')">
      <i data-feather="list"></i> Listado
    </button>
  </div>

  <!-- Formulario Nuevo Cliente -->
  <div id="crear" class="content active" style="margin-top: 20px;">
    <form class="login-form" method="post" action="cliente01valida.php">
      <h3 style="text-align: center;">Registrar Cliente</h3>

      <div class="form-group">
        <label for="nit">NIT</label>
        <input type="text" name="nit" id="nit" maxlength="15" required>
      </div>

      <div class="form-group">
        <label for="razon_social">Razón Social</label>
        <input type="text" name="razon_social" id="razon_social" maxlength="100" required>
      </div>

      <div class="form-group">
        <label for="direccion">Dirección</label>
        <textarea name="direccion" id="direccion" rows="2" required></textarea>
      </div>

      <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" maxlength="15" required>
      </div>

      <div class="form-group">
        <label for="numero_contacto">Contacto</label>
        <input type="text" name="numero_contacto" id="numero_contacto" maxlength="30" required>
      </div>

      <button type="submit" class="btn">Registrar Cliente</button>
    </form>
  </div>

  <!-- Listado de Clientes -->
  <div id="listado" class="content" style="margin-top: 20px;">
    <h3 style="text-align: center;">Listado de Clientes</h3>

    <div style="overflow-x: auto; margin-top: 20px;">
      <?php
      try {
          $stmt = $pdo->query("SELECT cod_cliente, nit, razon_social, telefono, status_cliente, fecha_creacion FROM clientes ORDER BY fecha_creacion DESC");

          if ($stmt->rowCount() > 0) {
              echo "<table>";
              echo "<thead><tr><th>Código</th><th>NIT</th><th>Razón Social</th><th>Teléfono</th><th>Estado</th><th>Fecha Creación</th></tr></thead><tbody>";

              while ($reg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($reg['cod_cliente']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['nit']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['razon_social']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['telefono']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['status_cliente']) . "</td>";
                  echo "<td>" . htmlspecialchars($reg['fecha_creacion']) . "</td>";
                  echo "</tr>";
              }

              echo "</tbody></table>";
          } else {
              echo "<p>No hay clientes registrados.</p>";
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

<!-- Script para pestañas -->
<script>
function openTab(tabId) {
    document.querySelectorAll('.content').forEach(tab => {
        tab.classList.remove('active');
    });
    document.getElementById(tabId).classList.add('active');
}
</script>

</body>
</html>