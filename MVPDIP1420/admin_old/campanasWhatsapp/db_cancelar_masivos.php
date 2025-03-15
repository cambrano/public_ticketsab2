<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_whatsapp',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$conexion->autocommit(FALSE);
		$id=$_POST['id']; 
		$success=true;

		$update_secciones_ine_ciudadanos_campanas_whatsapp_programadas = "UPDATE secciones_ine_ciudadanos_campanas_whatsapp_programadas SET status=4 WHERE id <>0 AND id_campana_whatsapp=".$id." AND status = 0 ";
			$conexion->autocommit(FALSE);
			$update_secciones_ine_ciudadanos_campanas_whatsapp_programadas=$conexion->query($update_secciones_ine_ciudadanos_campanas_whatsapp_programadas);
			$num=$conexion->affected_rows;
			if(!$update_secciones_ine_ciudadanos_campanas_whatsapp_programadas || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_secciones_ine_ciudadanos_campanas_whatsapp_programadas"; 
				var_dump($conexion->error);
			}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],"secciones_ine_ciudadanos_campanas_whatsapp_programadas",$id,'cancelar_masivos','',$fechaH);
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
