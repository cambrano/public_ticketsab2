<?php
session_start();
include "comp.php";
include "functions.php";
if(!empty($_POST) && !empty($_FILES) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['hash_user'] == $_POST['hash_user'] && !empty($_POST['hash_user']) && !empty($_SESSION['hash_user']) && COUNT($_FILES)>1 ){
    
    $fechaObjetivo = "2025-09-25 23:59:59";
    if ($fechaH > $fechaObjetivo) {
        // Redireccionar a res.php
        echo "Termino la encuesta muchas gracias.";
        exit; // Asegúrate de que no se ejecuten más instrucciones después de la redirección
        die; // Asegúrate de que no se ejecuten más instrucciones después de la redirección
    }
    foreach ($_POST as $key => $value) {
        if($key == 'correo_electronico' || $key == 'num_int' || $key == 'fecha_nacimiento' || $key == 'referido' ){
            //
        }else{
            $campoSinEspacios = trim($value);
            if (empty($campoSinEspacios)) {
                if($key=='nombre'){
                    $input = 'Nombre';
                }elseif ($key=='apellido_paterno') {
                    $input = 'Apellido paterno';
                }elseif ($key=='apellido_materno') {
                    $input = 'Apellido materno';
                }elseif ($key=='clave_elector') {
                    $input = 'Clave de elector';
                }elseif ($key=='telefono') {
                    $input = 'Número de contacto o de Whatsapp';
                }elseif ($key=='id_municipio') {
                    $input = 'Municipio';
                }elseif ($key=='id_localidad') {
                    $input = 'Localidad';
                }elseif ($key=='calle') {
                    $input = 'Calle';
                }elseif ($key=='num_ext') {
                    $input = 'No. Ext.';
                }elseif ($key=='num_int') {
                    $input = 'No. Int.';
                }elseif ($key=='colonia') {
                    $input = 'Colonia';
                }elseif ($key=='codigo_postal') {
                    $input = 'Código Postal';
                }
                echo $input." requerido";
                die;
            }
        }
    }

    if (validarFecha($_POST['fecha_nacimiento'])) {
        //echo "Fecha válida";
    } else {
        echo "Fecha de nacimiento: {$_POST['fecha_nacimiento']}, inválida.";
        die;
    }

    $_POST['clave_elector'] = strtoupper($_POST['clave_elector']);
    if (validarClaveElector($_POST['clave_elector'])) {
        //echo "La clave de elector es válida.";
    } else {
        echo "La clave de elector: {$_POST['clave_elector']}, inválida.";
        die;
    }

    // Consulta preparada para buscar por clave_elector
    $query = "SELECT id,clave_elector FROM ciudadanos_abierto WHERE clave_elector = ? LIMIT 1";
    // Preparar la consulta
    $stmt = $conexion->prepare($query);
    // Vincular el parámetro
    $stmt->bind_param("s", $_POST['clave_elector']);
    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Bind the result variables
        $stmt->bind_result($id, $clave_elector);
        // Fetch the result
        if ($stmt->fetch()) {
            // La clave de elector ya se encuentra registrada
            echo "La clave de elector: $clave_elector ya se encuentra registrada. Favor de hablar para preguntar informes.";
            die;
        } else {
            // La clave de elector no se encontró en la base de datos
            //echo "La clave de elector no se encuentra registrada. Puede proceder con su registro.";
            //die;
        }
    } else {
        // Error en la ejecución de la consulta
        echo "Error en la ejecución de la consulta: " . $stmt->error;
        die;
    }

    if($_POST['referido']!=""){
        $data['referido'] = $_POST['referido'];
    }
    $data['nombre'] = $_POST['nombre'];
    $data['apellido_paterno'] = $_POST['apellido_paterno'];
    $data['apellido_materno'] = $_POST['apellido_materno'];
    $data['fecha_nacimiento'] = $_POST['fecha_nacimiento'];
    $clave_elector = $data['clave_elector'] = $_POST['clave_elector'];
    $data['telefono'] = $_POST['telefono'];
    if($_POST['correo_electronico']!=""){
        $data['correo_electronico'] = $_POST['correo_electronico'];
    }
    $data['id_municipio'] = $_POST['id_municipio'];
    $data['id_localidad'] = $_POST['id_localidad'];
    $data['calle'] = $_POST['calle'];
    $data['num_ext'] = $_POST['num_ext'];
    if($_POST['num_int']!=""){
        $data['num_int'] = $_POST['num_int'];
    }
    $data['colonia'] = $_POST['colonia'];
    $data['codigo_postal'] = $_POST['codigo_postal'];
    $data['hash_user'] = $_POST['hash_user'];
    $data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $data['http_sec_ch_ua_platform'] = $_SERVER['HTTP_SEC_CH_UA_PLATFORM'];
    $data['http_origin'] = $_SERVER['HTTP_ORIGIN'];
    $data['server_name'] = $_SERVER['SERVER_NAME'];
    $data['script_name'] = $_SERVER['SCRIPT_NAME'];
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    $data['fecha'] = $fechaSF;
    $data['hora'] = $fechaSH;
    $data['fecha_hora'] = $fechaH;
    $data['hash_server'] = bin2hex(random_bytes(32));

    //! Archivo Frente
    $length=22; 
    $mk_id=time();
    $gen_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
    $name_frontal=$gen_id.$mk_id."_".$clave_elector."_frontal";
    $name_trasera=$gen_id.$mk_id."_".$clave_elector."_trasera";


    if($_FILES['foto_ine_front']['error']==UPLOAD_ERR_OK) {
        if(is_uploaded_file($_FILES['foto_ine_front']['tmp_name'])){
            if($_FILES['foto_ine_front']['type'] == "image/jpg" || $_FILES['foto_ine_front']['type'] == "image/jpeg" || $_FILES["foto_ine_front"]["type"] == "image/png" ){
                $extension = pathinfo($_FILES['foto_ine_front']['name']);
                $data['ine_name_original_frontal'] = $_FILES['foto_ine_front']['name'];
                $data['ine_name_frontal'] = $name_frontal.'.'.$extension['extension'];
                $data['ine_file_frontal'] = 'files_CX9LkdVh8m6WtFwC1FjheBHi8jy7GP/'.$data['ine_name_frontal'];
                $data['ine_type_frontal'] = $_FILES['foto_ine_front']['type'];
                $data['ine_size_frontal'] = $_FILES['foto_ine_front']['size'];
            }else{
                echo "Error no puede subir archivo INE Frontal con la extension ".pathinfo($_FILES['foto_ine_front']['name'], PATHINFO_EXTENSION).', Solo puedes subir archivos PNG o JPG';
                echo "<br>";
                die;
            }
        }else{
            echo "Error Subir Archivo frontal 2";
            echo "<br>";
            die;
        }
    }else{
        echo "Error Subir Archivo frontal 1";
        echo "<br>";
        die;
    }
    if($_FILES['foto_ine_back']['error']==UPLOAD_ERR_OK) {
        if(is_uploaded_file($_FILES['foto_ine_back']['tmp_name'])){
            if($_FILES['foto_ine_back']['type'] == "image/jpg" || $_FILES['foto_ine_back']['type'] == "image/jpeg" || $_FILES["foto_ine_back"]["type"] == "image/png" ){
                $extension = pathinfo($_FILES['foto_ine_back']['name']);
                $data['ine_name_original_trasera'] = $_FILES['foto_ine_back']['name'];
                $data['ine_name_trasera'] = $name_trasera.'.'.$extension['extension'];
                $data['ine_file_trasera'] = 'files_CX9LkdVh8m6WtFwC1FjheBHi8jy7GP/'.$data['ine_name_trasera'];
                $data['ine_type_trasera'] = $_FILES['foto_ine_back']['type'];
                $data['ine_size_trasera'] = $_FILES['foto_ine_back']['size'];
            }else{
                echo "Error no puede subir el archivo INE Trasera con la extension ".pathinfo($_FILES['foto_ine_back']['name'], PATHINFO_EXTENSION).', Solo puedes subir archivos PNG o JPG';
                echo "<br>";
                die;
            }
        }else{
            echo "Error Subir Archivo trasera 2";
            echo "<br>";
            die;
        }
    }else{
        echo "Error Subir Archivo trasera 1";
        echo "<br>";
        die;
    }
    

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    // Motrar todos los errores de PHP
    error_reporting(-1);

    // No mostrar los errores de PHP
    error_reporting(0);

    // Motrar todos los errores de PHP
    error_reporting(E_ALL);

    // Motrar todos los errores de PHP
    ini_set('error_reporting', E_ALL);
    // Comienza una transacción
    $conexion->begin_transaction();

    try {
        $columns = implode(", ", array_keys($data));
        $values = implode(", ", array_fill(0, count($data), "?"));
        $query = "INSERT INTO ciudadanos_abierto ($columns) VALUES ($values)";
        

        // Preparar la consulta
        $stmt = $conexion->prepare($query);
        if (!$stmt) {
            var_dump($query);
            die("Error en la consulta: " . $stmt);
        }

        // Vincular parámetros
        $types = str_repeat("s", count($data));
        $stmt->bind_param($types, ...array_values($data));

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Confirmar los cambios

            $success = true;
            $rutaTemporal=$_FILES['foto_ine_front']['tmp_name'];
			if(!move_uploaded_file($rutaTemporal,$data['ine_file_frontal'])){
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
				echo "<br>";
			}
			if (!file_exists($data['ine_file_frontal'])) {
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
				echo "<br>";
			}

            $rutaTemporal=$_FILES['foto_ine_back']['tmp_name'];
			if(!move_uploaded_file($rutaTemporal,$data['ine_file_trasera'])){
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
				echo "<br>";
			}
			if (!file_exists($data['ine_file_trasera'])) {
				$success=false;
				echo "ERROR, Imagen Intente Subir otra vez";
				echo "<br>";
			}


            if($success){
                $conexion->commit();
            }
            echo "SI";
        } else {
            // Revertir los cambios en caso de error
            $conexion->rollback();
            echo "Error al insertar el registro: " . $stmt->error;
        }

        // Cerrar la consulta y la conexión
        $stmt->close();
    } catch (Exception $e) {
        // Revertir los cambios en caso de excepción
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Cierra la conexión
    $conexion->close();

}else{
    echo "Debe certificar sus credenciales :D ";
    //! visitas
    $data['hash_user'] = $_SESSION['hash_user'];
    $data['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    $data['http_sec_ch_ua_platform'] = $_SERVER['HTTP_SEC_CH_UA_PLATFORM'];
    $data['http_origin'] = $_SERVER['HTTP_ORIGIN'];
    $data['server_name'] = $_SERVER['SERVER_NAME'];
    $data['script_name'] = $_SERVER['SCRIPT_NAME'];
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    $data['fecha'] = $fechaSF;
    $data['hora'] = $fechaSH;
    $data['fecha_hora'] = $fechaH;
    $data['tipo'] = 'save scanneo';
    $visitas = visitas($conexion,$data);
    $visitasConteo = visitasConteo($conexion,'20',$data['ip'],'','30',$data['fecha_hora']);
    if($visitasConteo['bloqueo'] == 1 ){
        $data['descripcion'] = 'multiples peticiones a save.php';
        $bloquearIP = bloquearIP($conexion,'5-D',$data);
        $ips_bloqueadosHtaccess = ips_bloqueadosHtaccess($conexion,'',$data['ip'],1,'');
    }
}
//unset($_SESSION['hash']);
?>