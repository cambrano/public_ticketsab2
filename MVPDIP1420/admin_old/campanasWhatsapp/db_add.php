<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/campanas_whatsapp.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_whatsapp',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["tipo_casilla"][0]);
	if(!empty($_POST)){

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
				foreach ($atributos as $key => $value) {
					$_POST["campana_whatsapp_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["campana_whatsapp_tipo_ciudadano"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["campana_whatsapp_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}

		foreach($_POST["campana_whatsapp_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['id']=='' && $atributos['check']==1 ){
				foreach ($atributos as $key => $value) {
					$_POST["campana_whatsapp_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["campana_whatsapp_tipo_categoria_ciudadano"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["campana_whatsapp_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}



		$success=true;
		$conexion->autocommit(FALSE);

		foreach ($_POST['campana_whatsapp'][0] as $key => $value){
			if ($key == 'tipo_sender' && $value =='1' ){
				///Python debemos elminar el id_api_whatsapp
				unset($_POST['campana_whatsapp'][0]['id_api_whatsapp']);

			}
			if ($key == 'tipo_sender' && $value =='2' ){
				///Python debemos elminar el id_api_whatsapp
				unset($_POST['campana_whatsapp'][0]['id_whatsapp_python']);

			}
		}

		

		$_POST["campana_whatsapp"][0]['fechaR']=$fechaH; 
		$_POST["campana_whatsapp"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["campana_whatsapp"][0]['envio'] = 0;

		$_POST["campana_whatsapp_cuerpo"][0]['fechaR']=$fechaH; 
		$_POST["campana_whatsapp_cuerpo"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_whatsapp_programada"][0]['fechaR']=$fechaH; 
		$_POST["campana_whatsapp_programada"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_whatsapp_encuesta"][0]['fechaR']=$fechaH; 
		$_POST["campana_whatsapp_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_whatsapp_cartografia"][0]['fechaR']=$fechaH; 
		$_POST["campana_whatsapp_cartografia"][0]['codigo_plataforma']=$codigo_plataforma;

		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_whatsapp'][0])."'";
		#$values_pdo = str_replace("''", "NULL", $values_pdo);
		//echo "<pre>";
		$insert_campanas_whatsapp= "INSERT INTO campanas_whatsapp ($fields_pdo) VALUES ($values_pdo);";
		//echo "</pre>";
		

		$insert_campanas_whatsapp=$conexion->query($insert_campanas_whatsapp);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_whatsapp || $num=0){
			$success=false;
			echo "ERROR insert_campanas_whatsapp"; 
			var_dump($conexion->error);
		}

		$id_campana_whatsapp=$_POST['campana_whatsapp'][0]['id_campana_whatsapp']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_whatsapp'][0])."'";

		$values_pdo = str_replace("''", "NULL", $values_pdo);

		$insert_campanas_whatsapp_historicos= "INSERT INTO campanas_whatsapp_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_whatsapp_historicos=$conexion->query($insert_campanas_whatsapp_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_whatsapp_historicos || $num=0){
			$success=false;
			echo "ERROR insert_campanas_whatsapp_historicos"; 
			var_dump($conexion->error);
		}

		//metemos en el cuerpo
		$_POST["campana_whatsapp_cuerpo"][0]['id_campana_whatsapp'] = $id_campana_whatsapp;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_cuerpo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_whatsapp_cuerpo'][0])."'";
		$insert_campanas_whatsapp= "INSERT INTO campanas_whatsapp_cuerpos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_whatsapp=$conexion->query($insert_campanas_whatsapp);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_whatsapp || $num=0){
			$success=false;
			echo "ERROR insert_campanas_whatsapp"; 
			var_dump($conexion->error);
		}

		$_POST['campana_whatsapp_cuerpo'][0]['id_campana_whatsapp_cuerpo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_cuerpo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_whatsapp_cuerpo'][0])."'";
		$insert_campanas_whatsapp_historicos= "INSERT INTO campanas_whatsapp_cuerpos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_whatsapp_historicos=$conexion->query($insert_campanas_whatsapp_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_whatsapp_historicos || $num=0){
			$success=false;
			echo "ERROR insert_campanas_whatsapp_historicos"; 
			var_dump($conexion->error);
		}

		//metemos en el programada
		if($_POST["campana_whatsapp"][0]['tipo']==2){
			$_POST["campana_whatsapp_programada"][0]['id_campana_whatsapp'] = $id_campana_whatsapp;
			$_POST["campana_whatsapp_programada"][0]['fecha_hora']=$_POST["campana_whatsapp_programada"][0]['fecha']." ".$_POST["campana_whatsapp_programada"][0]['hora'];
			$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_programada'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['campana_whatsapp_programada'][0])."'";
			$insert_campanas_whatsapp_programada= "INSERT INTO campanas_whatsapp_programadas ($fields_pdo) VALUES ($values_pdo);";

			$insert_campanas_whatsapp_programada=$conexion->query($insert_campanas_whatsapp_programada);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_whatsapp_programada || $num=0){
				$success=false;
				echo "ERROR insert_campanas_whatsapp"; 
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

			foreach($_POST["campana_whatsapp_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['check']==1 ){
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

				}
			}
		}
		if($_POST["campana_whatsapp"][0]['tipo']==3){
			$_POST["campana_whatsapp_encuesta"][0]['id_campana_whatsapp'] = $id_campana_whatsapp;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_whatsapp_encuesta'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['campana_whatsapp_encuesta'][0])."'";
			$insert_campanas_whatsapp_encuesta= "INSERT INTO campanas_whatsapp_encuestas ($fields_pdo) VALUES ($values_pdo);";

			$insert_campanas_whatsapp_encuesta=$conexion->query($insert_campanas_whatsapp_encuesta);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_whatsapp_encuesta || $num=0){
				$success=false;
				echo "ERROR insert_campanas_whatsapp"; 
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

			foreach($_POST["campana_whatsapp_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['check']==1 ){
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

				}
			}


		}


		if($_POST["campana_whatsapp_cartografia"][0]['tipo_cartografia'] != ''){
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
		}


		foreach($_POST["campana_whatsapp_tipo_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['check']==1 ){
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
			}
		}




		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'campañas_whatsapp',$id_campana_whatsapp,'Insert','',$fechaH);
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