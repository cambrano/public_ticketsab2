<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','servidores_correos',$_COOKIE["id_usuario"]);
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
		$delete_servidores_correos = "DELETE FROM servidores_correos  WHERE  id='$id' ";

		$conexion->autocommit(FALSE);

		$delete_servidores_correos=$conexion->query($delete_servidores_correos);
		$num=$conexion->affected_rows;
		if(!$delete_servidores_correos || $num=0){
			$success=false;
			echo "ERROR delete_servidores_correos"; 
			var_dump($conexion->error);
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'servidores_correos',$id,'Delete','',$fechaH);
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