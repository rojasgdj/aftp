<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Crear usuario</title>

<link href="Public/css/estilos.css" rel="stylesheet"/>
<link href="clases.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>

<style type="text/css">
  #apDivInicio {
	position: absolute;
	width: 117px;
	height: 28px;
	z-index: 3;
	left: 986px;
	top: 12px;
}
  </style>
<head>
 

</head>
<body>

<div id="container">
  <form id="form1" name="form1" method="post" action="crearusuario.php">
  <center>
  <p><img src="LogoVeramedWEB.jpg" width="293" height="119" /></p>
  <div class="block-border">
  <div id="apDivInicio"><a href="login.php" title="Ir al Menu Inicio">Menu Inicio</a></div>
  <p>&nbsp;</p>
  <p><strong>Sistema de archivo- Registro de usuarios</strong></p>
<table  >
  <tr>
    <td class="nom_campos">Cédula</td>
    <td width="20" class="dat_campos"><span id="sprytextfield1">
      <input type="text" name="cedula" id="cedula" />
      <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
  </tr>
  <tr>
    <td class="nom_campos">Contraseña</td>
    <td class="dat_campos"><span id="sprypassword1">
      <input type="password" name="clave1" id="clave1" />
      <span class="passwordRequiredMsg">Se necesita un valor.</span></span></td>
  </tr>
  <tr>
    <td class="nom_campos">Repita contraseña</td>
    <td class="dat_campos"><span id="sprypassword2">
      <input type="password" name="clave2" id="clave2" />
      <span class="passwordRequiredMsg">Se necesita un valor.</span></span></td>
  </tr>
    <tr>
      <td class="nom_campos">&nbsp;</td>
      <td class="dat_campos"><input type="submit" name="enviar" id="enviar" value="Crear usuario" /></td>
    </tr>
</table>
  </center>
</form>
</div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var sprypassword2 = new Spry.Widget.ValidationPassword("sprypassword2");
</script>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div id="footer">
    <h5>Copyright 2016 © Bits Software. | Design with us</h5>
  </div>
</body>
</html>