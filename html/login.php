<?php
session_start();

// Evitar que el navegador almacene caché para evitar acceso a páginas protegidas después del logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Si el usuario ya está autenticado, lo redirige al index
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
  <meta name="description" content="Sistema de Control de Archivo - Inicio de sesión">
  <meta name="author" content="Bits Software">
  <title>Iniciar Sesión - Sistema de Control de Archivo</title>

  <!-- Estilos -->
  <link href="clases.css" rel="stylesheet">
  <link href="Public/css/estilos.css" rel="stylesheet">
  <link href="Public/css/bg.jpg" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    #container {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      text-align: center;
    }
    .block-border {
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .nom_campos {
      font-weight: bold;
      text-align: left;
    }
    .dat_campos input {
      width: 100%;
      padding: 8px;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    input[type="submit"] {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
    }
    input[type="submit"]:hover {
      background-color: #218838;
    }
    #footer {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9rem;
      color: #666;
    }
  </style>
</head>
<body>

  <div id="container">
    <form id="loginForm" method="post" action="iniciasesion.php" autocomplete="off">
      <center>
        <p><img src="LogoVeramedWEB.jpg" alt="Logo de Veramed" width="293" height="119"></p>
        <p>&nbsp;</p>
        <div class="block-border">
          <p><strong>Sistema de Control de Archivo - Inicio de sesión</strong></p>
          <table>
            <tr>
              <td class="nom_campos"><label for="cedula"><b>Cédula</b></label></td>
              <td class="dat_campos">
                <input type="number" name="cedula" id="cedula" placeholder="Cédula" required min="1" autocomplete="off">
              </td>
            </tr>
            <tr>
              <td class="nom_campos"><label for="clave1">Contraseña</label></td>
              <td class="dat_campos">
                <input type="password" name="clave1" id="clave1" placeholder="Clave" required minlength="4" autocomplete="off">
              </td>
            </tr>
            <tr>
              <td class="nom_campos">&nbsp;</td>
              <td class="dat_campos">
                <input type="submit" name="enviar" id="enviar" value="Iniciar sesión">
              </td>
            </tr>
          </table>
        </div>
        <p><a href="registrousuario.php">Registrarse</a></p>
        <p><a href="AFTP.pdf">Manual de uso</a></p>
      </center>
    </form>

    <div id="footer">
      <h5>Copyright 2025 © Bits Software. | Design with us</h5>
    </div>
  </div>

  <!-- JavaScript para mejorar seguridad -->
  <script>
    window.onload = function() {
      document.getElementById("cedula").value = "";
      document.getElementById("clave1").value = "";
    };

    // Script de copyright
    document.addEventListener('DOMContentLoaded', function () {
      console.log('NOTIFICACIÓN de Copyright© ["2024"] ["Bits Software"]...');
    });
  </script>

</body>
</html>