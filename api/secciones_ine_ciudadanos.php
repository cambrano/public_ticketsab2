<?php
include 'codigos_validadores_rH6xWQgwgWPPfuew.php';
///$_POST['codigo_seguro'] = 1;
///$_POST['codigo_dispositivo'] = "6gC*xx8{H.Zb+1z:#v#iYXK}Y#q_-}Q@";
///$_SERVER['REQUEST_METHOD'] = 'POST';
if (!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['codigo_dispositivo'] == $codigo_dispositivo) {
    $_POST['ip'] = $_SERVER['REMOTE_ADDR'];
    $postData = $_POST;
    $jsonData = json_encode($postData, JSON_PRETTY_PRINT);
    $jsonFilePath = 'secciones_ine_ciudadanos.json';

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

        if($_POST['inputClave']){
            $where .= " AND sic.clave LIKE '%".$_POST['inputClave']."%' ";
        }
        if($_POST['inputFolio']){
            $where .= " AND sic.folio LIKE '%".$_POST['inputFolio']."%' ";
        }
        if($_POST['inputCurp']){
            $where .= " AND sic.curp LIKE '%".$_POST['inputCurp']."%' ";
        }
        if($_POST['inputClaveElector']){
            $where .= " AND sic.clave_elector LIKE '%".$_POST['inputClaveElector']."%' ";
        }
        if($_POST['inputNombre']){
            $where .= " AND sic.nombre LIKE '%".$_POST['inputNombre']."%' ";
        }
        if($_POST['inputApellitoPaterno']){
            $where .= " AND sic.apellido_paterno LIKE '%".$_POST['inputApellitoPaterno']."%' ";
        }
        if($_POST['inputApellitoMaterno']){
            $where .= " AND sic.apellido_materno LIKE '%".$_POST['inputApellitoMaterno']."%' ";
        }
        if($_POST['inputIdSeccionIne']){
            $where .= " AND sic.id_seccion_ine = '".$_POST['inputIdSeccionIne']."' ";
        }
        if($_POST['inputIdDistritoLocal']){
            $where .= " AND sic.id_distrito_local = '".$_POST['inputIdDistritoLocal']."' ";
        }
        if($_POST['inputIdDistritoFederal']){
            $where .= " AND sic.id_distrito_federal = '".$_POST['inputIdDistritoFederal']."' ";
        }
        if($_POST['inputIdMunicipio']){
            $where .= " AND sic.id_municipio = '".$_POST['inputIdMunicipio']."' ";
        }
        if($_POST['inputIdLocalidad']){
            $where .= " AND sic.id_localidad = '".$_POST['inputIdLocalidad']."' ";
        }


        $sql_total = "SELECT COUNT(*) AS registros_totales FROM secciones_ine_ciudadanos sic WHERE 1 {$where} ";
        $resultado = $conexion->query($sql_total); 
        $row=$resultado->fetch_assoc();
        ///Obtenemos el numero de contactos totales con los filtros
        $registros_totales = $row['registros_totales'];
        

        if($registros_totales>0){
            //Solo se puede mostrar 11 registros por cada pagina
            if($_POST['inputPagina']==0 || empty($_POST['inputPagina']) ){
                $_POST['inputPagina']=1;
            }
            $registros_mostrar = 20;
            $pagina_numero = $_POST['inputPagina'];
            $paginas_totales = ceil($registros_totales / $registros_mostrar);
            $limit_mostrar = ($pagina_numero - 1) * $registros_mostrar;
            $limite_script = "$limit_mostrar , $registros_mostrar";
            $limit = "LIMIT ".$limite_script;

            if($paginas_totales>=$_POST['inputPagina']){
                $sql = "
                    SELECT 
                        sic.id,
                        sic.clave,
                        sic.folio,
                        sic.curp,
                        sic.clave_elector,
                        sic.ocr,
                        sic.status_verificacion,
                        tc.nombre AS tipo,
                        sic.id_tipo_ciudadano,
                        CONCAT(sic.apellido_paterno,' ',sic.apellido_materno,' ',sic.nombre) AS nombre_completo,
                        sic.nombre,
                        sic.apellido_paterno,
                        sic.apellido_materno,
                        sic.fecha_nacimiento,
                        sic.correo_electronico,
                        sic.whatsapp,
                        sic.telefono,
                        sic.celular,
                        sic.id_municipio,
                        m.municipio,
                        sic.id_localidad,
                        l.localidad,
                        sic.calle,
                        sic.num_ext,
                        sic.num_int,
                        sic.colonia,
                        sic.codigo_postal,
                        sic.latitud,
                        sic.longitud,
                        CONCAT(sic.latitud,',',sic.longitud) AS ubicacion,
                        sic.id_municipio_ine,
                        mx.municipio AS municipio_ine,
                        sic.id_localidad_ine,
                        lx.localidad AS localidad_ine,
                        sic.calle_ine,
                        sic.num_ext_ine,
                        sic.num_int_ine,
                        sic.colonia_ine,
                        sic.codigo_postal_ine,
                        sic.id_seccion_ine,
                        s.clave AS seccion_ine,
                        dl.id AS id_distrito_local,
                        dl.clave AS distrito_local,
                        df.id AS id_distrito_federal,
                        df.clave AS distrito_federal,
                        sic.manzana,
                        sic.vigencia,
                        sic.observaciones
                    FROM secciones_ine_ciudadanos AS sic
                    LEFT JOIN tipos_ciudadanos tc
                    ON sic.id_tipo_ciudadano = tc.id
                    LEFT JOIN municipios m
                    ON sic.id_municipio = m.id
                    LEFT JOIN localidades l
                    ON sic.id_localidad = l.id
                    LEFT JOIN municipios mx
                    ON sic.id_municipio_ine = mx.id
                    LEFT JOIN localidades lx
                    ON sic.id_localidad_ine = lx.id
                    LEFT JOIN secciones_ine s
                    ON sic.id_seccion_ine = s.id
                    LEFT JOIN distritos_locales dl
                    ON s.id_distrito_local = dl.id
                    LEFT JOIN distritos_federales df
                    ON s.id_distrito_federal = df.id
                    WHERE 1 {$where} 
                    {$limit}
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
                            $limit_mostrar = $limit_mostrar + 1;
                            $row['numero_listado'] = $limit_mostrar;
                            $arraySeccion[$row['id']] = $row;
                        }
                        $registros_mostrados = COUNT($arraySeccion);
                        $datos = array(
                            'status' => 1,
                            'registros_totales' => $registros_totales,
                            'paginas_totales' => $paginas_totales,
                            'registros_mostrados' => $registros_mostrados,
                            'pagina' => $pagina_numero,
                            'limites' => $limite_script,
                            'message' => 'OK!',
                            'error' =>'',
                            'tiempo_save' =>$tiempo_save,
                            'datos' => $arraySeccion
                        );
                    }
            }else{
                $datos = array(
                    'status' => 1,
                    'registros_totales' => $registros_totales,
                    'paginas_totales' => $paginas_totales,
                    'registros_mostrados' => $registros_mostrados,
                    'pagina' => $pagina_numero,
                    'limites' => $limite_script,
                    'message' => 'OK!',
                    'error' =>'',
                    'tiempo_save' =>$tiempo_save,
                    'datos' => []
                );
            }
        }else{
            $datos = array(
                    'status' => 1,
                    'registros_totales' => 0,
                    'paginas_totales' => 0,
                    'registros_mostrados' => 0,
                    'pagina' => $pagina_numero,
                    'limites' => 0,
                    'message' => 'OK!',
                    'error' =>'',
                    'tiempo_save' =>$tiempo_save,
                    'datos' => []
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
