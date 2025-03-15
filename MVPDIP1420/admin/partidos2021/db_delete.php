<?php
	include "../functions/security.php";
	include "../functions/timemex.php"; 
	include "../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','partidos_2021',$_COOKIE["id_usuario"]);
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


		$delete_partidos_2021 = "DELETE FROM partidos_2021  WHERE  id='{$id}' ";
		$delete_partidos_2021=$conexion->query($delete_partidos_2021);
		$num=$conexion->affected_rows;
		if(!$delete_partidos_2021 || $num=0){
			$success=false;
			echo "ERROR delete partido 2021"; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'partidos_2021',$id,'Delete','',$fechaH);
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
