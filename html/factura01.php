<?php
session_start();
session_regenerate_id(true);

// Evitar caché
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
    <title>Sistema AFTP - Ingreso de Facturas</title>
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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

        .menu a:hover {
            background: #218838;
        }

        .tab-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .tab {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            background: #007bff;
            color: white;
            width: 160px;
            text-align: center;
            font-weight: bold;
        }

        .tab:hover {
            background: #0056b3;
        }

        .tab a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            height: 100%;
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

        button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }

        button:hover {
            background: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            padding: 10px;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        .descripcion {
            text-align: left;
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Título -->
        <div class="titulo">
            <h2>Sistema de Control de Archivo - Ingreso de Facturas</h2>
        </div>

        <!-- Menú -->
        <div class="menu">
            <a href="index.php">Menú Inicio</a>
        </div>

        <!-- Pestañas -->
        <div class="tab-container">
            <div class="tab" onclick="openTab('crear')">Nueva Factura</div>
            <div class="tab"><a href="buscarfactura.php">🔎 Consultar Factura</a></div>
            <div class="tab" onclick="openTab('listado')">Listado</div>
        </div>

        <!-- Formulario de creación -->
        <div id="crear" class="content active">
            <h3>Registrar Factura</h3>
            <form method="post" action="factura01valida.php">
                <label><b>Número de Factura</b></label>
                <input type="text" name="factura" required>

                <label><b>Concepto</b></label>
                <textarea name="concepto" rows="3" required></textarea>

                <label><b>Fecha de Emisión</b></label>
                <input type="date" name="fecha" required>

                <label><b>Proveedor</b></label>
                <select name="proveedor" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT cod_proveedor, razon_social FROM proveedores ORDER BY razon_social");
                        while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($prov['cod_proveedor']) . "'>" . htmlspecialchars($prov['razon_social']) . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option value=''>Error al cargar proveedores</option>";
                    }
                    ?>
                </select>

                <label><b>Sucursal</b></label>
                <select name="cod_cia" required>
                    <option value="">Seleccione una sucursal</option>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT cod_cia, razon_social FROM sucursal ORDER BY razon_social");
                        while ($suc = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($suc['cod_cia']) . "'>" . htmlspecialchars($suc['razon_social']) . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<option value=''>Error al cargar sucursales</option>";
                    }
                    ?>
                </select>

                <button type="submit">Registrar Factura</button>
            </form>
        </div>

        <!-- Listado de facturas -->
        <div id="listado" class="content">
            <h3>Últimas Facturas Registradas</h3>
            <?php
            try {
                $stmt = $pdo->prepare("
                    SELECT f.numero_factura, f.concepto, f.fecha_emision, f.fecha_creacion,
                           f.valor_factura, p.razon_social AS proveedor, s.razon_social AS sucursal
                    FROM facturas f
                    INNER JOIN proveedores p ON f.cod_proveedor = p.cod_proveedor
                    INNER JOIN sucursal s ON f.cod_cia = s.cod_cia
                    ORDER BY f.fecha_creacion DESC LIMIT 10
                ");
                $stmt->execute();
                $facturas = $stmt->fetchAll();

                if ($facturas) {
                    echo "<table>";
                    echo "<tr>
                            <th>Número</th>
                            <th>Proveedor</th>
                            <th>Sucursal</th>
                            <th>Fecha</th>
                            <th>Descripción</th>
                          </tr>";
                    foreach ($facturas as $f) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($f['numero_factura']) . "</td>";
                        echo "<td>" . htmlspecialchars($f['proveedor']) . "</td>";
                        echo "<td>" . htmlspecialchars($f['sucursal']) . "</td>";
                        echo "<td>" . htmlspecialchars($f['fecha_emision']) . "</td>";
                        echo "<td class='descripcion'>" . htmlspecialchars($f['concepto']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No hay facturas registradas.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
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