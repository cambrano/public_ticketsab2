<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	if(
		moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'Delete') ||
		moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'All') ){
	}else{
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
		$delete_secciones_ine = "DELETE FROM secciones_ine  WHERE  id='$id' ";

		$delete_secciones_ine_parametros = "DELETE FROM secciones_ine_parametros  WHERE id<>0 AND id_seccion_ine='$id' ";

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

		$delete_secciones_ine_parametros=$conexion->query($delete_secciones_ine_parametros);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_parametros || $num=0){
			$success=false;
			echo "ERROR delete_secciones_ine_parametros"; 
			var_dump($conexion->error);
		}

		$delete_secciones_ine=$conexion->query($delete_secciones_ine);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine || $num=0){
			$success=false;
			echo "ERROR delete_secciones_ine"; 
			var_dump($conexion->error);
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine',$id,'Delete','',$fechaH);
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
