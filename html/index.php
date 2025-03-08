<?php
session_start();

// Evitar caché para prevenir que se acceda a la página después del logout
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
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .titulo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Evita superposición en pantallas pequeñas */
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
            margin-top: 5px; /* Espaciado en móviles */
        }

        .menu a:hover {
            background: darkred;
        }

        /* Estilo del menú */
        .nav {
            background: #343a40;
            border-radius: 0 0 8px 8px;
        }

        .nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav ul li {
            position: relative;
        }

        .nav ul li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .nav ul li:hover {
            background: #495057;
        }

        /* Submenú oculto */
        .nav ul li ul {
            display: none;
            position: absolute;
            background: #495057;
            min-width: 200px;
            top: 100%;
            left: 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 0;
        }

        .nav ul li ul li {
            display: block;
            text-align: left;
        }

        .nav ul li ul li a {
            padding: 10px;
            font-size: 14px;
            display: block;
        }

        .nav ul li ul li a:hover {
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

            .nav ul li ul {
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
                    <a href="#" class="menu-toggle">Fichas ▼</a>
                    <ul>
                        <li><a href="cia01.php">Compañías</a></li>
                        <li><a href="clientes01.php">Clientes</a></li>
                        <li><a href="proveedores01.php">Proveedores</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle">Ingreso AFTP ▼</a>
                    <ul>
                        <li><a href="factura01.php">Ingreso de Factura</a></li>
                        <li><a href="buscarfactura.php">Consulta</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle">Gastos ▼</a>
                    <ul>
                        <li><a href="gastos01.php">Ingreso de Gastos</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle">Reportes ▼</a>
                    <ul>
                        <li><a href="reportefacturas.php">Facturas Recibidas</a></li>
                        <li><a href="reportegastos.php">Gastos Recibidos</a></li>
                        <li><a href="reporteprov.php">Listado de Proveedores</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

    <script>
        // Activa el menú desplegable al hacer clic
        document.querySelectorAll('.menu-toggle').forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault(); // Evita que el enlace navegue
                let submenu = this.nextElementSibling;
                
                // Cierra otros submenús abiertos
                document.querySelectorAll('.nav ul li ul').forEach(menu => {
                    if (menu !== submenu) {
                        menu.style.display = 'none';
                    }
                });

                // Alterna la visibilidad del submenú actual
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            });
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function (event) {
            let isClickInsideMenu = event.target.closest('.nav ul li');
            if (!isClickInsideMenu) {
                document.querySelectorAll('.nav ul li ul').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>