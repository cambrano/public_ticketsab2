<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_actividades.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/secciones_ine_actividades_puntos.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_actividades",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_actividad"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_actividad"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	$id_seccion_ine_actividad = $_POST['seccion_ine_actividad'][0]['id'];

	$seccion_ine_actividadClaveVerificacion=seccion_ine_actividadClaveVerificacion($_POST["seccion_ine_actividad"][0]["clave"],$_POST["seccion_ine_actividad"][0]['id'],1);
	if($seccion_ine_actividadClaveVerificacion){
		$claveF= clave("secciones_ine_actividades");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["seccion_ine_actividad"][0]["clave"] = $claveF["clave"];
		}
	}
	$success=true;
	$entra = false;
	if( registrosCompara("secciones_ine_actividades",$_POST['seccion_ine_actividad'][0],1) ){
		if(!empty($_POST)){
			$entra = true;
			$seccion_ine_actividadDatos=seccion_ine_actividadDatos($_POST['seccion_ine_actividad'][0]['id']);


			//$_POST['registro']=$fechaH;
			$_POST["seccion_ine_actividad"][0]['fechaR']=$fechaH;
			//$_POST["seccion_ine_actividad"][0]['fecha_hora']=$_POST["seccion_ine_actividad"][0]['fecha']." ".$_POST["seccion_ine_actividad"][0]['hora'];

			$_POST["seccion_ine_actividad"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["seccion_ine_actividad"][0]["referencia_importacion"]=$seccion_ine_actividadDatos['referencia_importacion'];


			$success=true;
			foreach($_POST['seccion_ine_actividad'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}

			$update_secciones_ine_actividades = "UPDATE secciones_ine_actividades SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_actividades=$conexion->query($update_secciones_ine_actividades);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_actividades || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_actividades"; 
				var_dump($conexion->error);
			}

			unset($_POST["seccion_ine_actividad"][0]['id']); 
			$id_seccion_ine_actividad=$_POST['seccion_ine_actividad'][0]['id_seccion_ine_actividad']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_actividad"][0]))."`";
			$values_pdo = "'".implode("','", $_POST['seccion_ine_actividad'][0])."'";
			$inset_secciones_ine_actividades_historicos= "INSERT INTO secciones_ine_actividades_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_actividades_historicos=$conexion->query($inset_secciones_ine_actividades_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_actividades_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_actividades_historicos"; 
				var_dump($conexion->error);
			}
		}
	}

	$secciones_ine_actividades_puntosDatos = secciones_ine_actividades_puntosDatos('',$id_seccion_ine_actividad);

	foreach ($secciones_ine_actividades_puntosDatos as $key => $value) {
		if(empty($_POST['puntos_registrados'][$value['id']])){
			//! Borra el registro
			$delete_puntos[] = $value['id'];
		}
	}
	$_POST['puntos_registrados'] = array_filter($_POST['puntos_registrados'], function($value) {
		return !empty($value);
	});
	foreach ($_POST['puntos_registrados'] as $key => $value) {
		if( registrosCompara("secciones_ine_actividades",$_POST['seccion_ine_actividad'][0],1) ){
			$entra = true;
			$value['id_seccion_ine_actividad'] = $_POST['seccion_ine_actividad'][0]['id'];
			$value['id_seccion_ine'] = $_POST["seccion_ine_actividad"][0]['id_seccion_ine'];
			$value['id_municipio'] = $_POST["seccion_ine_actividad"][0]['id_municipio'];
			$value['id_localidad'] = $_POST["seccion_ine_actividad"][0]['id_localidad'];
			$value['fechaR']  = $fechaH;
			$value['codigo_plataforma']=$codigo_plataforma; 

			foreach($value as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
				}else{
					$id_seccion_ine_actividad_punto=$atributos;
				}
			}
			echo "<pre>";
			echo $update_secciones_ine_actividades_punto = "UPDATE secciones_ine_actividades_puntos SET ". join(",",$valueSets) . " WHERE id=".$id_seccion_ine_actividad_punto;
			echo "</pre>";die;
			$update_secciones_ine_actividades_punto=$conexion->query($update_secciones_ine_actividades_punto);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_actividades_punto || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_actividades_punto"; 
				var_dump($conexion->error);
			}

			unset($value['id']);
			$value['id_seccion_ine_actividad_punto'] = $id_seccion_ine_actividad_punto;
			$fields_pdo = "`".implode('`,`', array_keys($value))."`";
			$values_pdo = "'".implode("','", $value)."'";
			$inset_secciones_ine_actividades_historicos= "INSERT INTO secciones_ine_actividades_puntos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_secciones_ine_actividades_historicos=$conexion->query($inset_secciones_ine_actividades_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_secciones_ine_actividades_historicos || $num=0){
				$success=false;
				echo "ERROR inset_secciones_ine_actividades_historicos"; 
				var_dump($conexion->error);
			}
		}
	}

	foreach ($_POST['puntos_nuevos'] as $key => $value){ 
		$entra = true;
		$value['id_seccion_ine_actividad'] = $id_seccion_ine_actividad;
		$value['id_seccion_ine'] = $_POST["seccion_ine_actividad"][0]['id_seccion_ine'];
		$value['id_municipio'] = $_POST["seccion_ine_actividad"][0]['id_municipio'];
		$value['id_localidad'] = $_POST["seccion_ine_actividad"][0]['id_localidad'];
		$value['fechaR']  = $fechaH;
		$value['codigo_plataforma']=$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($value))."`";
		$values_pdo = "'".implode("','", $value)."'";
		$inset_secciones_ine_actividades_puntos= "INSERT INTO secciones_ine_actividades_puntos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_actividades_puntos=$conexion->query($inset_secciones_ine_actividades_puntos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_actividades_puntos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_actividades_puntos"; 
			var_dump($conexion->error);
		}
		$value['id_seccion_ine_actividad_punto']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($value))."`";
		$values_pdo = "'".implode("','", $value)."'";
		$inset_secciones_ine_actividades_puntos_historicos= "INSERT INTO secciones_ine_actividades_puntos_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_actividades_puntos_historicos=$conexion->query($inset_secciones_ine_actividades_puntos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_actividades_puntos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_actividades_puntos_historicos"; 
			var_dump($conexion->error);
		}
	}
	foreach ($delete_puntos as $key => $value) {
		$entra = true;
		$success=true;
		$id = $value;
		$delete_cuestionarios_respuestas = "DELETE FROM secciones_ine_actividades_puntos  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_cuestionarios_respuestas=$conexion->query($delete_cuestionarios_respuestas);
		$num=$conexion->affected_rows;
		if(!$delete_cuestionarios_respuestas || $num=0){
			$success=false;
			echo "ERROR delete cuestionario respuesta, tiene registros relacionados."; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
	}

	if($entra == true){
		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_actividades',$id_seccion_ine_actividad,'Update','',$fechaH);
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
