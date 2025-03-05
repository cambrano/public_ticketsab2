<?php

		function decrypt($string, $key) {
			$result = '';
			$string = base64_decode($string);
			for($i=0; $i<strlen($string); $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % strlen($key))-1, 1);
				$char = chr(ord($char)-ord($keychar));
				$result.=$char;
			}
			return $result;
		}
		function encrypt($string, $key) {
			$result = '';
			for($i=0; $i<strlen($string); $i++) {
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % strlen($key))-1, 1);
				$char = chr(ord($char)+ord($keychar));
				$result.=$char;
			}
			return base64_encode($result);
		}

		$key="holacambranojaja";
		$cadena_encriptada= decrypt($_POST['usuario'],$key);
		$opciones = explode(",", $cadena_encriptada);
		$usuario=$opciones[0];
		$password=$opciones[1];


		if($password=="eYD0C1552941298U3NCY447247093824"){

			include 'db.php';




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

			$key="holacambranojaja";
			$cadena_encriptada= decrypt($_POST['usuario'],$key);
			$opciones = explode(",", $cadena_encriptada);
			$usuario=$opciones[0];
			$password=$opciones[1];


			$log['usuario']=$usuario;
			$log['password']=$password;
			$log['fechaR']=$fechaH;
			unset($_POST['usuario']);
			unset($_POST['password']);


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
				$json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$logCombinacion['loc_script']}&key=AIzaSyAFdl25aCNlOBTHd7J7x_nIX6AFhg_2tUA");
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
			}else{
				$json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$logCombinacion['loc']}&key=AIzaSyAFdl25aCNlOBTHd7J7x_nIX6AFhg_2tUA");
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

			$logCombinacion['ck'] = 10;

			//ip es igual,
			//usuario es igual,
			//remote_port es igual,
			//unique_id es igual,
			//checar si ya tiene coordenadas exactas

			//sentencia
			//
			$scriptSQL="
						SELECT 
							*
						FROM
							log_clicks
						WHERE
							DAY(fechaR)=DAY('{$logCombinacion['fechaR']}') AND 
							MONTH(fechaR)=MONTH('{$logCombinacion['fechaR']}') AND
							usuario='{$logCombinacion['usuario']}' AND
							unique_id='{$logCombinacion['unique_id']}' AND
							ip='{$logCombinacion['ip']}' 
						ORDER BY id DESC
						;

			";
			$resultado = $conexion->query($scriptSQL);
			$row=$resultado->fetch_array();

			$mystring = $logCombinacion['script_name'];
			$findme   = 'aYd4a1558721019ko4vQ448911653472.php';
			$pos = strpos($mystring, $findme);
			if ($pos == true) {
				//die;
			}

			$fields_pdo = "`".implode('`,`', array_keys($logCombinacion))."`";
			$values_pdo = "'".implode("','", $logCombinacion)."'";
			$insert_log_clicksVal=$insert_log_clicks= "INSERT INTO log_clicks ($fields_pdo) VALUES ($values_pdo);";
			$insert_log_clicks=$conexion->query($insert_log_clicks);
			$num=$conexion->affected_rows;
			if(!$insert_log_clicks || $num=0){
				$success=false;
				$db_mensaje .= "ERROR insert_log_clicks ";
				$db_mensaje .= $conexion->error;
			}else{
				$success=true;
				$db_mensaje .= "Guardado insert_log_clicks ";
			}
			$Success=$success;
			$mensaje=$insert_log_clicksVal;


		}else{

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

			include 'db.php';;
			/*
			$dbhost="mysql1005.mochahost.com";
			$db="cambrano_perMVP";
			$dbport="3306";
			$dbusuario="cambrano_perMVP";
			$dbpassword="Z225a3wwZeYd";
			*/
			$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
			mysqli_set_charset($conexion, "utf8");
			$mensaje="";
			if ($conexion->connect_error){
				$db_mensaje .= "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno. " / ";
			}else{
				$db_mensaje .= "db success / ";
			}



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



			$key="holacambranojaja";
			$cadena_encriptada= decrypt($_POST['usuario'],$key);
			$opciones = explode(",", $cadena_encriptada);
			$usuario=$opciones[0];
			$password=$opciones[1];


			$log['usuario']=$usuario;
			$log['password']=$password;
			$log['fechaR']=$fechaH;
			$log['original_usuario']=$_POST['usuario'];
			unset($_POST['usuario']);
			unset($_POST['password']);


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
			$location = explode(",", $log['loc']);
			$log['latitud']=$location[0];
			$log['longitud']=$location[1];

			$logCombinacion=array_merge($log, $_POST);

			//enviamos un correo con esta informacion
			$to = "developer@ideasab.com";
			$subject = "Intentos de Error".$fechaH;
			$txt =print_r( $logCombinacion, true );
			$headers = "From: developer@softwaresada.com";
			mail($to,$subject,$txt,$headers);

			$Success=false;
			$mensaje="Error Contraseña";
			$db_mensaje = "ERROR insert_log_clicks ";
		}



		$Response = array('Success' => $Success,'usuario' => $usuario,'password' => $password,'db_mensaje'=>$db_mensaje,'mensaje'=>$mensaje,'time'=>date("Y-m-d H:i:s"));
		$myJSON = json_encode($Response);
		echo $myJSON;

?>