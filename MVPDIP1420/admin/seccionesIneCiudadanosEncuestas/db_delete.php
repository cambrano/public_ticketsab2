<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_encuestas',$_COOKIE["id_usuario"]);
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


		$delete_secciones_ine_ciudadanos_encuestas_respuestas = "DELETE FROM secciones_ine_ciudadanos_encuestas_respuestas  WHERE  id_seccion_ine_ciudadano_encuesta='$id' AND id<>0 ";
		$conexion->autocommit(FALSE);
		$delete_secciones_ine_ciudadanos_encuestas_respuestas=$conexion->query($delete_secciones_ine_ciudadanos_encuestas_respuestas);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_ciudadanos_encuestas_respuestas || $num=0){
			$success=false;
			echo "ERROR delete Ciudadano respuestas"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_secciones_ine_ciudadanos_encuestas = "DELETE FROM secciones_ine_ciudadanos_encuestas  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_secciones_ine_ciudadanos_encuestas=$conexion->query($delete_secciones_ine_ciudadanos_encuestas);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_ciudadanos_encuestas || $num=0){
			$success=false;
			echo "ERROR delete Ciudadano Encuesta"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos_encuestas',$id,'Delete','',$fechaH);
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
