<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sistema Administrativo AFTP - Menú Principal</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        #apDiv1 { position: absolute; width: 1276px; height: 209px; z-index: 1; left: 4px; top: 7px; }
        #apDiv2 { position: absolute; width: 1275px; height: 396px; z-index: 2; left: 5px; top: 219px; }
        #apDivInicio { position: absolute; width: 117px; height: 28px; z-index: 3; left: 295px; top: 21px; }
    </style>
    
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">

    <script src="SpryAssets/SpryValidationTextField.js"></script>
    <script src="SpryAssets/SpryValidationTextarea.js"></script>
    <script src="SpryAssets/SpryTabbedPanels.js"></script>
    <script src="SpryAssets/SpryValidationConfirm.js"></script>
</head>
    
<body>
    <div class="titulo" id="apDiv1">
        <p><img src="LogoVeramedWEB.jpg" width="293" height="119"></p>
        <p>Sistema de Control de Archivo - Compañías</p>
        <div id="apDivInicio"><a href="index.php" title="Ir al Menú Inicio">Menú Inicio</a></div>
    </div>

    <div id="apDiv2">
        <div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0">Datos Iniciales</li>
                <li class="TabbedPanelsTab" tabindex="0">Listado</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
                    <p>Creación de Compañías</p>
                    <form name="form1" method="post" action="cia01valida.php">
                        <label for="codigo">Código</label>
                        <span id="sprytextfield1">
                            <input name="codigo" type="text" id="codigo" size="6" maxlength="6">
                            <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                        </span>

                        <label for="razonsoc">Razón Social</label>
                        <span id="sprytextfield2">
                            <input name="razonsoc" type="text" id="razonsoc" size="100" maxlength="100">
                            <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                        </span>

                        <p>
                            <label for="rif">R.I.F</label>
                            <span id="sprytextfield3">
                                <input name="rif" type="text" id="rif" size="20" maxlength="20">
                                <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                            </span>
                        </p>

                        <p>
                            <label for="direccion">Dirección Fiscal</label>
                            <span id="sprytextarea1">
                                <textarea name="direccion" id="direccion" cols="120" rows="5"></textarea>
                                <span class="textareaRequiredMsg">Se necesita un valor.</span>
                            </span>
                        </p>

                        <p><input type="submit" id="insertar" value="Insertar"></p>
                    </form>
                </div>

                <div class="TabbedPanelsContent">
                    <p>Listado de Compañías</p>

                    <?php
                        require 'db.php'; // Incluir la conexión a la base de datos

                        try {
                            // Consulta de compañías
                            $stmt = $pdo->prepare("SELECT codigo, razonsoc, rif FROM compania");
                            $stmt->execute();
                            $companias = $stmt->fetchAll();

                            // Mostrar tabla solo si hay datos
                            if (count($companias) > 0) {
                                echo "<table border='1'>";
                                echo "<tr><th>Código</th><th>Razón Social</th><th>RIF</th></tr>";

                                foreach ($companias as $compania) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($compania['codigo']) . "</td>";
                                    echo "<td>" . htmlspecialchars($compania['razonsoc']) . "</td>";
                                    echo "<td>" . htmlspecialchars($compania['rif']) . "</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                            } else {
                                echo "<p>No hay compañías registradas.</p>";
                            }
                        } catch (PDOException $e) {
                            echo "<p>Error al cargar compañías: " . htmlspecialchars($e->getMessage()) . "</p>";
                        }
                    ?>
                </div>
            </div>
        </div> 
    </div>

    <script>
        var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
        var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
        var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
        var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
        var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
    </script>
</body>
</html>