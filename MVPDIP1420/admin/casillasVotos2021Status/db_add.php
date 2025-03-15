<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2021',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["casilla_voto_2021_status"][0] as $keyPrincipal => $atributo) {
		$_POST["casilla_voto_2021_status"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	if(!empty($_POST)){
		$_POST["casilla_voto_2021_status"][0]['fechaR'] = $fechaH;
		$_POST["casilla_voto_2021_status"][0]['tipo'] = '1';
		$_POST["casilla_voto_2021_status"][0]['fecha_hora'] = $_POST["casilla_voto_2021_status"][0]['fecha']." ".$_POST["casilla_voto_2021_status"][0]['hora'];
		$_POST["casilla_voto_2021_status"][0]['codigo_plataforma']=$codigo_plataforma; 
		$success=true;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["casilla_voto_2021_status"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["casilla_voto_2021_status"][0])."'";
		$insert_casillas_votos_2021= "INSERT INTO casillas_votos_2021_status ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_casillas_votos_2021=$conexion->query($insert_casillas_votos_2021);
		$num=$conexion->affected_rows;
		if(!$insert_casillas_votos_2021 || $num=0){
			$success=false;
			echo "ERROR insert_casillas_votos_2021_status"; 
			var_dump($conexion->error);
		}
		$id_casilla_voto_2021_status = $id=$_POST["casilla_voto_2021_status"][0]['id_casilla_voto_2021_status']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["casilla_voto_2021_status"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["casilla_voto_2021_status"][0])."'";
		$insert_casillas_votos_2021_historicos= "INSERT INTO casillas_votos_2021_status_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_casillas_votos_2021_historicos=$conexion->query($insert_casillas_votos_2021_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_casillas_votos_2021_historicos || $num=0){
			$success=false;
			echo "ERROR insert_casillas_votos_2021_status_historicos"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2021_status',$id,'Insert','',$fechaH);
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
