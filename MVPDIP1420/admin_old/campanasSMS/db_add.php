<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/campanas_sms.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_sms',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["tipo_casilla"][0]);
	if(!empty($_POST)){

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
				foreach ($atributos as $key => $value) {
					$_POST["campana_sms_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["campana_sms_tipo_ciudadano"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["campana_sms_tipo_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}

		foreach($_POST["campana_sms_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['id']=='' && $atributos['check']==1 ){
				foreach ($atributos as $key => $value) {
					$_POST["campana_sms_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["campana_sms_tipo_categoria_ciudadano"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["campana_sms_tipo_categoria_ciudadano"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}
	 


		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["campana_sms"][0]['fechaR']=$fechaH; 
		$_POST["campana_sms"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["campana_sms"][0]['envio'] = 0;

		$_POST["campana_sms_cuerpo"][0]['fechaR']=$fechaH; 
		$_POST["campana_sms_cuerpo"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_sms_programada"][0]['fechaR']=$fechaH; 
		$_POST["campana_sms_programada"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_sms_encuesta"][0]['fechaR']=$fechaH; 
		$_POST["campana_sms_encuesta"][0]['codigo_plataforma']=$codigo_plataforma;

		$_POST["campana_sms_cartografia"][0]['fechaR']=$fechaH; 
		$_POST["campana_sms_cartografia"][0]['codigo_plataforma']=$codigo_plataforma;

		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_sms'][0])."'";
		$insert_campanas_sms= "INSERT INTO campanas_sms ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_sms=$conexion->query($insert_campanas_sms);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_sms || $num=0){
			$success=false;
			echo "ERROR insert_campanas_sms"; 
			var_dump($conexion->error);
		}

		$id_campana_sms=$_POST['campana_sms'][0]['id_campana_sms']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_sms'][0])."'";
		$insert_campanas_sms_historicos= "INSERT INTO campanas_sms_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_sms_historicos=$conexion->query($insert_campanas_sms_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_sms_historicos || $num=0){
			$success=false;
			echo "ERROR insert_campanas_sms_historicos"; 
			var_dump($conexion->error);
		}

		//metemos en el cuerpo
		$_POST["campana_sms_cuerpo"][0]['id_campana_sms'] = $id_campana_sms;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_cuerpo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_sms_cuerpo'][0])."'";
		$insert_campanas_sms= "INSERT INTO campanas_sms_cuerpos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_sms=$conexion->query($insert_campanas_sms);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_sms || $num=0){
			$success=false;
			echo "ERROR insert_campanas_sms"; 
			var_dump($conexion->error);
		}

		$_POST['campana_sms_cuerpo'][0]['id_campana_sms_cuerpo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_cuerpo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['campana_sms_cuerpo'][0])."'";
		$insert_campanas_sms_historicos= "INSERT INTO campanas_sms_cuerpos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$insert_campanas_sms_historicos=$conexion->query($insert_campanas_sms_historicos);
		$num=$conexion->affected_rows;
		if(!$insert_campanas_sms_historicos || $num=0){
			$success=false;
			echo "ERROR insert_campanas_sms_historicos"; 
			var_dump($conexion->error);
		}

		//metemos en el programada
		if($_POST["campana_sms"][0]['tipo']==2){
			$_POST["campana_sms_programada"][0]['id_campana_sms'] = $id_campana_sms;
			$_POST["campana_sms_programada"][0]['fecha_hora']=$_POST["campana_sms_programada"][0]['fecha']." ".$_POST["campana_sms_programada"][0]['hora'];
			$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_programada'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['campana_sms_programada'][0])."'";
			$insert_campanas_sms_programada= "INSERT INTO campanas_sms_programadas ($fields_pdo) VALUES ($values_pdo);";

			$insert_campanas_sms_programada=$conexion->query($insert_campanas_sms_programada);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_sms_programada || $num=0){
				$success=false;
				echo "ERROR insert_campanas_sms"; 
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

			foreach($_POST["campana_sms_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['check']==1 ){
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

				}
			}
		}
		if($_POST["campana_sms"][0]['tipo']==3){
			$_POST["campana_sms_encuesta"][0]['id_campana_sms'] = $id_campana_sms;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['campana_sms_encuesta'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['campana_sms_encuesta'][0])."'";
			$insert_campanas_sms_encuesta= "INSERT INTO campanas_sms_encuestas ($fields_pdo) VALUES ($values_pdo);";

			$insert_campanas_sms_encuesta=$conexion->query($insert_campanas_sms_encuesta);
			$num=$conexion->affected_rows;
			if(!$insert_campanas_sms_encuesta || $num=0){
				$success=false;
				echo "ERROR insert_campanas_sms"; 
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

			foreach($_POST["campana_sms_tipo_categoria_ciudadano"] as $keyPrincipal => $atributos) {
				if($atributos['check']==1 ){
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

				}
			}


		}


		if($_POST["campana_sms_cartografia"][0]['tipo_cartografia'] != ''){
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
		}


		foreach($_POST["campana_sms_tipo_ciudadano"] as $keyPrincipal => $atributos) {
			if($atributos['check']==1 ){
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
			}
		}




		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'campañas_sms',$id_campana_sms,'Insert','',$fechaH);
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