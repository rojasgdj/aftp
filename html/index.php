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

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .titulo { display: flex; justify-content: space-between; align-items: center; padding: 10px; background: #007bff; color: white; border-radius: 8px 8px 0 0; }
        .titulo img { height: 50px; }
        .menu { text-align: right; }
        .menu a { text-decoration: none; color: white; font-weight: bold; background: red; padding: 8px 12px; border-radius: 5px; display: inline-block; }
        .menu a:hover { background: darkred; }

        .nav { background: #343a40; border-radius: 0 0 8px 8px; }
        .nav ul { list-style: none; padding: 0; display: flex; justify-content: center; position: relative; }
        .nav ul li { position: relative; padding: 15px; }
        .nav ul li a { color: white; text-decoration: none; font-size: 16px; display: block; cursor: pointer; }
        .nav ul li:hover { background: #495057; }
        .nav ul li ul.submenu { display: none; position: absolute; background: #495057; min-width: 200px; top: 100%; left: 0; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); padding: 0; }
        .nav ul li ul.submenu li { display: block; text-align: left; }
        .nav ul li ul.submenu li a { padding: 10px; font-size: 14px; display: block; color: white; }
        .nav ul li ul.submenu li a:hover { background: #007bff; }

        @media (max-width: 768px) {
            .titulo { flex-direction: column; text-align: center; }
            .menu { text-align: center; width: 100%; margin-top: 10px; }
            .nav ul { flex-direction: column; text-align: left; }
            .nav ul li { width: 100%; }
            .nav ul li ul.submenu { position: relative; width: 100%; }
        }

        .dashboard {
            background: white;
            padding: 30px;
            margin: 30px auto;
            width: 90%;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .dashboard p {
            margin: 10px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="titulo">
        <img src="img/aftp-logo.png" alt="Logo">
        <h2>Sistema de Control de Archivo</h2>
        <div class="menu"><a href="logout.php">Cerrar sesión</a></div>
    </div>

    <nav class="nav">
        <ul>
            <li><a href="#" class="menu-toggle">Fichas de Registros ▼</a>
                <ul class="submenu">
                    <li><a href="cia01.php">Sucursales</a></li>
                    <li><a href="clientes01.php">Clientes</a></li>
                    <li><a href="proveedores01.php">Proveedores</a></li>
                </ul>
            </li>
            <li><a href="#" class="menu-toggle">Ingreso de Facturas ▼</a>
                <ul class="submenu">
                    <li><a href="factura01.php">Ingreso de Factura</a></li>
                    <li><a href="buscarfactura.php">Consulta de Factura</a></li>
                </ul>
            </li>
            <li><a href="#" class="menu-toggle">Gastos ▼</a>
                <ul class="submenu">
                    <li><a href="gastos01.php">Ingreso de Gastos</a></li>
                </ul>
            </li>
            <li><a href="#" class="menu-toggle">Reportes ▼</a>
                <ul class="submenu">
                    <li><a href="reportefacturas.php">Facturas Recibidas</a></li>
                    <li><a href="reportegastos.php">Gastos Recibidos</a></li>
                    <li><a href="reporteprov.php">Listado de Proveedores</a></li>
                </ul>
            </li>
            <li><a href="#" class="menu-toggle">AFTP ▼</a>
                <ul class="submenu">
                    <li><a href="soportes.php">Cargar Soporte</a></li>
                    <li><a href="buscarsoporte.php">Buscar Soporte</a></li>
                    <li><a href="indices.php">Índices de Soportes</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Dashboard ML -->
    <div class="dashboard">
    <h3>Dashboard Clasificador</h3>
    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
        <div style="flex: 1 1 250px;"><canvas id="graficoDona"></canvas></div>
        <div style="flex: 1 1 250px;">
            <canvas id="graficoProveedores"></canvas>
            <!-- Resumen debajo del gráfico de barras -->
            <div style="margin-top: 20px; border: 2px solid black; border-radius: 8px; padding: 15px; font-size: 16px;">
                <p><strong>Total de Soportes:</strong> <?= $total ?></p>
                <p style="color: green;">Coincidencias: <?= $coinciden ?> (<?= $porcCoin ?>%)</p>
                <p style="color: red;">Discrepancias: <?= $discrepan ?> (<?= $porcDisc ?>%)</p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxDona = document.getElementById('graficoDona').getContext('2d');
new Chart(ctxDona, {
    type: 'doughnut',
    data: {
        labels: ['Coinciden', 'Discrepancias'],
        datasets: [{
            label: 'Clasificación',
            data: [<?= $coinciden ?>, <?= $discrepan ?>],
            backgroundColor: ['#28a745', '#dc3545']
        }]
    },
    options: {
        plugins: {
            title: { display: true, text: 'Precisión del Clasificador ML' }
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
            backgroundColor: '#007bff'
        }]
    },
    options: {
        plugins: {
            title: { display: true, text: 'Proveedores con más discrepancias' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<script>
document.querySelectorAll('.menu-toggle').forEach(item => {
    item.addEventListener('click', function (event) {
        event.preventDefault();
        let submenu = this.nextElementSibling;
        document.querySelectorAll('.submenu').forEach(menu => {
            if (menu !== submenu) menu.style.display = 'none';
        });
        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    });
});
document.addEventListener('click', function (event) {
    let isClickInsideMenu = event.target.closest('.nav ul li');
    if (!isClickInsideMenu) {
        document.querySelectorAll('.submenu').forEach(menu => menu.style.display = 'none');
    }
});
</script>
</body>
</html>