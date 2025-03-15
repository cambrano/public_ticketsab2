<?php
    //! visitas
    session_start();
    ini_set('upload_max_filesize', '124M');
    // Aumentar el límite de tamaño máximo para variables POST a 10MB (debe ser igual o mayor que upload_max_filesize)
    ini_set('post_max_size', '124M');
    date_default_timezone_set('America/Cancun');//!cambio de zona horaria
    setlocale(LC_ALL,"es_ES");
    $fechaH=date('Y-m-d H:i:s');
    $fechaSH=date('H:i:s');
    $fechaSF=date('Y-m-d');



    $dbhost = 'localhost';
    $db = "formulario_registros";
    $dbusuario_user = "root";
    $dbpassword_user = "root";
    $dbport = 3306;
    $id_estado = 23;

    $conexion = new mysqli($dbhost, $dbusuario_user, $dbpassword_user, $db, $dbport);
    mysqli_set_charset($conexion, "utf8mb4");
    if ($conexion->connect_error) {
        echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
    }

