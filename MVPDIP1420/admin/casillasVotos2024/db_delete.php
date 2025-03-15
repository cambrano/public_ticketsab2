<?php
	
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include "../functions/casillas_votos_2024.php";
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
		$conexion->autocommit(FALSE);
		$id=$_POST['id']; 
		$success=true;

		$delete_casillas_votos_partidos_2024 = "DELETE FROM casillas_votos_partidos_2024  WHERE id<>0 AND id_casilla_voto_2024='{$id}' ";
		$delete_casillas_votos_partidos_2024=$conexion->query($delete_casillas_votos_partidos_2024);
		$num=$conexion->affected_rows;
		if(!$delete_casillas_votos_partidos_2024 || $num=0){
			$success=false;
			echo "ERROR delete casilla voto partidos 2024"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}


		$delete_casillas_votos_2024 = "DELETE FROM casillas_votos_2024  WHERE  id='{$id}' ";
		$delete_casillas_votos_2024=$conexion->query($delete_casillas_votos_2024);
		if(!$delete_casillas_votos_2024 || $num=0){
			$success=false;
			echo "ERROR delete casilla voto 2024"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}
		
	 

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2024',$id,'Delete','',$fechaH);
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
