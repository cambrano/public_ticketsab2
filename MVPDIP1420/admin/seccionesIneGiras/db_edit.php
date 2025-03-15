<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_giras.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/secciones_ine_giras_puntos.php";
	include __DIR__."/../functions/secciones_ine_giras_dependencias.php";
	include __DIR__."/../functions/secciones_ine_giras_dependencias_generales.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_giras",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$id_seccion_ine_gira = $_POST['seccion_ine_gira'][0]['id'];

	$seccion_ine_giraClaveVerificacion=seccion_ine_giraClaveVerificacion($_POST["seccion_ine_gira"][0]["clave"],$_POST["seccion_ine_gira"][0]['id'],1);
	if($seccion_ine_giraClaveVerificacion){
		$claveF= clave("secciones_ine_giras");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["seccion_ine_gira"][0]["clave"] = $claveF["clave"];
		}
	}
	$success=true;
	$entra = false;
	if( registrosCompara("secciones_ine_giras",$_POST['seccion_ine_gira'][0],1) ){
		if(!empty($_POST)){
			$entra = true;
			$seccion_ine_giraDatos=seccion_ine_giraDatos($_POST['seccion_ine_gira'][0]['id']);


			//$_POST['registro']=$fechaH;
			$_POST["seccion_ine_gira"][0]['fechaR']=$fechaH;
			//$_POST["seccion_ine_gira"][0]['fecha_hora']=$_POST["seccion_ine_gira"][0]['fecha']." ".$_POST["seccion_ine_gira"][0]['hora'];
			$_POST["seccion_ine_gira"][0]['fecha_hora'] = $_POST["seccion_ine_gira"][0]['fecha'].' '.$_POST["seccion_ine_gira"][0]['hora'];

			$_POST["seccion_ine_gira"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["seccion_ine_gira"][0]["referencia_importacion"]=$seccion_ine_giraDatos['referencia_importacion'];


			$success=true;
			foreach($_POST['seccion_ine_gira'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			$update_secciones_ine_giras = "UPDATE secciones_ine_giras SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_giras=$conexion->query($update_secciones_ine_giras);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_giras || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_giras"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_gira"][0]['id']); 
			$id_seccion_ine_gira=$_POST['seccion_ine_gira'][0]['id_seccion_ine_gira']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_gira"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_gira'][0])."'";
			$inset_secciones_ine_giras_historicos= "INSERT INTO secciones_ine_giras_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_giras_historicos=$conexion->query($inset_secciones_ine_giras_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_giras_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_giras_historicos"; 
				var_dump($conexion->error);
			}
		}
	}

	//Validamos si las secciones son igual o son diferentes
	$ids_dependencias = explode(",", $_POST["seccion_ine_gira"][0]['ids_dependencias']);
	// Usar array_map y trim para eliminar los espacios en blanco de cada elemento
	$ids_dependencias = array_map('trim', $ids_dependencias);
	// Usar array_filter para eliminar los elementos vacíos o solo espacios
	$ids_dependencias = array_filter($ids_dependencias, function($value) {
		return trim($value) !== "";
	});
	// Reindexar el array para tener claves continuas
	$ids_dependencias = array_values($ids_dependencias);
	if (!empty($ids_dependencias)) {
		$dependencias_array = array_combine($ids_dependencias, $ids_dependencias);
	}

	$secciones_ine_giras_dependenciasIdsDatos = secciones_ine_giras_dependenciasIdsDatos($_POST['seccion_ine_gira'][0]['id']);

	foreach ($secciones_ine_giras_dependenciasIdsDatos as $key => $value) {
		if(empty($dependencias_array[$key])){
			$delete_dependencias[] = $key;
		}
	}
	foreach ($ids_dependencias as $key => $value) {
		if(empty($secciones_ine_giras_dependenciasIdsDatos[$value])){
			$add_dependencias[] = $value;
		}
	}

	foreach ($add_dependencias as $key => $value) {
		$dep['id_seccion_ine_gira'] = $id_seccion_ine_gira;
		$dep['id_dependencia'] = $value;
		$dep['fechaR']  = $fechaH;
		$dep['codigo_plataforma']=$codigo_plataforma;
		$fields_pdo = "`".implode('`,`', array_keys($dep))."`";
		$values_pdo = "'".implode("','", $dep)."'";
		$insert_secciones_ine_giras_dependencia= "INSERT INTO secciones_ine_giras_dependencias ($fields_pdo) VALUES ($values_pdo);";
		$insert_secciones_ine_giras_dependencia=$conexion->query($insert_secciones_ine_giras_dependencia);
		$num=$conexion->affected_rows;
		if(!$insert_secciones_ine_giras_dependencia || $num=0){
			$success=false;
			echo "ERROR insert_secciones_ine_giras_dependencia"; 
			var_dump($conexion->error);
		}
	}

	foreach ($delete_dependencias as $key => $value) {
		$id = $value;
		$delete_secciones_ine_giras_puntos = "DELETE FROM secciones_ine_giras_dependencias  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_secciones_ine_giras_puntos=$conexion->query($delete_secciones_ine_giras_puntos);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_giras_puntos || $num=0){
			$success=false;
			echo "ERROR delete_secciones_ine_giras_puntos."; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
	}




	//Validamos si las secciones son igual o son diferentes
    $ids_dependencias_generales = explode(",", $_POST["seccion_ine_gira"][0]['ids_dependencias']);
    $ids_dependencias_generales[] = $_POST["seccion_ine_gira"][0]['id_dependencia'];
    // Usar array_map y trim para eliminar los espacios en blanco de cada elemento
    $ids_dependencias_generales = array_map('trim', $ids_dependencias_generales);
	// Eliminar valores repetidos
	$ids_dependencias_generales = array_unique($ids_dependencias_generales);
    // Usar array_filter para eliminar los elementos vacíos o solo espacios
    $ids_dependencias_generales = array_filter($ids_dependencias_generales, function($value) {
        return trim($value) !== "";
    });
    // Reindexar el array para tener claves continuas
    $ids_dependencias_generales = array_values($ids_dependencias_generales);
    if (!empty($ids_dependencias_generales)) {
        $dependencias_generales_array = array_combine($ids_dependencias_generales, $ids_dependencias_generales);
        //var_dump($dependencias_generales_array);
    }

    $secciones_ine_giras_dependencias_generalesIdsDatos = secciones_ine_giras_dependencias_generalesIdsDatos($_POST['seccion_ine_gira'][0]['id']);

    foreach ($secciones_ine_giras_dependencias_generalesIdsDatos as $key => $value) {
        if(empty($dependencias_generales_array[$key])){
            $delete_dependencias_generales[] = $key;
        }
    }
    foreach ($ids_dependencias_generales as $key => $value) {
        if(empty($secciones_ine_giras_dependencias_generalesIdsDatos[$value])){
            $add_dependencias_generales[] = $value;
        }
    }

    foreach ($add_dependencias_generales as $key => $value) {
        $dep['id_seccion_ine_gira'] = $id_seccion_ine_gira;
        $dep['id_dependencia'] = $value;
        $dep['fechaR']  = $fechaH;
        $dep['codigo_plataforma']=$codigo_plataforma;
        $fields_pdo = "`".implode('`,`', array_keys($dep))."`";
        $values_pdo = "'".implode("','", $dep)."'";
        $insert_secciones_ine_giras_dependencia_general= "INSERT INTO secciones_ine_giras_dependencias_generales ($fields_pdo) VALUES ($values_pdo);";
        $insert_secciones_ine_giras_dependencia_general=$conexion->query($insert_secciones_ine_giras_dependencia_general);
        $num=$conexion->affected_rows;
        if(!$insert_secciones_ine_giras_dependencia_general || $num=0){
            $success=false;
            echo "ERROR insert_secciones_ine_giras_dependencia_general"; 
            var_dump($conexion->error);
        }
    }

    foreach ($delete_dependencias_generales as $key => $value) {
        $id = $value;
        $delete_secciones_ine_giras_puntos = "DELETE FROM secciones_ine_giras_dependencias_generales  WHERE  id='$id' ";
        $conexion->autocommit(FALSE);
        $delete_secciones_ine_giras_puntos=$conexion->query($delete_secciones_ine_giras_puntos);
        $num=$conexion->affected_rows;
        if(!$delete_secciones_ine_giras_puntos || $num=0){
            $success=false;
            echo "ERROR delete_dependencias_generales."; 
            echo "<br>";
            echo("Errorcode: " . mysqli_errno($conexion));
            echo "<br>";
        }
    }




	$secciones_ine_giras_puntosDatos = secciones_ine_giras_puntosDatos('',$id_seccion_ine_gira);

	foreach ($secciones_ine_giras_puntosDatos as $key => $value) {
		if(empty($_POST['puntos_registrados'][$value['id']])){
			//! Borra el registro
			$delete_puntos[] = $value['id'];
		}
	}
	$_POST['puntos_registrados'] = array_filter($_POST['puntos_registrados'], function($value) {
		return !empty($value);
	});
	foreach ($_POST['puntos_registrados'] as $key => $value) {
		if( registrosCompara("secciones_ine_giras",$_POST['seccion_ine_gira'][0],1) ){
			$entra = true;
			$value['id_seccion_ine_gira'] = $_POST['seccion_ine_gira'][0]['id'];
			$value['id_seccion_ine'] = $_POST["seccion_ine_gira"][0]['id_seccion_ine'];
			$value['id_municipio'] = $_POST["seccion_ine_gira"][0]['id_municipio'];
			$value['id_localidad'] = $_POST["seccion_ine_gira"][0]['id_localidad'];
			$value['fechaR']  = $fechaH;
			$value['codigo_plataforma']=$codigo_plataforma; 

			foreach($value as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
				}else{
					$id_seccion_ine_gira_punto=$atributos;
				}
			}
			echo "<pre>";
			echo $update_secciones_ine_giras_punto = "UPDATE secciones_ine_giras_puntos SET ". join(",",$valueSets) . " WHERE id=".$id_seccion_ine_gira_punto;
			echo "</pre>";die;
			$update_secciones_ine_giras_punto=$conexion->query($update_secciones_ine_giras_punto);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_giras_punto || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_giras_punto"; 
				var_dump($conexion->error);
			}

			unset($value['id']);
			$value['id_seccion_ine_gira_punto'] = $id_seccion_ine_gira_punto;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$inset_secciones_ine_giras_historicos= "INSERT INTO secciones_ine_giras_puntos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_giras_historicos=$conexion->query($inset_secciones_ine_giras_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_giras_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_giras_historicos"; 
				var_dump($conexion->error);
			}
		}
	}

	foreach ($_POST['puntos_nuevos'] as $key => $value){ 
		$entra = true;
		$value['id_seccion_ine_gira'] = $id_seccion_ine_gira;
		$value['id_seccion_ine'] = $_POST["seccion_ine_gira"][0]['id_seccion_ine'];
		$value['id_municipio'] = $_POST["seccion_ine_gira"][0]['id_municipio'];
		$value['id_localidad'] = $_POST["seccion_ine_gira"][0]['id_localidad'];
		$value['fechaR']  = $fechaH;
		$value['codigo_plataforma']=$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($value))."`";
		$values_pdo = "'".implode("','", $value)."'";
		$inset_secciones_ine_giras_puntos= "INSERT INTO secciones_ine_giras_puntos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_giras_puntos=$conexion->query($inset_secciones_ine_giras_puntos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_giras_puntos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_giras_puntos"; 
			var_dump($conexion->error);
		}
		$value['id_seccion_ine_gira_punto']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($value))."`";
		$values_pdo = "'".implode("','", $value)."'";
		$inset_secciones_ine_giras_puntos_historicos= "INSERT INTO secciones_ine_giras_puntos_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_giras_puntos_historicos=$conexion->query($inset_secciones_ine_giras_puntos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_giras_puntos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_giras_puntos_historicos"; 
			var_dump($conexion->error);
		}
	}
	foreach ($delete_puntos as $key => $value) {
		$entra = true;
		$success=true;
		$id = $value;
		$delete_secciones_ine_giras_puntos = "DELETE FROM secciones_ine_giras_puntos  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_secciones_ine_giras_puntos=$conexion->query($delete_secciones_ine_giras_puntos);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_giras_puntos || $num=0){
			$success=false;
			echo "ERROR delete_secciones_ine_giras_puntos."; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
	}

	if($entra == true){
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_giras',$id_seccion_ine_gira,'Update','',$fechaH);
			if($log==true){
				echo "SI";
				$conexion->commit();
				$conexion->close();
			}else{
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}
		}else{
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		}
	}
