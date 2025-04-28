<?php
session_start();
session_regenerate_id(true);

// Seguridad y control de caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require_once "db.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Proveedores</title>

    <link rel="stylesheet" href="css/style.css">
    <script src="js/feather.min.js"></script> <!-- Feather Icons -->
</head>
<body>

<div class="container">

  <!-- Título -->
  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 60px;">
    <h2>Reporte de Proveedores</h2>
  </div>

  <!-- Botones -->
  <div style="margin-top: 20px; display: flex; justify-content: space-between; flex-wrap: wrap;">
    <a href="index.php" class="btn">
      <i data-feather="arrow-left"></i> Volver al Menú
    </a>
    <button class="btn" onclick="window.print()">
      <i data-feather="printer"></i> Imprimir
    </button>
  </div>

  <!-- Listado -->
  <div style="overflow-x: auto; margin-top: 20px;">
    <?php
    try {
        $stmt = $pdo->query("
            SELECT cod_proveedor, nit, razon_social, telefono, contacto 
            FROM proveedores 
            ORDER BY fecha_creacion DESC
        ");

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<thead><tr>
                    <th>Código</th>
                    <th>RIF / NIT</th>
                    <th>Razón Social</th>
                    <th>Teléfono</th>
                    <th>Contacto</th>
                  </tr></thead><tbody>";

            while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($prov['cod_proveedor']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['nit']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['razon_social']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['telefono']) . "</td>";
                echo "<td>" . htmlspecialchars($prov['contacto']) . "</td>";
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

  <!-- Fecha reporte -->
  <p style="margin-top: 20px; font-size: 13px; color: #666;">
    Fecha del reporte: <?= date("d-m-Y H:i:s") ?>
  </p>

</div>

<script>
  feather.replace(); // Activar Feather Icons
</script>

</body>
</html>