<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','programas_apoyos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//var_dump($_POST["programa_apoyo"][0]);
	if(!empty($_POST)){
		//metemos los valores para que se no tengamos error
		foreach($_POST["programa_apoyo"][0] as $keyPrincipal => $atributo) {
			$_POST["programa_apoyo"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$programa_apoyoClaveVerificacion=programa_apoyoClaveVerificacion($_POST["programa_apoyo"][0]['clave'],'',1);
		if($programa_apoyoClaveVerificacion){
			$claveF= clave('programas_apoyos');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["programa_apoyo"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		$conexion->autocommit(FALSE);
		$_POST["programa_apoyo"][0]['fechaR']=$fechaH; 
		$_POST["programa_apoyo"][0]['codigo_plataforma']=$codigo_plataforma;


		$fields_pdo = "`".implode('`,`', array_keys($_POST['programa_apoyo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['programa_apoyo'][0])."'";
		$inset_programas_apoyos= "INSERT INTO programas_apoyos ($fields_pdo) VALUES ($values_pdo);";

		$inset_programas_apoyos=$conexion->query($inset_programas_apoyos);
		$num=$conexion->affected_rows;
		if(!$inset_programas_apoyos || $num=0){
			$success=false;
			echo "ERROR inset_programas_apoyos"; 
			var_dump($conexion->error);
		}

		$id_programa_apoyo=$_POST['programa_apoyo'][0]['id_programa_apoyo']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['programa_apoyo'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['programa_apoyo'][0])."'";
		$inset_programas_apoyos_historicos= "INSERT INTO programas_apoyos_historicos ($fields_pdo) VALUES ($values_pdo);";

		$inset_programas_apoyos_historicos=$conexion->query($inset_programas_apoyos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_programas_apoyos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_programas_apoyos_historicos"; 
			var_dump($conexion->error);
		}


		foreach($_POST["programas_apoyos_territorios"] as $keyPrincipal => $atributos) {
			if($atributos['id']=='' && $atributos['check']==1 ){
				foreach ($atributos as $key => $value) {
					$_POST["programas_apoyos_territorios"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["programas_apoyos_territorios"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["programas_apoyos_territorios"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}

		foreach($_POST["programas_apoyos_territorios"] as $keyPrincipal => $atributos) {
			if($atributos['check']==1 ){
				///insertamos
				unset($atributos['check']);
				unset($atributos['id']);
				$atributos['codigo_plataforma']=$codigo_plataforma;
				$atributos['fechaR']=$fechaH;
				$atributos['id_programa_apoyo']=$id_programa_apoyo;

				$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
				$values_pdo = "'".implode("','", $atributos)."'";
				$insert_campanas_sms_tipo_ciudadano= "INSERT INTO programas_apoyos_territorios ($fields_pdo) VALUES ($values_pdo);";
				$insert_campanas_sms_tipo_ciudadano=$conexion->query($insert_campanas_sms_tipo_ciudadano);
				$num=$conexion->affected_rows;
				if(!$insert_campanas_sms_tipo_ciudadano || $num=0){
					$success=false;
					echo "ERROR insert_programas_apoyos_territorios"; 
					var_dump($conexion->error);
				}

				$atributos['id_programa_apoyo_territorio']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
				$values_pdo = "'".implode("','", $atributos)."'";
				$insert_campanas_sms_tipo_ciudadano_historicos= "INSERT INTO programas_apoyos_territorios_historicos ($fields_pdo) VALUES ($values_pdo);";

				$insert_campanas_sms_tipo_ciudadano_historicos=$conexion->query($insert_campanas_sms_tipo_ciudadano_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_campanas_sms_tipo_ciudadano_historicos || $num=0){
					$success=false;
					echo "ERROR insert_programas_apoyos_territorios_historicos"; 
					var_dump($conexion->error);
				}
			}
		}


		foreach($_POST["programas_apoyos_categorias"] as $keyPrincipal => $atributos) {
			if($atributos['id']=='' && $atributos['check']==1 ){
				foreach ($atributos as $key => $value) {
					$_POST["programas_apoyos_categorias"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
				}
			}else{
				if($_POST["programas_apoyos_categorias"][0]['id'] !='' && $atributos['check']==0){
					foreach ($atributos as $key => $value) {
						$_POST["programas_apoyos_categorias"][$keyPrincipal][$key] = mysqli_real_escape_string($conexion,$value);
					}
				}
			}
		}

		foreach($_POST["programas_apoyos_categorias"] as $keyPrincipal => $atributos) {
			if($atributos['check']==1 ){
				///insertamos
				unset($atributos['check']);
				unset($atributos['id']);
				$atributos['codigo_plataforma']=$codigo_plataforma;
				$atributos['fechaR']=$fechaH;
				$atributos['id_programa_apoyo']=$id_programa_apoyo;

				$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
				$values_pdo = "'".implode("','", $atributos)."'";
				$insert_campanas_sms_tipo_ciudadano= "INSERT INTO programas_apoyos_categorias ($fields_pdo) VALUES ($values_pdo);";
				$insert_campanas_sms_tipo_ciudadano=$conexion->query($insert_campanas_sms_tipo_ciudadano);
				$num=$conexion->affected_rows;
				if(!$insert_campanas_sms_tipo_ciudadano || $num=0){
					$success=false;
					echo "ERROR insert_programas_apoyos_categorias";
					var_dump($conexion->error);
				}

				$atributos['id_programa_apoyo_categoria']=$conexion->insert_id;
				$fields_pdo = "`".implode('`,`', array_keys($atributos))."`";
				$values_pdo = "'".implode("','", $atributos)."'";
				$insert_campanas_sms_tipo_ciudadano_historicos= "INSERT INTO programas_apoyos_categorias_historicos ($fields_pdo) VALUES ($values_pdo);";

				$insert_campanas_sms_tipo_ciudadano_historicos=$conexion->query($insert_campanas_sms_tipo_ciudadano_historicos);
				$num=$conexion->affected_rows;
				if(!$insert_campanas_sms_tipo_ciudadano_historicos || $num=0){
					$success=false;
					echo "ERROR insert_programas_apoyos_categorias_historicos";
					var_dump($conexion->error);
				}
			}
		}




		$_POST["programas_apoyos_dependencias"][0]['fechaR']=$fechaH; 
		$_POST["programas_apoyos_dependencias"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["programas_apoyos_dependencias"][0]['id_programa_apoyo']=$id_programa_apoyo;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['programas_apoyos_dependencias'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['programas_apoyos_dependencias'][0])."'";
		$inset_programas_apoyos= "INSERT INTO programas_apoyos_dependencias ($fields_pdo) VALUES ($values_pdo);";

		$inset_programas_apoyos=$conexion->query($inset_programas_apoyos);
		$num=$conexion->affected_rows;
		if(!$inset_programas_apoyos || $num=0){
			$success=false;
			echo "ERROR inset_programas_apoyos"; 
			var_dump($conexion->error);
		}

		$id_programa_apoyo_dependencia=$_POST['programas_apoyos_dependencias'][0]['id_programa_apoyo_dependencia']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['programas_apoyos_dependencias'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['programas_apoyos_dependencias'][0])."'";
		$inset_programas_apoyos_historicos= "INSERT INTO programas_apoyos_dependencias_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_programas_apoyos_historicos=$conexion->query($inset_programas_apoyos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_programas_apoyos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_programas_apoyos_historicos"; 
			var_dump($conexion->error);
		}



		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'programas_apoyos',$id_programa_apoyo,'Insert','',$fechaH);
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