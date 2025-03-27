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
    <title>Buscar Factura - Sistema AFTP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f4; text-align: center; padding: 20px; }
        .container {
            max-width: 600px; margin: auto; background: white;
            padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .titulo {
            background: #007bff; color: white; padding: 15px;
            border-radius: 8px 8px 0 0; margin-bottom: 20px;
        }
        .menu { text-align: right; margin-bottom: 20px; }
        .menu a {
            text-decoration: none; background-color: #28a745; color: white;
            padding: 10px; border-radius: 5px;
        }
        .menu a:hover { background: #218838; }
        form {
            background: #fff; padding: 20px; border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2); text-align: left;
        }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, select {
            width: 100%; padding: 10px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            background: #007bff; color: white; padding: 10px;
            border: none; border-radius: 5px; cursor: pointer;
            width: 100%; margin-top: 15px;
        }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="titulo">
            <h2>Sistema de Control de Archivo - Búsqueda de Factura</h2>
        </div>

        <div class="menu">
            <a href="index.php">Menú Inicio</a>
        </div>

        <form method="post" action="resulbusqfact.php">
            <label for="factura">Número de Factura:</label>
            <input type="text" name="factura" id="factura" maxlength="50" placeholder="Ej. BITS12345">

            <label for="fecha">Fecha de Emisión:</label>
            <input type="date" name="fecha" id="fecha">

            <label for="proveedor">Proveedor:</label>
            <select name="proveedor" id="proveedor">
                <option value="">Seleccione un proveedor</option>
                <?php
                try {
                    $stmt = $pdo->query("SELECT cod_proveedor, razon_social FROM proveedores ORDER BY razon_social ASC");
                    while ($prov = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($prov['cod_proveedor']) . "'>" . htmlspecialchars($prov['razon_social']) . "</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option value=''>Error al cargar proveedores</option>";
                }
                ?>
            </select>

            <button type="submit">Buscar</button>
        </form>
    </div>
</body>
</html>