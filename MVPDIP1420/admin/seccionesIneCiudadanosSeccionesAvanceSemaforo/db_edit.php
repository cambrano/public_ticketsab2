<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_secciones_avance_semaforo.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_ciudadanos_secciones_avance_semaforo",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}


	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$id_seccion_ine = $_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]['id_seccion_ine'];
	$seccion_ine_ciudadano_seccion_avance_semaforoDatos = seccion_ine_ciudadano_seccion_avance_semaforoDatos('',$id_seccion_ine);

	if($seccion_ine_ciudadano_seccion_avance_semaforoDatos['id'] == '' ){
		$entra = true;
		//! Guardamos
		$success=true;
		$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]['fechaR']=$fechaH;
		$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]['codigo_plataforma']=$codigo_plataforma;
		unset($_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]['id']);
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0])."'";
		$insert_secciones_ine_ciudadanos_secciones_avance_semaforo= "INSERT INTO secciones_ine_ciudadanos_secciones_avance_semaforo ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_secciones_ine_ciudadanos_secciones_avance_semaforo=$conexion->query($insert_secciones_ine_ciudadanos_secciones_avance_semaforo);
		$num=$conexion->affected_rows;
		if(!$insert_secciones_ine_ciudadanos_secciones_avance_semaforo || $num=0){
			$success=false;
			echo "ERROR insert_secciones_ine_ciudadanos_secciones_avance_semaforo"; 
			var_dump($conexion->error);
		}
		$id=$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]['id_seccion_ine_ciudadano_seccion_avance_semaforo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0])."'";
		$insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos= "INSERT INTO secciones_ine_ciudadanos_secciones_avance_semaforo_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos=$conexion->query($insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos || $num=0){
			$success=false;
			echo "ERROR insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos"; 
			var_dump($conexion->error);
		}
	}else{
		if( registrosCompara("secciones_ine_ciudadanos_secciones_avance_semaforo",$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0],1)){
			$entra = true;
			if(!empty($_POST)){ 
				$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]["fechaR"]=$fechaH;
				$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]["codigo_plataforma"]=$codigo_plataforma;
				$success=true;
				foreach($_POST["seccion_ine_ciudadano_seccion_avance_semaforo"] as $keyPrincipal => $atributos) {
					foreach ($atributos as $key => $value) {
						if($key !='id'){
							$valueSets[] = $key . " = '" . $value . "'";
						}else{
							$id=$value;
						}
					}
				}
				
				$update_secciones_ine_ciudadanos_secciones_avance_semaforo = "UPDATE secciones_ine_ciudadanos_secciones_avance_semaforo SET ". join(",",$valueSets) . " WHERE id=".$id;
				$conexion->autocommit(FALSE);
				$update_secciones_ine_ciudadanos_secciones_avance_semaforo=$conexion->query($update_secciones_ine_ciudadanos_secciones_avance_semaforo);
				$num=$conexion->affected_rows;
				if(!$update_secciones_ine_ciudadanos_secciones_avance_semaforo || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR update_secciones_ine_ciudadanos_secciones_avance_semaforo"; 
					var_dump($conexion->error);
				}
	
				unset($_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]['id']); 
				$id_seccion_ine_ciudadano_seccion_avance_semaforo=$_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]["id_seccion_ine_ciudadano_seccion_avance_semaforo"]=$id;
				$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0]))."`";
				$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano_seccion_avance_semaforo"][0])."'";
				$insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos= "INSERT INTO secciones_ine_ciudadanos_secciones_avance_semaforo_historicos ($fields_pdo) VALUES ($values_pdo);";
				$insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos=$conexion->query($insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos || $num=0){
					$success=false;
					echo "ERROR insert_secciones_ine_ciudadanos_secciones_avance_semaforo_historicos"; 
					var_dump($conexion->error);
				}
			}
		}
	}

	if($success & $entra ){
		$log= logUsuario($_COOKIE["id_usuario"],"secciones_ine_ciudadanos_secciones_avance_semaforo",$id,'Update','',$fechaH);
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
