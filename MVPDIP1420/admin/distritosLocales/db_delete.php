<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','distritos_locales',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$id=$_POST['id'];
		$success=true;
		//$delete_usuarios = "DELETE FROM usuarios  WHERE  id_empleado='$id' AND id<>0 ";
		$delete_distritos_locales = "DELETE FROM distritos_locales  WHERE  id='$id' ";

		$delete_distritos_locales_parametros = "DELETE FROM distritos_locales_parametros  WHERE id<>0 AND id_distrito_local='$id' ";

		$conexion->autocommit(FALSE);

		/*
		$delete_usuarios=$conexion->query($delete_usuarios);
		$num=$conexion->affected_rows;
		if(!$delete_usuarios || $num=0){
			$success=false;
			echo "ERROR delete_usuarios"; 
			var_dump($conexion->error);
		}
		*/

		$delete_distritos_locales_parametros=$conexion->query($delete_distritos_locales_parametros);
		$num=$conexion->affected_rows;
		if(!$delete_distritos_locales_parametros || $num=0){
			$success=false;
			echo "ERROR delete_distritos_locales_parametros"; 
			var_dump($conexion->error);
		}

		$delete_distritos_locales=$conexion->query($delete_distritos_locales);
		$num=$conexion->affected_rows;
		if(!$delete_distritos_locales || $num=0){
			$success=false;
			echo "ERROR delete_distritos_locales"; 
			var_dump($conexion->error);
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'distritos_locales',$id,'Delete','',$fechaH);
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
