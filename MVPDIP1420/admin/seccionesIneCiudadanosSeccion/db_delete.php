<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST as $keyPrincipal => $atributo) {
			$_POST[$keyPrincipal]= trim(mysqli_real_escape_string($conexion,$atributo));
		}
		$id=$_POST['id'];
		$success=true;
		//$delete_usuarios = "DELETE FROM usuarios  WHERE  id_empleado='$id' AND id<>0 ";
		$conexion->autocommit(FALSE);
		$delete_seccion_ine_ciudadano = "DELETE FROM secciones_ine_ciudadanos_check_2021  WHERE  id_seccion_ine_ciudadano='$id' AND id<>0 ";
		$delete_seccion_ine_ciudadano=$conexion->query($delete_seccion_ine_ciudadano);
		$num=$conexion->affected_rows;
		if(!$delete_seccion_ine_ciudadano || $num=0){
			$success=false;
			echo "ERROR delete ciudadano check 2021"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}


		$delete_seccion_ine_ciudadano = "DELETE FROM secciones_ine_ciudadanos_seguimientos  WHERE  id_seccion_ine_ciudadano='$id' AND id<>0 ";
		$delete_seccion_ine_ciudadano=$conexion->query($delete_seccion_ine_ciudadano);
		$num=$conexion->affected_rows;
		if(!$delete_seccion_ine_ciudadano || $num=0){
			$success=false;
			echo "ERROR delete ciudadano seguimientos"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_seccion_ine_ciudadano = "DELETE FROM secciones_ine_ciudadanos_permisos  WHERE  id_seccion_ine_ciudadano='$id' AND id<>0 ";
		$delete_seccion_ine_ciudadano=$conexion->query($delete_seccion_ine_ciudadano);
		$num=$conexion->affected_rows;
		if(!$delete_seccion_ine_ciudadano || $num=0){
			$success=false;
			echo "ERROR delete ciudadano permisos"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_seccion_ine_ciudadano = "DELETE FROM secciones_ine_ciudadanos_categorias  WHERE  id_seccion_ine_ciudadano='$id' AND id<>0 ";
		$delete_seccion_ine_ciudadano=$conexion->query($delete_seccion_ine_ciudadano);
		$num=$conexion->affected_rows;
		if(!$delete_seccion_ine_ciudadano || $num=0){
			$success=false;
			echo "ERROR delete ciudadano categorías"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_seccion_ine_ciudadano = "DELETE FROM usuarios  WHERE  id_seccion_ine_ciudadano='$id' AND id<>0 ";
		$delete_seccion_ine_ciudadano=$conexion->query($delete_seccion_ine_ciudadano);
		$num=$conexion->affected_rows;
		if(!$delete_seccion_ine_ciudadano || $num=0){
			$success=false;
			echo "ERROR delete usuario"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_seccion_ine_ciudadano = "DELETE FROM secciones_ine_ciudadanos  WHERE  id='$id' ";
		$delete_seccion_ine_ciudadano=$conexion->query($delete_seccion_ine_ciudadano);
		$num=$conexion->affected_rows;
		if(!$delete_seccion_ine_ciudadano || $num=0){
			$success=false;
			echo "ERROR delete ciudadano"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos',$id,'Delete','',$fechaH);
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
