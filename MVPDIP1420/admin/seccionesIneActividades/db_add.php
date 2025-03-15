<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/secciones_ine_actividades.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_actividades',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}


	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["seccion_ine_actividad"][0] as $keyPrincipal => $atributo) {
			$_POST["seccion_ine_actividad"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$seccion_ine_actividadClaveVerificacion=seccion_ine_actividadClaveVerificacion($_POST["seccion_ine_actividad"][0]['clave'],'',1);
		if($seccion_ine_actividadClaveVerificacion){
			$claveF= clave('secciones_ine_actividades');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["seccion_ine_actividad"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$_POST["seccion_ine_actividad"][0]['fechaR']=$fechaH;
		//$_POST["seccion_ine_actividad"][0]['fecha_hora']=$_POST["seccion_ine_actividad"][0]['fecha']." ".$_POST["seccion_ine_actividad"][0]['hora'];

		$_POST["seccion_ine_actividad"][0]['codigo_plataforma']=$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_actividad"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_actividad"][0])."'";
		$inset_secciones_ine_actividades= "INSERT INTO secciones_ine_actividades ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$inset_secciones_ine_actividades=$conexion->query($inset_secciones_ine_actividades);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_actividades || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_actividades"; 
			var_dump($conexion->error);
		}
		$id=$_POST["seccion_ine_actividad"][0]['id_seccion_ine_actividad']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_actividad"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_actividad"][0])."'";
		$inset_secciones_ine_actividades_historicos= "INSERT INTO secciones_ine_actividades_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_actividades_historicos=$conexion->query($inset_secciones_ine_actividades_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_actividades_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_actividades_historicos"; 
			var_dump($conexion->error);
		}

		foreach ($_POST['puntos'] as $key => $value){ 
			$value['id_seccion_ine_actividad'] = $id;
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


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_actividades',$id,'Insert','',$fechaH);
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
