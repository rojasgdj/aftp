<?php
require 'db.php'; // Conectar a la base de datos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Administrativo AFTP - Búsqueda de Factura</title>

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

        form {
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
            text-align: left;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, select {
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
    </style>
</head>

<body>
    <div class="container">
        <!-- Título -->
        <div class="titulo">
            <h2>Sistema de Control de Archivo - Búsqueda de Factura</h2>
        </div>

        <!-- Menú -->
        <div class="menu">
            <a href="index.php">Menú Inicio</a>
        </div>

        <!-- Formulario de Búsqueda -->
        <form name="form1" method="post" action="resulbusqfact.php">
            <label for="factura">Número de Factura:</label>
            <input type="text" name="factura" id="factura">

            <label for="fecha">Fecha de Emisión:</label>
            <input type="date" name="fecha" id="fecha">

            <label for="cliente">Cliente:</label>
            <select name="cliente" id="cliente">
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

            <label for="monto">Monto Bs.:</label>
            <input type="number" name="monto" id="monto" step="0.01" min="0">

            <button type="submit">Buscar</button>
        </form>
    </div>
</body>
</html>