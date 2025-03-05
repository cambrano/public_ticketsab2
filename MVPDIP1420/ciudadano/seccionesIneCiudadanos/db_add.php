<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/usuarios.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/gps_distancias.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/lista_nominal.php";
	include __DIR__."/../functions/api_mailing.php";
	include __DIR__."/../functions/api_sms.php";
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['registro']!=true){
		echo "No tiene permiso.";
		die;
	}
	//metemos los valores para que se no tengamos error
	foreach($_POST["seccion_ine_ciudadano"][0] as $keyPrincipal => $atributo) {
		$_POST["seccion_ine_ciudadano"][0][$keyPrincipal]= rtrim(ltrim(mysqli_real_escape_string($conexion,$atributo)));
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["usuarios"][0] as $keyPrincipal => $atributo) {
		$_POST["usuarios"][0][$keyPrincipal]= rtrim(ltrim(mysqli_real_escape_string($conexion,$atributo)));
	}

	$usuarioValidadorSistema=usuarioValidadorSistema($_POST["usuarios"][0]['usuario'],'','');
	if($usuarioValidadorSistema){
		echo "Favor de Ingresar un Usuario Válido o que no exista en sistema.";
		die;
	}


	$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
	$id_seccion_ine_ciudadano_compartido = $usuarioDatos['id_seccion_ine_ciudadano'];
	$_POST["seccion_ine_ciudadano"][0]['id_seccion_ine_ciudadano_compartido'] = $id_seccion_ine_ciudadano_compartido;

	$claveF= clave('secciones_ine_ciudadanos');
	$_POST["seccion_ine_ciudadano"][0]['clave'] = $claveF['clave'];
	$_POST["usuarios"][0]['clave'] = $claveF['clave'];

	$seccion_ine_ciudadanoClaveVerificacion=seccion_ine_ciudadanoClaveVerificacion($_POST["seccion_ine_ciudadano"][0]['clave'],'',1);
	if($seccion_ine_ciudadanoClaveVerificacion){
		$claveF= clave('secciones_ine_ciudadanos');
		$_POST["seccion_ine_ciudadano"][0]['clave'] = $claveF['clave'];
	}

	$seccion_ine_ciudadanoClaveElectorVerificacion=seccion_ine_ciudadanoClaveElectorVerificacion($_POST["seccion_ine_ciudadano"][0]['clave_elector'],'',1);
	if($seccion_ine_ciudadanoClaveElectorVerificacion>0){
		echo "Esta clave de elector ya esta en el sistema.";
		die;
	}

	if(!empty($_POST)){
		$_POST['seccion_ine_ciudadano'][0]['nombre_completo'] = $_POST['seccion_ine_ciudadano'][0]['nombre'].' '.$_POST['seccion_ine_ciudadano'][0]['apellido_paterno'].' '.$_POST['seccion_ine_ciudadano'][0]['apellido_materno'];

		$seccion_ineDatos=seccion_ineDatos($_POST['seccion_ine_ciudadano'][0]['id_seccion_ine']);
		$latitud = $_POST['seccion_ine_ciudadano'][0]['latitud'];
		$longitud = $_POST['seccion_ine_ciudadano'][0]['longitud'];
		$_POST["seccion_ine_ciudadano"][0]['distancia_m'] = distanceCalculation($latitud, $longitud, $seccion_ineDatos['latitud'], $seccion_ineDatos['longitud'],'m',3);
		$_POST["seccion_ine_ciudadano"][0]['distancia_km'] = distanceCalculation($latitud, $longitud, $seccion_ineDatos['latitud'], $seccion_ineDatos['longitud'],'km',3);

		$_POST["seccion_ine_ciudadano"][0]['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
		$_POST["seccion_ine_ciudadano"][0]['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
		$_POST["seccion_ine_ciudadano"][0]['id_cuartel'] = $seccion_ineDatos['id_cuartel'];
		$_POST["seccion_ine_ciudadano"][0]['seccion'] = $seccion_ineDatos['numero'];

		$success=true;
		$_POST["seccion_ine_ciudadano"][0]['fechaR']=$fechaH;
		$_POST["seccion_ine_ciudadano"][0]['fechaU']=$fechaH;
		$_POST["seccion_ine_ciudadano"][0]['status']=1;
		//$_POST["seccion_ine_ciudadano"][0]['folio'] = 'CIUDADANO';
		//$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
		$_POST["seccion_ine_ciudadano"][0]['folio'] = 'CIDBR'.$usuarioDatos['clave'];

		$_POST["seccion_ine_ciudadano"][0]['fecha_emision'] = date("Y-m-d");
		$_POST["seccion_ine_ciudadano"][0]['hora_emision'] = date("H:i:s");
		$_POST["seccion_ine_ciudadano"][0]['fecha_hora_emision'] = date("Y-m-d H:i:s");


		$diff = (date('Y') - date('Y',strtotime($_POST["seccion_ine_ciudadano"][0]['fecha_nacimiento'])));
		if($diff==""){
			$diff=0;
		}
		$_POST["seccion_ine_ciudadano"][0]['edad'] = $diff;
		$_POST["seccion_ine_ciudadano"][0]['medio_registro'] = 1;
		$latitud_r = $_POST['seccion_ine_ciudadano'][0]['latitud_r'];
		$longitud_r = $_POST['seccion_ine_ciudadano'][0]['longitud_r'];
		$_POST["seccion_ine_ciudadano"][0]['distancia_m_r'] = distanceCalculation($latitud, $longitud, $latitud_r, $longitud_r,'m',3);
		$_POST["seccion_ine_ciudadano"][0]['distancia_km_r'] = distanceCalculation($latitud, $longitud, $latitud_r, $longitud_r,'km',3);
		
		if($_POST["seccion_ine_ciudadano"][0]['distancia_m_r'] > 100){
			$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 1;
		}else{
			$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 0;
		}

		

		$_POST["usuarios"][0]['clave'] = $_POST["seccion_ine_ciudadano"][0]['clave'];

		$_POST["usuarios"][0]['fechaR']=$fechaH;
		//$_POST["usuarios"][0]['status']=1;
		$_POST["usuarios"][0]['id_perfil_usuario']=4;
		$_POST["usuarios"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["usuarios"][0]['tabla']="secciones_ine_ciudadanos";
		$_POST["usuarios"][0]['identificador']=$gen_id3.'_'.$cod32;

		foreach ($_POST["seccion_ine_ciudadano"][0] as $key => $value) {
			if($value==""){
				unset($_POST["seccion_ine_ciudadano"][0][$key]);
			}
		}

		//verificador lista_nominal
		$lista_nominalId = lista_nominalId($_POST["seccion_ine_ciudadano"][0]['clave_elector']);
		if($lista_nominalId != ''){
			if($_POST["seccion_ine_ciudadano"][0]['status_verificacion']==0){
				$_POST["seccion_ine_ciudadano"][0]['status_verificacion'] = 1;
			}
			$_POST["seccion_ine_ciudadano"][0]['id_lista_nominal'] = $lista_nominalId;
		}else{
			$_POST["seccion_ine_ciudadano"][0]['status_verificacion'] = 0;
		}
		$_POST["seccion_ine_ciudadano"][0]['status_verificacion'] = 1;

		if($_POST["seccion_ine_ciudadano"][0]['distancia_m_r'] > 100){
			$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 1;
		}else{
			$_POST["seccion_ine_ciudadano"][0]['distancia_alert'] = 0;
		}

		$_POST["seccion_ine_ciudadano"][0]['status_verificacion'] = 1;

		$_POST["seccion_ine_ciudadano"][0]['fecha_hora_emision'] = $_POST["seccion_ine_ciudadano"][0]['fecha_emision']." ".$_POST["seccion_ine_ciudadano"][0]['hora_emision'];

		$_POST["seccion_ine_ciudadano"][0]['codigo_plataforma']=$codigo_plataforma; 
		$_POST["seccion_ine_ciudadano"][0]['codigo_seccion_ine_ciudadano']=$cod32."_".$codigo_plataforma; 
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano"][0])."'";
		$inset_secciones_ine_ciudadanos= "INSERT INTO secciones_ine_ciudadanos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);
		$inset_secciones_ine_ciudadanos=$conexion->query($inset_secciones_ine_ciudadanos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos"; 
			var_dump($conexion->error);
		}
		$id=$_POST["seccion_ine_ciudadano"][0]['id_seccion_ine_ciudadano']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["seccion_ine_ciudadano"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["seccion_ine_ciudadano"][0])."'";
		$inset_secciones_ine_ciudadanos_historicos= "INSERT INTO secciones_ine_ciudadanos_historicos ($fields_pdo) VALUES ($values_pdo);";
		$inset_secciones_ine_ciudadanos_historicos=$conexion->query($inset_secciones_ine_ciudadanos_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_secciones_ine_ciudadanos_historicos || $num=0){
			$success=false;
			echo "ERROR inset_secciones_ine_ciudadanos_historicos"; 
			var_dump($conexion->error);
		}

		
		$_POST['usuarios'][0]['id_seccion_ine_ciudadano']= $id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['usuarios'][0])."'";
		$inset_usuarios= "INSERT INTO usuarios ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_usuarios=$conexion->query($inset_usuarios);
		$num=$conexion->affected_rows;
		if(!$inset_usuarios || $num=0){
			$success=false;
			echo "ERROR inset_usuarios"; 
			var_dump($conexion->error);
		}

		

		$id_usuario=$_POST['usuarios'][0]['id_usuario']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST['usuarios'][0]))."`";
		$values_pdo = "'".implode("','", $_POST['usuarios'][0])."'";
		$inset_usuarios_historicos= "INSERT INTO usuarios_historicos ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_usuarios_historicos=$conexion->query($inset_usuarios_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_usuarios_historicos || $num=0){
			$success=false;
			echo "ERROR inset_usuarios_historicos"; 
			var_dump($conexion->error);
		}

		///verificamos si esta autorizado los correos desde la api
		///verificamos si esta autorizado los correos desde la api
		$api_mailingDatos = api_mailingDatos();

		if($api_mailingDatos['status'] == 1){
			include __DIR__."/../functions/campanas_mailing_cartografias.php";
			include __DIR__."/../functions/campanas_mailing_cuerpos.php";
			include __DIR__."/../functions/campanas_mailing_tipos_ciudadanos.php";

			// vemos si hay campañas de bienvenida
			$sqlCamMailing = '
				SELECT 
					cm.id,
					cm.tipo,
					cm.nombre,
					scm.status correo_status,
					cm.status campana_status,
					cm.envio envio_status
				FROM campanas_mailing cm 
				LEFT JOIN correos_mailing scm
				ON cm.id_correo_mailing = scm.id
				WHERE cm.status = 1 AND cm.tipo=1 AND  scm.status =1';

			$id_municipio = $_POST["seccion_ine_ciudadano"][0]['id_municipio'];
			$id_distrito_local = $_POST["seccion_ine_ciudadano"][0]['id_distrito_local'];
			$id_distrito_federal = $_POST["seccion_ine_ciudadano"][0]['id_distrito_federal'];
			$id_seccion_ine = $_POST["seccion_ine_ciudadano"][0]['id_seccion_ine'];
			$id_tipo_ciudadano = $_POST["seccion_ine_ciudadano"][0]['id_tipo_ciudadano'];

			$id_estado = $_POST["seccion_ine_ciudadano"][0]['id_estado'];
			$id_municipio = $_POST["seccion_ine_ciudadano"][0]['id_municipio'];
			$codigo_seccion_ine_ciudadano = $_POST["seccion_ine_ciudadano"][0]['codigo_seccion_ine_ciudadano'];

			$resultCamMailing = $conexion->query($sqlCamMailing);  
			while($rowCampMailing=$resultCamMailing->fetch_assoc()){
				$id_campana_mailing = $rowCampMailing['id'];
				$tipo = $rowCampMailing['tipo'];

				$campana_mailing_cartografiaDatos=campana_mailing_cartografiaDatos('',$id_campana_mailing);
				$campanas_mailing_tipos_ciudadanosIdDatos=campanas_mailing_tipos_ciudadanosIdDatos('',$id_campana_mailing); 
				$campana_mailing_cuerpoDatos=campana_mailing_cuerpoDatos('',$id_campana_mailing);
				// verificar tipo de ciudadano
				// tipo de cartografia

				//var_dump($campana_mailing_cartografiaDatos);
				$tipo_cartografia = true;
				if($campana_mailing_cartografiaDatos['tipo_cartografia']=='municipios'){
					if($campana_mailing_cartografiaDatos['id_tipo_cartografia']!=$id_municipio){
						$tipo_cartografia = false;
					}
				}elseif($campana_mailing_cartografiaDatos['tipo_cartografia']=='secciones_ine'){
					if($campana_mailing_cartografiaDatos['id_tipo_cartografia']!=$id_seccion_ine){
						$tipo_cartografia = false;
					}
				}elseif($campana_mailing_cartografiaDatos['tipo_cartografia']=='distritos_locales'){
					if($campana_mailing_cartografiaDatos['id_tipo_cartografia']!=$id_distrito_local){
						$tipo_cartografia = false;
					}
				}
				elseif($campana_mailing_cartografiaDatos['tipo_cartografia']=='distritos_federales'){
					if($campana_mailing_cartografiaDatos['id_tipo_cartografia']!=$id_distrito_federal){
						$tipo_cartografia = false;
					}
				}else{
					$tipo_cartografia = true;
				}

				$tipo_ciudadano = true;
				if(!empty($campanas_mailing_tipos_ciudadanosIdDatos)){
					if(empty($campanas_mailing_tipos_ciudadanosIdDatos[$id_tipo_ciudadano])){
						$tipo_ciudadano = false;
					}
				}

				if($tipo_cartografia && $tipo_ciudadano){
					//envia 
					unset($campana_mailing);
					$campana_mailing['id_seccion_ine_ciudadano'] = $id;
					$campana_mailing['id_seccion_ine'] = $id_seccion_ine;
					$campana_mailing['id_distrito_local'] = $id_distrito_local;
					$campana_mailing['id_distrito_federal'] = $id_distrito_federal;
					$campana_mailing['id_estado'] = $id_estado;
					$campana_mailing['id_municipio'] = $id_municipio;
					$campana_mailing['id_campana_mailing'] = $id_campana_mailing;
					$campana_mailing['id_campana_mailing_cuerpo'] = $campana_mailing_cuerpoDatos['id'];
					$campana_mailing['status'] = 0 ;
					$campana_mailing['fechaR'] = $fechaH;
					$campana_mailing['codigo_plataforma'] = $codigo_plataforma;
					$campana_mailing['codigo_seccion_ine_ciudadano'] = $codigo_seccion_ine_ciudadano;
					$campana_mailing['identificador'] = 1;
					$campana_mailing['fecha_registro'] = $fechaSF;
					$campana_mailing['hora_registro'] = $fechaSH;
					$campana_mailing['fecha_hora_registro'] = $fechaH;
					$campana_mailing['tipo'] = $tipo;

					$fields_pdo = "`".implode('`,`', array_keys($campana_mailing))."`";
					$values_pdo = "'".implode("','", $campana_mailing)."'";
					$insert_secciones_ine_ciudadanos_campanas_mailing_programadas= "INSERT INTO secciones_ine_ciudadanos_campanas_mailing_programadas ($fields_pdo) VALUES ($values_pdo);";
					$conexion->autocommit(FALSE);
					$insert_secciones_ine_ciudadanos_campanas_mailing_programadas=$conexion->query($insert_secciones_ine_ciudadanos_campanas_mailing_programadas);
					$num=$conexion->affected_rows;
					if(!$insert_secciones_ine_ciudadanos_campanas_mailing_programadas || $num=0){
						$success=false;
						echo "ERROR secciones_ine_ciudadanos_campanas_mailing_programadas"; 
						var_dump($conexion->error);
					}
				}
			}
		}

		$api_smsDatos = api_smsDatos();
		if($api_smsDatos['status'] == 1){
			include __DIR__."/../functions/campanas_sms_cartografias.php";
			include __DIR__."/../functions/campanas_sms_cuerpos.php";
			include __DIR__."/../functions/campanas_sms_tipos_ciudadanos.php";

			// vemos si hay campañas de bienvenida
			$sqlCamSMS = '
				SELECT 
					cm.id,
					cm.tipo,
					cm.nombre,
					scm.status api_status,
					cm.status campana_status,
					cm.envio envio_status
				FROM campanas_sms cm 
				LEFT JOIN api_sms scm
				ON cm.id_api_sms = scm.id
				WHERE cm.status = 1 AND cm.tipo=1 AND  scm.status =1';

			$id_municipio = $_POST["seccion_ine_ciudadano"][0]['id_municipio'];
			$id_distrito_local = $_POST["seccion_ine_ciudadano"][0]['id_distrito_local'];
			$id_distrito_federal = $_POST["seccion_ine_ciudadano"][0]['id_distrito_federal'];
			$id_seccion_ine = $_POST["seccion_ine_ciudadano"][0]['id_seccion_ine'];
			$id_tipo_ciudadano = $_POST["seccion_ine_ciudadano"][0]['id_tipo_ciudadano'];

			$id_estado = $_POST["seccion_ine_ciudadano"][0]['id_estado'];
			$id_municipio = $_POST["seccion_ine_ciudadano"][0]['id_municipio'];
			$codigo_seccion_ine_ciudadano = $_POST["seccion_ine_ciudadano"][0]['codigo_seccion_ine_ciudadano'];

			$resultCamSMS = $conexion->query($sqlCamSMS);  
			while($rowCampSMS=$resultCamSMS->fetch_assoc()){
				$id_campana_sms = $rowCampSMS['id'];
				$tipo = $rowCampSMS['tipo'];

				$campana_sms_cartografiaDatos=campana_sms_cartografiaDatos('',$id_campana_sms);
				$campanas_sms_tipos_ciudadanosIdDatos=campanas_sms_tipos_ciudadanosIdDatos('',$id_campana_sms); 
				$campana_sms_cuerpoDatos=campana_sms_cuerpoDatos('',$id_campana_sms);
				// verificar tipo de ciudadano
				// tipo de cartografia

				//var_dump($campana_sms_cartografiaDatos);
				$tipo_cartografia = true;
				if($campana_sms_cartografiaDatos['tipo_cartografia']=='municipios'){
					if($campana_sms_cartografiaDatos['id_tipo_cartografia']!=$id_municipio){
						$tipo_cartografia = false;
					}
				}elseif($campana_sms_cartografiaDatos['tipo_cartografia']=='secciones_ine'){
					if($campana_sms_cartografiaDatos['id_tipo_cartografia']!=$id_seccion_ine){
						$tipo_cartografia = false;
					}
				}elseif($campana_sms_cartografiaDatos['tipo_cartografia']=='distritos_locales'){
					if($campana_sms_cartografiaDatos['id_tipo_cartografia']!=$id_distrito_local){
						$tipo_cartografia = false;
					}
				}
				elseif($campana_sms_cartografiaDatos['tipo_cartografia']=='distritos_federales'){
					if($campana_sms_cartografiaDatos['id_tipo_cartografia']!=$id_distrito_federal){
						$tipo_cartografia = false;
					}
				}else{
					$tipo_cartografia = true;
				}

				$tipo_ciudadano = true;
				if(!empty($campanas_sms_tipos_ciudadanosIdDatos)){
					if(empty($campanas_sms_tipos_ciudadanosIdDatos[$id_tipo_ciudadano])){
						$tipo_ciudadano = false;
					}
				}

				if($tipo_cartografia && $tipo_ciudadano){
					//envia 
					unset($campana_sms);
					$campana_sms['id_seccion_ine_ciudadano'] = $id;
					$campana_sms['id_seccion_ine'] = $id_seccion_ine;
					$campana_sms['id_distrito_local'] = $id_distrito_local;
					$campana_sms['id_distrito_federal'] = $id_distrito_federal;
					$campana_sms['id_estado'] = $id_estado;
					$campana_sms['id_municipio'] = $id_municipio;
					$campana_sms['id_campana_sms'] = $id_campana_sms;
					$campana_sms['id_campana_sms_cuerpo'] = $campana_sms_cuerpoDatos['id'];
					$campana_sms['status'] = 0 ;
					$campana_sms['fechaR'] = $fechaH;
					$campana_sms['codigo_plataforma'] = $codigo_plataforma;
					$campana_sms['codigo_seccion_ine_ciudadano'] = $codigo_seccion_ine_ciudadano;
					$campana_sms['identificador'] = 1;
					$campana_sms['fecha_registro'] = $fechaSF;
					$campana_sms['hora_registro'] = $fechaSH;
					$campana_sms['fecha_hora_registro'] = $fechaH;
					$campana_sms['tipo'] = $tipo;

					$fields_pdo = "`".implode('`,`', array_keys($campana_sms))."`";
					$values_pdo = "'".implode("','", $campana_sms)."'";
					$insert_secciones_ine_ciudadanos_campanas_sms_programadas= "INSERT INTO secciones_ine_ciudadanos_campanas_sms_programadas ($fields_pdo) VALUES ($values_pdo);";
					$conexion->autocommit(FALSE);
					$insert_secciones_ine_ciudadanos_campanas_sms_programadas=$conexion->query($insert_secciones_ine_ciudadanos_campanas_sms_programadas);
					$num=$conexion->affected_rows;
					if(!$insert_secciones_ine_ciudadanos_campanas_sms_programadas || $num=0){
						$success=false;
						echo "ERROR secciones_ine_ciudadanos_campanas_sms_programadas"; 
						var_dump($conexion->error);
					}
				}
			}
		}

		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_ciudadanos',$id,'Insert','',$fechaH);
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
