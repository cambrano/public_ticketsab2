<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/casillas_votos_2024.php";
	include __DIR__."/../functions/casillas_votos_partidos_2024.php";
	include __DIR__."/../functions/partidos_2024.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__.'/../functions/switch_operaciones.php';
	include __DIR__.'/../functions/secciones_ine_ciudadanos_permisos.php';
	$seccion_ine_ciudadano_permisosDatos = seccion_ine_ciudadano_permisosDatos('','',$_COOKIE["id_usuario"]);
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['casilla'] && $seccion_ine_ciudadano_permisosDatos['casilla'] == "1"){
	}else{
		echo "No tiene permiso.";
		die;
	}
	if($_POST["casilla_voto_2024"][0]['tipo']=='x'){
		$_POST["casilla_voto_2024"][0]['tipo']=0;
	}

	$id_casilla_voto_2024 = $_COOKIE["id_casilla_voto_partidos"];
	$_POST["casilla_voto_2024"][0]['id'] = $id_casilla_voto_2024;
	$casilla_voto_2024Datos=casilla_voto_2024Datos($id_casilla_voto_2024);

	$_POST["casilla_voto_2024"][0]['clave'] = $casilla_voto_2024Datos['clave'];
	$_POST["casilla_voto_2024"][0]['id_seccion_ine'] = $casilla_voto_2024Datos['id_seccion_ine'];
	$_POST["casilla_voto_2024"][0]['id_tipo_casilla'] = $casilla_voto_2024Datos['id_tipo_casilla'];
	$_POST["casilla_voto_2024"][0]['codigo'] = $casilla_voto_2024Datos['codigo'];
	$_POST["casilla_voto_2024"][0]['id_pais'] = $casilla_voto_2024Datos['id_pais'];
	$_POST["casilla_voto_2024"][0]['id_estado'] = $casilla_voto_2024Datos['id_estado'];
	$_POST["casilla_voto_2024"][0]['id_municipio'] = $casilla_voto_2024Datos['id_municipio'];
	$_POST["casilla_voto_2024"][0]['id_localidad'] = $casilla_voto_2024Datos['id_localidad'];
	$_POST["casilla_voto_2024"][0]['calle'] = $casilla_voto_2024Datos['calle'];
	$_POST["casilla_voto_2024"][0]['colonia'] = $casilla_voto_2024Datos['colonia'];
	$_POST["casilla_voto_2024"][0]['codigo_postal'] = $casilla_voto_2024Datos['codigo_postal'];
	$_POST["casilla_voto_2024"][0]['longitud'] = $casilla_voto_2024Datos['longitud'];
	$_POST["casilla_voto_2024"][0]['latitud'] = $casilla_voto_2024Datos['latitud'];


	//metemos los valores para que se no tengamos error
	$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2024"][0]['id_seccion_ine'] );
	$_POST["casilla_voto_2024"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
	$_POST["casilla_voto_2024"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
	$_POST["casilla_voto_2024"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
	foreach($_POST["casilla_voto_2024"][0] as $keyPrincipal => $atributo) {
		$_POST["casilla_voto_2024"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["votos_partidos_2024"][0] as $keyPrincipal => $atributo) {
		$_POST["votos_partidos_2024"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	if(!empty($_POST)){
		if( registrosCompara("casillas_votos_2024",$_POST['casilla_voto_2024'][0],1)){
			//edit casilla
			$_POST["casilla_voto_2024"][0]['fechaR']=$fechaH;
			$_POST["casilla_voto_2024"][0]['codigo_plataforma']=$codigo_plataforma;

			$success=true;
			foreach($_POST['casilla_voto_2024'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_casillas_votos_2024 = "UPDATE casillas_votos_2024 SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_casillas_votos_2024=$conexion->query($update_casillas_votos_2024);
			$num=$conexion->affected_rows;
			if(!$update_casillas_votos_2024 || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_casillas_votos_2024"; 
				var_dump($conexion->error);
			}

			unset($_POST["casilla_voto_2024"][0]['id']); 
			$id_casilla_voto_2024=$_POST['casilla_voto_2024'][0]['id_casilla_voto_2024']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2024'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['casilla_voto_2024'][0])."'";
			$insert_casillas_votos_2024_historicos= "INSERT INTO casillas_votos_2024_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_casillas_votos_2024_historicos=$conexion->query($insert_casillas_votos_2024_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_casillas_votos_2024_historicos || $num=0){
				$success=false;
				echo "ERROR insert_casillas_votos_2024_historicos"; 
				var_dump($conexion->error);
			}

			//votos partidos
			$id_casilla_voto_2024 = $id;
			$casillas_votos_partidos_2024Datos = casillas_votos_partidos_2024Datos('',$id_casilla_voto_2024,'');
			$tipo = $_POST["casilla_voto_2024"][0]['tipo'];
			if(!empty($casillas_votos_partidos_2024Datos)){
				//edita votos partidos
				$partidos_2024Datos = partidos_2024Datos('','',$tipo);
				foreach ($partidos_2024Datos as $key => $value) {
					$id_partido_2024 = $value['id'];

					//buscamos el resultado
					foreach ($_POST['votos_partidos_2024'] as $key => $value) {
						if($id_partido_2024 == $value['id_partido_2024']){
							$votos = $value['votos'];
						}
					}

					foreach ($casillas_votos_partidos_2024Datos as $keyT => $valueT) {
						if( $id_partido_2024 == $valueT['id_partido_2024']){
							$votos_partidos_2024[] = array(
								'id' => $valueT['id'], 
								'votos' => $votos, 
								'id_partido_2024' => $id_partido_2024,
								'id_casilla_voto_2024' => $_POST["casilla_voto_2024"][0]['id'],
								'id_seccion_ine' => $_POST["casilla_voto_2024"][0]['id_seccion_ine'],
								'tipo' => $_POST["casilla_voto_2024"][0]['tipo'],
								'id_municipio' => $_POST["casilla_voto_2024"][0]['id_municipio'],
								'id_distrito_local' => $_POST["casilla_voto_2024"][0]['id_distrito_local'],
								'id_distrito_federal' => $_POST["casilla_voto_2024"][0]['id_distrito_federal'],
							);
						}
					}
				}
				foreach ($votos_partidos_2024 as $key => $value) {
					if( registrosCompara("casillas_votos_partidos_2024",$value,1)){
						$entra=1;
						$voto_partido = $value;
						$voto_partido['fechaR']=$fechaH;
						$voto_partido['id_casilla_voto_2024']=$id_casilla_voto_2024;
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
						$update_casillas_votos_partidos_2024 = "UPDATE casillas_votos_partidos_2024 SET ". join(",",$valueSets) . " WHERE id=".$id;
						$conexion->autocommit(FALSE);
						$update_casillas_votos_partidos_2024=$conexion->query($update_casillas_votos_partidos_2024);
						$num=$conexion->affected_rows;
						if(!$update_casillas_votos_partidos_2024 || $num=0){
							$success=false;
							echo "<br>";
							echo "ERROR update_casillas_votos_partidos_20241"; 
							var_dump($conexion->error);
						}

						unset($voto_partido['id']);
						$voto_partido['id_casilla_voto_partido_2024']=$id;
						$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
						$values_pdo = "'".implode("','", $voto_partido)."'";
						$insert_casillas_votos_partidos_2024_historicos= "INSERT INTO casillas_votos_partidos_2024_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert_casillas_votos_2024_historicos=$conexion->query($insert_casillas_votos_partidos_2024_historicos);
						$num=$conexion->affected_rows;
						if(!$insert_casillas_votos_partidos_2024_historicos || $num=0){
							$success=false;
							echo "ERROR insert_casillas_votos_partidos_2024_historicos"; 
							var_dump($conexion->error);
						}

					}
				}
			}else{
				//inserta votos partidos
				$conexion->autocommit(FALSE);
				$success=true;
				foreach ($_POST['votos_partidos_2024'] as $key => $value) {
					$entra=1;
					include __DIR__."/../functions/genid.php";
					unset($votos_partidos_2024);
					$votos_partidos_2024['clave'] = $cod32;
					$votos_partidos_2024['id_seccion_ine'] = $_POST["casilla_voto_2024"][0]['id_seccion_ine'];
					$votos_partidos_2024['id_casilla_voto_2024'] = $id_casilla_voto_2024;
					$votos_partidos_2024['id_partido_2024'] = $value['id_partido_2024'];
					$votos_partidos_2024['votos'] = $value['votos'];
					$votos_partidos_2024['codigo_plataforma'] = $codigo_plataforma;
					$votos_partidos_2024['fechaR'] = $fechaH;
					$votos_partidos_2024['id_municipio'] = $_POST["casilla_voto_2024"][0]['id_municipio'];
					$votos_partidos_2024['id_distrito_local'] = $_POST["casilla_voto_2024"][0]['id_distrito_local'];
					$votos_partidos_2024['id_distrito_federal'] = $_POST["casilla_voto_2024"][0]['id_distrito_federal'];
					$votos_partidos_2024['tipo'] = $_POST["casilla_voto_2024"][0]['tipo'];
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
			}


			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2024',$id_casilla_voto_2024,'Update','',$fechaH);
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
			$id_casilla_voto_2024 = $_POST["casilla_voto_2024"][0]['id'];
			$casillas_votos_partidos_2024Datos = casillas_votos_partidos_2024Datos('',$id_casilla_voto_2024,'');
			$tipo = $_POST["casilla_voto_2024"][0]['tipo'];
			if(!empty($casillas_votos_partidos_2024Datos)){
				//edita votos partidos
				$partidos_2024Datos = partidos_2024Datos('','',$tipo);
				foreach ($partidos_2024Datos as $key => $value) {
					$id_partido_2024 = $value['id'];

					//buscamos el resultado
					foreach ($_POST['votos_partidos_2024'] as $key => $value) {
						if($id_partido_2024 == $value['id_partido_2024']){
							$votos = $value['votos'];
						}
					}

					foreach ($casillas_votos_partidos_2024Datos as $keyT => $valueT) {
						if( $id_partido_2024 == $valueT['id_partido_2024']){
							$votos_partidos_2024[] = array(
								'id' => $valueT['id'], 
								'votos' => $votos, 
								'id_partido_2024' => $id_partido_2024,
								'id_casilla_voto_2024' => $_POST["casilla_voto_2024"][0]['id'],
								'id_seccion_ine' => $_POST["casilla_voto_2024"][0]['id_seccion_ine'],
								'tipo' => $_POST["casilla_voto_2024"][0]['tipo'],
								'id_municipio' => $_POST["casilla_voto_2024"][0]['id_municipio'],
								'id_distrito_local' => $_POST["casilla_voto_2024"][0]['id_distrito_local'],
								'id_distrito_federal' => $_POST["casilla_voto_2024"][0]['id_distrito_federal'],
							);
						}
					}
				}
				foreach ($votos_partidos_2024 as $key => $value) {
					if( registrosCompara("casillas_votos_partidos_2024",$value,1)){
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
						$update_casillas_votos_partidos_2024 = "UPDATE casillas_votos_partidos_2024 SET ". join(",",$valueSets) . " WHERE id=".$id;
						$conexion->autocommit(FALSE);
						$update_casillas_votos_partidos_2024=$conexion->query($update_casillas_votos_partidos_2024);
						$num=$conexion->affected_rows;
						if(!$update_casillas_votos_partidos_2024 || $num=0){
							$success=false;
							echo "<br>";
							echo "ERROR update_casillas_votos_partidos_2024"; 
							var_dump($conexion->error);
						}

						unset($voto_partido['id']);
						$voto_partido['id_casilla_voto_partido_2024']=$id;
						$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
						$values_pdo = "'".implode("','", $voto_partido)."'";
						$insert_casillas_votos_partidos_2024_historicos= "INSERT INTO casillas_votos_partidos_2024_historicos ($fields_pdo) VALUES ($values_pdo);";
						$insert_casillas_votos_2024_historicos=$conexion->query($insert_casillas_votos_partidos_2024_historicos);
						$num=$conexion->affected_rows;
						if(!$insert_casillas_votos_partidos_2024_historicos || $num=0){
							$success=false;
							echo "ERROR insert_casillas_votos_partidos_2024_historicos"; 
							var_dump($conexion->error);
						}

					}
				}
			}else{
				//inserta votos partidos
				$conexion->autocommit(FALSE);
				$success=true;
				foreach ($_POST['votos_partidos_2024'] as $key => $value) {
					$entra=1;
					include __DIR__."/../functions/genid.php";
					unset($votos_partidos_2024);
					$votos_partidos_2024['clave'] = $cod32;
					$votos_partidos_2024['id_seccion_ine'] = $_POST["casilla_voto_2024"][0]['id_seccion_ine'];
					$votos_partidos_2024['id_casilla_voto_2024'] = $id_casilla_voto_2024;
					$votos_partidos_2024['id_partido_2024'] = $value['id_partido_2024'];
					$votos_partidos_2024['votos'] = $value['votos'];
					$votos_partidos_2024['codigo_plataforma'] = $codigo_plataforma;
					$votos_partidos_2024['fechaR'] = $fechaH;
					$votos_partidos_2024['id_municipio'] = $_POST["casilla_voto_2024"][0]['id_municipio'];
					$votos_partidos_2024['id_distrito_local'] = $_POST["casilla_voto_2024"][0]['id_distrito_local'];
					$votos_partidos_2024['id_distrito_federal'] = $_POST["casilla_voto_2024"][0]['id_distrito_federal'];
					$votos_partidos_2024['tipo'] = $_POST["casilla_voto_2024"][0]['tipo'];
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
			}

			
			if($entra==1){
				if($success){
					$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2024',$id_casilla_voto_2024,'Update','',$fechaH);
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
