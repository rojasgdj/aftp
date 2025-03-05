<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sistema Administrativo AFTP - Ingreso de Gastos</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        #apDiv1 { position: absolute; width: 1276px; height: 210px; z-index: 1; left: 4px; top: 7px; }
        #apDiv2 { position: absolute; width: 1275px; height: 396px; z-index: 2; left: 4px; top: 222px; }
        #apDiv3 { position: absolute; width: 174px; height: 39px; z-index: 3; left: 293px; top: 21px; }
    </style>
    <script>
        function asignaprov() {
            document.getElementById('proveedor').value = document.getElementById('listacli').value;
        }
    </script>
</head>
<body>
    <div class="titulo" id="apDiv1">
        <p><img src="LogoVeramedWEB.jpg" width="293" height="119"></p>
        <p>Sistema de Control de Archivos - Ingreso de Gastos</p>
        <div id="apDiv3"><a href="index.php" title="Ir a Menú Inicio">Menú Inicio</a></div>
    </div>

    <form method="post" action="gastos01valida.php">
        <div id="apDiv2">
            <table border="0">
                <tr>
                    <td>Código</td>
                    <td><input type="text" name="codigo" required></td>
                </tr>
                <tr>
                    <td>Número de Factura</td>
                    <td><input type="text" name="factura" required></td>
                </tr>
                <tr>
                    <td>Concepto</td>
                    <td><textarea name="concepto" cols="45" rows="5" required></textarea></td>
                </tr>
                <tr>
                    <td>Fecha</td>
                    <td>
                        <input type="text" name="fecha" placeholder="dd/mm/yyyy" required>
                    </td>
                </tr>
                <tr>
                    <td>Proveedor</td>
                    <td>
                        <input type="text" name="proveedor" id="proveedor" required>
                        <select name="listacli" id="listacli" onChange="asignaprov()">
                            <?php
                            require 'db.php'; // Conexión centralizada
                            try {
                                $query = $pdo->query("SELECT codprov, razonsoc FROM proveedores");
                                while ($reg = $query->fetch()) {
                                    echo "<option value='{$reg['codprov']}'>{$reg['codprov']} - {$reg['razonsoc']}</option>";
                                }
                                $pdo = null;
                            } catch (PDOException $e) {
                                die("Error: " . htmlspecialchars($e->getMessage()));
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Monto Bs.</td>
                    <td><input type="text" name="monto" required></td>
                </tr>
            </table>
            <p><input type="submit" value="Enviar"></p>

            <h3>Últimos Gastos Registrados</h3>
            <?php
            require 'db.php';
            try {
                $query = $pdo->query("
                    SELECT g.codigo, g.factura, g.fechaems, g.monto, g.fechacre, g.codprov, p.razonsoc 
                    FROM gastos g
                    INNER JOIN proveedores p ON g.codprov = p.codprov
                    ORDER BY g.fechacre DESC 
                    LIMIT 10
                ");

                if ($query->rowCount() > 0) {
                    echo "<table border='1'>
                            <tr>
                                <th>Código</th>
                                <th>Proveedor</th>
                                <th>Razón Social</th>
                                <th>Número de Factura</th>
                                <th>Fecha Factura</th>
                                <th>Monto Bs.</th>
                            </tr>";

                    while ($reg = $query->fetch()) {
                        echo "<tr>
                                <td>{$reg['codigo']}</td>
                                <td>{$reg['codprov']}</td>
                                <td>{$reg['razonsoc']}</td>
                                <td>{$reg['factura']}</td>
                                <td>{$reg['fechaems']}</td>
                                <td align='right'>{$reg['monto']}</td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No hay gastos registrados.</p>";
                }

                $pdo = null;
            } catch (PDOException $e) {
                die("Error en la conexión: " . htmlspecialchars($e->getMessage()));
            }
            ?>
        </div>
    </form>
</body>
</html>