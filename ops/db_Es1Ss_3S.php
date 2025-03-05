<?php

	 
	
	
	$id_estado = 31;
	$latitud="20.804850619727194";
	$longitud="-88.9397908318924";
	$estado_nombre = "Yuc.";
/*
	$id_estado = 27;
	$latitud="17.936412456387718";
	$longitud="-92.8633196777344";
	$estado_nombre = "Tab.";*/

	$dbhost = 'localhost';
	$db="cambrano_cozumel";
	//$db="cambrano_cozumel";
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario']="root";
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password']="root";

	$extranjeros_mode=false;
	$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
	mysqli_set_charset($conexion, "utf8mb4"); 
	if ($conexion->connect_error){
		echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
	}

	date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
	setlocale(LC_ALL,"es_ES");
	$fechaH=date('Y-m-d H:i:s');
	$fechaSH=date('H:i:s');
	$fechaSF=date('Y-m-d');
	$nombreSemana= array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
	$numeroSemanaActual=date('w');
	$diaSemanaActual=$nombreSemana[$numeroSemanaActual];
	$numeroMesActual=date('n');
	$nombreMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	$mesNombreAcutal=$nombreMes[$numeroMesActual];

	function fechaNormalSimpleWDDMMAA_ES($fecha){
		$nombreSemana= array('Dom','Lun','Mar','Mie','Jue','Vie','Sab');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Ene','Feb','Mar','Abr','Ma','Jun','Jul','Ago','Sep','Oct','Nov','Dic'); 

		$return=$nombreSemana[$diaSemana]." ".$dia." ".$nombreMes[$mes]." ".$ano;
		return $return;
	}

	function convertidorAMPM($hora=null,$segundos=null,$tipo_letra=null){
		if($tipo_letra=="mayuscula"){
			if($segundos==""){
				$return=date("g:i A",strtotime($hora));
			}else{
				$return=date("g:i:s A",strtotime($hora));
			}
		}else{
			if($segundos==""){
				$return=date("g:i a",strtotime($hora));
			}else{
				$return=date("g:i:s a",strtotime($hora));
			}
		}
		return $return; 
	}



	$_POST['remote_port'] = $_SERVER['REMOTE_PORT'];
	$_POST['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$_POST['ip'] = $_SERVER['REMOTE_ADDR'];
	$_POST['port'] = $_SERVER['REMOTE_PORT'];
	$_POST['hostname'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$_POST['server_name'] = $_SERVER['SERVER_NAME'];
	$_POST['document_root'] = $_SERVER['DOCUMENT_ROOT'];
	$_POST['request_scheme'] = $_SERVER['REQUEST_SCHEME'];
	$_POST['server_software'] = $_SERVER['SERVER_SOFTWARE'];
	$_POST['server_addr'] = $_SERVER['SERVER_ADDR']; 
	$_POST['unique_id'] = $_SERVER['UNIQUE_ID'];
	$_POST['server_port'] = $_SERVER['SERVER_PORT'];
	$_POST['server_admin'] = $_SERVER['SERVER_ADMIN'];
	$_POST['request_time_float'] = $_SERVER['REQUEST_TIME_FLOAT'];


	function getPlatform($user_agent){
		if(strpos($user_agent, 'Windows NT 10.0') !== FALSE)
			return "Windows 10";
		elseif(strpos($user_agent, 'Windows NT 6.3') !== FALSE)
			return "Windows 8.1";
		elseif(strpos($user_agent, 'Windows NT 6.2') !== FALSE)
			return "Windows 8";
		elseif(strpos($user_agent, 'Windows NT 6.1') !== FALSE)
			return "Windows 7";
		elseif(strpos($user_agent, 'Windows NT 6.0') !== FALSE)
			return "Windows Vista";
		elseif(strpos($user_agent, 'Windows NT 5.1') !== FALSE)
			return "Windows XP";
		elseif(strpos($user_agent, 'Windows NT 5.2') !== FALSE)
			return 'Windows 2003';
		elseif(strpos($user_agent, 'Windows NT 5.0') !== FALSE)
			return 'Windows 2000';
		elseif(strpos($user_agent, 'Windows ME') !== FALSE)
			return 'Windows ME';
		elseif(strpos($user_agent, 'Win98') !== FALSE)
			return 'Windows 98';
		elseif(strpos($user_agent, 'Win95') !== FALSE)
			return 'Windows 95';
		elseif(strpos($user_agent, 'WinNT4.0') !== FALSE)
			return 'Windows NT 4.0';
		elseif(strpos($user_agent, 'Windows Phone') !== FALSE)
			return 'Windows Phone';
		elseif(strpos($user_agent, 'Windows') !== FALSE)
			return 'Windows';
		elseif(strpos($user_agent, 'iPhone') !== FALSE)
			return 'iPhone';
		elseif(strpos($user_agent, 'iPad') !== FALSE)
			return 'iPad';
		elseif(strpos($user_agent, 'Debian') !== FALSE)
			return 'Debian';
		elseif(strpos($user_agent, 'Ubuntu') !== FALSE)
			return 'Ubuntu';
		elseif(strpos($user_agent, 'Slackware') !== FALSE)
			return 'Slackware';
		elseif(strpos($user_agent, 'Linux Mint') !== FALSE)
			return 'Linux Mint';
		elseif(strpos($user_agent, 'Gentoo') !== FALSE)
			return 'Gentoo';
		elseif(strpos($user_agent, 'Elementary OS') !== FALSE)
			return 'ELementary OS';
		elseif(strpos($user_agent, 'Fedora') !== FALSE)
			return 'Fedora';
		elseif(strpos($user_agent, 'Kubuntu') !== FALSE)
			return 'Kubuntu';
		elseif(strpos($user_agent, 'Linux') !== FALSE)
			return 'Linux';
		elseif(strpos($user_agent, 'FreeBSD') !== FALSE)
			return 'FreeBSD';
		elseif(strpos($user_agent, 'OpenBSD') !== FALSE)
			return 'OpenBSD';
		elseif(strpos($user_agent, 'NetBSD') !== FALSE)
			return 'NetBSD';
		elseif(strpos($user_agent, 'SunOS') !== FALSE)
			return 'Solaris';
		elseif(strpos($user_agent, 'BlackBerry') !== FALSE)
			return 'BlackBerry';
		elseif(strpos($user_agent, 'Android') !== FALSE)
			return 'Android';
		elseif(strpos($user_agent, 'Mobile') !== FALSE)
			return 'Firefox OS';
		elseif(strpos($user_agent, 'Mac OS X+') || strpos($user_agent, 'CFNetwork+') !== FALSE)
			return 'Mac OS X';
		elseif(strpos($user_agent, 'Macintosh') !== FALSE)
			return 'Mac OS X';
		elseif(strpos($user_agent, 'OS/2') !== FALSE)
			return 'OS/2';
		elseif(strpos($user_agent, 'BeOS') !== FALSE)
			return 'BeOS';
		elseif(strpos($user_agent, 'Nintendo') !== FALSE)
			return 'Nintendo';
		else
			return 'Unknown Platform';
	}
	function getBrowser($user_agent){
		if(strpos($user_agent, 'Maxthon') !== FALSE)
			return "Maxthon";
		elseif(strpos($user_agent, 'SeaMonkey') !== FALSE)
			return "SeaMonkey";
		elseif(strpos($user_agent, 'Vivaldi') !== FALSE)
			return "Vivaldi";
		elseif(strpos($user_agent, 'Arora') !== FALSE)
			return "Arora";
		elseif(strpos($user_agent, 'Avant Browser') !== FALSE)
			return "Avant Browser";
		elseif(strpos($user_agent, 'Beamrise') !== FALSE)
			return "Beamrise";
		elseif(strpos($user_agent, 'Epiphany') !== FALSE)
			return 'Epiphany';
		elseif(strpos($user_agent, 'Chromium') !== FALSE)
			return 'Chromium';
		elseif(strpos($user_agent, 'Iceweasel') !== FALSE)
			return 'Iceweasel';
		elseif(strpos($user_agent, 'Galeon') !== FALSE)
			return 'Galeon';
		elseif(strpos($user_agent, 'Edge') !== FALSE)
			return 'Microsoft Edge';
		elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
			return 'Internet Explorer';
		elseif(strpos($user_agent, 'MSIE') !== FALSE)
			return 'Internet Explorer';
		elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
			return "Opera Mini";
		elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
			return "Opera";
		elseif(strpos($user_agent, 'Firefox') !== FALSE)
			return 'Mozilla Firefox';
		elseif(strpos($user_agent, 'Chrome') !== FALSE)
			return 'Google Chrome';
		elseif(strpos($user_agent, 'Safari') !== FALSE)
			return "Safari";
		elseif(strpos($user_agent, 'iTunes') !== FALSE)
			return 'iTunes';
		elseif(strpos($user_agent, 'Konqueror') !== FALSE)
			return 'Konqueror';
		elseif(strpos($user_agent, 'Dillo') !== FALSE)
			return 'Dillo';
		elseif(strpos($user_agent, 'Netscape') !== FALSE)
			return 'Netscape';
		elseif(strpos($user_agent, 'Midori') !== FALSE)
			return 'Midori';
		elseif(strpos($user_agent, 'ELinks') !== FALSE)
			return 'ELinks';
		elseif(strpos($user_agent, 'Links') !== FALSE)
			return 'Links';
		elseif(strpos($user_agent, 'Lynx') !== FALSE)
			return 'Lynx';
		elseif(strpos($user_agent, 'w3m') !== FALSE)
			return 'w3m';
		else
			return 'No hemos podido detectar su navegador';
	}

	$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
	$os=array("WIN","MAC","LINUX");
	# definimos unos valores por defecto para el navegador y el sistema operativo
	$log['browser'] = "OTHER";
	$log['os'] = "OTHER";
	# buscamos el navegador con su sistema operativo
	foreach($browser as $parent){
		$s = strpos(strtoupper($_POST['user_agent']), $parent);
		$f = $s + strlen($parent);
		$version = substr($_POST['user_agent'], $f, 15);
		$version = preg_replace('/[^0-9,.]/','',$version);
		if ($s)
		{
			$log['version'] = $version;
		}
	}
	$getBrowser=getBrowser($_POST['user_agent']);
	$log['browser'] = $getBrowser;
	$getPlatform=getPlatform($_POST['user_agent']);
	$log['os'] = $getPlatform;

	//$_POST['server_name']="apapachoviajes.com";
	$rest = substr($_POST['server_name'], 0, 4);
	if($rest=="www."){
		$log['server_name'] = substr($_POST['server_name'], 4);
	}else{
		$log['server_name'] = $_POST['server_name'];
	}
	unset($_POST['server_name']);

	$json = file_get_contents("https://ipinfo.io/{$_POST['ip']}/geo");
	$details = json_decode($json, true);
	$log['city']=$details['city'];
	$log['region']=$details['region'];
	$log['country']=$details['country'];
	$log['loc']=$details['loc'];
	$log['zip_code']=$details['postal'];
	$location = explode(",", $log['loc']);
	$log['latitud']=$location[0];
	$log['longitud']=$location[1];

	if($log['loc']==""){
		unset($details);
		$json = file_get_contents("https://freegeoip.app/json/{$_POST['ip']}");
		$details = json_decode($json, true);
		$log['city']=$details['city'];
		$log['region']=$details['region_name'];
		$log['country']=$details['country_code'];
		$log['zip_code']=$details['zip_code'];

		$log['latitud']=$details['latitude'];
		$log['longitud']=$details['longitude'];
		$log['loc']=$details['latitude'].','.$details['longitude'];
	}

	if($log['loc']==""){
		foreach ($logCombinacion as $key => $value) {
			if($value==""){
				$log[$key] = "Privado";
			}
		}
	}

	$logCombinacion=array_merge($log, $_POST);

	//validamos
	$logCombinacion['request_time_float'];
	$logCombinacion['unique_id'];
	$logCombinacion['remote_port'];
	$logCombinacion['fechaR'];
	$logCombinacion['request_uri'];
	$logCombinacion['usuario'];
	$logCombinacion['ip'];
	$logCombinacion['script_name'];
	$logCombinacion['loc_script'];
	$logCombinacion['fechaR'];

	if($logCombinacion['loc_script']!=""){
		//aqui van las location para saber la direccion
		$ch = curl_init();
		$url = "https://nominatim.openstreetmap.org/reverse";
		$dataArray = 
			[
				'lat' => $logCombinacion['latitud_script'],
				'lon' => $logCombinacion['longitud_script'],
				'format' => 'jsonv2',
			];
		$data = http_build_query($dataArray);
		$getUrl = $url."?".$data;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		curl_setopt($ch, CURLOPT_URL, $getUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		$obj = json_decode($result,true);
		curl_close($ch);
		$logCombinacion['direccion_completa'] = $obj['display_name'];
		$logCombinacion['direccion_numero'] = $obj['address']['house_number'];
		$logCombinacion['direccion_calle'] = $obj['address']['road'];
		$logCombinacion['direccion_colonia'] = $obj['address']['quarter'];
		$logCombinacion['city'] = $obj['address']['city'];
		$logCombinacion['region'] = $obj['address']['state'];
		$logCombinacion['zip_code'] = $obj['address']['postcode'];
		$logCombinacion['country'] = strtoupper($obj['address']['country_code']);
		/*
		$json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$logCombinacion['loc_script']}&key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI");
		$detailsGPS = json_decode($json, true);
		$logCombinacion['direccion_completa']=$detailsGPS['results'][0]['formatted_address'];
		$logCombinacion['direccion_numero']=$detailsGPS['results'][0]['address_components'][0]['long_name'];
		$logCombinacion['direccion_calle']=$detailsGPS['results'][0]['address_components'][1]['long_name'];
		$logCombinacion['direccion_colonia']=$detailsGPS['results'][0]['address_components'][2]['long_name'];

		foreach ($detailsGPS['results'][0]['address_components'] as $key => $value) {
			if (in_array("country", $value['types'])) {
				if($value['short_name'] != $log['country_iso']){
					$logCombinacion['country'] = $value['short_name'];
				}
			}

			if (in_array("administrative_area_level_1", $value['types'])) {
				if($value['long_name'] != $logCombinacion['region'] && $value['long_name']!="" ){
					if($logCombinacion['region']==""){
						$logCombinacion['region'] = $value['long_name'];
					}
				}
			}

			if (in_array("locality", $value['types'])) {
				if($value['long_name'] != $logCombinacion['city'] && $value['long_name']!="" ){
					if($logCombinacion['city']==""){
						$logCombinacion['city'] = $value['long_name'];
					}
				}
			}

			if (in_array("postal_code", $value['types'])) {
				if($value['long_name'] != $logCombinacion['zip_code'] && $value['long_name']!="" ){
					if($logCombinacion['zip_code']==""){
						$logCombinacion['zip_code'] = $value['long_name'];
					}
				}
			}
		}
		*/
	}else{
		//aqui van las location para saber la direccion
		$ch = curl_init();
		$url = "https://nominatim.openstreetmap.org/reverse";
		$dataArray = 
			[
				'lat' => $logCombinacion['latitud'],
				'lon' => $logCombinacion['longitud'],
				'format' => 'jsonv2',
			];
		$data = http_build_query($dataArray);
		$getUrl = $url."?".$data;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		curl_setopt($ch, CURLOPT_URL, $getUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		$obj = json_decode($result,true);
		curl_close($ch);
		$logCombinacion['direccion_completa'] = $obj['display_name'];
		$logCombinacion['direccion_numero'] = $obj['address']['house_number'];
		$logCombinacion['direccion_calle'] = $obj['address']['road'];
		$logCombinacion['direccion_colonia'] = $obj['address']['quarter'];
		$logCombinacion['city'] = $obj['address']['city'];
		$logCombinacion['region'] = $obj['address']['state'];
		$logCombinacion['zip_code'] = $obj['address']['postcode'];
		$logCombinacion['country'] = strtoupper($obj['address']['country_code']);
		/*
		$json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$logCombinacion['loc']}&key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI");
		$detailsGPS = json_decode($json, true);
		$logCombinacion['direccion_completa']=$detailsGPS['results'][0]['formatted_address'];
		$logCombinacion['direccion_numero']=$detailsGPS['results'][0]['address_components'][0]['long_name'];
		$logCombinacion['direccion_calle']=$detailsGPS['results'][0]['address_components'][1]['long_name'];
		$logCombinacion['direccion_colonia']=$detailsGPS['results'][0]['address_components'][2]['long_name'];

		foreach ($detailsGPS['results'][0]['address_components'] as $key => $value) {
			if (in_array("country", $value['types'])) {
				if($value['short_name'] != $log['country_iso']){
					$logCombinacion['country'] = $value['short_name'];
				}
			}
			
			if (in_array("administrative_area_level_1", $value['types'])) {
				if($value['long_name'] != $logCombinacion['region'] && $value['long_name']!="" ){
					if($logCombinacion['region']==""){
						$logCombinacion['region'] = $value['long_name'];
					}
				}
			}

			if (in_array("locality", $value['types'])) {
				if($value['long_name'] != $logCombinacion['city'] && $value['long_name']!="" ){
					if($logCombinacion['city']==""){
						$logCombinacion['city'] = $value['long_name'];
					}
				}
			}

			if (in_array("postal_code", $value['types'])) {
				if($value['long_name'] != $logCombinacion['zip_code'] && $value['long_name']!="" ){
					if($logCombinacion['zip_code']==""){
						$logCombinacion['zip_code'] = $value['long_name'];
					}
				}
			}
		}
		*/
	}

	$json = file_get_contents("http://extreme-ip-lookup.com/json/{$_POST['ip']}?key=jidr1wki00K7iOUfyaew");
	$detailsISP = json_decode($json, true);
	$logCombinacion['ipName']=$detailsISP['ipName'];

	$logCombinacion['ip_type']=$detailsISP['ipType'];
	$logCombinacion['isp']=$detailsISP['isp'];
	$logCombinacion['org']=$detailsISP['org'];

	$json = file_get_contents("http://ip-api.com/json/{$_POST['ip']}?fields=status,message,asname,mobile,proxy,hosting,query");
	$detailsService = json_decode($json, true);
	$logCombinacion['asname']=$detailsService['asname'];
	$logCombinacion['hosting']=$detailsService['hosting'];
	$logCombinacion['proxy']=$detailsService['proxy'];
	$logCombinacion['mobile']=$detailsService['mobile'];


	$json = file_get_contents("https://api.ipdata.co/{$_POST['ip']}?api-key=1ee6c3e0c29d83baeaf6502c2a27c0bff4361e24a89de22d4ff5bee8");
	$detailsSecurity = json_decode($json, true);
	$logCombinacion['asn']=$detailsSecurity['asn']['asn'];
	$logCombinacion['route']=$detailsSecurity['asn']['route'];
	$logCombinacion['domain']=$detailsSecurity['asn']['domain'];
	$logCombinacion['type']=$detailsSecurity['asn']['type'];
	$logCombinacion['mobile']=$detailsSecurity['asn']['mobile'];

	$logCombinacion['is_tor']=$detailsSecurity['threat']['is_tor'];
	$logCombinacion['is_proxy']=$detailsSecurity['threat']['is_proxy'];
	$logCombinacion['is_anonymous']=$detailsSecurity['threat']['is_anonymous'];
	$logCombinacion['is_known_attacker']=$detailsSecurity['threat']['is_known_attacker'];
	$logCombinacion['is_known_abuser']=$detailsSecurity['threat']['is_known_abuser'];
	$logCombinacion['is_threat']=$detailsSecurity['threat']['is_threat'];
	$logCombinacion['is_bogon']=$detailsSecurity['threat']['is_bogon'];

	foreach ($logCombinacion as $key => $value) {
		if($value==""){
			unset($logCombinacion[$key]);
		}
		if($value==false){
			unset($logCombinacion[$key]);
		}

		if($value==1){
			$logCombinacion[$key] = 'SI';
		}
	}

	if($logCombinacion['ipName']==""){
		$logCombinacion['ipName'] = $logCombinacion['hostname'];
	}