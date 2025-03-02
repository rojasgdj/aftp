<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Validación de Datos </title>
</head>
<?php
  $codigo=$_REQUEST['codigo'];
  $factura=$_REQUEST['factura'];
  $conceptop=$_REQUEST['concepto'];
  $fecha=$_REQUEST['fecha'];
  $fechaems=substr($fecha,6,4).substr($fecha,3,2).substr($fecha,0,2); 
  $proveedor=$_REQUEST['proveedor'];
  $monto=$_REQUEST['monto'];
  $cia=1;
  $hoy=date("Y-m-d H:i:s"); 
  
  $conexion=mysql_connect("localhost","root","Joybook") 
      or die("Problemas en la conexion");
	  
  mysql_select_db("vema",$conexion) or
      die("Problemas en la seleccion de la base de datos");
	  
  $comando="insert into gastos (codigo,factura,concepto,codprov,fechaems,fechacre,monto,status,codcia) values 
   ('$codigo','$factura','$conceptop',$proveedor,'$fechaems','$hoy',$monto,'ACTIVA',$cia)";
  $insert = mysql_query($comando,$conexion) 
       or die("Problemas en el select: "."<br>".mysql_error()."<br>".$comando);
	   
  
  if ($insert) {
	 echo "Datos Guardados con exito."; 
     print "<script type=\"text/javascript\">"; 
     print "alert('Los datos fueron guardados con exito.');"; 
      print "window.location.href = 'gastos01.php';";
     print "</script>";  
  }
  mysql_close($conexion);
  //header('Location: cia01.php'); 
  //exit();
  


?>



<body>
</body>
</html>