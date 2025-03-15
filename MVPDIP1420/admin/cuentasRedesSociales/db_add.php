<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/tools.php";
	include __DIR__."/../functions/cuentas_redes_sociales.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('perfiles','cuentas_redes_sociales',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}

	if(!empty($_POST)){

		$cuenta_red_socialClaveVerificacion=cuenta_red_socialClaveVerificacion($_POST["cuenta_red_social"][0]['clave'],'',1);
		if($cuenta_red_socialClaveVerificacion){
			$claveF= clave('cuentas_redes_sociales');
			if(empty($claveF['input'])){
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			}else{
				$_POST["cuenta_red_social"][0]['clave'] = $claveF['clave'];
			}
		}

		$success=true;
		//metemos los valores para que se no tengamos error
		foreach($_POST["cuenta_red_social"][0] as $keyPrincipal => $atributo) {
			$_POST["cuenta_red_social"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$_POST["cuenta_red_social"][0]['fechaR']=$fechaH;
		$_POST["cuenta_red_social"][0]['codigo_plataforma']=$codigo_plataforma;
		$_POST["cuenta_red_social"][0]['fecha_hora_emision'] = $_POST["cuenta_red_social"][0]['fecha_emision']." ".$_POST["cuenta_red_social"][0]['hora_emision'];

		$ip =  $_POST["cuenta_red_social"][0]['ip'];

		$_SERVER['REMOTE_ADDR'] = $ip;
		$_SERVER['HTTP_USER_AGENT'] = $_POST["cuenta_red_social"][0]['user_agent'];
		$SERVERDATA= $_SERVER;

		
		setcookie("mac_address",$_POST["cuenta_red_social"][0]['mac_address'], array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));



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

		$loc_script = $_POST["cuenta_red_social"][0]['loc_script'];
		if($loc_script != ""){
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
		}

		foreach ($detectUsuarioDatos as $key => $value) {
			if($value==""){
				unset($detectUsuarioDatos[$key]);
			}
			if($value==false){
				unset($detectUsuarioDatos[$key]);
			}

			if($value==1){

				if($key == "id_usuario" || $key == "1"){
					//echo $key;
					//echo "<br>";
				}else{
					$detectUsuarioDatos[$key] = 'SI';
				}
			}
		}

		//die;

		if($detectUsuarioDatos['ipName']==""){
			$detectUsuarioDatos['ipName'] = $detectUsuarioDatos['hostname'];
		}

		$_POST["cuenta_red_social"][0]=array_merge($detectUsuarioDatos, $_POST["cuenta_red_social"][0]);

		 
		//$_POST["cuenta_red_social"][0]['detalle']=mysqli_real_escape_string($conexion,$_POST["cuenta_red_social"][0]['detalle']);

		$fields_pdo = "`".implode('`,`', array_keys($_POST["cuenta_red_social"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["cuenta_red_social"][0])."'";
		$inset_cuentas_redes_sociales= "INSERT INTO cuentas_redes_sociales ($fields_pdo) VALUES ($values_pdo);";
		$conexion->autocommit(FALSE);

		$inset_cuentas_redes_sociales=$conexion->query($inset_cuentas_redes_sociales);
		$num=$conexion->affected_rows;
		if(!$inset_cuentas_redes_sociales || $num=0){
			$success=false;
			echo "ERROR inset_cuentas_redes_sociales"; 
			var_dump($conexion->error);
		}

		$id=$_POST["cuenta_red_social"][0]['id_cuenta_red_social']=$conexion->insert_id;
		$fields_pdo = "`".implode('`,`', array_keys($_POST["cuenta_red_social"][0]))."`";
		$values_pdo = "'".implode("','", $_POST["cuenta_red_social"][0])."'";
		$inset_cuentas_redes_sociales_historicos= "INSERT INTO cuentas_redes_sociales_historicos ($fields_pdo) VALUES ($values_pdo);";
		 

		$inset_cuentas_redes_sociales_historicos=$conexion->query($inset_cuentas_redes_sociales_historicos);
		$num=$conexion->affected_rows;
		if(!$inset_cuentas_redes_sociales_historicos || $num=0){
			$success=false;
			echo "ERROR inset_cuentas_redes_sociales_historicos"; 
			var_dump($conexion->error);
		}


		if($success){
			$log= logUsuario($_COOKIE["id_usuario"],'cuentas_redes_sociales',$id,'Insert','',$fechaH);
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