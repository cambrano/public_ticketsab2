<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','redes_sociales',$_COOKIE["id_usuario"]);
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
		$delete_redes_sociales = "DELETE FROM redes_sociales  WHERE  id='$id' ";

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
		$delete_redes_sociales=$conexion->query($delete_redes_sociales);
		$num=$conexion->affected_rows;
		if(!$delete_redes_sociales || $num=0){
			$success=false;
			echo "ERROR delete_redes_sociales"; 
			var_dump($conexion->error);
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'redes_sociales',$id,'Delete','',$fechaH);
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
