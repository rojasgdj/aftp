<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Sistema de Control de Archivo - Inicio de sesión">
  <meta name="author" content="Bits Software">
  <title>Crear usuario - Sistema de Control de Archivo</title>

  <!-- Estilos -->
  <link href="clases.css" rel="stylesheet">
  <link href="Public/css/estilos.css" rel="stylesheet">
  <link href="Public/css/bg.jpg" rel="stylesheet">
  <style>
    /* Estilos adicionales para mejorar el diseño */
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
    }
    .block-border {
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .nom_campos {
      font-weight: bold;
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
    <form id="form1" name="form1" method="post" action="iniciasesion.php">
      <center>
        <p><img src="LogoVeramedWEB.jpg" alt="Logo de Veramed" width="293" height="119"></p>
        <p>&nbsp;</p>
        <div class="block-border">
          <p><strong>Sistema de Control de Archivo - Inicio de sesión</strong></p>
          <table>
            <tr>
              <td class="nom_campos"><label for="cedula"><b>Cédula</b></label></td>
              <td class="dat_campos">
                <input type="number" name="cedula" id="cedula" placeholder="Cédula" required>
              </td>
            </tr>
            <tr>
              <td class="nom_campos"><label for="clave1">Contraseña</label></td>
              <td class="dat_campos">
                <input type="password" name="clave1" id="clave1" placeholder="Clave" required>
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
      <h5>Copyright 2016 © Bits Software. | Design with us</h5>
    </div>
  </div>

  <!-- Script para mostrar el mensaje de copyright -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      console.log('NOTIFICACIÓN de Copyright© ["2024"] ["Bits Software"]________(1) Propiedad Intelectual e Industrial “Bits Software”] o sus licenciantes son los propietarios de todos los derechos de propiedad intelectual e industrial de:_____________ (a) este sitio web publicado bajo el dominio [TEG.com], en adelante Este Sitio Web;_______________ (b) todo el material publicado en Este Sitio Web (incluyendo, sin limitación, textos, imágenes, fotografías, dibujos, música, marcas o logotipos, estructura y diseño de la composición de cada una de las páginas individuales que componen la totalidad del sitio, combinaciones de colores, códigos fuentes de los programas que generan la composición de las páginas, software necesario para su funcionamiento, acceso y uso)_______________(2) Licencia de uso  “ Bits Software” ] le concede una licencia universal, no exclusiva, de libre uso y revocable en cualquier momento para:_______________(a) visualizar Este Sitio Web y todo el material publicado en el mediante el uso de un ordenador o dispositivo móvil a través de un navegador web. visite o contacte con Bist Software._______________ http://bitssoftware.com/ info@bitssoftware.com');
    });
  </script>
</body>
</html>