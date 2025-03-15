<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','programas_apoyos',$_COOKIE["id_usuario"]);
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

		$delete_programa_apoyos_territorios = "DELETE FROM programas_apoyos_territorios  WHERE  id_programa_apoyo='$id' AND id<>0 ";
		$delete_programa_apoyos_territorios=$conexion->query($delete_programa_apoyos_territorios);
		$num=$conexion->affected_rows;
		if(!$delete_programa_apoyos_territorios || $num=0){
			$success=false;
			echo "ERROR delete territorio programa apoyo"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_programa_apoyos_dependencias = "DELETE FROM programas_apoyos_dependencias  WHERE  id_programa_apoyo='$id' AND id<>0 ";
		$delete_programa_apoyos_dependencias=$conexion->query($delete_programa_apoyos_dependencias);
		$num=$conexion->affected_rows;
		if(!$delete_programa_apoyos_dependencias || $num=0){
			$success=false;
			echo "ERROR delete dependencia programa apoyo"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_programa_apoyos_categorias = "DELETE FROM programas_apoyos_categorias  WHERE  id_programa_apoyo='$id' AND id<>0 ";
		$delete_programa_apoyos_categorias=$conexion->query($delete_programa_apoyos_categorias);
		$num=$conexion->affected_rows;
		if(!$delete_programa_apoyos_categorias || $num=0){
			$success=false;
			echo "ERROR delete categoria programa apoyo"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		$delete_programas_apoyos = "DELETE FROM programas_apoyos  WHERE  id='{$id}' ";
		$delete_programas_apoyos=$conexion->query($delete_programas_apoyos);
		$num=$conexion->affected_rows;
		if(!$delete_programas_apoyos || $num=0){
			$success=false;
			echo "ERROR delete tipo territorio"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'programas_apoyos',$id,'Delete','',$fechaH);
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
			echo "";
			$conexion->rollback();
			$conexion->close();
		}
		 
	}
