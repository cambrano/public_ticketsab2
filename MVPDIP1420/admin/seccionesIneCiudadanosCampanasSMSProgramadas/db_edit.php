<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_campanas_sms_programadas.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_ciudadanos_campanas_sms_programadas",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_ciudadano_campana_sms_programada"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano_campana_sms_programada"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	if( registrosCompara("secciones_ine_ciudadanos_campanas_sms_programadas",$_POST["seccion_ine_ciudadano_campana_sms_programada"][0],1)){
		if(!empty($_POST)){ 
			$success=true;
			foreach($_POST["seccion_ine_ciudadano_campana_sms_programada"] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_secciones_ine_ciudadanos_campanas_sms_programadas = "UPDATE secciones_ine_ciudadanos_campanas_sms_programadas SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_secciones_ine_ciudadanos_campanas_sms_programadas=$conexion->query($update_secciones_ine_ciudadanos_campanas_sms_programadas);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_ciudadanos_campanas_sms_programadas || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_ciudadanos_campanas_sms_programadas"; 
				var_dump($conexion->error);
			}


			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"secciones_ine_ciudadanos_campanas_sms_programadas",$id,'Update','',$fechaH);
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
