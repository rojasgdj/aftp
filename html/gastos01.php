<?php
session_start();

// Evitar caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Verifica si hay sesión activa
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require 'db.php'; // Conectar a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Administrativo AFTP - Ingreso de Gastos</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .titulo {
            background: #007bff;
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }
        .menu {
            text-align: right;
            margin-bottom: 20px;
        }
        .menu a {
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 5px;
        }
        .tab-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            background: #007bff;
            color: white;
            width: 150px;
            text-align: center;
        }
        .tab:hover {
            background: #0056b3;
        }
        .content {
            display: none;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .active {
            display: block;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="titulo">
            <h2>Sistema de Control de Archivo - Ingreso de Gastos</h2>
        </div>

        <div class="menu">
            <a href="index.php">Menú Inicio</a>
        </div>

        <div class="tab-container">
            <div class="tab" onclick="openTab('crear')">Nuevo Gasto</div>
            <div class="tab" onclick="openTab('listado')">Listado</div>
        </div>

        <!-- Formulario de Creación -->
        <div id="crear" class="content active">
            <h3>Registrar Gasto</h3>
            <form method="post" action="gastos01valida.php">
                <label for="codigo"><b>Código</b></label>
                <input type="text" name="codigo" id="codigo" required>

                <label for="factura"><b>Número de Factura</b></label>
                <input type="text" name="factura" id="factura" required>

                <label for="concepto"><b>Concepto</b></label>
                <textarea name="concepto_gasto" id="concepto_gasto" rows="3" required></textarea>

                <label for="fecha"><b>Fecha de Gasto</b></label>
                <input type="date" name="fecha_emision" id="fecha_emision" required>

                <label for="proveedor"><b>Proveedor</b></label>
                <select name="cod_proveedor" id="cod_proveedor" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php
                    $stmt = $pdo->query("SELECT cod_proveedor, razon_social FROM proveedores");
                    while ($proveedor = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$proveedor['cod_proveedor']}'>" . htmlspecialchars($proveedor['razon_social']) . "</option>";
                    }
                    ?>
                </select>

                <label for="cod_cia"><b>Sucursal</b></label>
                <select name="cod_cia" id="cod_cia" required>
                    <option value="">Seleccione una sucursal</option>
                    <?php
                    $stmt = $pdo->query("SELECT cod_cia, razon_social FROM sucursal");
                    while ($sucursal = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$sucursal['cod_cia']}'>" . htmlspecialchars($sucursal['razon_social']) . "</option>";
                    }
                    ?>
                </select>

                <label for="valor_gasto"><b>Monto Bs.</b></label>
                <input type="number" name="valor_gasto" id="valor_gasto" step="0.01" required>

                <button type="submit">Registrar</button>
            </form>
        </div>

        <!-- Listado de Gastos -->
        <div id="listado" class="content">
            <h3>Listado de Gastos</h3>
            <button onclick="window.print()">🖨️ Imprimir Listado</button>
            <table border="1">
                <tr>
                    <th>Código</th>
                    <th>Proveedor</th>
                    <th>Factura</th>
                    <th>Sucursal</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                </tr>
                <?php
                try {
                    $stmt = $pdo->query("SELECT g.codigo, g.factura, g.fecha_emision, g.valor_gasto, p.razon_social, s.razon_social AS sucursal 
                                         FROM gastos g 
                                         INNER JOIN proveedores p ON g.cod_proveedor = p.cod_proveedor 
                                         INNER JOIN sucursal s ON g.cod_cia = s.cod_cia
                                         ORDER BY g.fecha_creacion DESC LIMIT 10");

                    while ($gasto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$gasto['codigo']}</td>
                                <td>{$gasto['razon_social']}</td>
                                <td>{$gasto['factura']}</td>
                                <td>{$gasto['sucursal']}</td>
                                <td>{$gasto['fecha_emision']}</td>
                                <td align='right'>" . number_format($gasto['valor_gasto'], 2, ',', '.') . "</td>
                              </tr>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                ?>
            </table>
        </div>
    </div>

    <script>
        function openTab(tabId) {
            document.querySelectorAll(".content").forEach(el => el.classList.remove("active"));
            document.getElementById(tabId).classList.add("active");
        }
    </script>
</body>
</html>