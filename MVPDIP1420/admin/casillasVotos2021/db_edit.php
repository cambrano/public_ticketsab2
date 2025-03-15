<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/casillas_votos_2021.php";
	include __DIR__."/../functions/casillas_votos_partidos_2021.php";
	include __DIR__."/../functions/partidos_2021.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"casillas_votos_2021",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2021"][0]['id_seccion_ine'] );
	$_POST["casilla_voto_2021"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
	$_POST["casilla_voto_2021"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
	$_POST["casilla_voto_2021"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
	$_POST["casilla_voto_2021"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];
	foreach($_POST["casilla_voto_2021"][0] as $keyPrincipal => $atributo) {
		$_POST["casilla_voto_2021"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["votos_partidos_2021"][0] as $keyPrincipal => $atributo) {
		$_POST["votos_partidos_2021"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$casilla_voto_2021ClaveVerificacion=casilla_voto_2021ClaveVerificacion($_POST["casilla_voto_2021"][0]["clave"],$_POST["casilla_voto_2021"][0]['id'],1);
	if($casilla_voto_2021ClaveVerificacion){
		$claveF= clave("casillas_votos_2021");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["casilla_voto_2021"][0]["clave"] = $claveF["clave"];
		}
	}
	if(!empty($_POST)){ 
		if( registrosCompara("casillas_votos_2021",$_POST['casilla_voto_2021'][0],1)){
			$_POST["casilla_voto_2021"][0]['fechaR']=$fechaH;
			$_POST["casilla_voto_2021"][0]['codigo_plataforma']=$codigo_plataforma;

			$success=true;
			foreach($_POST['casilla_voto_2021'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_casillas_votos_2021 = "UPDATE casillas_votos_2021 SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_casillas_votos_2021=$conexion->query($update_casillas_votos_2021);
			$num=$conexion->affected_rows;
			if(!$update_casillas_votos_2021 || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_casillas_votos_2021"; 
				var_dump($conexion->error);
			}

			unset($_POST["casilla_voto_2021"][0]['id']); 
			$id_casilla_voto_2021=$_POST['casilla_voto_2021'][0]['id_casilla_voto_2021']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2021'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['casilla_voto_2021'][0])."'";
			$insert_casillas_votos_2021_historicos= "INSERT INTO casillas_votos_2021_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_casillas_votos_2021_historicos=$conexion->query($insert_casillas_votos_2021_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_casillas_votos_2021_historicos || $num=0){
				$success=false;
				echo "ERROR insert_casillas_votos_2021_historicos"; 
				var_dump($conexion->error);
			}

			//votos partidos
			$id_casilla_voto_2021 = $id;
			$casillas_votos_partidos_2021Datos = casillas_votos_partidos_2021Datos('',$id_casilla_voto_2021,'');
			$tipo = $_POST["casilla_voto_2021"][0]['tipo'];
			if(!empty($casillas_votos_partidos_2021Datos)){
				//edita votos partidos
				$partidos_2021Datos = partidos_2021Datos('','',$tipo);
				foreach ($partidos_2021Datos as $key => $value) {
					$id_partido_2021 = $value['id'];

					//buscamos el resultado
					foreach ($_POST['votos_partidos_2021'] as $key => $value) {
						if($id_partido_2021 == $value['id_partido_2021']){
							$votos = $value['votos'];
						}
					}

					foreach ($casillas_votos_partidos_2021Datos as $keyT => $valueT) {
						if( $id_partido_2021 == $valueT['id_partido_2021']){
							$votos_partidos_2021[] = array(
								'id' => $valueT['id'], 
								'votos' => $votos, 
								'id_partido_2021' => $id_partido_2021,
								'id_casilla_voto_2021' => $id_casilla_voto_2021,
								'id_seccion_ine' => $_POST["casilla_voto_2021"][0]['id_seccion_ine'],
								'tipo' => $_POST["casilla_voto_2021"][0]['tipo'],
								'id_municipio' => $_POST["casilla_voto_2021"][0]['id_municipio'],
								'id_distrito_local' => $_POST["casilla_voto_2021"][0]['id_distrito_local'],
								'id_distrito_federal' => $_POST["casilla_voto_2021"][0]['id_distrito_federal'],
								'id_cuartel' => $_POST["casilla_voto_2021"][0]['id_cuartel'],
							);
						}
					}
				}
				foreach ($votos_partidos_2021 as $key => $value) {
					if( registrosCompara("casillas_votos_partidos_2021",$value,1)){
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
						$update_casillas_votos_partidos_2021 = "UPDATE casillas_votos_partidos_2021 SET ". join(",",$valueSets) . " WHERE id=".$id;
						$conexion->autocommit(FALSE);
						$update_casillas_votos_partidos_2021=$conexion->query($update_casillas_votos_partidos_2021);
						$num=$conexion->affected_rows;
						if(!$update_casillas_votos_partidos_2021 || $num=0){
							$success=false;
							echo "<br>";
							echo "ERROR update_casillas_votos_partidos_2021"; 
							var_dump($conexion->error);
						}

						unset($voto_partido['id']);
						$voto_partido['id_casilla_voto_partido_2021']=$id;
						$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
						$values_pdo = "'".implode("','", $voto_partido)."'";
						$insert_casillas_votos_partidos_2021_historicos= "INSERT INTO casillas_votos_partidos_2021_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert_casillas_votos_2021_historicos=$conexion->query($insert_casillas_votos_partidos_2021_historicos);
						$num=$conexion->affected_rows;
						if(!$insert_casillas_votos_partidos_2021_historicos || $num=0){
							$success=false;
							echo "ERROR insert_casillas_votos_partidos_2021_historicos"; 
							var_dump($conexion->error);
						}

					}
				}
			}else{
				//inserta votos partidos
				$conexion->autocommit(FALSE);
				$success=true;
				foreach ($_POST['votos_partidos_2021'] as $key => $value) {
					$entra=1;
					include __DIR__."/../functions/genid.php";
					unset($votos_partidos_2021);
					$votos_partidos_2021['clave'] = $cod32;
					$votos_partidos_2021['id_seccion_ine'] = $_POST["casilla_voto_2021"][0]['id_seccion_ine'];
					$votos_partidos_2021['id_casilla_voto_2021'] = $id_casilla_voto_2021;
					$votos_partidos_2021['id_partido_2021'] = $value['id_partido_2021'];
					$votos_partidos_2021['votos'] = $value['votos'];
					$votos_partidos_2021['codigo_plataforma'] = $codigo_plataforma;
					$votos_partidos_2021['fechaR'] = $fechaH;
					$votos_partidos_2021['id_municipio'] = $_POST["casilla_voto_2021"][0]['id_municipio'];
					$votos_partidos_2021['id_distrito_local'] = $_POST["casilla_voto_2021"][0]['id_distrito_local'];
					$votos_partidos_2021['id_distrito_federal'] = $_POST["casilla_voto_2021"][0]['id_distrito_federal'];
					$votos_partidos_2021['id_cuartel'] = $_POST["casilla_voto_2021"][0]['id_cuartel'];
					$votos_partidos_2021['tipo'] = $_POST["casilla_voto_2021"][0]['tipo'];
					//insertamos
					$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2021))."`";
					$values_pdo = "'".implode("','", $votos_partidos_2021)."'";
					$inset_casillas_votos_2021_partido= "INSERT INTO casillas_votos_partidos_2021 ($fields_pdo) VALUES ($values_pdo);";
					$inset_casillas_votos_2021_partido=$conexion->query($inset_casillas_votos_2021_partido);
					$num=$conexion->affected_rows;
					if(!$inset_casillas_votos_2021_partido || $num=0){
						$success=false;
						echo "ERROR inset_casillas_votos_2021_partido"; 
						var_dump($conexion->error);
					}
					$votos_partidos_2021['id_casilla_voto_partido_2021'] = $conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2021))."`";
					$values_pdo = "'".implode("','", $votos_partidos_2021)."'";
					$inset_casillas_votos_2021_partido_historicos= "INSERT INTO casillas_votos_partidos_2021_historicos ($fields_pdo) VALUES ($values_pdo);";
					$inset_casillas_votos_2021_partido_historicos=$conexion->query($inset_casillas_votos_2021_partido_historicos);
					$num=$conexion->affected_rows;
					if(!$inset_casillas_votos_2021_partido_historicos || $num=0){
						$success=false;
						echo "ERROR inset_casillas_votos_2021_partido_historicos"; 
						var_dump($conexion->error);
					}
				}

			}


			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2021',$id_casilla_voto_2021,'Update','',$fechaH);
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
			 
			//edit casilla votos
			$id_casilla_voto_2021 = $_POST["casilla_voto_2021"][0]['id'];
			$casillas_votos_partidos_2021Datos = casillas_votos_partidos_2021Datos('',$id_casilla_voto_2021,'');

			$tipo = $_POST["casilla_voto_2021"][0]['tipo'];

			if(!empty($casillas_votos_partidos_2021Datos)){
				//edita votos partidos
				$partidos_2021Datos = partidos_2021Datos('','',$tipo);
				foreach ($partidos_2021Datos as $key => $value) {
					$id_partido_2021 = $value['id'];
					//buscamos el resultado
					foreach ($_POST['votos_partidos_2021'] as $key => $value) {
						if($id_partido_2021 == $value['id_partido_2021']){
							$votos = $value['votos'];
						}
					}

					foreach ($casillas_votos_partidos_2021Datos as $keyT => $valueT) {
						if( $id_partido_2021 == $valueT['id_partido_2021']){
							$votos_partidos_2021[] = array(
								'id' => $valueT['id'], 
								'votos' => $votos, 
								'id_partido_2021' => $id_partido_2021,
								'id_casilla_voto_2021' => $_POST["casilla_voto_2021"][0]['id'],
								'id_seccion_ine' => $_POST["casilla_voto_2021"][0]['id_seccion_ine'],
								'tipo' => $_POST["casilla_voto_2021"][0]['tipo'],
								'id_municipio' => $_POST["casilla_voto_2021"][0]['id_municipio'],
								'id_distrito_local' => $_POST["casilla_voto_2021"][0]['id_distrito_local'],
								'id_distrito_federal' => $_POST["casilla_voto_2021"][0]['id_distrito_federal'],
								'id_cuartel' => $_POST["casilla_voto_2021"][0]['id_cuartel'],
							);
						}
					}
				}
				foreach ($votos_partidos_2021 as $key => $value) {
					if( registrosCompara("casillas_votos_partidos_2021",$value,1)){
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
						$update_casillas_votos_partidos_2021 = "UPDATE casillas_votos_partidos_2021 SET ". join(",",$valueSets) . " WHERE id=".$id;
						$conexion->autocommit(FALSE);
						$update_casillas_votos_partidos_2021=$conexion->query($update_casillas_votos_partidos_2021);
						$num=$conexion->affected_rows;
						if(!$update_casillas_votos_partidos_2021 || $num=0){
							$success=false;
							echo "<br>";
							echo "ERROR update_casillas_votos_partidos_2021"; 
							var_dump($conexion->error);
						}

						unset($voto_partido['id']);
						$voto_partido['id_casilla_voto_partido_2021']=$id;
						$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
						$values_pdo = "'".implode("','", $voto_partido)."'";
						$insert_casillas_votos_partidos_2021_historicos= "INSERT INTO casillas_votos_partidos_2021_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert_casillas_votos_2021_historicos=$conexion->query($insert_casillas_votos_partidos_2021_historicos);
						$num=$conexion->affected_rows;
						if(!$insert_casillas_votos_partidos_2021_historicos || $num=0){
							$success=false;
							echo "ERROR insert_casillas_votos_partidos_2021_historicos"; 
							var_dump($conexion->error);
						}

					}
				}
			}else{
				//inserta votos partidos
				$conexion->autocommit(FALSE);
				$success=true;
				foreach ($_POST['votos_partidos_2021'] as $key => $value) {
					$entra=1;
					include __DIR__."/../functions/genid.php";
					unset($votos_partidos_2021);
					$votos_partidos_2021['clave'] = $cod32;
					$votos_partidos_2021['id_seccion_ine'] = $_POST["casilla_voto_2021"][0]['id_seccion_ine'];
					$votos_partidos_2021['id_casilla_voto_2021'] = $id_casilla_voto_2021;
					$votos_partidos_2021['id_partido_2021'] = $value['id_partido_2021'];
					$votos_partidos_2021['votos'] = $value['votos'];
					$votos_partidos_2021['codigo_plataforma'] = $codigo_plataforma;
					$votos_partidos_2021['fechaR'] = $fechaH;
					$votos_partidos_2021['id_municipio'] = $_POST["casilla_voto_2021"][0]['id_municipio'];
					$votos_partidos_2021['id_distrito_local'] = $_POST["casilla_voto_2021"][0]['id_distrito_local'];
					$votos_partidos_2021['id_distrito_federal'] = $_POST["casilla_voto_2021"][0]['id_distrito_federal'];
					$votos_partidos_2021['id_cuartel'] = $_POST["casilla_voto_2021"][0]['id_cuartel'];
					$votos_partidos_2021['tipo'] = $_POST["casilla_voto_2021"][0]['tipo'];
					//insertamos
					$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2021))."`";
					$values_pdo = "'".implode("','", $votos_partidos_2021)."'";
					$inset_casillas_votos_2021_partido= "INSERT INTO casillas_votos_partidos_2021 ($fields_pdo) VALUES ($values_pdo);";
					$inset_casillas_votos_2021_partido=$conexion->query($inset_casillas_votos_2021_partido);
					$num=$conexion->affected_rows;
					if(!$inset_casillas_votos_2021_partido || $num=0){
						$success=false;
						echo "ERROR inset_casillas_votos_2021_partido"; 
						var_dump($conexion->error);
					}
					$votos_partidos_2021['id_casilla_voto_partido_2021'] = $conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($votos_partidos_2021))."`";
					$values_pdo = "'".implode("','", $votos_partidos_2021)."'";
					$inset_casillas_votos_2021_partido_historicos= "INSERT INTO casillas_votos_partidos_2021_historicos ($fields_pdo) VALUES ($values_pdo);";
					$inset_casillas_votos_2021_partido_historicos=$conexion->query($inset_casillas_votos_2021_partido_historicos);
					$num=$conexion->affected_rows;
					if(!$inset_casillas_votos_2021_partido_historicos || $num=0){
						$success=false;
						echo "ERROR inset_casillas_votos_2021_partido_historicos"; 
						var_dump($conexion->error);
					}
				}

			}

			
			if($entra==1){
				if($success){
					$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2021',$id_casilla_voto_2021,'Update','',$fechaH);
					if($log==true){
						echo "SI";
						$conexion->commit();
						$conexion->close();
					}else{
						echo "NO2";
						$conexion->rollback();
						$conexion->close();
					}
				}else{
					echo "NO1";
					$conexion->rollback();
					$conexion->close();
				}
			 
			}


		}
	}
