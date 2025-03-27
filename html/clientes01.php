<?php
session_start();
session_regenerate_id(true); // Prevenir secuestro de sesión

// Evitar caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db.php'; // Conectar a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Administrativo AFTP - Clientes</title>

    <!-- Estilos -->
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        input, textarea {
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
    </style>
</head>

<body>
    <div class="container">
        <!-- Título -->
        <div class="titulo">
            <h2>Sistema de Control de Archivo - Clientes</h2>
        </div>

        <!-- Menú -->
        <div class="menu">
            <a href="index.php">Menú Inicio</a>
        </div>

        <!-- Pestañas -->
        <div class="tab-container">
            <div class="tab" onclick="openTab('crear')">Nuevo Cliente</div>
            <div class="tab" onclick="openTab('listado')">Listado</div>
        </div>

        <!-- Formulario de creación -->
        <div id="crear" class="content active">
            <h3>Registrar Cliente</h3>
            <form method="post" action="cliente01valida.php">
                <label for="nit"><b>NIT</b></label>
                <input type="text" name="nit" id="nit" maxlength="15" required>

                <label for="razon_social"><b>Razón Social</b></label>
                <input type="text" name="razon_social" id="razon_social" maxlength="100" required>

                <label for="direccion"><b>Dirección</b></label>
                <textarea name="direccion" id="direccion" rows="3" required></textarea>

                <label for="telefono"><b>Teléfono</b></label>
                <input type="text" name="telefono" id="telefono" maxlength="15" required>

                <label for="numero_contacto"><b>Contacto</b></label>
                <input type="text" name="numero_contacto" id="numero_contacto" maxlength="30" required>

                <button type="submit">Registrar</button>
            </form>
        </div>

        <!-- Listado de clientes -->
        <div id="listado" class="content">
            <h3>Listado de Clientes</h3>

            <div id="printArea">
                <?php
                try {
                    // Consulta de clientes
                    $stmt = $pdo->query("SELECT cod_cliente, nit, razon_social, telefono, status_cliente, fecha_creacion FROM clientes ORDER BY fecha_creacion DESC");

                    if ($stmt->rowCount() > 0) {
                        echo "<table>";
                        echo "<tr><th>Código</th><th>NIT</th><th>Razón Social</th><th>Teléfono</th><th>Estado</th><th>Fecha Creación</th></tr>";

                        while ($reg = $stmt->fetch()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($reg['cod_cliente']) . "</td>";
                            echo "<td>" . htmlspecialchars($reg['nit']) . "</td>";
                            echo "<td>" . htmlspecialchars($reg['razon_social']) . "</td>";
                            echo "<td>" . htmlspecialchars($reg['telefono']) . "</td>";
                            echo "<td>" . htmlspecialchars($reg['status_cliente']) . "</td>";
                            echo "<td>" . htmlspecialchars($reg['fecha_creacion']) . "</td>";
                            echo "</tr>";
                        }

                        echo "</table>";
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

    <!-- Script para manejar las pestañas -->
    <script>
        function openTab(tabId) {
            document.querySelectorAll(".content").forEach(el => el.classList.remove("active"));
            document.getElementById(tabId).classList.add("active");
        }
    </script>
</body>
</html>