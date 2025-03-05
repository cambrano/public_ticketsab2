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
        if($id_municipio!=""){
            //$where = " WHERE s.id_municipio = ".$id_municipio;
        }elseif($id_distrito_local!=""){
            //$where = " WHERE s.id_distrito_local = ".$id_distrito_local;
        }elseif($id_distrito_federal!=""){
            $where = " WHERE s.id_distrito_federal = ".$id_distrito_federal;
        }else{
            //$where = "";
        }
        $sql = "

            SELECT 
                s.id,
                LPAD(s.numero,4,0) AS numero,
                s.latitud AS lat_seccion,
                s.longitud AS lng_seccion,
                sp.tipo,
                sp.orden,
                sp.latitud,
                sp.longitud,
                s.id_municipio,
                m.municipio,
                s.id_distrito_local,
                LPAD(dl.clave,2,0) AS distrito_local,
                s.id_distrito_federal,
                LPAD(dl.clave,2,0) AS distrito_federal
            FROM secciones_ine_parametros sp
            LEFT JOIN secciones_ine s
            ON sp.id_seccion_ine = s.id
            LEFT JOIN municipios m
            ON s.id_municipio = m.id
            LEFT JOIN distritos_locales dl
            ON s.id_distrito_local = dl.id
            LEFT JOIN distritos_federales df
            ON s.id_distrito_federal = df.id
            ".$where."
            ORDER BY s.id,sp.tipo,sp.orden
        ";
        if($id_municipio==$row['id_municipio']){
            $color = "30,144,255";
        }elseif($id_distrito_local==$row['id_distrito_local']){
            $color = "30,144,255";
        }elseif($id_distrito_federal==$row['id_distrito_federal']){
            $color = "30,144,255";
        }else{
            $color = "220,20,60";
        }
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

                $arraySeccion[$row['id']]['id'] = $row['id'];
                $arraySeccion[$row['id']]['nombre'] = $row['numero'];
                $arraySeccion[$row['id']]['color'] = $color;
                $arraySeccion[$row['id']]['id_municipio'] = $row['id_municipio'];
                $arraySeccion[$row['id']]['municipio'] = $row['municipio'];
                $arraySeccion[$row['id']]['id_distrito_local'] = $row['id_distrito_local'];
                $arraySeccion[$row['id']]['distrito_local'] = $row['distrito_local'];
                $arraySeccion[$row['id']]['id_distrito_federal'] = $row['id_distrito_federal'];
                $arraySeccion[$row['id']]['distrito_federal'] = $row['distrito_federal'];
                $arraySeccion[$row['id']]['ubicacion']['latlng'] = $row['lat_seccion'].','.$row['lng_seccion'];
                $arraySeccion[$row['id']]['path'][$row['tipo']][$row['orden']]['latlng'] = $row['latitud'].','.$row['longitud'];
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
