<?php
function clasificar_soporte($ruta_pdf) {
    $escapedPath = escapeshellarg($ruta_pdf);
    $cmd = "/opt/aftp-ml/env/bin/python3 /opt/aftp-ml/clasificador.py $escapedPath";
    $output = shell_exec($cmd);

    if (preg_match('/Clasificado como: (\w+)/i', $output, $match)) {
        return strtolower($match[1]); // factura o gasto
    }
    return null;
}
?>