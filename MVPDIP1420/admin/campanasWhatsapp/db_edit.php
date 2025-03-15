<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/campanas_whatsapp.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/campanas_whatsapp_programadas.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"campañas_whatsapp",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	

	//metemos los valores para que se no tengamos error
	foreach($_POST["campana_whatsapp"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_whatsapp"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_whatsapp_cuerpo"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_whatsapp_cuerpo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_whatsapp_programada"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_whatsapp_programada"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_whatsapp_cartografia"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_whatsapp_cartografia"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}
	foreach($_POST["campana_whatsapp_encuesta"][0] as $keyPrincipal => $atributo) {
		$_POST["campana_whatsapp_encuesta"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	

	foreach($_POST["campana_whatsapp_tipo_ciudadano"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_tipo_ciudadano = true;
			foreach ($atributos as $key => $value) {
				$_POST["campana_whatsapp_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_tipo_ciudadano = true;
				foreach ($atributos as $key => $value) {
					$_POST["campana_whatsapp_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	foreach($_POST["campana_whatsapp_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
		if($atributos['id']=='' && $atributos['check']==1 ){
			$compara_tipo_categoria_ciudadano = true;
			foreach ($atributos as $key => $value) {
				$_POST["campana_whatsapp_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
			}
		}else{
			if($atributos['id'] !='' && $atributos['check']==0){
				$compara_tipo_categoria_ciudadano = true;
				foreach ($atributos as $key => $value) {
					$_POST["campana_whatsapp_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}
		}
	}

	///si id is null and id_tipo_categoria is null
	if($_POST["campana_whatsapp_cartografia"][0]['id'] =='' && $_POST["campana_whatsapp_cartografia"][0]['tipo_cartografia'] !='' ){
		$compara_cartagrafia = true;
	}else{
		if($_POST["campana_whatsapp_cartografia"][0]['id'] !=''){
			if(registrosCompara("campanas_whatsapp_cartografias",$_POST["campana_whatsapp_cartografia"][0],1)){
				$compara_cartagrafia = true;
			}else{
				$compara_cartagrafia = false;
			}
		}
	}

	

	if(!empty($_POST)){
		if( 
			registrosCompara("campanas_whatsapp",$_POST["campana_whatsapp"][0],1) || 
			registrosCompara("campanas_whatsapp_cuerpos",$_POST["campana_whatsapp_cuerpo"][0],1) ||
			registrosCompara("campanas_whatsapp_programadas",$_POST["campana_whatsapp_programada"][0],1) ||
			registrosCompara("campanas_whatsapp_encuestas",$_POST["campana_whatsapp_encuesta"][0],1) ||
			$compara_cartagrafia ||
			$compara_tipo_ciudadano ||
			$compara_tipo_categoria_ciudadano
		){
			$success=true;
			$edit_total = true;
			$conexion->autocommit(FALSE);


			$_POST["campana_whatsapp"][0]['fechaR']=$fechaH; 
			$_POST["campana_whatsapp"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_whatsapp_cuerpo"][0]['fechaR']=$fechaH; 
			$_POST["campana_whatsapp_cuerpo"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_whatsapp_programada"][0]['fechaR']=$fechaH; 
			$_POST["campana_whatsapp_programada"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_whatsapp_encuesta"][0]['fechaR']=$fechaH; 
			$_POST["campana_whatsapp_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

			$_POST["campana_whatsapp_cartografia"][0]['fechaR']=$fechaH; 
			$_POST["campana_whatsapp_cartografia"][0]['codigo_plataforma']=$codigo_plataforma;


			$campana_whatsapp_programadaDatos=campana_whatsapp_programadaDatos('',$id_campana_whatsapp);
			$_POST["campana_whatsapp_programada"][0]['fecha_hora']=$_POST["campana_whatsapp_programada"][0]['fecha']." ".$_POST["campana_whatsapp_programada"][0]['hora'];

			if($campana_whatsapp_programadaDatos['fecha_hora'] != $_POST["campana_whatsapp_programada"][0]['fecha_hora'] ){
				$_POST["campana_whatsapp"][0]['envio'] = 0;
			}

			foreach ($_POST['campana_whatsapp'][0] as $key => $value){
				if ($key == 'tipo_sender' && $value =='1' ){
					///Python debemos elminar el id_api_whatsapp
					$_POST['campana_whatsapp'][0]['id_api_whatsapp']= 0;
	
				}
				if ($key == 'tipo_sender' && $value =='2' ){
					///Python debemos elminar el id_api_whatsapp
					$_POST['campana_whatsapp'][0]['id_whatsapp_python']= 0;
	
				}
			}
			

			foreach($_POST["campana_whatsapp"][0] as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					if($atributos==NULL){
						$valueSets[] = $keyPrincipal . " = NULL ";
					}else{
						$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
					}
				}else{
					$id_campana_whatsapp = $id = $atributos;
				}
			}

			$update_campanas_whatsapp = "UPDATE campanas_whatsapp SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_campanas_whatsapp=$conexion->query($update_campanas_whatsapp);
			$num=$conexion->affected_rows;
			if(!$update_campanas_whatsapp || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_campanas_whatsapp"; 
				var_dump($conexion->error);
			}

			unset($_POST["campana_whatsapp"][0]['id']); 
			$id_campana_whatsapp=$_POST["campana_whatsapp"][0]["id_campana_whatsapp"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_whatsapp"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["campana_whatsapp"][0])."'";
			$insert_campanas_whatsapp_historicos= "INSERT INTO campanas_whatsapp_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_campanas_whatsapp_historicos=$conexion->query($insert_campanas_whatsapp_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_whatsapp_historicos || $num=0){
				$success=false;
				echo "ERROR insert_campanas_whatsapp_historicos"; 
				var_dump($conexion->error);
			}

			unset($valueSets);
			foreach($_POST["campana_whatsapp_cuerpo"][0] as $keyPrincipal => $atributos) {
				if($keyPrincipal !='id'){
					$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
				}else{
					$id = $atributos;
				}
			}
			$_POST["campana_whatsapp_cuerpo"][0]['id_campana_whatsapp'] = $id_campana_whatsapp;
			$update_campanas_whatsapp_cuerpos = "UPDATE campanas_whatsapp_cuerpos SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_campanas_whatsapp_cuerpos=$conexion->query($update_campanas_whatsapp_cuerpos);
			$num=$conexion->affected_rows;
			if(!$update_campanas_whatsapp_cuerpos || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_campanas_whatsapp_cuerpos"; 
				var_dump($conexion->error);
			}

			unset($_POST["campana_whatsapp_cuerpo"][0]['id']); 
			$id_tipo_casilla=$_POST["campana_whatsapp_cuerpo"][0]["id_campana_whatsapp_cuerpo"]=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_whatsapp_cuerpo"][0]))."`";
			$values_pdo = "'".implode("','", $_POST["campana_whatsapp_cuerpo"][0])."'";
			$insert_campanas_whatsapp_cuerpos_historicos= "INSERT INTO campanas_whatsapp_cuerpos_historicos ($fields_pdo) VALUES ($values_pdo);";
			$insert_campanas_whatsapp_cuerpos_historicos=$conexion->query($insert_campanas_whatsapp_cuerpos_historicos);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_whatsapp_cuerpos_historicos || $num=0){
				$success=false;
				echo "ERROR insert_campanas_whatsapp_cuerpos_historicos"; 
				var_dump($conexion->error);
			}


			if($_POST["campana_whatsapp"][0]['tipo']==1){
				//elimina todo

				$delete_campanas_whatsapp_tipos_categorias_ciudadanos = "DELETE FROM campanas_whatsapp_tipos_categorias_ciudadanos  WHERE id<>0 AND id_campana_whatsapp='{$id_campana_whatsapp}' ";
				$delete_campanas_whatsapp_tipos_categorias_ciudadanos=$conexion->query($delete_campanas_whatsapp_tipos_categorias_ciudadanos);
				$num=$conexion->affected_rows;
				if(!$delete_campanas_whatsapp_tipos_categorias_ciudadanos || $num=0){
					$success=false;
					echo "ERROR delete campaña whatsapp programadas"; 
					echo "<br>";
					echo("Errorcode: " . mysqli_errno($conexion));
					echo "<br>";
				}

				if($_POST["campana_whatsapp_programada"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_whatsapp_programada = "DELETE FROM campanas_whatsapp_programadas  WHERE id<>0 AND id_campana_whatsapp='{$id_campana_whatsapp}' ";
					$delete_campanas_whatsapp_programada=$conexion->query($delete_campanas_whatsapp_programada);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_whatsapp_programada || $num=0){
						$success=false;
						echo "ERROR delete campaña whatsapp programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
				if($_POST["campana_whatsapp_encuesta"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_whatsapp_encuesta = "DELETE FROM campanas_whatsapp_encuestas  WHERE id<>0 AND id_campana_whatsapp='{$id_campana_whatsapp}' ";
					$delete_campanas_whatsapp_encuesta=$conexion->query($delete_campanas_whatsapp_encuesta);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_whatsapp_encuesta || $num=0){
						$success=false;
						echo "ERROR delete campaña whatsapp programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}

			}elseif ($_POST["campana_whatsapp"][0]['tipo']==2) {
				if($_POST["campana_whatsapp_programada"][0]['id'] !=''){
					///editamos programa
					//update
					unset($valueSets);
					foreach($_POST["campana_whatsapp_programada"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_whatsapp_programada"][0]['id_campana_whatsapp']=$id_campana_whatsapp;
					$_POST["campana_whatsapp_programada"][0]['fecha_hora']=$_POST["campana_whatsapp_programada"][0]['fecha']." ".$_POST["campana_whatsapp_programada"][0]['hora'];
					$update_campanas_whatsapp_programadas = "UPDATE campanas_whatsapp_programadas SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_whatsapp_programadas=$conexion->query($update_campanas_whatsapp_programadas);
					$num=$conexion->affected_rows;
					if(!$update_campanas_whatsapp_programadas || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_whatsapp_programadas"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_whatsapp_programada"][0]['id']); 
					$_POST["campana_whatsapp_programada"][0]["id_campana_whatsapp_programada"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_whatsapp_programada"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_whatsapp_programada"][0])."'";
					$insert_campanas_whatsapp_programadas_historicos= "INSERT INTO campanas_whatsapp_programadas_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_programadas_historicos=$conexion->query($insert_campanas_whatsapp_programadas_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_programadas_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_programadas_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					//insertamos
					unset($_POST["campana_whatsapp_programada"][0]['id']);
					$_POST["campana_whatsapp_programada"][0]['id_campana_whatsapp'] = $id_campana_whatsapp;
					$_POST["campana_whatsapp_programada"][0]['fecha_hora']=$_POST["campana_whatsapp_programada"][0]['fecha']." ".$_POST["campana_whatsapp_programada"][0]['hora'];
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_programada'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_whatsapp_programada'][0])."'";
					$insert_campanas_whatsapp_programada= "INSERT INTO campanas_whatsapp_programadas ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_programada=$conexion->query($insert_campanas_whatsapp_programada);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_programada || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_programada"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_whatsapp_programada'][0]['id_campana_whatsapp_programada']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_programada'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_whatsapp_programada'][0])."'";
					$insert_campanas_whatsapp_programada_historicos= "INSERT INTO campanas_whatsapp_programadas_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_whatsapp_programada_historicos=$conexion->query($insert_campanas_whatsapp_programada_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_programada_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_programada_historicos"; 
						var_dump($conexion->error);
					}
				}
				if($_POST["campana_whatsapp_encuesta"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_whatsapp_encuesta = "DELETE FROM campanas_whatsapp_encuestas  WHERE id<>0 AND id_campana_whatsapp='{$id_campana_whatsapp}' ";
					$delete_campanas_whatsapp_encuesta=$conexion->query($delete_campanas_whatsapp_encuesta);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_whatsapp_encuesta || $num=0){
						$success=false;
						echo "ERROR delete campaña whatsapp programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			} else {
				if($_POST["campana_whatsapp_encuesta"][0]['id'] !=''){
					///editamos programa
					//update
					unset($valueSets);
					$_POST["campana_whatsapp_encuesta"][0]['fecha_hora']=$_POST["campana_whatsapp_encuesta"][0]['fecha']." ".$_POST["campana_whatsapp_encuesta"][0]['hora'];
					foreach($_POST["campana_whatsapp_encuesta"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_whatsapp_encuesta"][0]['id_campana_whatsapp']=$id_campana_whatsapp;
					$update_campanas_whatsapp_encuestas = "UPDATE campanas_whatsapp_encuestas SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_whatsapp_encuestas=$conexion->query($update_campanas_whatsapp_encuestas);
					$num=$conexion->affected_rows;
					if(!$update_campanas_whatsapp_encuestas || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_whatsapp_encuestas"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_whatsapp_encuesta"][0]['id']); 
					$_POST["campana_whatsapp_encuesta"][0]["id_campana_whatsapp_encuesta"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_whatsapp_encuesta"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_whatsapp_encuesta"][0])."'";
					$insert_campanas_whatsapp_encuestas_historicos= "INSERT INTO campanas_whatsapp_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_encuestas_historicos=$conexion->query($insert_campanas_whatsapp_encuestas_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_encuestas_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_encuestas_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					///insertamos programa
					unset($_POST["campana_whatsapp_encuesta"][0]['id']);
					$_POST["campana_whatsapp_encuesta"][0]['id_campana_whatsapp'] = $id_campana_whatsapp;
					$_POST["campana_whatsapp_encuesta"][0]['fecha_hora']=$_POST["campana_whatsapp_encuesta"][0]['fecha']." ".$_POST["campana_whatsapp_encuesta"][0]['hora'];
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_encuesta'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_whatsapp_encuesta'][0])."'";
					$insert_campanas_whatsapp_encuesta= "INSERT INTO campanas_whatsapp_encuestas ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_encuesta=$conexion->query($insert_campanas_whatsapp_encuesta);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_encuesta || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_encuesta"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_whatsapp_encuesta'][0]['id_campana_whatsapp_encuesta']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_encuesta'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_whatsapp_encuesta'][0])."'";
					$insert_campanas_whatsapp_encuesta_historicos= "INSERT INTO campanas_whatsapp_encuestas_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_whatsapp_encuesta_historicos=$conexion->query($insert_campanas_whatsapp_encuesta_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_encuesta_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_encuesta_historicos"; 
						var_dump($conexion->error);
					}
				}
				if($_POST["campana_whatsapp_programada"][0]['id'] !=''){
					///eliminamos la encuesta
					$delete_campanas_whatsapp_programada = "DELETE FROM campanas_whatsapp_programadas  WHERE id<>0 AND id_campana_whatsapp='{$id_campana_whatsapp}' ";
					$delete_campanas_whatsapp_programada=$conexion->query($delete_campanas_whatsapp_programada);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_whatsapp_programada || $num=0){
						$success=false;
						echo "ERROR delete campaña whatsapp programadas"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			}



			if($_POST["campana_whatsapp_cartografia"][0]['tipo_cartografia'] != ''){
				if($_POST["campana_whatsapp_cartografia"][0]['id'] == ''){
					//insert
					//insertamos
					unset($_POST["campana_whatsapp_cartografia"][0]['id']);
					$_POST["campana_whatsapp_cartografia"][0]['id_campana_whatsapp'] = $id_campana_whatsapp;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_cartografia'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_whatsapp_cartografia'][0])."'";
					$insert_campanas_whatsapp_cartografia= "INSERT INTO campanas_whatsapp_cartografias ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_cartografia=$conexion->query($insert_campanas_whatsapp_cartografia);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_cartografia || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_cartografia"; 
						var_dump($conexion->error);
					}
					

					$_POST['campana_whatsapp_cartografia'][0]['id_campana_whatsapp_cartografia']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_cartografia'][0]))."`";
					$values_pdo = "'".implode("','", $_POST['campana_whatsapp_cartografia'][0])."'";
					$insert_campanas_whatsapp_cartografia_historicos= "INSERT INTO campanas_whatsapp_cartografias_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_whatsapp_cartografia_historicos=$conexion->query($insert_campanas_whatsapp_cartografia_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_cartografia_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_cartografia_historicos"; 
						var_dump($conexion->error);
					}
				}else{
					///update
					unset($valueSets);
					foreach($_POST["campana_whatsapp_cartografia"][0] as $keyPrincipal => $atributos) {
						if($keyPrincipal !='id'){
							$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
						}else{
							$id = $atributos;
						}
					}
					$_POST["campana_whatsapp_cartografia"][0]['id_campana_whatsapp']=$id_campana_whatsapp;
					$update_campanas_whatsapp_cartografias = "UPDATE campanas_whatsapp_cartografias SET ". join(",",$valueSets) . " WHERE id=".$id;
					$conexion->autocommit(FALSE);
					$update_campanas_whatsapp_cartografias=$conexion->query($update_campanas_whatsapp_cartografias);
					$num=$conexion->affected_rows;
					if(!$update_campanas_whatsapp_cartografias || $num=0){
						$success=false;
						echo "<br>";
						echo "ERROR update_campanas_whatsapp_cartografias"; 
						var_dump($conexion->error);
					}

					unset($_POST["campana_whatsapp_cartografia"][0]['id']); 
					$_POST["campana_whatsapp_cartografia"][0]["id_campana_whatsapp_cartografia"]=$id;
					$fields_pdo = "`".implode('`,`', array_keys($_POST["campana_whatsapp_cartografia"][0]))."`";
					$values_pdo = "'".implode("','", $_POST["campana_whatsapp_cartografia"][0])."'";
					$insert_campanas_whatsapp_cartografias_historicos= "INSERT INTO campanas_whatsapp_cartografias_historicos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_cartografias_historicos=$conexion->query($insert_campanas_whatsapp_cartografias_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_cartografias_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_cartografias_historicos"; 
						var_dump($conexion->error);
					}
				}
			}else{
				if($_POST["campana_whatsapp_cartografia"][0]['id'] != ''){
					//eliminamos
					$delete_campanas_whatsapp = "DELETE FROM campanas_whatsapp_cartografias  WHERE id<>0 AND id_campana_whatsapp='{$id_campana_whatsapp}' ";
					$delete_campanas_whatsapp=$conexion->query($delete_campanas_whatsapp);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_whatsapp || $num=0){
						$success=false;
						echo "ERROR delete campaña whatsapp cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}
			}


			foreach($_POST["campana_whatsapp_tipo_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_campana_whatsapp']=$id_campana_whatsapp;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_whatsapp_tipo_ciudadano= "INSERT INTO campanas_whatsapp_tipos_ciudadanos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_tipo_ciudadano=$conexion->query($insert_campanas_whatsapp_tipo_ciudadano);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_tipo_ciudadano || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_tipo_ciudadano"; 
						var_dump($conexion->error);
					}

					$atributos['id_campana_whatsapp_tipo_ciudadano']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_whatsapp_tipo_ciudadano_historicos= "INSERT INTO campanas_whatsapp_tipos_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_whatsapp_tipo_ciudadano_historicos=$conexion->query($insert_campanas_whatsapp_tipo_ciudadano_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_tipo_ciudadano_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_tipo_ciudadano_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_campanas_whatsapp = "DELETE FROM campanas_whatsapp_tipos_ciudadanos  WHERE id = '{$id}' ";
					$delete_campanas_whatsapp=$conexion->query($delete_campanas_whatsapp);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_whatsapp || $num=0){
						$success=false;
						echo "ERROR delete campaña whatsapp cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			foreach($_POST["campana_whatsapp_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['id']=='' && $atributos['check']==1 ){
					///insertamos
					unset($atributos['check']);
					unset($atributos['id']);
					$atributos['codigo_plataforma']=$codigo_plataforma;
					$atributos['fechaR']=$fechaH;
					$atributos['id_campana_whatsapp']=$id_campana_whatsapp;

					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_whatsapp_tipo_categoria_ciudadano= "INSERT INTO campanas_whatsapp_tipos_categorias_ciudadanos ($fields_pdo) VALUES ($values_pdo);";
					$insert_campanas_whatsapp_tipo_categoria_ciudadano=$conexion->query($insert_campanas_whatsapp_tipo_categoria_ciudadano);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_tipo_categoria_ciudadano || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_tipo_categoria_ciudadano"; 
						var_dump($conexion->error);
					}

					$atributos['id_campana_whatsapp_tipo_categoria_ciudadano']=$conexion->insert_id;
					$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
					$values_pdo = "'".implode("','", $atributos)."'";
					$insert_campanas_whatsapp_tipo_categoria_ciudadano_historicos= "INSERT INTO campanas_whatsapp_tipos_categorias_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";

					$insert_campanas_whatsapp_tipo_categoria_ciudadano_historicos=$conexion->query($insert_campanas_whatsapp_tipo_categoria_ciudadano_historicos);
					$num=$conexion->affected_rows;
					if(!$insert_campanas_whatsapp_tipo_categoria_ciudadano_historicos || $num=0){
						$success=false;
						echo "ERROR insert_campanas_whatsapp_tipo_categoria_ciudadano_historicos"; 
						var_dump($conexion->error);
					}

				}elseif($atributos['id']!='' && $atributos['check']==0 ){
					///eliminamos
					$id = $atributos['id'];
					$delete_campanas_whatsapp = "DELETE FROM campanas_whatsapp_tipos_categorias_ciudadanos  WHERE id = '{$id}' ";
					$delete_campanas_whatsapp=$conexion->query($delete_campanas_whatsapp);
					$num=$conexion->affected_rows;
					if(!$delete_campanas_whatsapp || $num=0){
						$success=false;
						echo "ERROR delete campaña whatsapp cartografias"; 
						echo "<br>";
						echo("Errorcode: " . mysqli_errno($conexion));
						echo "<br>";
					}
				}else{}
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],"campañas_whatsapp",$id_campana_whatsapp,'Update','',$fechaH);
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

