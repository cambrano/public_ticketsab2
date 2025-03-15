<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/casillas_votos_2022_revocacion_mandato.php";
	include __DIR__."/../functions/casillas_preguntas_2022_revocacion_mandato.php";
	include __DIR__."/../functions/preguntas_2022_revocacion_mandato.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"casillas_votos_2022_revocacion_mandato",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2022_revocacion_mandato"][0]['id_seccion_ine'] );
	$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
	$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
	$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
	$_POST["casilla_voto_2022_revocacion_mandato"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];
	foreach($_POST["casilla_voto_2022_revocacion_mandato"][0] as $keyPrincipal => $atributo) {
		$_POST["casilla_voto_2022_revocacion_mandato"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["votos_preguntas_2022_revocacion_mandato"][0] as $keyPrincipal => $atributo) {
		$_POST["votos_preguntas_2022_revocacion_mandato"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$casilla_voto_2022_revocacion_mandatoClaveVerificacion=casilla_voto_2022_revocacion_mandatoClaveVerificacion($_POST["casilla_voto_2022_revocacion_mandato"][0]["clave"],$_POST["casilla_voto_2022_revocacion_mandato"][0]['id'],1);
	if($casilla_voto_2022_revocacion_mandatoClaveVerificacion){
		$claveF= clave("casillas_votos_2022_revocacion_mandato");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["casilla_voto_2022_revocacion_mandato"][0]["clave"] = $claveF["clave"];
		}
	}


	if(!empty($_POST)){ 
		if( registrosCompara("casillas_votos_2022_revocacion_mandato",$_POST['casilla_voto_2022_revocacion_mandato'][0],1)){
			$_POST["casilla_voto_2022_revocacion_mandato"][0]['fechaR']=$fechaH;
			$_POST["casilla_voto_2022_revocacion_mandato"][0]['codigo_plataforma']=$codigo_plataforma;

			$success=true;
			foreach($_POST['casilla_voto_2022_revocacion_mandato'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_casillas_votos_2022_revocacion_mandato = "UPDATE casillas_votos_2022_revocacion_mandato SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_casillas_votos_2022_revocacion_mandato=$conexion->query($update_casillas_votos_2022_revocacion_mandato);
			$num=$conexion->affected_rows;
			if(!$update_casillas_votos_2022_revocacion_mandato || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_casillas_votos_2022_revocacion_mandato"; 
				var_dump($conexion->error);
			}

			unset($_POST["casilla_voto_2022_revocacion_mandato"][0]['id']); 
			$id_casilla_voto_2022_revocacion_mandato=$_POST['casilla_voto_2022_revocacion_mandato'][0]['id_casilla_voto_2022_revocacion_mandato']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2022_revocacion_mandato'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['casilla_voto_2022_revocacion_mandato'][0])."'";
			$insert_casillas_votos_2022_revocacion_mandato_historicos= "INSERT INTO casillas_votos_2022_revocacion_mandato_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_casillas_votos_2022_revocacion_mandato_historicos=$conexion->query($insert_casillas_votos_2022_revocacion_mandato_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_casillas_votos_2022_revocacion_mandato_historicos || $num=0){
				$success=false;
				echo "ERROR insert_casillas_votos_2022_revocacion_mandato_historicos"; 
				var_dump($conexion->error);
			}

			//votos partidos
			$id_casilla_voto_2022_revocacion_mandato = $id;
			$casillas_preguntas_2022_revocacion_mandatoDatos = casillas_preguntas_2022_revocacion_mandatoDatos('',$id_casilla_voto_2022_revocacion_mandato,'');

			$tipo = $_POST["casilla_voto_2022_revocacion_mandato"][0]['tipo'];
			$preguntas_2022_revocacion_mandatoDatos = preguntas_2022_revocacion_mandatoDatos('','',$tipo);
			foreach ($preguntas_2022_revocacion_mandatoDatos as $key => $value) {
				$id_pregunta_2022_revocacion_mandato = $value['id'];

				//buscamos el resultado
				foreach ($_POST['votos_preguntas_2022_revocacion_mandato'] as $key => $value) {
					if($id_pregunta_2022_revocacion_mandato == $value['id_pregunta_2022_revocacion_mandato']){
						$votos = $value['votos'];
					}
				}

				foreach ($casillas_preguntas_2022_revocacion_mandatoDatos as $keyT => $valueT) {
					if( $id_pregunta_2022_revocacion_mandato == $valueT['id_pregunta_2022_revocacion_mandato']){
						$votos_preguntas_2022_revocacion_mandato[] = array(
							'id' => $valueT['id'], 
							'votos' => $votos, 
							'id_pregunta_2022_revocacion_mandato' => $id_pregunta_2022_revocacion_mandato,
							'id_casilla_voto_2022_revocacion_mandato' => $id_casilla_voto_2022_revocacion_mandato,
							'id_seccion_ine' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_seccion_ine'],
							'tipo' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['tipo'],
							'id_municipio' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_municipio'],
							'id_distrito_local' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_local'],
							'id_distrito_federal' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_federal'],
							'id_cuartel' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_cuartel'],
						);
					}
				}
			}


			foreach ($votos_preguntas_2022_revocacion_mandato as $key => $value) {
				if( registrosCompara("casillas_preguntas_2022_revocacion_mandato",$value,1)){
					$entra=1;
					$voto_partido = $value;
					$voto_partido['fechaR']=$fechaH;
					$voto_partido['codigo_plataforma']=$codigo_plataforma;
					unset($valueSets);
					$success=true;
					foreach($voto_partido as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id=$atributos;
						}
					}
					$update_casillas_preguntas_2022_revocacion_mandato = "UPDATE casillas_preguntas_2022_revocacion_mandato SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_casillas_preguntas_2022_revocacion_mandato=$conexion->query($update_casillas_preguntas_2022_revocacion_mandato);
					$num=$conexion->affected_rows;
					if(!$update_casillas_preguntas_2022_revocacion_mandato || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_casillas_preguntas_2022_revocacion_mandato"; 
						var_dump($conexion->error);
					}

					unset($voto_partido['id']);
					$voto_partido['id_casilla_pregunta_2022_revocacion_mandato']=$id;
					$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
					$values_pdo = "'".implode("','", $voto_partido)."'";
					$insert_casillas_preguntas_2022_revocacion_mandato_historicos= "INSERT INTO casillas_preguntas_2022_revocacion_mandato_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_casillas_votos_2022_revocacion_mandato_historicos=$conexion->query($insert_casillas_preguntas_2022_revocacion_mandato_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_casillas_preguntas_2022_revocacion_mandato_historicos || $num=0){
						$success=false;
						echo "ERROR insert_casillas_preguntas_2022_revocacion_mandato_historicos"; 
						var_dump($conexion->error);
					}

				}
			}


			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2022_revocacion_mandato',$id_casilla_voto_2022_revocacion_mandato,'Update','',$fechaH);
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
		}else{
			$id_casilla_voto_2022_revocacion_mandato = $_POST["casilla_voto_2022_revocacion_mandato"][0]['id'];
			$casillas_preguntas_2022_revocacion_mandatoDatos = casillas_preguntas_2022_revocacion_mandatoDatos('',$id_casilla_voto_2022_revocacion_mandato,'');
			$preguntas_2022_revocacion_mandatoDatos = preguntas_2022_revocacion_mandatoDatos();
			foreach ($preguntas_2022_revocacion_mandatoDatos as $key => $value) {
				$id_pregunta_2022_revocacion_mandato = $value['id'];

				//buscamos el resultado
				foreach ($_POST['votos_preguntas_2022_revocacion_mandato'] as $key => $value) {
					if($id_pregunta_2022_revocacion_mandato == $value['id_pregunta_2022_revocacion_mandato']){
						$votos = $value['votos'];
					}
				}

				foreach ($casillas_preguntas_2022_revocacion_mandatoDatos as $keyT => $valueT) {
					if( $id_pregunta_2022_revocacion_mandato == $valueT['id_pregunta_2022_revocacion_mandato']){
						$votos_preguntas_2022_revocacion_mandato[] = array(
							'id' => $valueT['id'], 
							'votos' => $votos, 
							'id_pregunta_2022_revocacion_mandato' => $id_pregunta_2022_revocacion_mandato,
							'id_casilla_voto_2022_revocacion_mandato' => $id_casilla_voto_2022_revocacion_mandato,
							'id_seccion_ine' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_seccion_ine'],
							'tipo' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['tipo'],
							'id_municipio' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_municipio'],
							'id_distrito_local' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_local'],
							'id_distrito_federal' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_distrito_federal'],
							'id_cuartel' => $_POST["casilla_voto_2022_revocacion_mandato"][0]['id_cuartel'],
						);
					}
				}
			}

			foreach ($votos_preguntas_2022_revocacion_mandato as $key => $value) {
				if( registrosCompara("casillas_preguntas_2022_revocacion_mandato",$value,1)){
					$entra=1;
					$voto_partido = $value;
					$voto_partido['fechaR']=$fechaH;
					$voto_partido['codigo_plataforma']=$codigo_plataforma;
					unset($valueSets);
					$success=true;
					foreach($voto_partido as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id=$atributos;
						}
					}
					$update_casillas_preguntas_2022_revocacion_mandato = "UPDATE casillas_preguntas_2022_revocacion_mandato SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_casillas_preguntas_2022_revocacion_mandato=$conexion->query($update_casillas_preguntas_2022_revocacion_mandato);
					$num=$conexion->affected_rows;
					if(!$update_casillas_preguntas_2022_revocacion_mandato || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_casillas_preguntas_2022_revocacion_mandato"; 
						var_dump($conexion->error);
					}

					unset($voto_partido['id']);
					$voto_partido['id_casilla_pregunta_2022_revocacion_mandato']=$id;
					$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
					$values_pdo = "'".implode("','", $voto_partido)."'";
					$insert_casillas_preguntas_2022_revocacion_mandato_historicos= "INSERT INTO casillas_preguntas_2022_revocacion_mandato_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_casillas_votos_2022_revocacion_mandato_historicos=$conexion->query($insert_casillas_preguntas_2022_revocacion_mandato_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_casillas_preguntas_2022_revocacion_mandato_historicos || $num=0){
						$success=false;
						echo "ERROR insert_casillas_preguntas_2022_revocacion_mandato_historicos"; 
						var_dump($conexion->error);
					}

				}
			}
			if($entra==1){
				if($success){
					$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2022_revocacion_mandato',$id_casilla_voto_2022_revocacion_mandato,'Update','',$fechaH);
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


		}
	}
