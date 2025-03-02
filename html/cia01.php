<!doctype html>


  <html>
  <head>
  <meta charset="utf-8">
  <title>Sistema Administrativo AFTP - Menú Principal</title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv1 {
	position: absolute;
	width: 1276px;
	height: 209px;
	z-index: 1;
	left: 4px;
	top: 7px;
}
  #apDiv2 {
	position: absolute;
	width: 1275px;
	height: 396px;
	z-index: 2;
	left: 5px;
	top: 219px;
}
  </style>
  <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDivInicio {
	position: absolute;
	width: 117px;
	height: 28px;
	z-index: 3;
	left: 295px;
	top: 21px;
}
  </style>
  
  <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
  </head>
    
  <body>
  <div class="titulo" id="apDiv1">
    <p><img src="LogoVeramedWEB.jpg" width="293" height="119"> </p>
    <p>Sistema de Control de Archivo- Compañías</p>
    <div id="apDivInicio"><a href="index.php" title="Ir al Menu Inicio">Menu Inicio</a></div>
  </div>
  <div id="apDiv2">
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Datos Iniciales</li>
        <li class="TabbedPanelsTab" tabindex="0">Listado</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <p>Creación de Compañías </p>
          <p>&nbsp;</p>
          <form name="form1" method="post" action="cia01valida.php">
            <span id="sprytextfield1">
            <label for="codigo">Código</label>
              <input name="codigo" type="text" id="codigo" size="6" maxlength="6">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span>
            <span id="sprytextfield2">
            <label for="razonsoc">Razón Social </label>
            <input name="razonsoc" type="text" id="razonsoc" size="100" maxlength="100">
            <span class="textfieldRequiredMsg">Se necesita un valor.</span></span>
            <p><span id="sprytextfield3">
              <label for="rif">R.I.F</label>
              <input name="rif" type="text" id="rif" size="20" maxlength="20">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></p>
            <p><span id="sprytextarea1">
              <label for="direccion">Dirección Fiscal </label>
              <textarea name="direccion" id="direccion" cols="120" rows="5"></textarea>
            <span class="textareaRequiredMsg">Se necesita un valor.</span></span></p>
        
            <p>&nbsp;</p>
            <p>
              <input type="submit" id="insertar" value="Insertar">
            </p>
          </form>
        </div>
        <div class="TabbedPanelsContent">Listado de Compañias
        
        <?php
		    echo "<br>";
			error_reporting(E_ALL ^ E_DEPRECATED);
		    $conexion=mysql_connect("localhost","root","Joybook") 
            or die("Problemas en la conexion");
	        mysql_select_db("vema",$conexion) or
            die("Problemas en la seleccion de la base de datos");
	  
            $registros = mysql_query("SELECT * FROM compania",$conexion) 
            or die("Problemas en el select".mysql_error());
			echo " <table border='1'>";
			  while ($reg=mysql_fetch_array($registros))
              {
              echo "<tr><th>Código</th><th>Razón Social</th><th>RIF</th>";
			  while ($reg=mysql_fetch_array($registros))
              {
			   echo "<tr>";
			   echo "<td>".$reg['codigo']."</td>";
			   echo "<td>".$reg['razonsoc']."</td>";
			   echo "<td>".$reg['rif']."</td>";
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
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
  </script>
  </body>
</html>

