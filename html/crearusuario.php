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
  <meta name="description" content="Sistema de Control de Archivo - Registro de Usuario">
  <meta name="author" content="Bits Software">
  <title>Registro de Usuario - Sistema de Control AFTP</title>

  <link rel="stylesheet" href="css/style.css"> <!-- Correcto style.css -->
</head>
<body>

<div class="container">

  <div class="titulo">
    <img src="img/aftp-logo.png" alt="Logo AFTP" style="height: 80px;">
    <h2>Registro de Usuario</h2>
  </div>

  <form class="login-form" method="post" action="registrousuario.php" autocomplete="off">
    <div class="form-group">
      <label for="cedula">Cédula</label>
      <input type="number" name="cedula" id="cedula" placeholder="Ingrese su cédula" required min="1" autocomplete="off">
    </div>

    <div class="form-group">
      <label for="clave1">Contraseña</label>
      <input type="password" name="clave1" id="clave1" placeholder="Ingrese su contraseña" required minlength="4" autocomplete="off">
    </div>

    <div class="form-group">
      <label for="clave2">Confirmar Contraseña</label>
      <input type="password" name="clave2" id="clave2" placeholder="Confirme su contraseña" required minlength="4" autocomplete="off">
    </div>

    <button type="submit" class="btn">Registrar</button>

    <div class="extra-links">
      <a href="login.php">← Volver al Inicio</a>
    </div>
  </form>

  <div class="footer">
    Copyright © 2025 - AFTP | Design with us
  </div>

</div>

</body>
</html>