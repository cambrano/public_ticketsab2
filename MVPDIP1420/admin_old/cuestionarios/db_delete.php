<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/cuestionarios_images.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
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

		$delete_cuestionario = "DELETE FROM cuestionarios_respuestas  WHERE  id_cuestionario='$id' AND id<>0 ";
		$conexion->autocommit(FALSE);
		$delete_cuestionario=$conexion->query($delete_cuestionario);
		$num=$conexion->affected_rows;
		if(!$delete_cuestionario || $num=0){
			$success=false;
			echo "ERROR delete cuestionarios"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_cuestionario = "DELETE FROM cuestionarios  WHERE  id='$id' ";
		$delete_cuestionario=$conexion->query($delete_cuestionario);
		$num=$conexion->affected_rows;
		if(!$delete_cuestionario || $num=0){
			$success=false;
			echo "ERROR delete cuestionario"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
		

		if($success){
			foreach ($delete_images_files as $key => $value) {
				unlink($value['file']);
			}
			$log= logUsuario($_COOKIE["id_usuario"],'cuestionarios',$id,'Delete','',$fechaH);
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
