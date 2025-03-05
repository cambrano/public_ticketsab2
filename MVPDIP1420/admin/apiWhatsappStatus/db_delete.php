<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_whatsapp_status',$_COOKIE["id_usuario"]);
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


		$delete_api_whatsapp_status = "DELETE FROM api_whatsapp_status  WHERE  id='{$id}' ";
		$delete_api_whatsapp_status=$conexion->query($delete_api_whatsapp_status);
		$num=$conexion->affected_rows;
		if(!$delete_api_whatsapp_status || $num=0){
			$success=false;
			echo "ERROR delete_api_whatsapp_status"; 
			var_dump($conexion->error);
		}
		
		if($success){
			unlink($file);
			if (file_exists($file)) {
				$success=false;
				echo "Error Eliminar Imagen";
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'api_whatsapp_status',$id,'Delete','',$fechaH);
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
