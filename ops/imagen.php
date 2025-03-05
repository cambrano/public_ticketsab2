<?php
// Cargamos la imagen a mostrar
$file = $_GET['id_img'];

$carpeta_files = '/Volumes/efsczm/ftpFiles/files/';
$no_file = '/Volumes/efsczm/ftpFiles/file_roto.gif';

$carpeta_files = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/files/';
$no_file = $_SERVER['DOCUMENT_ROOT'].'/MVPDIP1420/admin/ftpFiles/file_roto.gif';

if ($file != "") {
    // use dirname to get the directory of the current file
    $archivo = $carpeta_files.$file;
    if (file_exists($archivo)) {
        $file_extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

        // Define MIME types for common file extensions
        $mime_types = [
            'pdf'  => 'application/pdf',
            'gif'  => 'image/gif',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'bmp'  => 'image/bmp',
            'tiff' => 'image/tiff',
            'txt'  => 'text/plain',
            'html' => 'text/html',
            'xml'  => 'application/xml',
            'json' => 'application/json',
            'csv'  => 'text/csv',
            'zip'  => 'application/zip',
            // Add more MIME types as needed
        ];

        if (array_key_exists($file_extension, $mime_types)) {
            $mime_type = $mime_types[$file_extension];
        } else {
            // Default to application/octet-stream for unknown file types
            $mime_type = 'application/octet-stream';
        }

        header("Content-type: $mime_type");
        header("Content-length: " . filesize($archivo));
        header("Content-Disposition: inline; name={$file}; filename={$file}");
        header('Content-Transfer-Encoding: binary');
        echo file_get_contents($archivo);
    } else {
        $archivo = $no_file;
        $mime = mime_content_type($archivo);
        header("Content-type: {$mime}");
        header("Content-length: " . filesize($archivo));
        header("Content-Disposition: inline; filename=$file");
        readfile($archivo);
    }
} else {
    $archivo = $no_file;
    $mime = mime_content_type($archivo);
    header("Content-type: {$mime}");
    header("Content-length: " . filesize($archivo));
    header("Content-Disposition: inline; filename=$file");
    readfile($archivo);
}
