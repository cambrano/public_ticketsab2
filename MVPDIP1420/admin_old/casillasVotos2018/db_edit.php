<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/casillas_votos_2018.php";
	include __DIR__."/../functions/casillas_votos_partidos_2018.php";
	include __DIR__."/../functions/partidos_2018.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"casillas_votos_2018",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	$seccion_ineDatos = seccion_ineDatos($_POST["casilla_voto_2018"][0]['id_seccion_ine'] );
	$_POST["casilla_voto_2018"][0]['id_municipio'] = $seccion_ineDatos['id_municipio'];
	$_POST["casilla_voto_2018"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
	$_POST["casilla_voto_2018"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
	$_POST["casilla_voto_2018"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];
	foreach($_POST["casilla_voto_2018"][0] as $keyPrincipal => $atributo) {
		$_POST["casilla_voto_2018"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["votos_partidos_2018"][0] as $keyPrincipal => $atributo) {
		$_POST["votos_partidos_2018"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	$casilla_voto_2018ClaveVerificacion=casilla_voto_2018ClaveVerificacion($_POST["casilla_voto_2018"][0]["clave"],$_POST["casilla_voto_2018"][0]['id'],1);
	if($casilla_voto_2018ClaveVerificacion){
		$claveF= clave("casillas_votos_2018");
		if(empty($claveF['input'])){
			echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
			die;
		}else{
			$_POST["casilla_voto_2018"][0]["clave"] = $claveF["clave"];
		}
	}


	if(!empty($_POST)){ 
		if( registrosCompara("casillas_votos_2018",$_POST['casilla_voto_2018'][0],1)){
			$_POST["casilla_voto_2018"][0]['fechaR']=$fechaH;
			$_POST["casilla_voto_2018"][0]['codigo_plataforma']=$codigo_plataforma;

			$success=true;
			foreach($_POST['casilla_voto_2018'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_casillas_votos_2018 = "UPDATE casillas_votos_2018 SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_casillas_votos_2018=$conexion->query($update_casillas_votos_2018);
			$num=$conexion->affected_rows;
			if(!$update_casillas_votos_2018 || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_casillas_votos_2018"; 
				var_dump($conexion->error);
			}

			unset($_POST["casilla_voto_2018"][0]['id']); 
			$id_casilla_voto_2018=$_POST['casilla_voto_2018'][0]['id_casilla_voto_2018']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['casilla_voto_2018'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['casilla_voto_2018'][0])."'";
			$insert_casillas_votos_2018_historicos= "INSERT INTO casillas_votos_2018_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_casillas_votos_2018_historicos=$conexion->query($insert_casillas_votos_2018_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_casillas_votos_2018_historicos || $num=0){
				$success=false;
				echo "ERROR insert_casillas_votos_2018_historicos"; 
				var_dump($conexion->error);
			}

			//votos partidos
			$id_casilla_voto_2018 = $id;
			$casillas_votos_partidos_2018Datos = casillas_votos_partidos_2018Datos('',$id_casilla_voto_2018,'');

			$tipo = $_POST["casilla_voto_2018"][0]['tipo'];
			$partidos_2018Datos = partidos_2018Datos('','',$tipo);
			foreach ($partidos_2018Datos as $key => $value) {
				$id_partido_2018 = $value['id'];

				//buscamos el resultado
				foreach ($_POST['votos_partidos_2018'] as $key => $value) {
					if($id_partido_2018 == $value['id_partido_2018']){
						$votos = $value['votos'];
					}
				}

				foreach ($casillas_votos_partidos_2018Datos as $keyT => $valueT) {
					if( $id_partido_2018 == $valueT['id_partido_2018']){
						$votos_partidos_2018[] = array(
							'id' => $valueT['id'], 
							'votos' => $votos, 
							'id_partido_2018' => $id_partido_2018,
							'id_casilla_voto_2018' => $id_casilla_voto_2018,
							'id_seccion_ine' => $_POST["casilla_voto_2018"][0]['id_seccion_ine'],
							'tipo' => $_POST["casilla_voto_2018"][0]['tipo'],
							'id_municipio' => $_POST["casilla_voto_2018"][0]['id_municipio'],
							'id_distrito_local' => $_POST["casilla_voto_2018"][0]['id_distrito_local'],
							'id_distrito_federal' => $_POST["casilla_voto_2018"][0]['id_distrito_federal'],
							'id_cuartel' => $_POST["casilla_voto_2018"][0]['id_cuartel'],
						);
					}
				}
			}


			foreach ($votos_partidos_2018 as $key => $value) {
				if( registrosCompara("casillas_votos_partidos_2018",$value,1)){
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
					$update_casillas_votos_partidos_2018 = "UPDATE casillas_votos_partidos_2018 SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_casillas_votos_partidos_2018=$conexion->query($update_casillas_votos_partidos_2018);
					$num=$conexion->affected_rows;
					if(!$update_casillas_votos_partidos_2018 || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_casillas_votos_partidos_2018"; 
						var_dump($conexion->error);
					}

					unset($voto_partido['id']);
					$voto_partido['id_casilla_voto_partido_2018']=$id;
					$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
					$values_pdo = "'".implode("','", $voto_partido)."'";
					$insert_casillas_votos_partidos_2018_historicos= "INSERT INTO casillas_votos_partidos_2018_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_casillas_votos_2018_historicos=$conexion->query($insert_casillas_votos_partidos_2018_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_casillas_votos_partidos_2018_historicos || $num=0){
						$success=false;
						echo "ERROR insert_casillas_votos_partidos_2018_historicos"; 
						var_dump($conexion->error);
					}

				}
			}


			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2018',$id_casilla_voto_2018,'Update','',$fechaH);
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
			$id_casilla_voto_2018 = $_POST["casilla_voto_2018"][0]['id'];
			$casillas_votos_partidos_2018Datos = casillas_votos_partidos_2018Datos('',$id_casilla_voto_2018,'');
			$partidos_2018Datos = partidos_2018Datos();
			foreach ($partidos_2018Datos as $key => $value) {
				$id_partido_2018 = $value['id'];

				//buscamos el resultado
				foreach ($_POST['votos_partidos_2018'] as $key => $value) {
					if($id_partido_2018 == $value['id_partido_2018']){
						$votos = $value['votos'];
					}
				}

				foreach ($casillas_votos_partidos_2018Datos as $keyT => $valueT) {
					if( $id_partido_2018 == $valueT['id_partido_2018']){
						$votos_partidos_2018[] = array(
							'id' => $valueT['id'], 
							'votos' => $votos, 
							'id_partido_2018' => $id_partido_2018,
							'id_casilla_voto_2018' => $id_casilla_voto_2018,
							'id_seccion_ine' => $_POST["casilla_voto_2018"][0]['id_seccion_ine'],
							'tipo' => $_POST["casilla_voto_2018"][0]['tipo'],
							'id_municipio' => $_POST["casilla_voto_2018"][0]['id_municipio'],
							'id_distrito_local' => $_POST["casilla_voto_2018"][0]['id_distrito_local'],
							'id_distrito_federal' => $_POST["casilla_voto_2018"][0]['id_distrito_federal'],
							'id_cuartel' => $_POST["casilla_voto_2018"][0]['id_cuartel'],
						);
					}
				}
			}

			foreach ($votos_partidos_2018 as $key => $value) {
				if( registrosCompara("casillas_votos_partidos_2018",$value,1)){
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
					$update_casillas_votos_partidos_2018 = "UPDATE casillas_votos_partidos_2018 SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_casillas_votos_partidos_2018=$conexion->query($update_casillas_votos_partidos_2018);
					$num=$conexion->affected_rows;
					if(!$update_casillas_votos_partidos_2018 || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_casillas_votos_partidos_2018"; 
						var_dump($conexion->error);
					}

					unset($voto_partido['id']);
					$voto_partido['id_casilla_voto_partido_2018']=$id;
					$fields_pdo = "`".implode('`,`', array_keys($voto_partido))."`";
					$values_pdo = "'".implode("','", $voto_partido)."'";
					$insert_casillas_votos_partidos_2018_historicos= "INSERT INTO casillas_votos_partidos_2018_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_casillas_votos_2018_historicos=$conexion->query($insert_casillas_votos_partidos_2018_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_casillas_votos_partidos_2018_historicos || $num=0){
						$success=false;
						echo "ERROR insert_casillas_votos_partidos_2018_historicos"; 
						var_dump($conexion->error);
					}

				}
			}
			if($entra==1){
				if($success){
					$log= logUsuario($_COOKIE["id_usuario"],'casillas_votos_2018',$id_casilla_voto_2018,'Update','',$fechaH);
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
