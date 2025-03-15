<?php
    include __DIR__."/../functions/security.php";
    include __DIR__."/../functions/efs.php";
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longitud = 5;
    $stringAleatorio = '';

    $resultado = array_merge($_POST['searchTable'][0], $_POST['searchOpciones'][0]);

    for ($i = 0; $i < $longitud; $i++) {
        $posicionAleatoria = rand(0, strlen($caracteres) - 1);
        $stringAleatorio .= $caracteres[$posicionAleatoria];
    }
    // Nombre del archivo CSV
    $rutaEfsSaveFileInternos = rutaEfsSaveFileInternos();
    $nombre_csv = 'LNln_'.$_COOKIE['id_usuario']."_I".date(Y_m_d).'-'.date(H_i_s)."I_".$stringAleatorio;
    $nombreArchivo = $rutaEfsSaveFileInternos.$nombre_csv.'.csv';
    // Abrir el archivo en modo escritura
    $archivoCSV = fopen($nombreArchivo, 'w');
    // Escribir los encabezados en el archivo CSV
    fputcsv($archivoCSV, array_keys($resultado));
    // Escribir los valores en otra línea del archivo CSV
    fputcsv($archivoCSV, array_values($resultado));
    // Cerrar el archivo
    fclose($archivoCSV);
    // Validar si el archivo se creó exitosamente
    if (file_exists($nombreArchivo)) {
        //echo "OK!";
    } else {
        //echo "bad";
    }

?>