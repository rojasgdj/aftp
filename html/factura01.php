<?php
require 'db.php'; // Conectar a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Administrativo AFTP - Ingreso de Facturas</title>

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
            text-align: center;
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

        /* Limitar la descripción para evitar desbordes */
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
            <div class="tab" onclick="openTab('listado')">Listado</div>
        </div>

        <!-- Formulario de creación -->
        <div id="crear" class="content active">
            <h3>Registrar Factura</h3>
            <form id="facturaForm" method="post" action="factura01valida.php">
                <label for="factura"><b>Número de Factura</b></label>
                <input type="text" name="factura" id="factura" required>

                <label for="concepto"><b>Concepto</b></label>
                <textarea name="concepto" id="concepto" rows="3" required></textarea>

                <label for="fecha"><b>Fecha de Emisión</b></label>
                <input type="date" name="fecha" id="fecha" required>

                <label for="cliente"><b>Cliente</b></label>
                <select name="cliente" id="cliente" required>
                    <option value="">Seleccione un cliente</option>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT cod_cliente, razon_social FROM clientes");
                        while ($cliente = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($cliente['cod_cliente']) . "'>" . htmlspecialchars($cliente['razon_social']) . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                    ?>
                </select>

                <label for="cod_cia"><b>Sucursal</b></label>
                <select name="cod_cia" id="cod_cia" required>
                    <option value="">Seleccione una sucursal</option>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT cod_cia, razon_social FROM sucursal");
                        while ($sucursal = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($sucursal['cod_cia']) . "'>" . htmlspecialchars($sucursal['razon_social']) . "</option>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                    ?>
                </select>

                <label for="monto"><b>Monto Bs.</b></label>
                <input type="number" name="monto" id="monto" step="0.01" min="0" required>

                <button type="submit">Registrar</button>
            </form>
        </div>

        <!-- Listado de Facturas -->
        <div id="listado" class="content">
            <h3>Últimas Facturas Registradas</h3>
            <?php
            try {
                $stmt = $pdo->prepare("
                    SELECT f.numero_factura, f.concepto, f.fecha_emision, f.valor_factura, f.fecha_creacion, f.cod_cliente, f.cod_cia, 
                        c.razon_social AS cliente, s.razon_social AS sucursal 
                    FROM facturas f
                    INNER JOIN clientes c ON f.cod_cliente = c.cod_cliente
                    INNER JOIN sucursal s ON f.cod_cia = s.cod_cia
                    ORDER BY f.fecha_creacion DESC
                    LIMIT 10
                ");
                $stmt->execute();
                $facturas = $stmt->fetchAll();

                if (count($facturas) > 0) {
                    echo "<table>";
                    echo "<tr><th>Número</th><th>Cliente</th><th>Sucursal</th><th>Fecha</th><th>Descripción</th><th>Monto</th></tr>";

                    foreach ($facturas as $factura) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($factura['numero_factura']) . "</td>";
                        echo "<td>" . htmlspecialchars($factura['cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($factura['sucursal']) . "</td>";
                        echo "<td>" . htmlspecialchars($factura['fecha_emision']) . "</td>";
                        echo "<td class='descripcion'>" . htmlspecialchars($factura['concepto']) . "</td>";
                        echo "<td align='right'>" . number_format($factura['valor_factura'], 2, ',', '.') . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>No hay facturas registradas.</p>";
                }
            } catch (PDOException $e) {
                echo "<p>Error en la consulta: " . htmlspecialchars($e->getMessage()) . "</p>";
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