<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/casillas_votos_2018.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/claves.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2018',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["casilla_voto_2018"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["casilla_voto_2018"][0] as $keyPrincipal => $atributo) {
			$_POST["casilla_voto_2018"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$casilla_voto_2018ClaveVerificacion=casilla_voto_2018ClaveVerificacion($_POST["casilla_voto_2018"][0]['clave'],'',1);
		if($casilla_voto_2018ClaveVerificacion){
			$claveF= clave('casillas_votos_2018');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["casilla_voto_2018"][0]['clave'] = $claveF['clave'];
			}
		}

		$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2018"][0]['id_seccion_ine'] );
		$_POST["casilla_voto_2018"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
		$_POST["casilla_voto_2018"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
		$_POST["casilla_voto_2018"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
		$_POST["casilla_voto_2018"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["casilla_voto_2018"][0]['fechaR']=$fechaH; 
		$_POST["casilla_voto_2018"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2018'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2018'][0])."'";
		$inset_casillas_votos_2018= "INSERT INTO casillas_votos_2018 ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2018=$conexion->query($inset_casillas_votos_2018);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2018 || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2018"; 
			var_dump($conexion->error);
		}

		$id_casilla_voto_2018 = $id=$_POST['casilla_voto_2018'][0]['id_casilla_voto_2018']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2018'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2018'][0])."'";
		$inset_casillas_votos_2018_historicos= "INSERT INTO casillas_votos_2018_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2018_historicos=$conexion->query($inset_casillas_votos_2018_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2018_historicos || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2018_historicos"; 
			var_dump($conexion->error);
		}

		foreach ($_POST['votos_partidos_2018'] as $key => $value) {
			include __DIR__."/../functions/genid.php";
			unset($votos_partidos_2018);
			$votos_partidos_2018['clave'] = $cod32;
			$votos_partidos_2018['id_seccion_ine'] = $_POST["casilla_voto_2018"][0]['id_seccion_ine'];
			$votos_partidos_2018['id_casilla_voto_2018'] = $id_casilla_voto_2018;
			$votos_partidos_2018['id_partido_2018'] = $value['id_partido_2018'];
			$votos_partidos_2018['votos'] = $value['votos'];
			$votos_partidos_2018['codigo_plataforma'] = $codigo_plataforma;
			$votos_partidos_2018['fechaR'] = $fechaH;

			$votos_partidos_2018['tipo'] = $_POST["casilla_voto_2018"][0]['tipo'];

			$votos_partidos_2018['id_municipio'] = $_POST["casilla_voto_2018"][0]['id_municipio'];
			$votos_partidos_2018['id_distrito_local'] = $_POST["casilla_voto_2018"][0]['id_distrito_local'];
			$votos_partidos_2018['id_distrito_federal'] = $_POST["casilla_voto_2018"][0]['id_distrito_federal'];
			$votos_partidos_2018['id_cuartel'] = $_POST["casilla_voto_2018"][0]['id_cuartel'];

			//insertamos
			$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2018))."`";
			$values_pdo = "'".implode("','", $votos_partidos_2018)."'";
			$inset_casillas_votos_2018_partido= "INSERT INTO casillas_votos_partidos_2018 ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2018_partido=$conexion->query($inset_casillas_votos_2018_partido);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2018_partido || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2018_partido"; 
				var_dump($conexion->error);
			}
			$votos_partidos_2018['id_casilla_voto_partido_2018'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2018))."`";
			$values_pdo = "'".implode("','", $votos_partidos_2018)."'";
			$inset_casillas_votos_2018_partido_historicos= "INSERT INTO casillas_votos_partidos_2018_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2018_partido_historicos=$conexion->query($inset_casillas_votos_2018_partido_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2018_partido_historicos || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2018_partido_historicos"; 
				var_dump($conexion->error);
			}
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2018',$id,'Insert','',$fechaH);
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