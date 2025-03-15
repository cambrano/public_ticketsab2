<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2024',$_COOKIE["id_usuario"]);
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


		$delete_casillas_votos_2024_status = "DELETE FROM casillas_votos_2024_status  WHERE  id='$id' ";
		$conexion->autocommit(FALSE);
		$delete_casillas_votos_2024_status=$conexion->query($delete_casillas_votos_2024_status);
		$num=$conexion->affected_rows;
		if(!$delete_casillas_votos_2024_status || $num=0){
			$success=false;
			echo "ERROR delete Status Casilla"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
		

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2024_status',$id,'Delete','',$fechaH);
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
