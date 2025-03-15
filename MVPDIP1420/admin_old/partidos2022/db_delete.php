<?php
	include "../functions/security.php";
	include "../functions/timemex.php"; 
	include "../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','partidos_',$_COOKIE["id_usuario"]);
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


		$delete_partidos_ = "DELETE FROM partidos_  WHERE  id='{$id}' ";
		$delete_partidos_=$conexion->query($delete_partidos_);
		$num=$conexion->affected_rows;
		if(!$delete_partidos_ || $num=0){
			$success=false;
			echo "ERROR delete partido "; 
			echo "<br>";
			echo("Errorcode: " . mysqli_errno($conexion));
			echo "<br>";
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'partidos_',$id,'Delete','',$fechaH);
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
