<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/casillas_votos_2022_revocacion_mandato.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/claves.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["casilla_voto_2022_revocacion_mandato"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["casilla_voto_2022_revocacion_mandato"][0] as $keyPrincipal => $atributo) {
			$_POST["casilla_voto_2022_revocacion_mandato"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		$casilla_voto_2022_revocacion_mandatoClaveVerificacion=casilla_voto_2022_revocacion_mandatoClaveVerificacion($_POST["casilla_voto_2022_revocacion_mandato"][0]['clave'],'',1);
		if($casilla_voto_2022_revocacion_mandatoClaveVerificacion){
			$claveF= clave('casillas_votos_2022_revocacion_mandato');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["casilla_voto_2022_revocacion_mandato"][0]['clave'] = $claveF['clave'];
			}
		}

		$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2022_revocacion_mandato"][0]['id_seccion_ine'] );
		$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
		$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
		$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
		$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["casilla_voto_2022_revocacion_mandato"][0]['fechaR']=$fechaH; 
		$_POST["casilla_voto_2022_revocacion_mandato"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2022_revocacion_mandato'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2022_revocacion_mandato'][0])."'";
		$inset_casillas_votos_2022_revocacion_mandato= "INSERT INTO casillas_votos_2022_revocacion_mandato ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2022_revocacion_mandato=$conexion->query($inset_casillas_votos_2022_revocacion_mandato);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2022_revocacion_mandato || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2022_revocacion_mandato"; 
			var_dump($conexion->error);
		}

		$id_casilla_voto_2022_revocacion_mandato = $id=$_POST['casilla_voto_2022_revocacion_mandato'][0]['id_casilla_voto_2022_revocacion_mandato']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2022_revocacion_mandato'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['casilla_voto_2022_revocacion_mandato'][0])."'";
		$inset_casillas_votos_2022_revocacion_mandato_historicos= "INSERT INTO casillas_votos_2022_revocacion_mandato_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_casillas_votos_2022_revocacion_mandato_historicos=$conexion->query($inset_casillas_votos_2022_revocacion_mandato_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_casillas_votos_2022_revocacion_mandato_historicos || $num=0){
			$success=false;
			echo "ERROR inset_casillas_votos_2022_revocacion_mandato_historicos"; 
			var_dump($conexion->error);
		}

		foreach ($_POST['votos_preguntas_2022_revocacion_mandato'] as $key => $value) {
			include __DIR__."/../functions/genid.php";
			unset($votos_preguntas_2022_revocacion_mandato);
			$votos_preguntas_2022_revocacion_mandato['clave'] = $cod32;
			$votos_preguntas_2022_revocacion_mandato['id_seccion_ine'] = $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_seccion_ine'];
			$votos_preguntas_2022_revocacion_mandato['id_casilla_voto_2022_revocacion_mandato'] = $id_casilla_voto_2022_revocacion_mandato;
			$votos_preguntas_2022_revocacion_mandato['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato'];
			$votos_preguntas_2022_revocacion_mandato['votos'] = $value['votos'];
			$votos_preguntas_2022_revocacion_mandato['codigo_plataforma'] = $codigo_plataforma;
			$votos_preguntas_2022_revocacion_mandato['fechaR'] = $fechaH;

			$votos_preguntas_2022_revocacion_mandato['tipo'] = $_POST["casilla_voto_2022_revocacion_mandato"][0]['tipo'];

			$votos_preguntas_2022_revocacion_mandato['id_municipio'] = $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_municipio'];
			$votos_preguntas_2022_revocacion_mandato['id_distrito_local'] = $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_local'];
			$votos_preguntas_2022_revocacion_mandato['id_distrito_federal'] = $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_federal'];
			$votos_preguntas_2022_revocacion_mandato['id_cuartel'] = $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_cuartel'];

			//insertamos
			$fields_pdo = "`".implode('`,`', array_keys($votos_preguntas_2022_revocacion_mandato))."`";
			$values_pdo = "'".implode("','", $votos_preguntas_2022_revocacion_mandato)."'";
			$inset_casillas_votos_2022_revocacion_mandato_partido= "INSERT INTO casillas_preguntas_2022_revocacion_mandato ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2022_revocacion_mandato_partido=$conexion->query($inset_casillas_votos_2022_revocacion_mandato_partido);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2022_revocacion_mandato_partido || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2022_revocacion_mandato_partido"; 
				var_dump($conexion->error);
			}
			$votos_preguntas_2022_revocacion_mandato['id_casilla_pregunta_2022_revocacion_mandato'] = $conexion->insert_id;
			$fields_pdo = "`".implode('`,`', array_keys($votos_preguntas_2022_revocacion_mandato))."`";
			$values_pdo = "'".implode("','", $votos_preguntas_2022_revocacion_mandato)."'";
			$inset_casillas_votos_2022_revocacion_mandato_partido_historicos= "INSERT INTO casillas_preguntas_2022_revocacion_mandato_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_casillas_votos_2022_revocacion_mandato_partido_historicos=$conexion->query($inset_casillas_votos_2022_revocacion_mandato_partido_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_casillas_votos_2022_revocacion_mandato_partido_historicos || $num=0){
				$success=false;
				echo "ERROR inset_casillas_votos_2022_revocacion_mandato_partido_historicos"; 
				var_dump($conexion->error);
			}
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2022_revocacion_mandato',$id,'Insert','',$fechaH);
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