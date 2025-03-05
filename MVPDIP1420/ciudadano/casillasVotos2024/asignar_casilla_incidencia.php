<?php
	include __DIR__."/../functions/security.php";
	if(!empty($_POST)){

		include __DIR__."/../functions/timemex.php";
		include __DIR__."/../functions/log_usuarios.php";

		foreach($_POST["casilla_voto_incidencia"][0] as $keyPrincipal => $atributo) {
			$_POST["casilla_voto_incidencia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		

		$_POST['casilla_voto_incidencia'][0]['id_casilla_voto_2024'];
		$_POST['casilla_voto_incidencia'][0]['status'];
		$_POST["casilla_voto_incidencia"][0]['fechaR'] = $fechaH;
		$_POST["casilla_voto_incidencia"][0]['tipo'] = '0';
		$_POST["casilla_voto_incidencia"][0]['fecha_hora'] = $fechaH;
		$_POST["casilla_voto_incidencia"][0]['fecha'] = $fechaSF;
		$_POST["casilla_voto_incidencia"][0]['hora'] = $fechaSH;
		$_POST["casilla_voto_incidencia"][0]['codigo_plataforma']=$codigo_plataforma; 

		$success=true;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["casilla_voto_incidencia"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["casilla_voto_incidencia"][0])."'";
		$insert_casillas_votos_2024= "INSERT INTO casillas_votos_2024_incidencias ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$insert_casillas_votos_2024=$conexion->query($insert_casillas_votos_2024);
		$num=$conexion->affected_rows;
		if(!$insert_casillas_votos_2024 || $num=0){
			$success=false;
			echo "ERROR insert_casillas_votos_2024_incidencias"; 
			var_dump($conexion->error);
		}
		$id_casilla_voto_2024_incidencia = $id=$_POST["casilla_voto_incidencia"][0]['id_casilla_voto_2024_incidencia']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["casilla_voto_incidencia"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["casilla_voto_incidencia"][0])."'";
		$insert_casillas_votos_2024_historicos= "INSERT INTO casillas_votos_2024_incidencias_historicos ($fields_pdo) VALUES ($values_pdo);";
		$insert_casillas_votos_2024_historicos=$conexion->query($insert_casillas_votos_2024_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_casillas_votos_2024_historicos || $num=0){
			$success=false;
			echo "ERROR insert_casillas_votos_2024_incidencias_historicos"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2024_incidencias',$id,'Insert','',$fechaH);
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