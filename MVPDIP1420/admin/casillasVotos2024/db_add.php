<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/casillas_votos_2024.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/claves.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2024',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["casilla_voto_2024"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["casilla_voto_2024"][0] as $keyPrincipal => $atributo) {
			$_POST["casilla_voto_2024"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$casilla_voto_2024ClaveVerificacion=casilla_voto_2024ClaveVerificacion($_POST["casilla_voto_2024"][0]['clave'],'',1);
		if($casilla_voto_2024ClaveVerificacion){
			$claveF= clave('casillas_votos_2024');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["casilla_voto_2024"][0]['clave'] = $claveF['clave'];
			}
		}

		$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2024"][0]['id_seccion_ine'] );
		$_POST["casilla_voto_2024"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
		$_POST["casilla_voto_2024"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
		$_POST["casilla_voto_2024"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
		$_POST["casilla_voto_2024"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["casilla_voto_2024"][0]['fechaR']=$fechaH; 
		$_POST["casilla_voto_2024"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2024'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2024'][0])."'";
		$inset_casillas_votos_2024= "INSERT INTO casillas_votos_2024 ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2024=$conexion->query($inset_casillas_votos_2024);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2024 || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2024"; 
			var_dump($conexion->error);
		}

		$id_casilla_voto_2024 = $id=$_POST['casilla_voto_2024'][0]['id_casilla_voto_2024']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2024'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2024'][0])."'";
		$inset_casillas_votos_2024_historicos= "INSERT INTO casillas_votos_2024_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2024_historicos=$conexion->query($inset_casillas_votos_2024_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2024_historicos || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2024_historicos"; 
			var_dump($conexion->error);
		}

		foreach ($_POST['votos_partidos_2024'] as $key => $value) {
			include __DIR__."/../functions/genid.php";
			unset($votos_partidos_2024);
			$votos_partidos_2024['clave'] = $cod32;
			$votos_partidos_2024['id_seccion_ine'] = $_POST["casilla_voto_2024"][0]['id_seccion_ine'];
			$votos_partidos_2024['id_casilla_voto_2024'] = $id_casilla_voto_2024;
			$votos_partidos_2024['id_partido_2024'] = $value['id_partido_2024'];
			$votos_partidos_2024['votos'] = $value['votos'];
			$votos_partidos_2024['codigo_plataforma'] = $codigo_plataforma;
			$votos_partidos_2024['fechaR'] = $fechaH;

			$votos_partidos_2024['tipo'] = $_POST["casilla_voto_2024"][0]['tipo'];

			$votos_partidos_2024['id_municipio'] = $_POST["casilla_voto_2024"][0]['id_municipio'];
			$votos_partidos_2024['id_distrito_local'] = $_POST["casilla_voto_2024"][0]['id_distrito_local'];
			$votos_partidos_2024['id_distrito_federal'] = $_POST["casilla_voto_2024"][0]['id_distrito_federal'];
			$votos_partidos_2024['id_cuartel'] = $_POST["casilla_voto_2024"][0]['id_cuartel'];

			//insertamos
			$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2024))."`";
			$values_pdo = "'".implode("','", $votos_partidos_2024)."'";
			$inset_casillas_votos_2024_partido= "INSERT INTO casillas_votos_partidos_2024 ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2024_partido=$conexion->query($inset_casillas_votos_2024_partido);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2024_partido || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2024_partido"; 
				var_dump($conexion->error);
			}
			$votos_partidos_2024['id_casilla_voto_partido_2024'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2024))."`";
			$values_pdo = "'".implode("','", $votos_partidos_2024)."'";
			$inset_casillas_votos_2024_partido_historicos= "INSERT INTO casillas_votos_partidos_2024_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2024_partido_historicos=$conexion->query($inset_casillas_votos_2024_partido_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2024_partido_historicos || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2024_partido_historicos"; 
				var_dump($conexion->error);
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2024',$id,'Insert','',$fechaH);
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