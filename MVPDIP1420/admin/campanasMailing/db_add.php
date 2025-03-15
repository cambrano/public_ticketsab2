<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/campanas_mailing.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_mailing',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["tipo_casilla"][0]);
	if(!empty($_POST)){

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
				foreach ($atributos as $key => $value) {
					$_POST["campana_mailing_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["campana_mailing_tipo_ciudadano"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["campana_mailing_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}

		foreach($_POST["campana_mailing_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['id']=='' && $atributos['check']==1 ){
				foreach ($atributos as $key => $value) {
					$_POST["campana_mailing_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["campana_mailing_tipo_categoria_ciudadano"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["campana_mailing_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}
	 


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["campana_mailing"][0]['fechaR']=$fechaH; 
		$_POST["campana_mailing"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["campana_mailing"][0]['envio'] = 0;

		$_POST["campana_mailing_cuerpo"][0]['fechaR']=$fechaH; 
		$_POST["campana_mailing_cuerpo"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_mailing_programada"][0]['fechaR']=$fechaH; 
		$_POST["campana_mailing_programada"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_mailing_encuesta"][0]['fechaR']=$fechaH; 
		$_POST["campana_mailing_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_mailing_cartografia"][0]['fechaR']=$fechaH; 
		$_POST["campana_mailing_cartografia"][0]['codigo_plataforma']=$codigo_plataforma;

		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_mailing'][0])."'";
		$insert_campanas_mailing= "INSERT INTO campanas_mailing ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_mailing=$conexion->query($insert_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR insert_campanas_mailing"; 
			var_dump($conexion->error);
		}

		$id_campana_mailing=$_POST['campana_mailing'][0]['id_campana_mailing']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_mailing'][0])."'";
		$insert_campanas_mailing_historicos= "INSERT INTO campanas_mailing_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_mailing_historicos=$conexion->query($insert_campanas_mailing_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_mailing_historicos || $num=0){
			$success=false;
			echo "ERROR insert_campanas_mailing_historicos"; 
			var_dump($conexion->error);
		}

		//metemos en el cuerpo
		$_POST["campana_mailing_cuerpo"][0]['id_campana_mailing'] = $id_campana_mailing;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_cuerpo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_mailing_cuerpo'][0])."'";
		$insert_campanas_mailing= "INSERT INTO campanas_mailing_cuerpos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_mailing=$conexion->query($insert_campanas_mailing);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_mailing || $num=0){
			$success=false;
			echo "ERROR insert_campanas_mailing"; 
			var_dump($conexion->error);
		}

		$_POST['campana_mailing_cuerpo'][0]['id_campana_mailing_cuerpo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_cuerpo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_mailing_cuerpo'][0])."'";
		$insert_campanas_mailing_historicos= "INSERT INTO campanas_mailing_cuerpos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_mailing_historicos=$conexion->query($insert_campanas_mailing_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_mailing_historicos || $num=0){
			$success=false;
			echo "ERROR insert_campanas_mailing_historicos"; 
			var_dump($conexion->error);
		}

		//metemos en el programada
		if($_POST["campana_mailing"][0]['tipo']==2){
			$_POST["campana_mailing_programada"][0]['id_campana_mailing'] = $id_campana_mailing;
			$_POST["campana_mailing_programada"][0]['fecha_hora']=$_POST["campana_mailing_programada"][0]['fecha']." ".$_POST["campana_mailing_programada"][0]['hora'];
			$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_programada'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['campana_mailing_programada'][0])."'";
			$insert_campanas_mailing_programada= "INSERT INTO campanas_mailing_programadas ($fields_pdo) VALUES ($values_pdo);";

			$insert_campanas_mailing_programada=$conexion->query($insert_campanas_mailing_programada);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_mailing_programada || $num=0){
				$success=false;
				echo "ERROR insert_campanas_mailing"; 
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

			foreach($_POST["campana_mailing_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['check']==1 ){
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

				}
			}
		}
		if($_POST["campana_mailing"][0]['tipo']==3){
			$_POST["campana_mailing_encuesta"][0]['id_campana_mailing'] = $id_campana_mailing;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_mailing_encuesta'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['campana_mailing_encuesta'][0])."'";
			$insert_campanas_mailing_encuesta= "INSERT INTO campanas_mailing_encuestas ($fields_pdo) VALUES ($values_pdo);";

			$insert_campanas_mailing_encuesta=$conexion->query($insert_campanas_mailing_encuesta);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_mailing_encuesta || $num=0){
				$success=false;
				echo "ERROR insert_campanas_mailing"; 
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

			foreach($_POST["campana_mailing_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['check']==1 ){
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

				}
			}


		}


		if($_POST["campana_mailing_cartografia"][0]['tipo_cartografia'] != ''){
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
		}


		foreach($_POST["campana_mailing_tipo_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['check']==1 ){
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
			}
		}




		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'campanas_mailing',$id_campana_mailing,'Insert','',$fechaH);
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