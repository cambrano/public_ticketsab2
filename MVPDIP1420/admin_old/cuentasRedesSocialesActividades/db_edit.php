<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/cuentas_redes_sociales_actividades.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles',"cuentas_actividades",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	//metemos los valores para que se no tengamos error
	foreach($_POST["cuenta_red_social_actividad"][0] as $keyPrincipal => $atributo) {
		$_POST["cuenta_red_social_actividad"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
	}

	if( registrosCompara("cuentas_redes_sociales_actividades",$_POST['cuenta_red_social_actividad'][0],1)){
		if(!empty($_POST)){
			$_POST["cuenta_red_social_actividad"][0]['fechaR']=$fechaH;
			$_POST["cuenta_red_social_actividad"][0]['codigo_plataforma']=$codigo_plataforma;
			$_POST["cuenta_red_social_actividad"][0]['fecha_hora_emision'] = $_POST["cuenta_red_social_actividad"][0]['fecha_emision']." ".$_POST["cuenta_red_social_actividad"][0]['hora_emision'];

			$cuenta_red_social_actividadDatos=cuenta_red_social_actividadDatos($_POST["cuenta_red_social"][0]['id']);
			$_POST["cuenta_red_social_actividad"][0]=array_merge($cuenta_red_social_actividadDatos, $_POST["cuenta_red_social_actividad"][0]);


			$tipo_actividadClaveVerificacion=tipo_actividadClaveVerificacion($_POST["cuenta_red_social_actividad"][0]["clave"],$_POST["cuenta_red_social_actividad"][0]['id'],1);
			if($tipo_actividadClaveVerificacion){
				$claveF= clave("cuentas_redes_sociales_actividades");
				if(empty($claveF['input'])){
					echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
					die;
				}else{
					$_POST["cuenta_red_social_actividad"][0]["clave"] = $claveF["clave"];
				}
			}



			if($cuenta_red_social_actividadDatos['ip'] != $_POST["cuenta_red_social_actividad"][0]['ip']){
				$ip =  $_POST["cuenta_red_social_actividad"][0]['ip'];
				$_SERVER['REMOTE_ADDR'] = $ip;
				$_SERVER['HTTP_USER_AGENT'] = $_POST["cuenta_red_social_actividad"][0]['user_agent'];
				$SERVERDATA= $_SERVER;
				include __DIR__."/../functions/tools.php";
				$detectUsuarioDatos=detectUsuarioPOST_Datos($SERVERDATA);
				$json = file_get_contents("https://ipinfo.io/{$ip}/geo");
				$details = json_decode($json, true);
				$detectUsuarioDatos['city']=$details['city'];
				$detectUsuarioDatos['region']=$details['region'];
				$detectUsuarioDatos['country']=$details['country'];
				$detectUsuarioDatos['loc']=$details['loc'];
				$detectUsuarioDatos['zip_code']=$details['postal'];
				$location = explode(",", $detectUsuarioDatos['loc']);
				$detectUsuarioDatos['latitud']=$location[0];
				$detectUsuarioDatos['longitud']=$location[1];

				if($detectUsuarioDatos['loc']==""){
					unset($details);
					$json = file_get_contents("https://freegeoip.app/json/{$ip}");
					$details = json_decode($json, true);
					$detectUsuarioDatos['city']=$details['city'];
					$detectUsuarioDatos['region']=$details['region_name'];
					$detectUsuarioDatos['country']=$details['country_code'];
					$detectUsuarioDatos['zip_code']=$details['zip_code'];

					$detectUsuarioDatos['latitud']=$details['latitude'];
					$detectUsuarioDatos['longitud']=$details['longitude'];
					$detectUsuarioDatos['loc']=$details['latitude'].','.$details['longitude'];
				}

				if($detectUsuarioDatos['loc']==""){
					foreach ($detectUsuarioDatos as $key => $value) {
						if($value==""){
							$detectUsuarioDatos[$key] = "Privado";
						}
					}
				}

				$json = file_get_contents("http://extreme-ip-lookup.com/json/{$ip}?key=jidr1wki00K7iOUfyaew");
				$detailsISP = json_decode($json, true);
				$detectUsuarioDatos['ipName']=$detailsISP['ipName'];

				$detectUsuarioDatos['ip_type']=$detailsISP['ipType'];
				$detectUsuarioDatos['isp']=$detailsISP['isp'];
				$detectUsuarioDatos['org']=$detailsISP['org'];

				$json = file_get_contents("http://ip-api.com/json/{$ip}?fields=status,message,asname,mobile,proxy,hosting,query");
				$detailsService = json_decode($json, true);
				$detectUsuarioDatos['asname']=$detailsService['asname'];
				$detectUsuarioDatos['hosting']=$detailsService['hosting'];
				$detectUsuarioDatos['proxy']=$detailsService['proxy'];
				$detectUsuarioDatos['mobile']=$detailsService['mobile'];


				$json = file_get_contents("https://api.ipdata.co/{$ip}?api-key=1ee6c3e0c29d83baeaf6502c2a27c0bff4361e24a89de22d4ff5bee8");
				$detailsSecurity = json_decode($json, true);
				$detectUsuarioDatos['asn']=$detailsSecurity['asn']['asn'];
				$detectUsuarioDatos['route']=$detailsSecurity['asn']['route'];
				$detectUsuarioDatos['domain']=$detailsSecurity['asn']['domain'];
				$detectUsuarioDatos['type']=$detailsSecurity['asn']['type'];
				$detectUsuarioDatos['mobile']=$detailsSecurity['asn']['mobile'];

				$detectUsuarioDatos['is_tor']=$detailsSecurity['threat']['is_tor'];
				$detectUsuarioDatos['is_proxy']=$detailsSecurity['threat']['is_proxy'];
				$detectUsuarioDatos['is_anonymous']=$detailsSecurity['threat']['is_anonymous'];
				$detectUsuarioDatos['is_known_attacker']=$detailsSecurity['threat']['is_known_attacker'];
				$detectUsuarioDatos['is_known_abuser']=$detailsSecurity['threat']['is_known_abuser'];
				$detectUsuarioDatos['is_threat']=$detailsSecurity['threat']['is_threat'];
				$detectUsuarioDatos['is_bogon']=$detailsSecurity['threat']['is_bogon'];

				$_POST["cuenta_red_social_actividad"][0]=array_merge($detectUsuarioDatos, $_POST["cuenta_red_social_actividad"][0]);
			}

			if($cuenta_red_socialDatos['loc_script'] != $_POST["cuenta_red_social"][0]['loc_script']){
				$loc_script = $_POST["cuenta_red_social"][0]['loc_script'];
				$json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$loc_script}&key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI");
				$detailsGPS = json_decode($json, true);
				$detectUsuarioDatos['direccion_completa']=$detailsGPS['results'][0]['formatted_address'];
				$detectUsuarioDatos['direccion_numero']=$detailsGPS['results'][0]['address_components'][0]['long_name'];
				$detectUsuarioDatos['direccion_calle']=$detailsGPS['results'][0]['address_components'][1]['long_name'];
				$detectUsuarioDatos['direccion_colonia']=$detailsGPS['results'][0]['address_components'][2]['long_name'];

				foreach ($detailsGPS['results'][0]['address_components'] as $key => $value) {
					if (in_array("country", $value['types'])) {
						if($value['short_name'] != $detectUsuarioDatos['country_iso']){
							$detectUsuarioDatos['country'] = $value['short_name'];
						}
					}
					
					if (in_array("administrative_area_level_1", $value['types'])) {
						if($value['long_name'] != $detectUsuarioDatos['region'] && $value['long_name']!="" ){
							if($detectUsuarioDatos['region']==""){
								$detectUsuarioDatos['region'] = $value['long_name'];
							}
						}
					}

					if (in_array("locality", $value['types'])) {
						if($value['long_name'] != $detectUsuarioDatos['city'] && $value['long_name']!="" ){
							if($detectUsuarioDatos['city']==""){
								$detectUsuarioDatos['city'] = $value['long_name'];
							}
						}
					}

					if (in_array("postal_code", $value['types'])) {
						if($value['long_name'] != $detectUsuarioDatos['zip_code'] && $value['long_name']!="" ){
							if($detectUsuarioDatos['zip_code']==""){
								$detectUsuarioDatos['zip_code'] = $value['long_name'];
							}
						}
					}
				}

				$_POST["cuenta_red_social_actividad"][0]=array_merge($detectUsuarioDatos, $_POST["cuenta_red_social_actividad"][0]);
			}


			$success=true;
			foreach($_POST['cuenta_red_social_actividad'] as $keyPrincipal => $atributos) {
				foreach ($atributos as $key => $value) {
					if($key !='id'){
						$valueSets[] = $key . " = '" . $value . "'";
					}else{
						$id=$value;
					}
				}
			}
			
			$update_cuentas_redes_sociales_actividades = "UPDATE cuentas_redes_sociales_actividades SET ". join(",",$valueSets) . " WHERE id=".$id;
			$conexion->autocommit(FALSE);
			$update_cuentas_redes_sociales_actividades=$conexion->query($update_cuentas_redes_sociales_actividades);
			$num=$conexion->affected_rows;
			if(!$update_cuentas_redes_sociales_actividades || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR update_cuentas_redes_sociales_actividades"; 
				var_dump($conexion->error);
			}

			unset($_POST["cuenta_red_social_actividad"][0]['id']); 
			$id_cuenta_red_social_actividad=$_POST['cuenta_red_social_actividad'][0]['id_cuenta_red_social_actividad']=$id;
			$fields_pdo = "`".implode('`,`', array_keys($_POST['cuenta_red_social_actividad'][0]))."`";
			$values_pdo = "'".implode("','", $_POST['cuenta_red_social_actividad'][0])."'";
			$inset_cuentas_redes_sociales_actividades_historicos= "INSERT INTO cuentas_redes_sociales_actividades_historicos ($fields_pdo) VALUES ($values_pdo);";
			$inset_cuentas_redes_sociales_actividades_historicos=$conexion->query($inset_cuentas_redes_sociales_actividades_historicos);
			$num=$conexion->affected_rows;
			if(!$inset_cuentas_redes_sociales_actividades_historicos || $num=0){
				$success=false;
				echo "ERROR inset_cuentas_redes_sociales_actividades_historicos"; 
				var_dump($conexion->error);
			}

			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'cuentas_redes_sociales_actividades',$id_cuenta_red_social_actividad,'Update','',$fechaH);
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