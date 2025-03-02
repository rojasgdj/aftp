<!doctype html>


  <html>
  <head>
  <meta charset="utf-8">
  <title>Sistema Administrativo AFTP - Clientes</title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv1 {
	position: absolute;
	width: 1276px;
	height: 207px;
	z-index: 1;
	left: 4px;
	top: 7px;
}
  #apDiv2 {
	position: absolute;
	width: 1275px;
	height: 396px;
	z-index: 2;
	left: 4px;
	top: 219px;
}
  </style>
  <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv3 {
	position: absolute;
	width: 117px;
	height: 28px;
	z-index: 3;
	left: 294px;
	top: 20px;
}
  </style>
  <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
  <script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
  </head>
    
  <body>
  <div class="titulo" id="apDiv1">
    <p><img src="LogoVeramedWEB.jpg" width="293" height="119"> </p>
    <p>Sistema  de Control de Archivos- Clientes</p>
    <div id="apDiv3"><a href="index.php" title="Ir al Menu Inicio">Menu Inicio</a></div>
  </div>
  <div id="apDiv2">
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Datos Iniciales</li>
        <li class="TabbedPanelsTab" tabindex="0">Listado</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <p>Creación de Clientes</p>
      <form name="form1" method="post" action="cliente01valida.php">
          <table width="849" border="0">
            <tr>
              <td width="236" >RIF</td>
              <td width="603" ><span id="sprytextfield1">
                <label for="rif"></label>
                <input type="text" name="rif" id="rif">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
            </tr>
            <tr>
              <td>Razón Social </td>
              <td><span id="sprytextfield2">
                <label for="razonsoc"></label>
                <input name="razonsoc" type="text" id="razonsoc" size="100" maxlength="100">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
            </tr>
            <tr>
              <td>Dirección</td>
              <td><span id="sprytextarea1">
                <label for="direccion"></label>
                <textarea name="direccion" id="direccion" cols="45" rows="5"></textarea>
              <span class="textareaRequiredMsg">Se necesita un valor.</span></span></td>
            </tr>
            <tr>
              <td>Teléfono</td>
              <td><span id="sprytextfield3">
              <label for="telefono"></label>
              <input type="text" name="telefono" id="telefono">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
            </tr>
            <tr>
              <td>Persona Contacto</td>
              <td><span id="sprytextfield4">
                <label for="persona"></label>
                <input name="persona" type="text" id="persona" size="50" maxlength="50">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
            </tr>
          </table>
          <p>&nbsp;</p>
          
           
            <p>
              <input type="submit" id="insertar" value="Insertar">
            </p>
          </form>
        </div>
        <div class="TabbedPanelsContent">Ultimos Clientes
        
        <?php
		    echo "<br>";
			error_reporting(E_ALL ^ E_DEPRECATED);
		    $conexion=mysql_connect("localhost","root","Joybook") 
            or die("Problemas en la conexion");
	        mysql_select_db("vema",$conexion) or
            die("Problemas en la seleccion de la base de datos");
	  
            $registros = mysql_query("SELECT * FROM clientes order by fechacre desc",$conexion) 
            or die("Problemas en el select".mysql_error());
			echo " <table border='1'>";
			  while ($reg=mysql_fetch_array($registros))
              {
              echo "<tr><th>Código</th><th>RIF</th><th>Razón Social</th>";
			  while ($reg=mysql_fetch_array($registros))
              {
			   echo "<tr>";
			   echo "<td>".$reg['codcli']."</td>";
			   echo "<td>".$reg['rif']."</td>";
			   echo "<td>".$reg['razonsoc']."</td>";
			   
			   echo "</tr>";
              } 

	        echo "</table>";
              } 

	   
            mysql_close($conexion);
		
		?>
        </div>
      </div>
    </div> 

  </div>
  <script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
  </script>
  </body>
</html>

