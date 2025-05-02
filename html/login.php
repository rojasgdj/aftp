<?php
session_start();

// Evitar caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirigir si ya está logueado
if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - Sistema de Control de Archivo</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Modal visor de manual */
    .modal-manual {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }
    .modal-manual-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 800px;
      max-height: 90vh;
      position: relative;
      overflow: hidden;
    }
    .modal-manual-content iframe {
      width: 100%;
      height: 500px;
      border-radius: 8px;
    }
    .modal-manual .close {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 24px;
      color: red;
      cursor: pointer;
    }

    /* Botón manual dentro del contenedor */
    .manual-btn {
      position: absolute;
      bottom: 20px;
      right: 20px;
      cursor: pointer;
      text-align: center;
    }
    .manual-btn img {
      width: 48px;
    }
    .manual-btn div {
      font-size: 12px;
      color: #555;
      font-weight: bold;
      margin-top: 3px;
    }
  </style>
</head>
<body>

<div class="container" style="position: relative;">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 80px;">
    <h2>Iniciar Sesión</h2>
  </div>

  <form class="login-form" method="post" action="iniciasesion.php" autocomplete="off">
    <div class="form-group">
      <label for="cedula">Cédula</label>
      <input type="number" name="cedula" id="cedula" placeholder="Ingrese su cédula" required min="1" autocomplete="off">
    </div>

    <div class="form-group">
      <label for="clave1">Contraseña</label>
      <input type="password" name="clave1" id="clave1" placeholder="Ingrese su contraseña" required minlength="4" autocomplete="off">
    </div>

    <button type="submit" class="btn">Iniciar Sesión</button>

    <div class="extra-links">
      <a href="crearusuario.php">¿No tienes cuenta? Regístrate aquí</a>
    </div>
  </form>

  <div class="footer">
    Copyright © 2025 - AFTP | Design with us
  </div>

  <!--Botón dentro del contenedor -->
  <div class="manual-btn" onclick="mostrarManual()">
    <img src="img/carpeta.png" alt="Ver manual" title="Ver Manual de Usuario">
    <div>Manual de usuario</div>
  </div>

</div>

<!-- Modal del visor -->
<div class="modal-manual" id="modalManual">
  <div class="modal-manual-content">
    <span class="close" onclick="cerrarManual()">&times;</span>
    <h3 style="text-align: center;">Manual de Usuario</h3>
    <iframe src="/manual/AFTP.pdf"></iframe>
    <div style="text-align: center; margin-top: 10px;">
      <a href="/manual/AFTP.pdf" download class="btn">⬇️ Descargar Manual</a>
    </div>
  </div>
</div>

<script>
  window.onload = function() {
    document.getElementById("cedula").value = "";
    document.getElementById("clave1").value = "";
  };

  function mostrarManual() {
    document.getElementById("modalManual").style.display = 'flex';
  }

  function cerrarManual() {
    document.getElementById("modalManual").style.display = 'none';
  }
</script>

</body>
</html>