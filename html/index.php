<?php
session_start();

// Evitar caché para prevenir acceso después del logout
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Administrativo AFTP - Menú Principal</title>

    <!-- Estilos mejorados -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .titulo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #007bff;
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .titulo img {
            height: 50px;
        }

        .menu {
            text-align: right;
        }

        .menu a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            background: red;
            padding: 8px 12px;
            border-radius: 5px;
            display: inline-block;
        }

        .menu a:hover {
            background: darkred;
        }

        /* Estilos del menú */
        .nav {
            background: #343a40;
            border-radius: 0 0 8px 8px;
        }

        .nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .nav ul li {
            position: relative;
            padding: 15px;
        }

        .nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: block;
            cursor: pointer;
        }

        .nav ul li:hover {
            background: #495057;
        }

        /* 🔹 Submenús ocultos por defecto */
        .nav ul li ul.submenu {
            display: none;
            position: absolute;
            background: #495057;
            min-width: 200px;
            top: 100%;
            left: 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 0;
        }

        .nav ul li ul.submenu li {
            display: block;
            text-align: left;
        }

        .nav ul li ul.submenu li a {
            padding: 10px;
            font-size: 14px;
            display: block;
            color: white;
        }

        .nav ul li ul.submenu li a:hover {
            background: #007bff;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .titulo {
                flex-direction: column;
                text-align: center;
            }

            .menu {
                text-align: center;
                width: 100%;
                margin-top: 10px;
            }

            .nav ul {
                flex-direction: column;
                text-align: left;
            }

            .nav ul li {
                width: 100%;
            }

            .nav ul li ul.submenu {
                position: relative;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="titulo">
            <img src="LogoVeramedWEB.jpg" alt="Logo">
            <h2>Sistema de Control de Archivo</h2>
            <div class="menu"><a href="logout.php">Cerrar sesión</a></div>
        </div>

        <!-- Menú de navegación -->
        <nav class="nav">
            <ul>
                <li>
                    <a href="#" class="menu-toggle">Fichas de Registros ▼</a>
                    <ul class="submenu">
                        <li><a href="cia01.php">Sucursales</a></li>
                        <li><a href="clientes01.php">Clientes</a></li>
                        <li><a href="proveedores01.php">Proveedores</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle">Ingreso de Facturas ▼</a>
                    <ul class="submenu">
                        <li><a href="factura01.php">Ingreso de Factura</a></li>
                        <li><a href="buscarfactura.php">Consulta de Factura</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle">Gastos ▼</a>
                    <ul class="submenu">
                        <li><a href="gastos01.php">Ingreso de Gastos</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle">Reportes ▼</a>
                    <ul class="submenu">
                        <li><a href="reportefacturas.php">Facturas Recibidas</a></li>
                        <li><a href="reportegastos.php">Gastos Recibidos</a></li>
                        <li><a href="reporteprov.php">Listado de Proveedores</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle">AFTP ▼</a>
                    <ul class="submenu">
                        <li><a href="soportes.php">Cargar Soporte</a></li>
                        <li><a href="buscarsoporte.php">Buscar Soporte</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

    <script>
        document.querySelectorAll('.menu-toggle').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                let submenu = this.nextElementSibling;

                // 🔹 Oculta otros submenús abiertos
                document.querySelectorAll('.submenu').forEach(menu => {
                    if (menu !== submenu) {
                        menu.style.display = 'none';
                    }
                });

                // 🔹 Alternar visibilidad del submenú
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            });
        });

        // 🔹 Cierra los menús si se hace clic fuera
        document.addEventListener('click', function (event) {
            let isClickInsideMenu = event.target.closest('.nav ul li');
            if (!isClickInsideMenu) {
                document.querySelectorAll('.submenu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>