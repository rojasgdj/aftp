<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db.php';

$total     = $pdo->query("SELECT COUNT(*) FROM soportes_factura")->fetchColumn();
$coinciden = $pdo->query("SELECT COUNT(*) FROM soportes_factura WHERE tipo_documento = tipo_detectado")->fetchColumn();
$discrepan = $pdo->query("SELECT COUNT(*) FROM soportes_factura WHERE tipo_detectado IS NOT NULL AND tipo_detectado != tipo_documento")->fetchColumn();

$por_proveedor = $pdo->query("
    SELECT LEFT(descripcion, 25) AS proveedor, COUNT(*) AS total
    FROM soportes_factura
    WHERE tipo_detectado IS NOT NULL AND tipo_detectado != tipo_documento
    GROUP BY proveedor
    ORDER BY total DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$labelsProveedores = json_encode(array_column($por_proveedor, 'proveedor'));
$valoresProveedores = json_encode(array_column($por_proveedor, 'total'));

$porcCoin = $total > 0 ? round(($coinciden * 100) / $total, 1) : 0;
$porcDisc = $total > 0 ? round(($discrepan * 100) / $total, 1) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema Administrativo AFTP - Menú Principal</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="js/chart.min.js"></script>
  <script src="js/feather.min.js"></script>
  <style>
    .dashboard h3 {
        margin-top: 40px;
        margin-bottom: 30px;
        text-align: center;
        font-size: 26px;
        color: #2090CD;
    }
    .info-box {
        margin-top: 30px;
        padding: 20px;
        border: 2px solid #007BFF;
        border-radius: 8px;
        text-align: center;
        background: #f9f9f9;
        font-size: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .info-box p {
        margin: 10px 0;
    }
    .success {
        color: green;
        font-weight: bold;
    }
    .danger {
        color: red;
        font-weight: bold;
    }
    #graficoDona {
        max-width: 300px;
        margin: 0 auto;
    }
    .leyenda-dona {
        margin-bottom: 10px;
        text-align: center;
        font-weight: bold;
        color: #555;
    }
  </style>
</head>
<body>

<div class="container">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP">
    <h2>Sistema de Control de Archivo</h2>
    <div class="menu">
      <button class="menu-toggle" onclick="toggleMenu()">☰ Menú</button> <!-- 🔥 Botón hamburguesa agregado -->
      <a href="logout.php" class="btn">Cerrar sesión</a>
    </div>
  </div>

  <nav class="nav">
    <ul>
      <li><a href="#"><i data-feather="folder"></i> Fichas ▼</a>
        <ul class="submenu">
          <li><a href="cia01.php"><i data-feather="home"></i> Sucursales</a></li>
          <li><a href="clientes01.php"><i data-feather="users"></i> Clientes</a></li>
          <li><a href="proveedores01.php"><i data-feather="truck"></i> Proveedores</a></li>
        </ul>
      </li>
      <li><a href="#"><i data-feather="file-text"></i> Facturas ▼</a>
        <ul class="submenu">
          <li><a href="factura01.php"><i data-feather="plus-square"></i> Ingreso</a></li>
          <li><a href="buscarfactura.php?origen=index.php"><i data-feather="search"></i> Buscar Factura</a></li>
        </ul>
      </li>
      <li><a href="#"><i data-feather="credit-card"></i> Gastos ▼</a>
        <ul class="submenu">
          <li><a href="gastos01.php"><i data-feather="dollar-sign"></i> Ingreso de Gastos</a></li>
        </ul>
      </li>
      <li><a href="#"><i data-feather="bar-chart-2"></i> Reportes ▼</a>
        <ul class="submenu">
          <li><a href="reportefacturas.php"><i data-feather="file"></i> Facturas Recibidas</a></li>
          <li><a href="reportegastos.php"><i data-feather="file-minus"></i> Gastos Recibidos</a></li>
          <li><a href="reporteprov.php"><i data-feather="file-text"></i> Listado de Proveedores</a></li>
        </ul>
      </li>
      <li><a href="#"><i data-feather="archive"></i> AFTP ▼</a>
        <ul class="submenu">
          <li><a href="soportes.php"><i data-feather="upload-cloud"></i> Cargar Soporte</a></li>
          <li><a href="buscarsoporte.php"><i data-feather="search"></i> Buscar Soporte</a></li>
          <li><a href="indices.php"><i data-feather="layers"></i> Índices</a></li>
        </ul>
      </li>
    </ul>
  </nav>

  <div class="dashboard">
    <h3>Dashboard Clasificador ML</h3>

    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
      <div style="flex: 1 1 250px; text-align: center;">
        <div class="leyenda-dona">Clasificación de Soportes</div>
        <canvas id="graficoDona"></canvas>
      </div>

      <div style="flex: 1 1 250px; text-align: center;">
        <canvas id="graficoProveedores"></canvas>

        <div class="info-box">
          <p><i data-feather="file"></i> <strong>Total de Soportes:</strong> <?= $total ?></p>
          <p class="success"><i data-feather="check-circle"></i> Coincidencias: <?= $coinciden ?> (<?= $porcCoin ?>%)</p>
          <p class="danger"><i data-feather="x-circle"></i> Discrepancias: <?= $discrepan ?> (<?= $porcDisc ?>%)</p>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
function toggleMenu() {
    const nav = document.querySelector('.nav ul');
    nav.classList.toggle('show');
}

const ctxDona = document.getElementById('graficoDona').getContext('2d');
new Chart(ctxDona, {
  type: 'doughnut',
  data: {
    labels: ['Coinciden', 'Discrepancias'],
    datasets: [{
      data: [<?= $coinciden ?>, <?= $discrepan ?>],
      backgroundColor: ['#2090CD', '#FF6B6B'],
      borderColor: '#fff',
      borderWidth: 2
    }]
  },
  options: {
    plugins: {
      legend: {
        display: true,
        position: 'bottom',
        labels: {
          color: '#333',
          font: { size: 14 }
        }
      }
    }
  }
});

const ctxProv = document.getElementById('graficoProveedores').getContext('2d');
new Chart(ctxProv, {
  type: 'bar',
  data: {
    labels: <?= $labelsProveedores ?>,
    datasets: [{
      label: 'Discrepancias',
      data: <?= $valoresProveedores ?>,
      backgroundColor: '#4DB7E8',
      borderRadius: 10
    }]
  },
  options: {
    plugins: {
      legend: { display: false },
      title: { display: false }
    },
    scales: {
      x: { ticks: { color: '#666' } },
      y: { ticks: { color: '#666' }, beginAtZero: true }
    }
  }
});
feather.replace();
</script>

</body>
</html>