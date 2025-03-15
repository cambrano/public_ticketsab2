<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/campanas_mailing.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/campanas_mailing_programadas.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"campañas_mailing",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["campana_mailing"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_mailing"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_mailing_cuerpo"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_mailing_cuerpo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_mailing_programada"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_mailing_programada"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_mailing_cartografia"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_mailing_cartografia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_mailing_encuesta"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_mailing_encuesta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	foreach($_POST["campana_mailing_tipo_ciudadano"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_tipo_ciudadano = true;
			foreach ($atributos as $key => $value) {
				$_POST["campana_mailing_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_tipo_ciudadano = true;
				foreach ($atributos as $key => $value) {
					$_POST["campana_mailing_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	foreach($_POST["campana_mailing_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_tipo_categoria_ciudadano = true;
			foreach ($atributos as $key => $value) {
				$_POST["campana_mailing_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_tipo_categoria_ciudadano = true;
				foreach ($atributos as $key => $value) {
					$_POST["campana_mailing_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	///si id is null and id_tipo_categoria is null
	if($_POST["campana_mailing_cartografia"][0]['id'] =='' && $_POST["campana_mailing_cartografia"][0]['tipo_cartografia'] !='' ){
		$compara_cartagrafia = true;
	}else{
		if($_POST["campana_mailing_cartografia"][0]['id'] !=''){
			if(registrosCompara("campanas_mailing_cartografias",$_POST["campana_mailing_cartografia"][0],1)){
				$compara_cartagrafia = true;
			}else{
				$compara_cartagrafia = false;
			}
		}
	}


	if(!empty($_POST)){
		if( 
			registrosCompara("campanas_mailing",$_POST["campana_mailing"][0],1) || 
			registrosCompara("campanas_mailing_cuerpos",$_POST["campana_mailing_cuerpo"][0],1) ||
			registrosCompara("campanas_mailing_programadas",$_POST["campana_mailing_programada"][0],1) ||
			registrosCompara("campanas_mailing_encuestas",$_POST["campana_mailing_encuesta"][0],1) ||
			$compara_cartagrafia ||
			$compara_tipo_ciudadano ||
			$compara_tipo_categoria_ciudadano
		){
			$success=true;
			$edit_total = true;
			$conexion->autocommit(FALSE);
			

			$_POST["campana_mailing"][0]['fechaR']=$fechaH; 
			$_POST["campana_mailing"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_mailing_cuerpo"][0]['fechaR']=$fechaH; 
			$_POST["campana_mailing_cuerpo"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_mailing_programada"][0]['fechaR']=$fechaH; 
			$_POST["campana_mailing_programada"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_mailing_encuesta"][0]['fechaR']=$fechaH; 
			$_POST["campana_mailing_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_mailing_cartografia"][0]['fechaR']=$fechaH; 
			$_POST["campana_mailing_cartografia"][0]['codigo_plataforma']=$codigo_plataforma;


			$campana_mailing_programadaDatos=campana_mailing_programadaDatos('',$id_campana_mailing);
			$_POST["campana_mailing_programada"][0]['fecha_hora']=$_POST["campana_mailing_programada"][0]['fecha']." ".$_POST["campana_mailing_programada"][0]['hora'];

			if($campana_mailing_programadaDatos['fecha_hora'] != $_POST["campana_mailing_programada"][0]['fecha_hora'] ){
				$_POST["campana_mailing"][0]['envio'] = 0;
			}

			foreach($_POST["campana_mailing"][0] as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
				}else{
					$id_campana_mailing = $id = $atributos;
				}
			}

			$update_campanas_mailing = "UPDATE campanas_mailing SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_campanas_mailing=$conexion->query($update_campanas_mailing);
			$num=$conexion->affected_rows;
			if(!$update_campanas_mailing || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_campanas_mailing"; 
				var_dump($conexion->error);
			}

			unset($_POST["campana_mailing"][0]['id']); 
			$id_campana_mailing=$_POST["campana_mailing"][0]["id_campana_mailing"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_mailing"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["campana_mailing"][0])."'";
			$insert_campanas_mailing_historicos= "INSERT INTO campanas_mailing_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_campanas_mailing_historicos=$conexion->query($insert_campanas_mailing_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_mailing_historicos || $num=0){
				$success=false;
				echo "ERROR insert_campanas_mailing_historicos"; 
				var_dump($conexion->error);
			}

			unset($valueSets);
			foreach($_POST["campana_mailing_cuerpo"][0] as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
				}else{
					$id = $atributos;
				}
			}
			$_POST["campana_mailing_cuerpo"][0]['id_campana_mailing'] = $id_campana_mailing;
			$update_campanas_mailing_cuerpos = "UPDATE campanas_mailing_cuerpos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_campanas_mailing_cuerpos=$conexion->query($update_campanas_mailing_cuerpos);
			$num=$conexion->affected_rows;
			if(!$update_campanas_mailing_cuerpos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_campanas_mailing_cuerpos"; 
				var_dump($conexion->error);
			}

			unset($_POST["campana_mailing_cuerpo"][0]['id']); 
			$id_tipo_casilla=$_POST["campana_mailing_cuerpo"][0]["id_campana_mailing_cuerpo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_mailing_cuerpo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["campana_mailing_cuerpo"][0])."'";
			$insert_campanas_mailing_cuerpos_historicos= "INSERT INTO campanas_mailing_cuerpos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_campanas_mailing_cuerpos_historicos=$conexion->query($insert_campanas_mailing_cuerpos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_mailing_cuerpos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_campanas_mailing_cuerpos_historicos"; 
				var_dump($conexion->error);
			}


			if($_POST["campana_mailing"][0]['tipo']==1){
				//elimina todo

				$delete_campanas_mailing_tipos_categorias_ciudadanos = "DELETE FROM campanas_mailing_tipos_categorias_ciudadanos  WHERE id<>0 AND id_campana_mailing='{$id_campana_mailing}' ";
				$delete_campanas_mailing_tipos_categorias_ciudadanos=$conexion->query($delete_campanas_mailing_tipos_categorias_ciudadanos);
				$num=$conexion->affected_rows;
				if(!$delete_campanas_mailing_tipos_categorias_ciudadanos || $num=0){
					$success=false;
					echo "ERROR delete campaña mailing programadas"; 
					echo "<br>";
					echo("Errorcode: " . mysqli_errno($conexion));
					echo "<br>";
				}

				if($_POST["campana_mailing_programada"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_mailing_programada = "DELETE FROM campanas_mailing_programadas  WHERE id<>0 AND id_campana_mailing='{$id_campana_mailing}' ";
					$delete_campanas_mailing_programada=$conexion->query($delete_campanas_mailing_programada);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_mailing_programada || $num=0){
						$success=false;
						echo "ERROR delete campaña mailing programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
				if($_POST["campana_mailing_encuesta"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_mailing_encuesta = "DELETE FROM campanas_mailing_encuestas  WHERE id<>0 AND id_campana_mailing='{$id_campana_mailing}' ";
					$delete_campanas_mailing_encuesta=$conexion->query($delete_campanas_mailing_encuesta);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_mailing_encuesta || $num=0){
						$success=false;
						echo "ERROR delete campaña mailing programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}

			}elseif ($_POST["campana_mailing"][0]['tipo']==2) {
				if($_POST["campana_mailing_programada"][0]['id'] !=''){
					///editamos programa
					//update
					unset($valueSets);
					foreach($_POST["campana_mailing_programada"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_mailing_programada"][0]['id_campana_mailing']=$id_campana_mailing;
					$_POST["campana_mailing_programada"][0]['fecha_hora']=$_POST["campana_mailing_programada"][0]['fecha']." ".$_POST["campana_mailing_programada"][0]['hora'];
					$update_campanas_mailing_programadas = "UPDATE campanas_mailing_programadas SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_mailing_programadas=$conexion->query($update_campanas_mailing_programadas);
					$num=$conexion->affected_rows;
					if(!$update_campanas_mailing_programadas || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_mailing_programadas"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_mailing_programada"][0]['id']); 
					$_POST["campana_mailing_programada"][0]["id_campana_mailing_programada"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_mailing_programada"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_mailing_programada"][0])."'";
					$insert_campanas_mailing_programadas_historicos= "INSERT INTO campanas_mailing_programadas_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_programadas_historicos=$conexion->query($insert_campanas_mailing_programadas_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_programadas_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_programadas_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					//insertamos
					unset($_POST["campana_mailing_programada"][0]['id']);
					$_POST["campana_mailing_programada"][0]['id_campana_mailing'] = $id_campana_mailing;
					$_POST["campana_mailing_programada"][0]['fecha_hora']=$_POST["campana_mailing_programada"][0]['fecha']." ".$_POST["campana_mailing_programada"][0]['hora'];
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_programada'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_mailing_programada'][0])."'";
					$insert_campanas_mailing_programada= "INSERT INTO campanas_mailing_programadas ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_programada=$conexion->query($insert_campanas_mailing_programada);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_programada || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_programada"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_mailing_programada'][0]['id_campana_mailing_programada']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_programada'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_mailing_programada'][0])."'";
					$insert_campanas_mailing_programada_historicos= "INSERT INTO campanas_mailing_programadas_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_mailing_programada_historicos=$conexion->query($insert_campanas_mailing_programada_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_programada_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_programada_historicos"; 
						var_dump($conexion->error);
					}
				}
				if($_POST["campana_mailing_encuesta"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_mailing_encuesta = "DELETE FROM campanas_mailing_encuestas  WHERE id<>0 AND id_campana_mailing='{$id_campana_mailing}' ";
					$delete_campanas_mailing_encuesta=$conexion->query($delete_campanas_mailing_encuesta);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_mailing_encuesta || $num=0){
						$success=false;
						echo "ERROR delete campaña mailing programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			} else {
				if($_POST["campana_mailing_encuesta"][0]['id'] !=''){
					///editamos programa
					//update
					unset($valueSets);
					foreach($_POST["campana_mailing_encuesta"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_mailing_encuesta"][0]['id_campana_mailing']=$id_campana_mailing;
					$update_campanas_mailing_encuestas = "UPDATE campanas_mailing_encuestas SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_mailing_encuestas=$conexion->query($update_campanas_mailing_encuestas);
					$num=$conexion->affected_rows;
					if(!$update_campanas_mailing_encuestas || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_mailing_encuestas"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_mailing_encuesta"][0]['id']); 
					$_POST["campana_mailing_encuesta"][0]["id_campana_mailing_encuesta"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_mailing_encuesta"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_mailing_encuesta"][0])."'";
					$insert_campanas_mailing_encuestas_historicos= "INSERT INTO campanas_mailing_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_encuestas_historicos=$conexion->query($insert_campanas_mailing_encuestas_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_encuestas_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_encuestas_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					///insertamos programa
					unset($_POST["campana_mailing_encuesta"][0]['id']);
					$_POST["campana_mailing_encuesta"][0]['id_campana_mailing'] = $id_campana_mailing;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_encuesta'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_mailing_encuesta'][0])."'";
					$insert_campanas_mailing_encuesta= "INSERT INTO campanas_mailing_encuestas ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_encuesta=$conexion->query($insert_campanas_mailing_encuesta);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_encuesta || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_encuesta"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_mailing_encuesta'][0]['id_campana_mailing_encuesta']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_encuesta'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_mailing_encuesta'][0])."'";
					$insert_campanas_mailing_encuesta_historicos= "INSERT INTO campanas_mailing_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_mailing_encuesta_historicos=$conexion->query($insert_campanas_mailing_encuesta_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_encuesta_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_encuesta_historicos"; 
						var_dump($conexion->error);
					}
				}
				if($_POST["campana_mailing_programada"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_mailing_programada = "DELETE FROM campanas_mailing_programadas  WHERE id<>0 AND id_campana_mailing='{$id_campana_mailing}' ";
					$delete_campanas_mailing_programada=$conexion->query($delete_campanas_mailing_programada);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_mailing_programada || $num=0){
						$success=false;
						echo "ERROR delete campaña mailing programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			}



			if($_POST["campana_mailing_cartografia"][0]['tipo_cartografia'] != ''){
				if($_POST["campana_mailing_cartografia"][0]['id'] == ''){
					//insert
					//insertamos
					unset($_POST["campana_mailing_cartografia"][0]['id']);
					$_POST["campana_mailing_cartografia"][0]['id_campana_mailing'] = $id_campana_mailing;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_cartografia'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_mailing_cartografia'][0])."'";
					$insert_campanas_mailing_cartografia= "INSERT INTO campanas_mailing_cartografias ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_cartografia=$conexion->query($insert_campanas_mailing_cartografia);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_cartografia || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_cartografia"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_mailing_cartografia'][0]['id_campana_mailing_cartografia']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_cartografia'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_mailing_cartografia'][0])."'";
					$insert_campanas_mailing_cartografia_historicos= "INSERT INTO campanas_mailing_cartografias_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_mailing_cartografia_historicos=$conexion->query($insert_campanas_mailing_cartografia_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_cartografia_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_cartografia_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					///update
					unset($valueSets);
					foreach($_POST["campana_mailing_cartografia"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_mailing_cartografia"][0]['id_campana_mailing']=$id_campana_mailing;
					$update_campanas_mailing_cartografias = "UPDATE campanas_mailing_cartografias SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_mailing_cartografias=$conexion->query($update_campanas_mailing_cartografias);
					$num=$conexion->affected_rows;
					if(!$update_campanas_mailing_cartografias || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_mailing_cartografias"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_mailing_cartografia"][0]['id']); 
					$_POST["campana_mailing_cartografia"][0]["id_campana_mailing_cartografia"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_mailing_cartografia"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_mailing_cartografia"][0])."'";
					$insert_campanas_mailing_cartografias_historicos= "INSERT INTO campanas_mailing_cartografias_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_cartografias_historicos=$conexion->query($insert_campanas_mailing_cartografias_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_cartografias_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_cartografias_historicos"; 
						var_dump($conexion->error);
					}
				}
			}else{
				if($_POST["campana_mailing_cartografia"][0]['id'] != ''){
					//eliminamos
					$delete_campanas_mailing = "DELETE FROM campanas_mailing_cartografias  WHERE id<>0 AND id_campana_mailing='{$id_campana_mailing}' ";
					$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_mailing || $num=0){
						$success=false;
						echo "ERROR delete campaña mailing cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			}


			foreach($_POST["campana_mailing_tipo_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_campana_mailing']=$id_campana_mailing;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_mailing_tipo_ciudadano= "INSERT INTO campanas_mailing_tipos_ciudadanos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_tipo_ciudadano=$conexion->query($insert_campanas_mailing_tipo_ciudadano);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_tipo_ciudadano || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_tipo_ciudadano"; 
						var_dump($conexion->error);
					}

					$atributos['id_campana_mailing_tipo_ciudadano']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_mailing_tipo_ciudadano_historicos= "INSERT INTO campanas_mailing_tipos_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_mailing_tipo_ciudadano_historicos=$conexion->query($insert_campanas_mailing_tipo_ciudadano_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_tipo_ciudadano_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_tipo_ciudadano_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_campanas_mailing = "DELETE FROM campanas_mailing_tipos_ciudadanos  WHERE id = '{$id}' ";
					$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_mailing || $num=0){
						$success=false;
						echo "ERROR delete campaña mailing cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			foreach($_POST["campana_mailing_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_campana_mailing']=$id_campana_mailing;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_mailing_tipo_categoria_ciudadano= "INSERT INTO campanas_mailing_tipos_categorias_ciudadanos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_mailing_tipo_categoria_ciudadano=$conexion->query($insert_campanas_mailing_tipo_categoria_ciudadano);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_tipo_categoria_ciudadano || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_tipo_categoria_ciudadano"; 
						var_dump($conexion->error);
					}

					$atributos['id_campana_mailing_tipo_categoria_ciudadano']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_mailing_tipo_categoria_ciudadano_historicos= "INSERT INTO campanas_mailing_tipos_categorias_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_mailing_tipo_categoria_ciudadano_historicos=$conexion->query($insert_campanas_mailing_tipo_categoria_ciudadano_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_mailing_tipo_categoria_ciudadano_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_mailing_tipo_categoria_ciudadano_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_campanas_mailing = "DELETE FROM campanas_mailing_tipos_categorias_ciudadanos  WHERE id = '{$id}' ";
					$delete_campanas_mailing=$conexion->query($delete_campanas_mailing);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_mailing || $num=0){
						$success=false;
						echo "ERROR delete campaña mailing cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"campanas_mailing",$id_campana_mailing,'Update','',$fechaH);
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

