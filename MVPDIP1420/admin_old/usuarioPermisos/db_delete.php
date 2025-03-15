<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_permisos',$_COOKIE["id_usuario"]);
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
		$delete_usuarios_permisos = "DELETE FROM usuarios_modulos  WHERE  id='$id' ";
		$delete_usuarios_modulos = "DELETE FROM usuarios_permisos  WHERE  id_usuario_modulo='$id' AND id<>0 ";
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
		$delete_usuarios_modulos=$conexion->query($delete_usuarios_modulos);
		$num=$conexion->affected_rows;
		if(!$delete_usuarios_modulos || $num=0){
			$success=false;
			echo "ERROR delete_usuarios_modulos"; 
			var_dump($conexion->error);
		}
		$delete_usuarios_permisos=$conexion->query($delete_usuarios_permisos);
		$num=$conexion->affected_rows;
		if(!$delete_usuarios_permisos || $num=0){
			$success=false;
			echo "ERROR delete_usuarios_permisos"; 
			var_dump($conexion->error);
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'empleados_modulos',$id,'Delete','',$fechaH);
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
