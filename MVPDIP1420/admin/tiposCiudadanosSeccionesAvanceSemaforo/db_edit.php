<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/tipos_ciudadanos_secciones_avance_semaforo.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"tipos_ciudadanos_secciones_avance_semaforo",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	$tipo_ciudadano_seccion_avance_semaforoDatos = tipo_ciudadano_seccion_avance_semaforoDatos('',$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['id_seccion_ine'],$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['id_tipo_ciudadano']);
	if (!empty($tipo_ciudadano_seccion_avance_semaforoDatos['id']) && $tipo_ciudadano_seccion_avance_semaforoDatos['id'] != $_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['id']) {
		echo "Ya existe ese tipo de ciudadano en esta sección.";
		die;
	}
	
	//metemos los valores para que se no tengamos error
	foreach($_POST["tipo_ciudadano_seccion_avance_semaforo"][0] as $keyPrincipal => $atributo) {
		$_POST["tipo_ciudadano_seccion_avance_semaforo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	if( registrosCompara("tipos_ciudadanos_secciones_avance_semaforo",$_POST["tipo_ciudadano_seccion_avance_semaforo"][0],1)){
		if(!empty($_POST)){ 
			$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]["fechaR"]=$fechaH;
			$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]["codigo_plataforma"]=$codigo_plataforma;
			$success=true;
			foreach($_POST["tipo_ciudadano_seccion_avance_semaforo"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_tipos_ciudadanos_secciones_avance_semaforo = "UPDATE tipos_ciudadanos_secciones_avance_semaforo SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_tipos_ciudadanos_secciones_avance_semaforo=$conexion->query($update_tipos_ciudadanos_secciones_avance_semaforo);
			$num=$conexion->affected_rows;
			if(!$update_tipos_ciudadanos_secciones_avance_semaforo || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_tipos_ciudadanos_secciones_avance_semaforo"; 
				var_dump($conexion->error);
			}


			unset($_POST["tipo_ciudadano_seccion_avance_semaforo"][0]['id']); 
			$id_tipo_ciudadano_seccion_avance_semaforo=$_POST["tipo_ciudadano_seccion_avance_semaforo"][0]["id_tipo_ciudadano_seccion_avance_semaforo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["tipo_ciudadano_seccion_avance_semaforo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["tipo_ciudadano_seccion_avance_semaforo"][0])."'";
			$insert_tipos_ciudadanos_secciones_avance_semaforo_historicos= "INSERT INTO tipos_ciudadanos_secciones_avance_semaforo_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_tipos_ciudadanos_secciones_avance_semaforo_historicos=$conexion->query($insert_tipos_ciudadanos_secciones_avance_semaforo_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_tipos_ciudadanos_secciones_avance_semaforo_historicos || $num=0){
				$success=false;
				echo "ERROR insert_tipos_ciudadanos_secciones_avance_semaforo_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"tipos_ciudadanos_secciones_avance_semaforo",$id_tipo_ciudadano_seccion_avance_semaforo,'Update','',$fechaH);
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
	}
