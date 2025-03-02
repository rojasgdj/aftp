<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>

<p>
  <?php 
   define("ENCRYPTION_KEY", "Llave123");
   error_reporting(E_ALL ^ E_DEPRECATED);
   $cedula = $_REQUEST['cedula'];
   $clave1 = $_REQUEST['clave1'];
   $validado = FALSE;
   $encrypted = encrypt($clave1, ENCRYPTION_KEY);
   // $decrypted = decrypt($encrypted, ENCRYPTION_KEY);
  
   $hoy = date("Y-m-d H:s:i");
   
   
   $conexion = mysql_connect("localhost", "root","Joybook");
   mysql_query("SET NAMES 'utf8'");
   mysql_select_db("vema", $conexion);
   
   $buscar = "SELECT * FROM empmain WHERE cedula=$cedula";
   $empmain = mysql_query($buscar, $conexion) or die(mysql_error());
   $numero_filas = mysql_num_rows($empmain);
   
    if ($numero_filas==0) {
		print "<script type=\"text/javascript\">"; 
        print "alert('El número de cedula no esta registrado como empleado.');"; 
        print "window.history.back();" ;
        print "</script>";  

		
	}else {
	  $rowemp=mysql_fetch_array($empmain);
	  $usrnombres = $rowemp['nombres']; 
	  $usrapellidos =  $rowemp['apellidos']; 
	  
	  session_start();
      $_SESSION = array();
	  session_destroy();
	  
 
      $conexion = mysql_connect("localhost", "root","Joybook");
      mysql_select_db("vema", $conexion);
      $buscar = "SELECT * FROM usuarios WHERE cedula=$cedula ";
      $valida1 = mysql_query($buscar, $conexion) or die(mysql_error());
      $numero_filas = mysql_num_rows($valida1); 
   
      if ($numero_filas>0) {
		$row=mysql_fetch_array($valida1);
	    session_start(); 
		if ($row['clave']=$encrypted) {
           $_SESSION['usrcedula'] = $row['cedula']; 
           $_SESSION['usrnombres'] = $usrnombres; 
           $_SESSION['usrapellidos'] = $usrapellidos; 
           $_SESSION['logged'] = TRUE; 
		   $validado=TRUE;
		
	       print "<script type=\"text/javascript\">"; 
		   print "alert('Sesion iniciada .$usrnombres .$usrapellidos');"; 
           print "window.location.href = 'index.php';" ;
           print "</script>";  
		}
 
      }

   
      if (!$validado)  {

	   print "<script type=\"text/javascript\">"; 
       print "alert('Usuario y/o contraseña incorrecta .');"; 
       print "window.top.location.href = 'login.php';" ;
       print "</script>";  
	   
       } else {
	   
       };
	}
	





/**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

/**
 * Returns decrypted original string
 */
function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}
?>
    

   

  
  
</p>

<p>&nbsp;</p>
</body>
</html>