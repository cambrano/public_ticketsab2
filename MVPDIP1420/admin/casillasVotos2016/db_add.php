<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/casillas_votos_2016.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/claves.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2016',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["casilla_voto_2016"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["casilla_voto_2016"][0] as $keyPrincipal => $atributo) {
			$_POST["casilla_voto_2016"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$casilla_voto_2016ClaveVerificacion=casilla_voto_2016ClaveVerificacion($_POST["casilla_voto_2016"][0]['clave'],'',1);
		if($casilla_voto_2016ClaveVerificacion){
			$claveF= clave('casillas_votos_2016');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["casilla_voto_2016"][0]['clave'] = $claveF['clave'];
			}
		}

		$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2016"][0]['id_seccion_ine'] );
		$_POST["casilla_voto_2016"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
		$_POST["casilla_voto_2016"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
		$_POST["casilla_voto_2016"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
		$_POST["casilla_voto_2016"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["casilla_voto_2016"][0]['fechaR']=$fechaH; 
		$_POST["casilla_voto_2016"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2016'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2016'][0])."'";
		$inset_casillas_votos_2016= "INSERT INTO casillas_votos_2016 ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2016=$conexion->query($inset_casillas_votos_2016);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2016 || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2016"; 
			var_dump($conexion->error);
		}

		$id_casilla_voto_2016 = $id=$_POST['casilla_voto_2016'][0]['id_casilla_voto_2016']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2016'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2016'][0])."'";
		$inset_casillas_votos_2016_historicos= "INSERT INTO casillas_votos_2016_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2016_historicos=$conexion->query($inset_casillas_votos_2016_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2016_historicos || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2016_historicos"; 
			var_dump($conexion->error);
		}

		foreach ($_POST['votos_partidos_2016'] as $key => $value) {
			include __DIR__."/../functions/genid.php";
			unset($votos_partidos_2016);
			$votos_partidos_2016['clave'] = $cod32;
			$votos_partidos_2016['id_seccion_ine'] = $_POST["casilla_voto_2016"][0]['id_seccion_ine'];
			$votos_partidos_2016['id_casilla_voto_2016'] = $id_casilla_voto_2016;
			$votos_partidos_2016['id_partido_2016'] = $value['id_partido_2016'];
			$votos_partidos_2016['votos'] = $value['votos'];
			$votos_partidos_2016['codigo_plataforma'] = $codigo_plataforma;
			$votos_partidos_2016['fechaR'] = $fechaH;

			$votos_partidos_2016['tipo'] = $_POST["casilla_voto_2016"][0]['tipo'];

			$votos_partidos_2016['id_municipio'] = $_POST["casilla_voto_2016"][0]['id_municipio'];
			$votos_partidos_2016['id_distrito_local'] = $_POST["casilla_voto_2016"][0]['id_distrito_local'];
			$votos_partidos_2016['id_distrito_federal'] = $_POST["casilla_voto_2016"][0]['id_distrito_federal'];
			$votos_partidos_2016['id_cuartel'] = $_POST["casilla_voto_2016"][0]['id_cuartel'];

			//insertamos
			$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2016))."`";
			$values_pdo = "'".implode("','", $votos_partidos_2016)."'";
			$inset_casillas_votos_2016_partido= "INSERT INTO casillas_votos_partidos_2016 ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2016_partido=$conexion->query($inset_casillas_votos_2016_partido);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2016_partido || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2016_partido"; 
				var_dump($conexion->error);
			}
			$votos_partidos_2016['id_casilla_voto_partido_2016'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2016))."`";
			$values_pdo = "'".implode("','", $votos_partidos_2016)."'";
			$inset_casillas_votos_2016_partido_historicos= "INSERT INTO casillas_votos_partidos_2016_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2016_partido_historicos=$conexion->query($inset_casillas_votos_2016_partido_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2016_partido_historicos || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2016_partido_historicos"; 
				var_dump($conexion->error);
			}
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2016',$id,'Insert','',$fechaH);
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