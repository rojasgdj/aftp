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

require_once "db.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reporte de Facturas Recibidas</title>

  <link rel="stylesheet" href="css/style.css">
  <script src="js/feather.min.js"></script> <!-- Feather Icons -->
</head>

<body>

<div class="container">

  <!-- Título -->
  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 60px;">
    <h2>Reporte de Facturas Recibidas</h2>
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
        $stmt = $pdo->prepare("
            SELECT f.numero_factura, f.concepto, f.fecha_emision, f.valor_factura,
                   p.razon_social AS proveedor, s.razon_social AS sucursal
            FROM facturas f
            INNER JOIN proveedores p ON f.cod_proveedor = p.cod_proveedor
            INNER JOIN sucursal s ON f.cod_cia = s.cod_cia
            ORDER BY f.fecha_creacion DESC
        ");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<thead><tr>
                    <th>N° Factura</th>
                    <th>Proveedor</th>
                    <th>Concepto</th>
                    <th>Fecha Emisión</th>
                    <th>Monto $.</th>
                    <th>Sucursal</th>
                  </tr></thead><tbody>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['numero_factura']) . "</td>";
                echo "<td>" . htmlspecialchars($row['proveedor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['concepto']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_emision']) . "</td>";
                echo "<td style='text-align: right;'>" . number_format($row['valor_factura'], 2, ',', '.') . "</td>";
                echo "<td>" . htmlspecialchars($row['sucursal']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No se encontraron facturas registradas.</p>";
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