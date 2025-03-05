<?php
    include 'codigos_validadores_rH6xWQgwgWPPfuew.php';
    if(!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['codigo_dispositivo']==$codigo_dispositivo ){
        $_POST['ip'] = $_SERVER['REMOTE_ADDR'];
        $postData = $_POST;
        // Convertir el array en formato JSON
        $jsonData = json_encode($postData, JSON_PRETTY_PRINT);
        // Ruta del archivo JSON
        $jsonFilePath = 'post_token.json';
        // Guardar los datos en el archivo JSON
        if (file_put_contents($jsonFilePath, $jsonData) !== false) {
            //echo "Datos guardados en $jsonFilePath";
        } else {
            //echo "Error al guardar los datos.";
        }
        if($_POST['codigo_seguro'] != $codigo_seguro){
            $datos = array(
                'id_usuario' => '',
                'status' => 0,
                'message' => 'Bad!',
                'error' => 'Error, código seguro'
            );
        }else{
            include '../MVPDIP1420/admin/functions/db.php';
            include '../MVPDIP1420/admin/keySistema/key.php';
            
            $identificadorx = mysqli_real_escape_string($conexion, $_POST['identificador']);
            $id_usuariox = mysqli_real_escape_string($conexion, $_POST['id_usuario']);
            
            if ($stmt = mysqli_prepare($conexion,"
                SELECT 
                u.id AS id_usuario,
                u.status,
                u.identificador 
                FROM usuarios u
                WHERE u.id = ? AND u.identificador = ?  AND (u.codigo_plataforma =  ? OR u.codigo_plataforma = 'x' ) "
            )){
                mysqli_stmt_bind_param($stmt,"sss", $id_usuariox,$identificadorx,$codigo_plataforma);
                /* execute query */
                mysqli_stmt_execute($stmt);
                /* bind result variables */
                mysqli_stmt_bind_result($stmt, $id_usuario,$status, $identificador);
                /* fetch value */
                mysqli_stmt_fetch($stmt);
                /* close statement */
                mysqli_stmt_close($stmt);
                if($status ==1){
                    $datos = array(
                        'identificador' => $identificador,
                        'status' => $status,
                        'message' => 'OK!',
                        'error' => ''
                    );
                }else{
                    $datos = array(
                        'identificador' => '',
                        'status' => 0,
                        'message' => 'Bad!',
                        'error' => 'Error, Usuario o Contraseña no encontrado'
                    );
                    
                }
            } 
        }
        
    }else{
        // bad
        $datos = array(
            'identificador' => '',
            'status' => 0,
            'message' => 'Bad!',
            'error' => 'Error, uso indebido del API',
        );
    }

    

    // Convertir el arreglo a JSON
    header('Content-Type: application/json');
    echo $json_datos = json_encode($datos);


?>