<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/campanas_sms.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/campanas_sms_programadas.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"campañas_sms",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["campana_sms"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_sms"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_sms_cuerpo"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_sms_cuerpo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_sms_programada"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_sms_programada"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_sms_cartografia"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_sms_cartografia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_sms_encuesta"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_sms_encuesta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	foreach($_POST["campana_sms_tipo_ciudadano"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_tipo_ciudadano = true;
			foreach ($atributos as $key => $value) {
				$_POST["campana_sms_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_tipo_ciudadano = true;
				foreach ($atributos as $key => $value) {
					$_POST["campana_sms_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	foreach($_POST["campana_sms_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_tipo_categoria_ciudadano = true;
			foreach ($atributos as $key => $value) {
				$_POST["campana_sms_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_tipo_categoria_ciudadano = true;
				foreach ($atributos as $key => $value) {
					$_POST["campana_sms_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	///si id is null and id_tipo_categoria is null
	if($_POST["campana_sms_cartografia"][0]['id'] =='' && $_POST["campana_sms_cartografia"][0]['tipo_cartografia'] !='' ){
		$compara_cartagrafia = true;
	}else{
		if($_POST["campana_sms_cartografia"][0]['id'] !=''){
			if(registrosCompara("campanas_sms_cartografias",$_POST["campana_sms_cartografia"][0],1)){
				$compara_cartagrafia = true;
			}else{
				$compara_cartagrafia = false;
			}
		}
	}


	if(!empty($_POST)){
		if( 
			registrosCompara("campanas_sms",$_POST["campana_sms"][0],1) || 
			registrosCompara("campanas_sms_cuerpos",$_POST["campana_sms_cuerpo"][0],1) ||
			registrosCompara("campanas_sms_programadas",$_POST["campana_sms_programada"][0],1) ||
			registrosCompara("campanas_sms_encuestas",$_POST["campana_sms_encuesta"][0],1) ||
			$compara_cartagrafia ||
			$compara_tipo_ciudadano ||
			$compara_tipo_categoria_ciudadano
		){
			$success=true;
			$edit_total = true;
			$conexion->autocommit(FALSE);
			

			$_POST["campana_sms"][0]['fechaR']=$fechaH; 
			$_POST["campana_sms"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_sms_cuerpo"][0]['fechaR']=$fechaH; 
			$_POST["campana_sms_cuerpo"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_sms_programada"][0]['fechaR']=$fechaH; 
			$_POST["campana_sms_programada"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_sms_encuesta"][0]['fechaR']=$fechaH; 
			$_POST["campana_sms_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_sms_cartografia"][0]['fechaR']=$fechaH; 
			$_POST["campana_sms_cartografia"][0]['codigo_plataforma']=$codigo_plataforma;


			$campana_sms_programadaDatos=campana_sms_programadaDatos('',$id_campana_sms);
			$_POST["campana_sms_programada"][0]['fecha_hora']=$_POST["campana_sms_programada"][0]['fecha']." ".$_POST["campana_sms_programada"][0]['hora'];

			if($campana_sms_programadaDatos['fecha_hora'] != $_POST["campana_sms_programada"][0]['fecha_hora'] ){
				$_POST["campana_sms"][0]['envio'] = 0;
			}

			foreach($_POST["campana_sms"][0] as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
				}else{
					$id_campana_sms = $id = $atributos;
				}
			}

			$update_campanas_sms = "UPDATE campanas_sms SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_campanas_sms=$conexion->query($update_campanas_sms);
			$num=$conexion->affected_rows;
			if(!$update_campanas_sms || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_campanas_sms"; 
				var_dump($conexion->error);
			}

			unset($_POST["campana_sms"][0]['id']); 
			$id_campana_sms=$_POST["campana_sms"][0]["id_campana_sms"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_sms"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["campana_sms"][0])."'";
			$insert_campanas_sms_historicos= "INSERT INTO campanas_sms_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_campanas_sms_historicos=$conexion->query($insert_campanas_sms_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_sms_historicos || $num=0){
				$success=false;
				echo "ERROR insert_campanas_sms_historicos"; 
				var_dump($conexion->error);
			}

			unset($valueSets);
			foreach($_POST["campana_sms_cuerpo"][0] as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
				}else{
					$id = $atributos;
				}
			}
			$_POST["campana_sms_cuerpo"][0]['id_campana_sms'] = $id_campana_sms;
			$update_campanas_sms_cuerpos = "UPDATE campanas_sms_cuerpos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_campanas_sms_cuerpos=$conexion->query($update_campanas_sms_cuerpos);
			$num=$conexion->affected_rows;
			if(!$update_campanas_sms_cuerpos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_campanas_sms_cuerpos"; 
				var_dump($conexion->error);
			}

			unset($_POST["campana_sms_cuerpo"][0]['id']); 
			$id_tipo_casilla=$_POST["campana_sms_cuerpo"][0]["id_campana_sms_cuerpo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_sms_cuerpo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["campana_sms_cuerpo"][0])."'";
			$insert_campanas_sms_cuerpos_historicos= "INSERT INTO campanas_sms_cuerpos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_campanas_sms_cuerpos_historicos=$conexion->query($insert_campanas_sms_cuerpos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_sms_cuerpos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_campanas_sms_cuerpos_historicos"; 
				var_dump($conexion->error);
			}


			if($_POST["campana_sms"][0]['tipo']==1){
				//elimina todo

				$delete_campanas_sms_tipos_categorias_ciudadanos = "DELETE FROM campanas_sms_tipos_categorias_ciudadanos  WHERE id<>0 AND id_campana_sms='{$id_campana_sms}' ";
				$delete_campanas_sms_tipos_categorias_ciudadanos=$conexion->query($delete_campanas_sms_tipos_categorias_ciudadanos);
				$num=$conexion->affected_rows;
				if(!$delete_campanas_sms_tipos_categorias_ciudadanos || $num=0){
					$success=false;
					echo "ERROR delete campaña sms programadas"; 
					echo "<br>";
					echo("Errorcode: " . mysqli_errno($conexion));
					echo "<br>";
				}

				if($_POST["campana_sms_programada"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_sms_programada = "DELETE FROM campanas_sms_programadas  WHERE id<>0 AND id_campana_sms='{$id_campana_sms}' ";
					$delete_campanas_sms_programada=$conexion->query($delete_campanas_sms_programada);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_sms_programada || $num=0){
						$success=false;
						echo "ERROR delete campaña sms programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
				if($_POST["campana_sms_encuesta"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_sms_encuesta = "DELETE FROM campanas_sms_encuestas  WHERE id<>0 AND id_campana_sms='{$id_campana_sms}' ";
					$delete_campanas_sms_encuesta=$conexion->query($delete_campanas_sms_encuesta);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_sms_encuesta || $num=0){
						$success=false;
						echo "ERROR delete campaña sms programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}

			}elseif ($_POST["campana_sms"][0]['tipo']==2) {
				if($_POST["campana_sms_programada"][0]['id'] !=''){
					///editamos programa
					//update
					unset($valueSets);
					foreach($_POST["campana_sms_programada"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_sms_programada"][0]['id_campana_sms']=$id_campana_sms;
					$_POST["campana_sms_programada"][0]['fecha_hora']=$_POST["campana_sms_programada"][0]['fecha']." ".$_POST["campana_sms_programada"][0]['hora'];
					$update_campanas_sms_programadas = "UPDATE campanas_sms_programadas SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_sms_programadas=$conexion->query($update_campanas_sms_programadas);
					$num=$conexion->affected_rows;
					if(!$update_campanas_sms_programadas || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_sms_programadas"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_sms_programada"][0]['id']); 
					$_POST["campana_sms_programada"][0]["id_campana_sms_programada"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_sms_programada"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_sms_programada"][0])."'";
					$insert_campanas_sms_programadas_historicos= "INSERT INTO campanas_sms_programadas_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_programadas_historicos=$conexion->query($insert_campanas_sms_programadas_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_programadas_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_programadas_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					//insertamos
					unset($_POST["campana_sms_programada"][0]['id']);
					$_POST["campana_sms_programada"][0]['id_campana_sms'] = $id_campana_sms;
					$_POST["campana_sms_programada"][0]['fecha_hora']=$_POST["campana_sms_programada"][0]['fecha']." ".$_POST["campana_sms_programada"][0]['hora'];
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_programada'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_sms_programada'][0])."'";
					$insert_campanas_sms_programada= "INSERT INTO campanas_sms_programadas ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_programada=$conexion->query($insert_campanas_sms_programada);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_programada || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_programada"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_sms_programada'][0]['id_campana_sms_programada']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_programada'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_sms_programada'][0])."'";
					$insert_campanas_sms_programada_historicos= "INSERT INTO campanas_sms_programadas_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_sms_programada_historicos=$conexion->query($insert_campanas_sms_programada_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_programada_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_programada_historicos"; 
						var_dump($conexion->error);
					}
				}
				if($_POST["campana_sms_encuesta"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_sms_encuesta = "DELETE FROM campanas_sms_encuestas  WHERE id<>0 AND id_campana_sms='{$id_campana_sms}' ";
					$delete_campanas_sms_encuesta=$conexion->query($delete_campanas_sms_encuesta);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_sms_encuesta || $num=0){
						$success=false;
						echo "ERROR delete campaña sms programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			} else {
				if($_POST["campana_sms_encuesta"][0]['id'] !=''){
					///editamos programa
					//update
					unset($valueSets);
					foreach($_POST["campana_sms_encuesta"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_sms_encuesta"][0]['id_campana_sms']=$id_campana_sms;
					$update_campanas_sms_encuestas = "UPDATE campanas_sms_encuestas SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_sms_encuestas=$conexion->query($update_campanas_sms_encuestas);
					$num=$conexion->affected_rows;
					if(!$update_campanas_sms_encuestas || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_sms_encuestas"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_sms_encuesta"][0]['id']); 
					$_POST["campana_sms_encuesta"][0]["id_campana_sms_encuesta"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_sms_encuesta"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_sms_encuesta"][0])."'";
					$insert_campanas_sms_encuestas_historicos= "INSERT INTO campanas_sms_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_encuestas_historicos=$conexion->query($insert_campanas_sms_encuestas_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_encuestas_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_encuestas_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					///insertamos programa
					unset($_POST["campana_sms_encuesta"][0]['id']);
					$_POST["campana_sms_encuesta"][0]['id_campana_sms'] = $id_campana_sms;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_encuesta'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_sms_encuesta'][0])."'";
					$insert_campanas_sms_encuesta= "INSERT INTO campanas_sms_encuestas ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_encuesta=$conexion->query($insert_campanas_sms_encuesta);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_encuesta || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_encuesta"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_sms_encuesta'][0]['id_campana_sms_encuesta']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_encuesta'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_sms_encuesta'][0])."'";
					$insert_campanas_sms_encuesta_historicos= "INSERT INTO campanas_sms_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_sms_encuesta_historicos=$conexion->query($insert_campanas_sms_encuesta_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_encuesta_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_encuesta_historicos"; 
						var_dump($conexion->error);
					}
				}
				if($_POST["campana_sms_programada"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_sms_programada = "DELETE FROM campanas_sms_programadas  WHERE id<>0 AND id_campana_sms='{$id_campana_sms}' ";
					$delete_campanas_sms_programada=$conexion->query($delete_campanas_sms_programada);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_sms_programada || $num=0){
						$success=false;
						echo "ERROR delete campaña sms programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			}



			if($_POST["campana_sms_cartografia"][0]['tipo_cartografia'] != ''){
				if($_POST["campana_sms_cartografia"][0]['id'] == ''){
					//insert
					//insertamos
					unset($_POST["campana_sms_cartografia"][0]['id']);
					$_POST["campana_sms_cartografia"][0]['id_campana_sms'] = $id_campana_sms;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_cartografia'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_sms_cartografia'][0])."'";
					$insert_campanas_sms_cartografia= "INSERT INTO campanas_sms_cartografias ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_cartografia=$conexion->query($insert_campanas_sms_cartografia);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_cartografia || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_cartografia"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_sms_cartografia'][0]['id_campana_sms_cartografia']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_cartografia'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_sms_cartografia'][0])."'";
					$insert_campanas_sms_cartografia_historicos= "INSERT INTO campanas_sms_cartografias_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_sms_cartografia_historicos=$conexion->query($insert_campanas_sms_cartografia_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_cartografia_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_cartografia_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					///update
					unset($valueSets);
					foreach($_POST["campana_sms_cartografia"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_sms_cartografia"][0]['id_campana_sms']=$id_campana_sms;
					$update_campanas_sms_cartografias = "UPDATE campanas_sms_cartografias SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_sms_cartografias=$conexion->query($update_campanas_sms_cartografias);
					$num=$conexion->affected_rows;
					if(!$update_campanas_sms_cartografias || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_sms_cartografias"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_sms_cartografia"][0]['id']); 
					$_POST["campana_sms_cartografia"][0]["id_campana_sms_cartografia"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_sms_cartografia"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_sms_cartografia"][0])."'";
					$insert_campanas_sms_cartografias_historicos= "INSERT INTO campanas_sms_cartografias_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_cartografias_historicos=$conexion->query($insert_campanas_sms_cartografias_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_cartografias_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_cartografias_historicos"; 
						var_dump($conexion->error);
					}
				}
			}else{
				if($_POST["campana_sms_cartografia"][0]['id'] != ''){
					//eliminamos
					$delete_campanas_sms = "DELETE FROM campanas_sms_cartografias  WHERE id<>0 AND id_campana_sms='{$id_campana_sms}' ";
					$delete_campanas_sms=$conexion->query($delete_campanas_sms);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_sms || $num=0){
						$success=false;
						echo "ERROR delete campaña sms cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			}


			foreach($_POST["campana_sms_tipo_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_campana_sms']=$id_campana_sms;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_sms_tipo_ciudadano= "INSERT INTO campanas_sms_tipos_ciudadanos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_tipo_ciudadano=$conexion->query($insert_campanas_sms_tipo_ciudadano);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_tipo_ciudadano || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_tipo_ciudadano"; 
						var_dump($conexion->error);
					}

					$atributos['id_campana_sms_tipo_ciudadano']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_sms_tipo_ciudadano_historicos= "INSERT INTO campanas_sms_tipos_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_sms_tipo_ciudadano_historicos=$conexion->query($insert_campanas_sms_tipo_ciudadano_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_tipo_ciudadano_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_tipo_ciudadano_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_campanas_sms = "DELETE FROM campanas_sms_tipos_ciudadanos  WHERE id = '{$id}' ";
					$delete_campanas_sms=$conexion->query($delete_campanas_sms);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_sms || $num=0){
						$success=false;
						echo "ERROR delete campaña sms cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			foreach($_POST["campana_sms_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_campana_sms']=$id_campana_sms;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_sms_tipo_categoria_ciudadano= "INSERT INTO campanas_sms_tipos_categorias_ciudadanos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_sms_tipo_categoria_ciudadano=$conexion->query($insert_campanas_sms_tipo_categoria_ciudadano);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_tipo_categoria_ciudadano || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_tipo_categoria_ciudadano"; 
						var_dump($conexion->error);
					}

					$atributos['id_campana_sms_tipo_categoria_ciudadano']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_sms_tipo_categoria_ciudadano_historicos= "INSERT INTO campanas_sms_tipos_categorias_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_sms_tipo_categoria_ciudadano_historicos=$conexion->query($insert_campanas_sms_tipo_categoria_ciudadano_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_sms_tipo_categoria_ciudadano_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_sms_tipo_categoria_ciudadano_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_campanas_sms = "DELETE FROM campanas_sms_tipos_categorias_ciudadanos  WHERE id = '{$id}' ";
					$delete_campanas_sms=$conexion->query($delete_campanas_sms);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_sms || $num=0){
						$success=false;
						echo "ERROR delete campaña sms cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"campañas_sms",$id_campana_sms,'Update','',$fechaH);
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

