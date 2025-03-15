<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
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
		$conexion->autocommit(FALSE);
		$id=$_POST['id']; 
		$success=true;

		$delete_cuestionarios_respuestas = "DELETE FROM cuestionarios_respuestas  WHERE  id_encuesta='{$id}' AND id<>0 ";
		$delete_cuestionarios_respuestas=$conexion->query($delete_cuestionarios_respuestas);
		$num=$conexion->affected_rows;
		if(!$delete_cuestionarios_respuestas || $num=0){
			$success=false;
			echo "ERROR delete cuestionarios"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_cuestionarios = "DELETE FROM cuestionarios  WHERE  id_encuesta='{$id}' AND id<>0 ";
		$delete_cuestionarios=$conexion->query($delete_cuestionarios);
		$num=$conexion->affected_rows;
		if(!$delete_cuestionarios || $num=0){
			$success=false;
			echo "ERROR delete cuestionarios respuestas"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}


		$delete_encuestas = "DELETE FROM encuestas  WHERE  id='{$id}' ";
		$delete_encuestas=$conexion->query($delete_encuestas);
		$num=$conexion->affected_rows;
		if(!$delete_encuestas || $num=0){
			$success=false;
			echo "ERROR delete encuesta"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'encuestas',$id,'Delete','',$fechaH);
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
