<?php
session_start();

// Evitar caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirigir si el usuario ya está autenticado
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
  <link href="Public/css/estilos.css" rel="stylesheet">

</head>
<body>

  <div id="container">
    <form id="loginForm" method="post" action="iniciasesion.php" autocomplete="off">
      <center>
        <p><img src="LogoVeramedWEB.jpg" alt="Logo de Veramed" width="293" height="119"></p>
        <p>&nbsp;</p>
        <div class="block-border">
          <p><strong>Sistema AFTP - Inicio de sesión</strong></p>
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
        <p><a href="crearusuario.php">Registrarse</a></p>
        <p><a href="AFTP.pdf">Manual de uso</a></p>
      </center>
    </form>

    <div id="footer">
      <h5>Copyright 2025 © Bits Software. | Design with us</h5>
    </div>
  </div>

  <script>
    window.onload = function() {
      document.getElementById("cedula").value = "";
      document.getElementById("clave1").value = "";
    };
  </script>

</body>
</html>