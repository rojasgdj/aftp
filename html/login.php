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

  <style>
    /* Estilos generales */
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
    table {
      width: 100%;
    }
    .error-msg {
      color: red;
      font-size: 0.85rem;
      display: none;
    }
  </style>
</head>
<body>

  <div id="container">
    <form id="loginForm" method="post" action="iniciasesion.php">
      <p><img src="LogoVeramedWEB.jpg" alt="Logo de Veramed" width="293" height="119"></p>

      <div class="block-border">
        <p><strong>Sistema de Control de Archivo - Inicio de sesión</strong></p>
        <table>
          <tr>
            <td class="nom_campos"><label for="cedula">Cédula</label></td>
            <td class="dat_campos">
              <input type="number" name="cedula" id="cedula" placeholder="Ingrese su cédula" required min="1">
              <span class="error-msg" id="cedulaError">Debe ingresar una cédula válida.</span>
            </td>
          </tr>
          <tr>
            <td class="nom_campos"><label for="clave1">Contraseña</label></td>
            <td class="dat_campos">
              <input type="password" name="clave1" id="clave1" placeholder="Ingrese su clave" required minlength="4" autocomplete="off">
              <span class="error-msg" id="claveError">Debe ingresar una contraseña válida (mínimo 4 caracteres).</span>
            </td>
          </tr>
          <tr>
            <td class="nom_campos"></td>
            <td class="dat_campos">
              <input type="submit" name="enviar" id="enviar" value="Iniciar sesión">
            </td>
          </tr>
        </table>
      </div>

      <p><a href="registrousuario.php">Registrarse</a> | <a href="AFTP.pdf">Manual de uso</a></p>
    </form>

    <div id="footer">
      <h5>Copyright 2025 © Bits Software. | Design with us</h5>
    </div>
  </div>

  <!-- Validación con JavaScript -->
  <script>
    document.getElementById("loginForm").addEventListener("submit", function (event) {
      let cedula = document.getElementById("cedula").value.trim();
      let clave = document.getElementById("clave1").value.trim();
      let cedulaError = document.getElementById("cedulaError");
      let claveError = document.getElementById("claveError");
      let valid = true;

      // Validar cédula
      if (cedula === "" || isNaN(cedula) || cedula < 1) {
        cedulaError.style.display = "block";
        valid = false;
      } else {
        cedulaError.style.display = "none";
      }

      // Validar contraseña
      if (clave.length < 4) {
        claveError.style.display = "block";
        valid = false;
      } else {
        claveError.style.display = "none";
      }

      // Si hay errores, detener el envío del formulario
      if (!valid) {
        event.preventDefault();
      }
    });
  </script>

</body>
</html>