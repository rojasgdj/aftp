<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Validación de Datos </title>
</head>
<?php

  $factura=$_REQUEST['factura'];
  $conceptop=$_REQUEST['concepto'];
  $fecha=$_REQUEST['fecha'];
  $fechaems=substr($fecha,6,4).substr($fecha,3,2).substr($fecha,0,2); 
  $cliente=$_REQUEST['cliente'];
  $monto=$_REQUEST['monto'];
  $cia=1;
  $hoy=date("Y-m-d H:i:s"); 
  error_reporting(E_ALL ^ E_DEPRECATED);
  $conexion=mysql_connect("localhost","root","Joybook") 
      or die("Problemas en la conexion");
	  
  mysql_select_db("vema",$conexion) or
      die("Problemas en la seleccion de la base de datos");
	  
  $comando="insert into facturas (numero,concepto,codcli,fechaems,fechacre,monto,status,codcia) values 
   ($factura,'$conceptop',$cliente,'$fechaems','$hoy',$monto,'ACTIVA',$cia)";

  $insert = mysql_query($comando,$conexion) 
       or die("Problemas en el select: "."<br>".mysql_error()."<br>".$comando);
	   
  
  if ($insert) {
	 echo "Datos Guardados con exito."; 
     print "<script type=\"text/javascript\">"; 
     print "alert('Los datos fueron guardados con exito.');"; 
      print "window.location.href = 'factura01.php';";
     print "</script>";  
  }
  mysql_close($conexion);
  //header('Location: cia01.php'); 
  //exit();
  


?>



<body>
</body>
</html>