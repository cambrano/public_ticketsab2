<?php
include 'codigos_validadores_rH6xWQgwgWPPfuew.php';
///$_POST['codigo_seguro'] = 1;
///$_POST['codigo_dispositivo'] = "6gC*xx8{H.Zb+1z:#v#iYXK}Y#q_-}Q@";
///$_SERVER['REQUEST_METHOD'] = 'POST';
if (!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['codigo_dispositivo'] == $codigo_dispositivo) {
    $_POST['ip'] = $_SERVER['REMOTE_ADDR'];
    $postData = $_POST;
    $jsonData = json_encode($postData, JSON_PRETTY_PRINT);
    $jsonFilePath = 'secciones_ine.json';

    if (file_put_contents($jsonFilePath, $jsonData) !== false) {
        // Data saved successfully
    } else {
        // Error saving data
    }

    if ($_POST['codigo_seguro'] != $codigo_seguro) {
        $datos = array(
            'status' => 0,
            'message' => 'Bad!',
            'error' => 'Error, código seguro'
        );
    } else {
        include '../MVPDIP1420/admin/functions/db.php';
        include '../MVPDIP1420/admin/keySistema/key.php';
        //include '../MVPDIP1420/admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php';
        $sql = "

            SELECT 
                m.id AS id_municipio,
                m.clave AS clave_municipio,
                m.municipio,
                m.latitud,
                m.longitud,
                l.id AS id_localidad,
                l.clave AS clave_localidad,
                l.localidad,
                l.latitud AS lat_localidad,
                l.longitud AS lng_localidad
            FROM localidades l
            LEFT JOIN municipios m
            ON l.id_municipio = m.id
            WHERE l.id_estado = ".$id_estado." AND m.id_estado = ".$id_estado."
            ORDER BY m.clave,l.clave ASC
        ";
        $result = $conexion->query($sql);
        if (!$result) {
            $error = $conexion->error;
            $errno = $conexion->errno;
            //$errno = mysqli_errno($conexion);
            $datos = array(
                'status' => 0,
                'message' => "Error ($errno): $error".$sql,
            );
        } else {
            $arraySeccion = array();
            while ($row = $result->fetch_assoc()) {

                $arraySeccion[$row['id_municipio']]['id'] = $row['id_municipio'];
                $arraySeccion[$row['id_municipio']]['clave_municipio'] = $row['municipio']." ".$row['clave_municipio'];
                $arraySeccion[$row['id_municipio']]['clave'] = $row['clave_municipio'];
                $arraySeccion[$row['id_municipio']]['municipio'] = $row['municipio']; 
                $arraySeccion[$row['id_municipio']]['ubicacion'] = $row['latitud'].','.$row['longitud'];
                
                $arraySeccion[$row['id_municipio']]['localidades'][$row['id_localidad']] = array(
                    'id' => $row['id_localidad'], 
                    'clave' => $row['clave_localidad'],  
                    'clave_localidad' => $row['localidad']." [".$row['clave_localidad']."]",  
                    'localidad' => $row['localidad'], 
                    'ubicacion' => $row['lat_localidad'].','.$row['lng_localidad'],
                );
            }
            
            $datos = array(
                'status' => 1,
                'message' => 'OK!',
                'error' =>'',
                'tiempo_save' =>$tiempo_save,
                'datos' => $arraySeccion
            );
        }
        $conexion->close();
    }
} else {
    $datos = array(
        'status' => 0,
        'message' => 'Bad!',
        'error' => 'Error, unauthorized API usage'
    );
}

// Send JSON response1
header('Content-Type: application/json');
echo json_encode($datos, JSON_PRETTY_PRINT);
?>
