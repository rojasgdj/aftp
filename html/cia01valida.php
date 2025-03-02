<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Validación de Datos </title>
</head>
<?php

  $codigo=$_REQUEST['codigo'];
  $razonsoc=$_REQUEST['razonsoc'];
  $rif=$_REQUEST['rif'];
  $dir=$_REQUEST['direccion'];
  $hoy=date("Ymd"); 
  error_reporting(E_ALL ^ E_DEPRECATED);
  $conexion=mysql_connect("localhost","root","Joybook") 
      or die("Problemas en la conexion");
	  
  mysql_select_db("vema",$conexion) or
      die("Problemas en la seleccion de la base de datos");
	  
  $insert = mysql_query("insert into compania(codigo,razonsoc,rif,direccion,fecha) values 
   ('$codigo','$razonsoc','$rif','$dir','$hoy')",$conexion) 
       or die("Problemas en el select".mysql_error());
	   
  mysql_close($conexion);
  
  if ($insert) {
     print "<script type=\"text/javascript\">"; 
     print "alert('Los datos fueron guardados con exito.');"; 
      print "window.location.href = 'cia01.php';";
     print "</script>";  
  }
  
  

?>



<body>
</body>
</html>