<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campanas_sms_programadas',$_COOKIE["id_usuario"]);
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

		$delete_secciones_ine_ciudadanos_campanas_sms_programadas = "DELETE FROM secciones_ine_ciudadanos_campanas_sms_programadas  WHERE  id='{$id}' ";
		$delete_secciones_ine_ciudadanos_campanas_sms_programadas=$conexion->query($delete_secciones_ine_ciudadanos_campanas_sms_programadas);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_ciudadanos_campanas_sms_programadas || $num=0){
			$success=false;
			echo "ERROR delete tipo casilla"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_campanas_sms_programadas',$id,'Delete','',$fechaH);
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
			echo "";
			$conexion->rollback();
			$conexion->close();
		}
		 
	}
