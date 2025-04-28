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

require_once 'db.php';

// Detectar origen
$origen = $_GET['origen'] ?? 'factura01.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buscar Factura - Sistema AFTP</title>

  <link rel="stylesheet" href="css/style.css">
  <style>
    .buscar-form {
      background: #ffffff;
      padding: 40px 30px;
      margin: 20px auto;
      max-width: 500px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      text-align: left;
    }
    .buscar-form label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #333;
      margin-top: 15px;
    }
    .buscar-form input,
    .buscar-form select {
      width: 100%;
      padding: 10px 15px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      outline: none;
    }
    .buscar-form input:focus,
    .buscar-form select:focus {
      border-color: #2090CD;
      box-shadow: 0 0 5px rgba(32, 144, 205, 0.5);
    }
    .buscar-form button {
      background: linear-gradient(to right, #4facfe, #00f2fe);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      margin-top: 20px;
      transition: background 0.3s ease;
    }
    .buscar-form button:hover {
      background: linear-gradient(to right, #00f2fe, #4facfe);
    }
    .menu {
      margin-top: 20px;
      text-align: left;
    }
    .menu a {
      background: linear-gradient(135deg, #78D1F9, #2090CD);
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 12px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .menu a:hover {
      background: #2090CD;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

<div class="container">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 60px;">
    <h2>Búsqueda de Facturas</h2>
  </div>

  <!-- Botón Volver -->
  <div class="menu">
    <a href="<?= htmlspecialchars($origen) ?>">
      <i data-feather="arrow-left"></i> Volver
    </a>
  </div>

  <!-- Formulario de búsqueda -->
  <form class="buscar-form" method="post" action="resulbusqfact.php">
    <h3 style="text-align:center; margin-bottom:20px;">Buscar Factura</h3>

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

<script src="js/feather.min.js"></script>
<script>feather.replace();</script>

</body>
</html>