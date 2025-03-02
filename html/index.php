<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- visor meta para restablecer la escala inital iPhone -->
<meta name="viewport" content="width=device-width, initial-scale=1.0"
  
  
  <?php
  error_reporting(0);
  
    if (!isset($_SESSION['logged'])) {
		session_start();
		}
	    
  
    if(!$_SESSION['logged']){ 
    print "<script type=\"text/javascript\">"; 
	
    print "window.location.href = 'login.php';" ;
    print "</script>";  
    exit; 
    }
  ?>
  <meta charset="utf-8">
  <title>Sistema Administrativo AFTP - Menú Principal ver 1.2</title>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv1 {
	position: absolute;
	width: 1276px;
	height: 183px;
	z-index: 1;
	left: 4px;
	top: 7px;
}
  #apDiv2 {
	position: absolute;
	width: 1275px;
	height: 396px;
	z-index: 2;
	left: 3px;
	top: 193px;
}
  </style>
  <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">
  <style type="text/css">
  #apDiv3 {
	position: absolute;
	width: 149px;
	height: 27px;
	z-index: 3;
	left: 298px;
	top: 19px;
}
  </style>
  <link href="Public/css/estilos.css" rel="stylesheet"/>
  <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
  </head>
    
  <body>
  <div class="titulo" id="apDiv1">
    <p><img src="LogoVeramedWEB.jpg" width="293" height="119"></p>
    <p>Sistema de Control de Archivo- Menu Principal</p>
    <div id="apDiv3"><a href="logout.php">Cerrar sesión</a></div>
    <p>&nbsp;</p>
  </div>
  <div id="apDiv2">
    <ul id="MenuBar1" class="MenuBarHorizontal">
      <li><a class="MenuBarItemSubmenu" href="#">Fichas</a>
        <ul>
          <li><a href="cia01.php">Compañías</a></li>
          <li><a href="clientes01.php">Clientes</a></li>
          <li><a href="proveedores01.php">Proveedores</a></li>
        </ul>
      </li>
      <li><a href="#">Ingreso AFTP  </a>
        <ul>
          <li><a href="factura01.php">Ingreso de Factura</a></li>
          <li><a href="buscarfactura.php">Consulta</a></li>
        </ul>
      </li>
      <li><a class="MenuBarItemSubmenu" href="#">Gastos</a>
        <ul>
          <li><a href="gastos01.php">Ingreso de Gastos</a></li>
        </ul>
      </li>
      <li><a href="#" class="MenuBarItemSubmenu">Reportes</a>
        <ul>
          <li><a href="reportefacturas.php">Facturas Recibidas</a></li>
          <li><a href="reportegastos.php">Gastos Recibidos</a></li>
          <li><a href="reporteprov.php">Listado de Proveedores</a></li>
        </ul>
      </li>
  
    </ul>
    <a href="logout.php"></a>
  </div>
  <p>&nbsp; </p>
  <p>&nbsp; </p>
  <script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
    </script>
  </body>
</html>

