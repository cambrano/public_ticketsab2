<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_giras',$_COOKIE["id_usuario"]);
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


		$delete_secciones_ine_giras = "DELETE FROM secciones_ine_giras  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);

		$delete_secciones_ine_giras_puntos = "DELETE FROM secciones_ine_giras_puntos  WHERE  id_seccion_ine_gira='$id' AND id<>0 ";
		$delete_secciones_ine_giras_puntos=$conexion->query($delete_secciones_ine_giras_puntos);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_giras_puntos || $num=0){
			$success=false;
			echo "ERROR delete secciones_ine_giras_puuntos"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		/*
		$delete_usuarios=$conexion->query($delete_usuarios);
		$num=$conexion->affected_rows;
		if(!$delete_usuarios || $num=0){
			$success=false;
			echo "ERROR delete_usuarios"; 
			var_dump($conexion->error);
		}
		*/
		$delete_secciones_ine_giras=$conexion->query($delete_secciones_ine_giras);
		$num=$conexion->affected_rows;
		if(!$delete_secciones_ine_giras || $num=0){
			$success=false;
			echo "ERROR delete secciones_ine_giras"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_giras',$id,'Delete','',$fechaH);
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
