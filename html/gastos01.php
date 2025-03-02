<!doctype html>


  <html>
  <head>
  <meta charset="utf-8">
  <title>Sistema Administrativo AFTP - Ingreso de Gastos</title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv1 {
	position: absolute;
	width: 1276px;
	height: 210px;
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
	top: 222px;
}

  </style>
  <script language="javascript">
       function asignaprov() {
		  var codigo = document.getElementById('listacli').value;
		  document.getElementById('proveedor').value = codigo ;
		   
	   }
  </script>  

  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
  <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv3 {
	position: absolute;
	width: 174px;
	height: 39px;
	z-index: 3;
	left: 293px;
	top: 21px;
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
    <p>Sistema de Control de Archivos- Ingreso de Gastos</p>
    <div id="apDiv3"><a href="index.php" title="Ir a Menu Inicio">Menu Inicio</a></div>
  </div>
  <form name="form1" method="post" action="gastos01valida.php">
  <div id="apDiv2">
    <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Datos</li>
        <li class="TabbedPanelsTab" tabindex="0">Ulimos Gastos Registrados</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <table width="1003" height="210" border="0">
            <tr>
              <td width="175">Código-01</td>
              <td width="301"><span id="sprytextfield1">
                  <input type="text" name="codigo" id="codigo">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
            </tr>
            <tr>
            <tr>
               <td>Número de Factura </td>
               <td><span id="sprytextfield5">
                 <label for="factura"></label>
                 <input type="text" name="factura" id="factura">
               <span class="textfieldRequiredMsg">Se necesita un valor.</span></span></td>
           </tr>
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
              <td>Proveedor </td>
              <td><span id="sprytextfield3">
              <label for="proveedor"></label>
              <input type="text" name="proveedor" id="proveedor">
              <span class="textfieldRequiredMsg">Se necesita un valor.</span><span class="textfieldInvalidFormatMsg">Debe ingresar solo Números.</span></span>
                <label for="listacli"></label>
                <select name="listacli" id="listacli" onChange="asignaprov()" >
                <?php
				    error_reporting(E_ALL ^ E_DEPRECATED);
                   $conexion=mysql_connect("localhost","root","Joybook") 
                       or die("Problemas en la conexion");
	               mysql_select_db("vema",$conexion) or
                       die("Problemas en la seleccion de la base de datos");
	  
                  $registros = mysql_query("select codprov,razonsoc from proveedores",$conexion) 
                      or die("Problemas en el select".mysql_error());
				  print "<option>";  
				  while ($reg=mysql_fetch_array($registros))
                   {
					$cli=$reg['codprov'];
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
	  
            $registros = mysql_query("select gastos.codigo,gastos.factura,gastos.fechaems,gastos.monto,gastos.fechacre,gastos.codprov,proveedores.razonsoc from gastos
			                          inner join proveedores on gastos.codprov=proveedores.codprov order by fechacre DESC LIMIT 10",$conexion) 
            or die("Problemas en el select".mysql_error());
			echo " <table border='1'>";
			echo "<tr><th>Código</th><th>Proveedor</th><th>Razon Social</th><th>Número de Factura</th><th>Fecha Factura</th><th>Monto Bs.</th>";
			  while ($reg=mysql_fetch_array($registros))
              {
			   echo "<tr>";
			   echo "<td>".$reg['codigo']."</td>";
			   echo "<td>".$reg['codprov']."</td>";
			   echo "<td>".$reg['razonsoc']."</td>";
			   echo "<td>".$reg['factura']."</td>";
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
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
  </script>
  </body>
</html>

