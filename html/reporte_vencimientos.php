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
  <title>Soportes Próximos a Vencer</title>

  <link rel="stylesheet" href="css/style.css">
  <script src="js/feather.min.js"></script> <!-- Feather Icons -->
</head>

<body>

<div class="container">

  <!-- Título -->
  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 60px;">
    <h2>Soportes Próximos a Vencer</h2>
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
            SELECT 
                sf.numero_factura,
                sf.descripcion,
                sf.sucursal,
                sf.fecha_emision,
                DATE_ADD(sf.fecha_emision, INTERVAL pr.anios_retencion YEAR) AS fecha_destruccion
            FROM soportes_factura sf
            INNER JOIN politicas_retencion pr ON sf.id_retencion = pr.id_retencion
            WHERE 
                DATE_ADD(sf.fecha_emision, INTERVAL pr.anios_retencion YEAR) <= DATE_ADD(NOW(), INTERVAL 1825 DAY)
            ORDER BY fecha_destruccion ASC
        ");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<thead><tr>
                    <th>N° Soporte</th>
                    <th>Descripción</th>
                    <th>Sucursal</th>
                    <th>Fecha Emisión</th>
                    <th>Fecha Destrucción</th>
                    <th>Días Restantes</th>
                  </tr></thead><tbody>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $fechaDestruccion = new DateTime($row['fecha_destruccion']);
                $hoy = new DateTime();
                $diasRestantes = $hoy->diff($fechaDestruccion)->days;
                $color = "";

                if ($fechaDestruccion < $hoy) {
                    $color = "style='background-color: #ffcccc;'"; // Rojo
                    $diasRestantes = "VENCIDO";
                } elseif ($diasRestantes <= 90) {
                    $color = "style='background-color: #fff3cd;'"; // Amarillo
                }

                echo "<tr $color>";
                echo "<td>" . htmlspecialchars($row['numero_factura']) . "</td>";
                echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                echo "<td>" . htmlspecialchars($row['sucursal']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_emision']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha_destruccion']) . "</td>";
                echo "<td>" . htmlspecialchars($diasRestantes) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>No hay soportes próximos a vencer en los próximos 90 días.</p>";
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
  feather.replace(); // Cargar íconos
</script>

</body>
</html>