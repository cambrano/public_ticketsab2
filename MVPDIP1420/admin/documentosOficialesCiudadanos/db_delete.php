<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/documentos_oficiales_images.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','documentos_oficiales',$_COOKIE["id_usuario"]);
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


		$documento_oficial_imagesDatos=documento_oficial_imagesDatos('',$id);
		foreach ($documento_oficial_imagesDatos as $key => $value) {
			$id_img=$value['id'];
			$delete_file = "DELETE FROM documentos_oficiales_images  WHERE  id='$id_img' ";
			$conexion->autocommit(FALSE);
			$delete_file=$conexion->query($delete_file);
			$num=$conexion->affected_rows;
			if(!$delete_file || $num=0){
				$success=false;
				echo "ERROR delete_file"; 
				var_dump($conexion->error);
			}
			$delete_images_files[]['file']= __DIR__.'/'.$value['file'];
			
		}

		$delete_documento_oficial = "DELETE FROM documentos_oficiales  WHERE  id='$id' ";

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
		$delete_documento_oficial=$conexion->query($delete_documento_oficial);
		$num=$conexion->affected_rows;
		if(!$delete_documento_oficial || $num=0){
			$success=false;
			echo "ERROR delete_documento_oficial"; 
			var_dump($conexion->error);
		}

		if($success){
			foreach ($delete_images_files as $key => $value) {
				unlink($value['file']);
			}
			$log= logUsuario($_COOKIE["id_usuario"],'documentos_oficiales_ciudadanos',$id,'Delete','',$fechaH);
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
