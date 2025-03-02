<!doctype html>


  <html>
  <head>
  <meta charset="utf-8">
  <title>Sistema Administrativo AFTP - Ingreso de Facturas</title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv1 {
	position: absolute;
	width: 1276px;
	height: 208px;
	z-index: 1;
	left: 4px;
	top: 7px;
}
  #apDiv2 {
	position: absolute;
	width: 1275px;
	height: 396px;
	z-index: 2;
	left: 6px;
	top: 225px;
}

  </style>
  <script language="javascript">
       function asignacli() {
		  var codigo = document.getElementById('listacli').value;
		  document.getElementById('cliente').value = codigo ;
		   
	   }
  </script>  

  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv3 {
	position: absolute;
	width: 178px;
	height: 36px;
	z-index: 3;
	left: 297px;
	top: 19px;
}
  </style>
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
  <script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
  </head>
    
  <body>
  <div class="titulo" id="apDiv1">
    <p><img src="LogoVeramedWEB.jpg" width="293" height="119"> </p>
    <p>Sistema de Control de Archivo- Ingreso de Facturas</p>
    <div id="apDiv3"><a href="index.php" title="Ir a Menu Inicio">Menu Inicio</a></div>
  </div>
  <form name="form1" method="post" action="factura01valida.php">
  <div id="apDiv2">
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Datos</li>
        <li class="TabbedPanelsTab" tabindex="0">Ulimas Facturas Registradas</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <table width="1003" height="210" border="0">
            <tr>
              <td width="175">Número de Factura</td>
              <td width="301"><span id="sprytextfield1">
                  <input type="text" name="factura" id="factura">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
            </tr>
            <tr>
              <td>Concepto</td>
              <td><span id="sprytextarea1">
                <label for="concepto"></label>
                <textarea name="concepto" id="concepto" cols="45" rows="5"></textarea>
                <span class="textareaRequiredMsg">Se necesita un valor.</span></span>
              </td>
             
            </tr>
            <tr>
              <td>Fecha</td>
              <td><span id="sprytextfield2">
              <label for="fecha"></label>
              <input type="text" name="fecha" id="fecha">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.(dd/mm/aaaa)</span></span></td>
              
            </tr>
            <tr>
              <td>Cliente </td>
              <td><span id="sprytextfield3">
              <label for="cliente"></label>
              <input type="text" name="cliente" id="cliente">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Debe ingresar solo Números.</span></span>
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
              <td><span id="sprytextfield4">
              <label for="monto"></label>
              <input type="text" name="monto" id="monto">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Formato no válido.</span><span class="textfieldMinValueMsg">El valor introducido es inferior al mínimo permitido.</span></span></td>
              
            </tr>
          </table>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>
            <input type="submit" name="Enviar" id="Enviar" value="Enviar">
          </p>
        </div>
        <div class="TabbedPanelsContent">
          <p>
            <?php
		    echo "<br>";
			 error_reporting(E_ALL ^ E_DEPRECATED);
		    $conexion=mysql_connect("localhost","root","Joybook") 
            or die("Problemas en la conexion");
	        mysql_select_db("vema",$conexion) or
            die("Problemas en la seleccion de la base de datos");
	  
            $registros = mysql_query("select facturas.numero,facturas.fechaems,facturas.monto,facturas.fechacre,facturas.codcli,clientes.razonsoc from facturas inner join clientes on facturas.codcli=clientes.codcli order by fechacre DESC LIMIT 10",$conexion) 
            or die("Problemas en el select".mysql_error());
			echo " <table border='1'>";
			echo "<tr><th>Código</th><th>Cliente</th><th>Número de Factura</th><th>Fecha Factura</th><th>Monto Bs.</th>";
			  while ($reg=mysql_fetch_array($registros))
              {
			   echo "<tr>";
			   echo "<td>".$reg['codcli']."</td>";
			   echo "<td>".$reg['razonsoc']."</td>";
			   echo "<td>".$reg['numero']."</td>";
			   echo "<td>".$reg['fechaems']."</td>";
			   echo "<td align='right' >".$reg['monto']."</td>";
			   echo "</tr>";
              } 

	        echo "</table>";
            mysql_close($conexion);
		
		?>
        </p>
        </div>
      </div>
    </div>
   
  </div>
  </form>
  <script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"dd/mm/yyyy"});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "currency", {minValue:1});
  </script>
  </body>
</html>

