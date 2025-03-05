<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/switch_operaciones.php";
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['evaluacion']==false){
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


		$delete_secciones_ine_ciudadanos_programas_apoyos = "DELETE FROM secciones_ine_ciudadanos_programas_apoyos  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_secciones_ine_ciudadanos_programas_apoyos=$conexion->query($delete_secciones_ine_ciudadanos_programas_apoyos);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_ciudadanos_programas_apoyos || $num=0){
			$success=false;
			echo "ERROR delete Ciudadano Programa Apoyo"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_programas_apoyos',$id,'Delete','',$fechaH);
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
