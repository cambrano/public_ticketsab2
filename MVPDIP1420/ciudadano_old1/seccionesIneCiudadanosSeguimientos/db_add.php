<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/gps_distancias.php";
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['evaluacion']==false){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_ciudadano_seguimiento"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano_seguimiento"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	if(!empty($_POST)){
		$seccion_ineDatos=seccion_ineDatos($_POST['seccion_ine_ciudadano_seguimiento'][0]['id_seccion_ine']);
		$seccion_ine_ciudadanoDatos=seccion_ine_ciudadanoDatos($_POST['seccion_ine_ciudadano_seguimiento'][0]['id_seccion_ine_ciudadano']);


		$latitud = $_POST['seccion_ine_ciudadano_seguimiento'][0]['latitud'];
		$longitud = $_POST['seccion_ine_ciudadano_seguimiento'][0]['longitud'];

		$latitud_r = $_POST['seccion_ine_ciudadano_seguimiento'][0]['latitud_r'];
		$longitud_r = $_POST['seccion_ine_ciudadano_seguimiento'][0]['longitud_r'];


		$_POST["seccion_ine_ciudadano_seguimiento"][0]['distancia_m'] = distanceCalculation($latitud, $longitud, $latitud_r, $longitud_r,'m',3);
		$_POST["seccion_ine_ciudadano_seguimiento"][0]['distancia_km'] = distanceCalculation($latitud, $longitud, $latitud_r, $longitud_r,'km',3);
		
		$_POST["seccion_ine_ciudadano_seguimiento"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
		$_POST["seccion_ine_ciudadano_seguimiento"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];

		$_POST["seccion_ine_ciudadano_seguimiento"][0]['id_localidad'] = $seccion_ineDatos['id_localidad'];
		$_POST["seccion_ine_ciudadano_seguimiento"][0]['id_estado'] = $seccion_ineDatos['id_estado'];
		$_POST["seccion_ine_ciudadano_seguimiento"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];


		$success=true;
		$_POST["seccion_ine_ciudadano_seguimiento"][0]['fechaR']=$fechaH;


		foreach ($_POST["seccion_ine_ciudadano_seguimiento"][0] as $key => $value) {
			if($value==""){
				unset($_POST["seccion_ine_ciudadano_seguimiento"][0][$key]);
			}
		}

		//distancia entre el registro y la casa del ciudadano


		$_POST["seccion_ine_ciudadano_seguimiento"][0]['medio_registro'] = 1;
		if($_POST["seccion_ine_ciudadano_seguimiento"][0]['distancia_m'] > 100){
			$_POST["seccion_ine_ciudadano_seguimiento"][0]['distancia_alert'] = 1;
		}else{
			$_POST["seccion_ine_ciudadano_seguimiento"][0]['distancia_alert'] = 0;
		}




		$_POST["seccion_ine_ciudadano_seguimiento"][0]['fecha_hora'] = $_POST["seccion_ine_ciudadano_seguimiento"][0]['fecha']." ".$_POST["seccion_ine_ciudadano_seguimiento"][0]['hora'];
		$_POST["seccion_ine_ciudadano_seguimiento"][0]['codigo_plataforma']=$codigo_plataforma; 

		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_seguimiento"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_seguimiento"][0])."'";
		$insert_secciones_ine_ciudadanos_seguimientos= "INSERT INTO secciones_ine_ciudadanos_seguimientos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_secciones_ine_ciudadanos_seguimientos=$conexion->query($insert_secciones_ine_ciudadanos_seguimientos);
		$num=$conexion->affected_rows;
		if(!$insert_secciones_ine_ciudadanos_seguimientos || $num=0){
			$success=false;
			echo "ERROR insert_secciones_ine_ciudadanos_seguimientos"; 
			var_dump($conexion->error);
		}
		$id_seccion_ine_ciudadano_seguimiento = $id=$_POST["seccion_ine_ciudadano_seguimiento"][0]['id_seccion_ine_ciudadano_seguimiento']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_seguimiento"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_seguimiento"][0])."'";
		$insert_secciones_ine_ciudadanos_seguimientos_historicos= "INSERT INTO secciones_ine_ciudadanos_seguimientos_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_secciones_ine_ciudadanos_seguimientos_historicos=$conexion->query($insert_secciones_ine_ciudadanos_seguimientos_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_secciones_ine_ciudadanos_seguimientos_historicos || $num=0){
			$success=false;
			echo "ERROR insert_secciones_ine_ciudadanos_seguimientos_historicos"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_seguimientos',$id,'Insert','',$fechaH);
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
