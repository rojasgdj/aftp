<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Validación de Datos </title>
</head>
<?php

  $rif=$_REQUEST['rif'];
  $razonsoc=$_REQUEST['razonsoc'];
  $tel=$_REQUEST['telefono'];
  $dir=$_REQUEST['direccion'];
  $persona=$_REQUEST['persona'];
  $hoy=date("Y-m-d H:i:s"); 
  
  $conexion=mysql_connect("localhost","root","Joybook") 
      or die("Problemas en la conexion");
	  
  mysql_select_db("vema",$conexion) or
      die("Problemas en la seleccion de la base de datos");
	  
  $insert = mysql_query("insert into proveedores (rif,razonsoc,telefono,direccion,contacto,status,fechacre) values 
                        ('$rif','$razonsoc','$tel','$dir','$persona','ACTIVO','$hoy')",$conexion) 
       or die("Problemas en el select".mysql_error());
	   
  mysql_close($conexion);
  
  if ($insert) {
     print "<script type=\"text/javascript\">"; 
     print "alert('Los datos fueron guardados con exito.');"; 
     print "window.location.href = 'proveedores01.php';";
     print "</script>";  
  }
  
  

?>



<body>
</body>
</html>