<?php
include 'codigos_validadores_rH6xWQgwgWPPfuew.php';
///$_POST['codigo_seguro'] = 1;
///$_POST['codigo_dispositivo'] = "6gC*xx8{H.Zb+1z:#v#iYXK}Y#q_-}Q@";
///$_SERVER['REQUEST_METHOD'] = 'POST';
if (!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['codigo_dispositivo'] == $codigo_dispositivo) {
    $_POST['ip'] = $_SERVER['REMOTE_ADDR'];
    $postData = $_POST;
    $jsonData = json_encode($postData, JSON_PRETTY_PRINT);
    $jsonFilePath = 'distritos_locales.json';

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
                dl.id,
                LPAD(dl.numero,2,0) AS numero,
                dl.latitud AS lat_seccion,
                dl.longitud AS lng_seccion
            FROM distritos_locales dl
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

                $arraySeccion[$row['id']] = $row;
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
