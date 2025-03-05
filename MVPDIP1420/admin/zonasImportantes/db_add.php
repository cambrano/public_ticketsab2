<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('security','zonas_importantes',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}


	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["zona_importante"][0] as $keyPrincipal => $atributo) {
			$_POST["zona_importante"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$success=true;
		$_POST["zona_importante"][0]['fechaR']=$fechaH;

		$_POST["zona_importante"][0]['codigo_plataforma']=$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($_POST["zona_importante"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["zona_importante"][0])."'";
		$insert_zonas_importantes= "INSERT INTO zonas_importantes ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_zonas_importantes=$conexion->query($insert_zonas_importantes);
		$num=$conexion->affected_rows;
		if(!$insert_zonas_importantes || $num=0){
			$success=false;
			echo "ERROR insert_zonas_importantes"; 
			var_dump($conexion->error);
		}
		$id=$_POST["zona_importante"][0]['id_zona_importante']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["zona_importante"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["zona_importante"][0])."'";
		$insert_zonas_importantes_historicos= "INSERT INTO zonas_importantes_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_zonas_importantes_historicos=$conexion->query($insert_zonas_importantes_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_zonas_importantes_historicos || $num=0){
			$success=false;
			echo "ERROR insert_zonas_importantes_historicos"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'zonas_importantes',$id,'Insert','',$fechaH);
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
