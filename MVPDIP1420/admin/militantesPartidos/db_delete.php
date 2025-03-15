<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/militantes_partidos.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
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
		$militante_partidoDatos = militante_partidoDatos($id);
		$success=true;
		

		$delete_militantes_partidos = "DELETE FROM militantes_partidos  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_militantes_partidos=$conexion->query($delete_militantes_partidos);
		$num=$conexion->affected_rows;
		if(!$delete_militantes_partidos || $num=0){
			$success=false;
			echo "ERROR delete Ciudadano Partido Legado"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}

		if($militante_partidoDatos['name'] != ""){
			unlink($militante_partidoDatos['file']);
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'militantes_partidos',$id,'Delete','',$fechaH);
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
