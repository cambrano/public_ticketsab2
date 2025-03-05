<?php
    include 'codigos_validadores_rH6xWQgwgWPPfuew.php';
    if(!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['codigo_dispositivo']==$codigo_dispositivo ){
        $_POST['ip'] = $_SERVER['REMOTE_ADDR'];
        $postData = $_POST;
        // Convertir el array en formato JSON
        $jsonData = json_encode($postData, JSON_PRETTY_PRINT);
        // Ruta del archivo JSON
        $jsonFilePath = 'usuario.json';
        // Guardar los datos en el archivo JSON
        if (file_put_contents($jsonFilePath, $jsonData) !== false) {
            //echo "Datos guardados en $jsonFilePath";
        } else {
            //echo "Error al guardar los datos.";
        }
        if($_POST['codigo_seguro'] != $codigo_seguro){
            $datos = array(
                'status' => 0,
                'message' => 'Bad!',
                'error' => 'Error, código seguro'
            );
        }else{
            include '../MVPDIP1420/admin/functions/db.php';
            include '../MVPDIP1420/admin/keySistema/key.php';

            $id_usuariox=mysqli_real_escape_string($conexion,$_POST['id_usuario']);
            $identificadorx=mysqli_real_escape_string($conexion,$_POST['identificador']);
            
            if ($stmt = mysqli_prepare($conexion, "
                SELECT 
                    u.id AS id_usuario,
                    u.usuario,
                    u.id_perfil_usuario,
                    u.identificador,
                    e.nombre,
                    e.apellido_paterno,
                    e.apellido_materno,
                    u.status
                FROM usuarios u
                LEFT JOIN empleados e ON e.id = u.id_empleado
                WHERE u.id = ? AND u.identificador = ? AND u.status = 1 
                LIMIT 1"
            )) {
                mysqli_stmt_bind_param($stmt, "ss", $id_usuariox, $identificadorx);
                /* execute query */
                if (mysqli_stmt_execute($stmt)) {
                    /* bind result variables */
                    mysqli_stmt_bind_result($stmt, $id_usuario, $usuario, $id_perfil_usuario, $identificador, $nombre, $apellido_paterno, $apellido_materno, $status);
                    /* fetch value */
                    if (mysqli_stmt_fetch($stmt)) {

                        if($nombre!=''){
                            $nombre_completo = $nombre.' '.$apellido_paterno.' '.$apellido_materno;
                        }else{
                            $nombre_completo = $usuario;
                        }

                        $datos = array(
                            'status' => $status,
                            'message' => 'OK!',
                            'error' => '',
                            'tiempo_save' =>604800000,
                            'datos' => array(
                                        'id_usuario' => $id_usuario,
                                        'usuario' => $usuario,
                                        'id_perfil_usuario' => $id_perfil_usuario,
                                        'identificador' => $identificador,
                                        'codigo_seguro' => $codigo_seguro,
                                        'nombre' => $nombre,
                                        'apellido_paterno' => $apellido_paterno,
                                        'apellido_materno' => $apellido_materno,
                                        'nombre_completo' => $nombre_completo,
                                    )
                        );
                    } else {
                        $datos = array(
                            'status' => 0,
                            'message' => 'Bad!',
                            'error' => 'Error al obtener los datos'
                        );
                    }
                    /* close statement */
                    mysqli_stmt_close($stmt);
                } else {
                    $datos = array(
                        'status' => 0,
                        'message' => 'Bad!',
                        'error' => 'Error en la ejecución del statement: ' . mysqli_stmt_error($stmt)
                    );
                }
            } else {
                $datos = array(
                    'status' => 0,
                    'message' => 'Bad!',
                    'error' => 'Error al preparar el statement: ' . mysqli_error($conexion)
                );
            }

        }
    }else{
        // bad
        $datos = array(
            'status' => 0,
            'message' => 'Bad!',
            'error' => 'Error, uso indebido del API',
        );
    }

    

    // Convertir el arreglo a JSON
    header('Content-Type: application/json');
    echo $json_datos = json_encode($datos);


?>