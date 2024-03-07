<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- visor meta para restablecer la escala inital iPhone -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Crear usuario</title>
<script type="text/javascript">
window.onload=function(){alert('NOTIFICACIÓN de Copyright© ["2015-2021"] ["Cartagena|Rojas Web Ltd"]________(1) Propiedad Intelectual e Industrial “Cartagena|Rojas Web Ltd”] o sus licenciantes son los propietarios de todos los derechos de propiedad intelectual e industrial de:_____________ (a) este sitio web publicado bajo el dominio [TEG.com], en adelante Este Sitio Web;_______________ (b) todo el material publicado en Este Sitio Web (incluyendo, sin limitación, textos, imágenes, fotografías, dibujos, música, marcas o logotipos, estructura y diseño de la composición de cada una de las páginas individuales que componen la totalidad del sitio, combinaciones de colores, códigos fuentes de los programas que generan la composición de las páginas, software necesario para su funcionamiento, acceso y uso)_______________(2) Licencia de uso  “ Cartagena|Rojas Web Ltd ” ] le concede una licencia universal, no exclusiva, de libre uso y revocable en cualquier momento para:_______________(a) visualizar Este Sitio Web y todo el material publicado en el mediante el uso de un ordenador o dispositivo móvil a través de un navegador web. visite o contacte con Cartagena|Rojas Web Ltd._______________ http://www.Cartagena|Rojas.com/ info@Cartagena|Rojas.com');}

</script>

<link href="clases.css" rel="stylesheet" type="text/css"/>
<link href="Public/css/estilos.css" rel="stylesheet"/>
<link href="Public/css/bg.jpg" rel="stylesheet" type="text/css"/>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<head>
 

</head>
<body>

<div id="container">
  <form id="form1" name="form1" method="post" action="iniciasesion.php">
  <center>
  <p><img src="LogoVeramedWEB.jpg" width="293" height="119" /></p>
  <p>&nbsp;</p>
  <div class="block-border">
  <p><strong>Sistema de Control de Archivo- Inicio de sesión</strong></p>
<table  >
    
  <tr>
    <td class="nom_campos"><b>Cédula</b></td>
    <td width="15" class="dat_campos"><span id="sprytextfield1">
      <input type="number" name="cedula" id="cedula" placeholder="Cedula" />
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
  </tr>
    
  <tr>
    <td class="nom_campos">Contraseña</td>
    <td class="dat_campos"><span id="sprypassword1">
      <input type="password" name="clave1" id="clave1" placeholder="Clave" />
      <span class="passwordRequiredMsg">Se necesita un valor.</span></span></td>
  </tr>
    <tr>
      <td class="nom_campos">&nbsp;</td>
      <td class="dat_campos"><input type="submit" name="enviar" id="enviar" value="Iniciar sesión" /></td>
    </tr>
    
</table></div>
  
<p><a href="registrousuario.php">Registrarse</a></p>
<p><a href="AFTP.pdf">Manual de uso</a></p>
  </center>
</form>
<div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>     
      </div>
<div id="footer">
    <h5>&nbsp;&nbsp;&nbsp;&nbsp;  Copyright 2016 © Cartagena Rojas. TEG Iutv. | Design with us</h5>
  </div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>

</body>
</html>