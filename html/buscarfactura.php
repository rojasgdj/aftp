<!doctype html>


  <html>
  <head>
  <meta charset="utf-8">
  <title>Sistema de Biblioca IUTV</title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv1 {	position: absolute;	width: 1276px;	height: 208px;	z-index: 1;	left: 4px;	top: 7px;}
  #apDiv2 {	position: absolute;	width: 1275px;	height: 396px;	z-index: 2;	left: 6px;	top: 225px;}

  </style>
  <script language="javascript">
       function asignacli() {
		  var codigo = document.getElementById('listacli').value;
		  document.getElementById('cliente').value = codigo ;
		   
	   }
  </script>  

  <style type="text/css">
  #apDiv3 {	position: absolute;	width: 178px;	height: 36px;	z-index: 3;	left: 296px;	top: 22px;}
  </style>
  <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  </head>
    
  <body aling="center">
  <div class="titulo" id="apDiv1">
    <p><img src="LogoVeramedWEB.jpg" width="293" height="119"> </p>
    <p>Sistema de Control de Archivo- Búsqueda de Factura</p>
    <div id="apDiv3"><a href="index.php" title="Ir a Menu Inicio">Menu Inicio</a></div>
  </div>
  <form name="form1" method="post" action="resulbusqfact.php">
  <div id="apDiv2">
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Coloque uno o varios datos a buscar</li>
</ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <table width="1003" height="210" border="1">
            <tr>
              <td width="175">Número de Factura</td>
              <td width="301"><span id="sprytextfield1">
              <label for="factura"></label>
              <input type="text" name="factura" id="factura">
<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
             
            </tr>
            <tr>
              <td>Fecha</td>
              <td><label for="fecha"></label>
                <span id="sprytextfield2">
                <label for="fecha2"></label>
                <input type="text" name="fecha" id="fecha2">
<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span> Formato dd/mm/yyyy</td>
              
            </tr>
            <tr>
              <td>Cliente </td>
              <td><label for="cliente"></label>
              <input type="text" name="cliente" id="cliente">
             
                <label for="listacli"></label>
                <select name="listacli" id="listacli" onChange="asignacli()" >
                 <?php
				   error_reporting(E_ALL ^ E_DEPRECATED);
                   $conexion=mysql_connect("localhost","root","Joybook") 
                       or die("Problemas en la conexion");
	               mysql_select_db("vema",$conexion) or
                       die("Problemas en la seleccion de la base de datos");
	  
                  $registros = mysql_query("select codcli,razonsoc from clientes",$conexion) 
                      or die("Problemas en el select".mysql_error());
					  
				  while ($reg=mysql_fetch_array($registros))
                   {
					$cli=$reg['codcli'];
					$razon=$reg['razonsoc'];   
					print "<option value=$cli>$cli-$razon";    
                   } 

			   ?>
               
               </select></td>
            
            </tr>
            <tr>
              <td>Monto Bs. </td>
              <td>
              <label for="monto"></label>
              <span id="sprytextfield3">
              <label for="monto2"></label>
              <input type="text" name="monto" id="monto2">
<span class="textfieldInvalidFormatMsg">Formato no válido.</span></span></td>
              
            </tr>
          </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>
            <input type="submit" name="Enviar" id="Buscar" value="Buscar">
          </p>
        </div>
</div>
    </div>
   
  </div>
  </form>
  <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {isRequired:false});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"dd/mm/yyyy", isRequired:false});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "currency", {isRequired:false});
  </script>
  </body>
</html>

