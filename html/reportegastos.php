<!doctype html>


  <html>
  <head>
  <meta charset="utf-8">
  <title>Sistema de Control de Archivos - Reporte de Gastos Recibidos</title>
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
  <style type="text/css">
  #apDiv3 {
	position: absolute;
	width: 178px;
	height: 36px;
	z-index: 3;
	left: 296px;
	top: 19px;
}
  </style>
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  </head>
    
  <body>
  <div class="titulo" id="apDiv1">
    <p><img src="LogoVeramedWEB.jpg" width="293" height="119"> </p>
    <p>Sistema de Control de Archivo- Reporte de Gastos Recibidos</p>
    <div id="apDiv3"><a href="index.php" title="Ir a Menu Inicio">Menu Inicio</a></div>
  </div>
  <form name="form1" method="post" action="index.php">
    <div id="apDiv2">
    
     <?php
		    echo "<br>";
			 error_reporting(E_ALL ^ E_DEPRECATED);
		    $conexion=mysql_connect("localhost","root","Joybook") 
            or die("Problemas en la conexion");
	        mysql_select_db("vema",$conexion) or
            die("Problemas en la seleccion de la base de datos");
	  
           $registros = mysql_query("select gastos.codigo,gastos.factura,gastos.fechaems,gastos.monto,gastos.fechacre,gastos.codprov,proveedores.razonsoc from gastos
			                          inner join proveedores on gastos.codprov=proveedores.codprov order by fechacre DESC",$conexion) 
									  
            or die("Problemas en el select".mysql_error());
			echo " <table border='1' >";
			   echo "<tr>";
			   echo "<th>Código</th>";
			   echo "<th>Razón Social</th>";
			   echo "<th>Número de Factura</th>";
			   echo "<th>Fecha</th>";
			   echo "<th>Monto Bs.</th>";
			   echo "</tr>";
			
			  while ($reg=mysql_fetch_array($registros))
              {
			   
			   echo "<tr>";
			   echo "<td>".$reg['codprov']."</td>";
			   echo "<td>".$reg['razonsoc']."</td>";
			   echo "<td>".$reg['factura']."</td>";
			   echo "<td>".$reg['fechaems']."</td>";
			   echo "<td align='right' >".$reg['monto']."</td>";
			   echo "</tr>";
              } 

	        echo "</table>";
            mysql_close($conexion);
			$hoy = date("d-m-Y H:i:s"); 
			echo "<br>";
			echo "Fecha de emisión : ".$hoy;
			echo "<br>";
		
		?>
    
    
    <button onClick="window.print()">Imprimir</button>
   </div>
  </form>
</body>
</html>

