<?php
		include __DIR__."/../MVPDIP1420/admin/functions/tool_xhpzab.php";
		include __DIR__."/../MVPDIP1420/admin/functions/timemex.php";
		include __DIR__."/../MVPDIP1420/admin/functions/tools.php";
		include __DIR__."/../MVPDIP1420/admin/functions/apis_ip_geodata.php";

		$key="holacambranojaja";
		$cadena_encriptada= decrypt($_POST['usuario'],$key);
		$opciones = explode(",", $cadena_encriptada);
		$usuario=$opciones[0];
		$password=$opciones[1];
		
		if( $_POST['ip'] == '::1'){
			$_POST['ip'] = '187.147.187.148';
		}

		if($password=="eYD0C1552941298U3NCY447247093824"){
			include __DIR__."/../MVPDIP1420/admin/functions/db.php";
			include __DIR__."/../MVPDIP1420/admin/functions/apis_geocoding.php";
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
			unset($log['password']);
			
			$browserAndOS = detectBrowserAndOS($_POST['user_agent']);
			$log['browser'] = $browserAndOS['browser'];
			$log['version'] = $browserAndOS['version'];
			$log['os'] = $browserAndOS['os'];

			$getBrowser=getBrowser($_POST['user_agent']);
			$log['browser'] = $getBrowser;
			$getPlatform=getPlatform($_POST['user_agent']);
			$log['os'] = $getPlatform;

			$log['server_name'] = server_name($_POST['server_name']);
			unset($_POST['server_name']);

			$sql="SELECT * FROM ips_georeferenciaciones WHERE ip='{$_POST['ip']}' LIMIT 1  ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$row;
			if(!empty($row)){
				unset($row['id']);
				unset($row['fechaR']);
				foreach ($row as $key => $value) {
					$log[$key] = $value;
				}
			}else{
				$log['ip']=$_POST['ip'];
				/*
				$ipinfo = ipinfo_io($_POST['ip']);
				$log['city']=$ipinfo['city'];
				$log['region']=$ipinfo['region'];
				$log['country']=$details['country'];
				$log['loc']=$ipinfo['loc'];
				$log['zip_code']=$ipinfo['postal'];
				$location = explode(",", $log['loc']);
				$log['latitud']=$location[0];
				$log['longitud']=$location[1];
				*/

				$freegeoip = freegeoip_app($_POST['ip']);
				$log['city']=$freegeoip['city'];
				$log['region']=$freegeoip['region_name'];
				$log['country']=$freegeoip['country_code'];
				$log['zip_code']=$freegeoip['zip_code'];

				$log['latitud']=str_replace(',', '.', $freegeoip['latitude']);
				$log['longitud']=str_replace(',', '.', $freegeoip['longitude']);
				$log['loc']=$log['latitud'].','.$log['longitud'];

				if($log['loc']==""){
					foreach ($logCombinacion as $key => $value) {
						if($value==""){
							$log[$key] = "Privado";
						}
					}
				}
				$extreme_ip_lookup = extreme_ip_lookup($_POST['ip']);
				$log['ipName']=$extreme_ip_lookup['ipName'];
				$log['ip_type']=$extreme_ip_lookup['ipType'];
				$log['isp']=$extreme_ip_lookup['isp'];
				$log['org']=$extreme_ip_lookup['org'];

				$ip_api = ip_api($_POST['ip']);
				$log['asname']=$ip_api['asname'];
				$log['hosting']=$ip_api['hosting'];
				$log['proxy']=$ip_api['proxy'];
				$log['mobile']=$ip_api['mobile'];

				$ipdata = api_ipdata($_POST['ip']);
				$log['asn']=$ipdata['asn']['asn'];
				$log['route']=$ipdata['asn']['route'];
				$log['domain']=$ipdata['asn']['domain'];
				$log['type']=$ipdata['asn']['type'];
				$log['mobile']=$ipdata['asn']['mobile'];
				$log['is_tor']=$ipdata['threat']['is_tor'];
				$log['is_proxy']=$ipdata['threat']['is_proxy'];
				$log['is_anonymous']=$ipdata['threat']['is_anonymous'];
				$log['is_known_attacker']=$ipdata['threat']['is_known_attacker'];
				$log['is_known_abuser']=$ipdata['threat']['is_known_abuser'];
				$log['is_threat']=$ipdata['threat']['is_threat'];
				$log['is_bogon']=$ipdata['threat']['is_bogon'];

				//! Guardamos los datos del ip para obtener la informacion despues
				$ip_insert['ip'] = $log['ip'];
				$ip_insert['fechaR'] = $fechaH;
				$ip_insert['city'] = $log['city'];
				$ip_insert['loc'] = $log['loc'];
				$ip_insert['zip_code'] = $log['zip_code'];
				$ip_insert['latitud'] = $log['latitud'];
				$ip_insert['longitud'] = $log['longitud'];
				$ip_insert['ipName'] = $log['ipName'];
				$ip_insert['ip_type'] = $log['ip_type'];
				$ip_insert['isp'] = $log['isp'];
				$ip_insert['org'] = $log['org'];
				$ip_insert['asname'] = $log['asname'];
				$ip_insert['hosting'] = $log['hosting'];
				$ip_insert['proxy'] = $log['proxy'];
				$ip_insert['mobile'] = $log['mobile'];
				$ip_insert['asn'] = $log['asn'];
				$ip_insert['route'] = $log['route'];
				$ip_insert['domain'] = $log['domain'];
				$ip_insert['type'] = $log['type'];
				$ip_insert['is_tor'] = $log['is_tor'];
				$ip_insert['is_proxy'] = $log['is_proxy'];
				$ip_insert['is_anonymous'] = $log['is_anonymous'];
				$ip_insert['is_known_attacker'] = $log['is_known_attacker'];
				$ip_insert['is_known_abuser'] = $log['is_known_abuser'];
				$ip_insert['is_threat'] = $log['is_threat'];
				$ip_insert['is_bogon'] = $log['is_bogon'];
				$ip_insert['region'] = $log['region'];
				$ip_insert['country'] = $log['country'];

				foreach($ip_insert as $keyPrincipal => $atributo) {
					$ip_insert[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
				}

				$fields_pdo = "`".implode('`,`', array_keys($ip_insert))."`";
				$values_pdo = "'".implode("','", $ip_insert)."'";
				$insert_log_usuarios_trackingVal=$insert_log_usuarios_tracking= "INSERT INTO ips_georeferenciaciones ($fields_pdo) VALUES ($values_pdo);";
				$insert_log_usuarios_tracking=$conexion->query($insert_log_usuarios_tracking);
				$num=$conexion->affected_rows;
				if(!$insert_log_usuarios_tracking || $num=0){
					$success=false;
					$db_mensaje.= "ERROR ips_georeferenciaciones ";
					$db_mensaje.= $conexion->error;
				}else{
					$success=true;
					$db_mensaje.= "Guardado ips_georeferenciaciones ";
				}
				$Success=$success;
				//$mensaje=$insert_log_usuarios_trackingVal;
			}
			$logCombinacion=array_merge($log, $_POST);
			if($logCombinacion['ipName']==""){
				$logCombinacion['ipName'] = $logCombinacion['hostname'];
			}

			$arrayApisGeocoding = array(
				'openstreetmap',
				'mapquestapi',
				'api_opencagedata',
				'bingmapsportal',
			);
			// Obtener un índice aleatorio del array
			$indiceAleatorio = array_rand($arrayApisGeocoding);
			// Obtener el valor correspondiente al índice aleatorio
			$valorAleatorio = $arrayApisGeocoding[$indiceAleatorio];

			if($logCombinacion['loc_script']!=""){
				$latitudApi = $logCombinacion['latitud_script'];
				$longirudApi = $logCombinacion['longitud_script'];
				$latitudApi=str_replace(',', '.', $logCombinacion['latitud_script']);
				$longirudApi=str_replace(',', '.', $logCombinacion['longitud_script']);
				$logCombinacion['loc_script'] = $latitudApi.','.$longirudApi;
			}else{
				$latitudApi = $logCombinacion['latitud'];
				$longirudApi = $logCombinacion['longitud'];
			}

			if($valorAleatorio == 'openstreetmap'){
				$obj = openstreetmap($latitudApi,$longirudApi);
			}
			if($valorAleatorio == 'mapquestapi'){
				$obj = mapquestapi($latitudApi,$longirudApi);
			}
			if($valorAleatorio == 'api_opencagedata'){
				$obj = api_opencagedata($latitudApi,$longirudApi);
			}
			if($valorAleatorio == 'bingmapsportal'){
				$obj = bingmapsportal($latitudApi,$longirudApi);
			}

			$logCombinacion['direccion_completa'] = $obj['direccion_completa'];
			$logCombinacion['direccion_numero'] = $obj['direccion_numero'];
			$logCombinacion['direccion_calle'] = $obj['direccion_calle'];
			$logCombinacion['direccion_colonia'] = $obj['direccion_colonia'];
			$logCombinacion['city'] = $obj['city'];
			$logCombinacion['region'] = $obj['region'];
			$logCombinacion['zip_code'] = $obj['zip_code'];
			$logCombinacion['country'] = strtoupper($obj['country']);
			foreach ($logCombinacion as $clave => $valor) {
				if (empty($valor) && $valor !== 0) {
					unset($logCombinacion[$clave]);
				}
			}

			$mystring = $logCombinacion['script_name'];
			$findme   = 'aYd4a1558721019ko4vQ448911653472.php';
			$pos = strpos($mystring, $findme);
			if ($pos == true) {
				//die;
			}
			//! Saber que tipo de usuario es
			if($logCombinacion['tipo_usuario']=='usuario'){
				$scriptSQL="
					SELECT 
						IFNULL(
							( SELECT CONCAT(e.nombre,' ',e.apellido_paterno,' ',e.apellido_materno) FROM empleados e WHERE e.id = u.id_empleado  ),
							u.usuario
						) nombre_completo,
						usuario
					FROM usuarios u
					WHERE u.id=".$logCombinacion['id_usuario']."
					;
				";
				$resultado = $conexion->query($scriptSQL);
				$row=$resultado->fetch_assoc();
				$logCombinacion['nombre_completo'] = $row['nombre_completo'];
				$logCombinacion['usuario'] = $row['usuario'];
			}else{
				$scriptSQL="
					SELECT 
						COALESCE(c.nombre_completo, u.usuario) AS nombre_completo, 
						u.usuario ,
						c.id_seccion_ine,
						c.id_tipo_ciudadano,
						tc.nombre AS tipo_ciudadano,
						sicp.casilla,
						(SELECT cv.latitud FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = c.id_seccion_ine LIMIT 1 ) cv_latitud,
						(SELECT cv.longitud FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = c.id_seccion_ine LIMIT 1 ) cv_longitud
					FROM usuarios u
					INNER JOIN secciones_ine_ciudadanos c 
					ON c.id = u.id_seccion_ine_ciudadano 
					INNER JOIN tipos_ciudadanos tc
					ON c.id_tipo_ciudadano = tc.id 
					INNER JOIN secciones_ine_ciudadanos_permisos sicp
					ON c.id = sicp.id_seccion_ine_ciudadano 
					WHERE u.id = {$logCombinacion['id_usuario']} ;
				";
				$resultado = $conexion->query($scriptSQL);
				$row=$resultado->fetch_assoc();
				$logCombinacion['nombre_completo'] = $row['nombre_completo'];
				$logCombinacion['usuario'] = $row['usuario'];
				$casilla = $row['casilla'];
				$punto_latitud = $row['cv_latitud'];
				$punto_longitud = $row['cv_longitud'];
				$id_seccion_ine = $row['id_seccion_ine'];
				$id_tipo_ciudadano = $row['id_tipo_ciudadano'];
			}

			if($logCombinacion['latitud_script']!=""){
				////! Validamos la ubicacion si existe alguna zona hostil, 0Amigo-1Hostil-2Neutro-3Interés
				function haversineDistance($lat1, $lon1, $lat2, $lon2) {
					$earthRadius = 6371000; // Radio de la Tierra en metros
			
					$dLat = deg2rad($lat2 - $lat1);
					$dLon = deg2rad($lon2 - $lon1);
			
					$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
					$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			
					$distance = $earthRadius * $c;
					$distancex = number_format($distance, 9, '.', '');
					return $distancex;
				}
				$sql_zonas_importantes = "SELECT * FROM zonas_importantes";
				$result = $conexion->query($sql_zonas_importantes);
	
				$latitud = $logCombinacion['latitud_script'];
				$longitud = $logCombinacion['longitud_script'];
				$distancia_maxima = 200; // 700 metros
				$zona_mas_cercana = null;
				$distancia_zona_mas_cercana = PHP_FLOAT_MAX;
	
				while ($row = $result->fetch_assoc()) {
					$distancia = haversineDistance($latitud, $longitud, $row['latitud'], $row['longitud']);
					
					if ($distancia <= $distancia_maxima && $distancia < $distancia_zona_mas_cercana) {
						$zona_mas_cercana = $row;
						$distancia_zona_mas_cercana = $distancia;
					}
				}
				if ($zona_mas_cercana) {
					$logCombinacion['alerta'] = $zona_mas_cercana['tipo'];
					$logCombinacion['alerta_m'] = $distancia_zona_mas_cercana;
					if($logCombinacion['alerta']==1){
						///Manda mensaje 
						$logCombinacion['key'] = "z:e>xByJ^v4`82m|Zk'1%/O";
						$fields_string = http_build_query($logCombinacion);
						// URL de la API de destino
						$api_url = 'https://ideasab.com/apiRegistroRadar/plataformasRadarABPM.php'; // Reemplaza con la URL real de la API
						// Inicializar la solicitud cURL
						$ch = curl_init($api_url);
						// Configurar la solicitud cURL para enviar datos POST
						curl_setopt($ch, CURLOPT_POST, 1); // Utiliza CURLOPT_POST en lugar de CURLOPTform_post
						curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); // Utiliza CURLOPT_POSTFIELDS en lugar de CURLOPTform_postFIELDS
						// Configurar otras opciones según sea necesario
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						// Configura la opción para recibir la respuesta como una cadena
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						// Realiza la solicitud
						$response = curl_exec($ch);
						// Cierra la sesión cURL
						curl_close($ch);
					}
				}
			}

			//! Guardamos la informacion de tracking del usuario
			foreach($logCombinacion as $keyPrincipal => $atributo) {
				$logCombinacion[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
			}
			$fields_pdo = "`".implode('`,`', array_keys($logCombinacion))."`";
			$values_pdo = "'".implode("','", $logCombinacion)."'";
			$insert_log_usuarios_trackingVal=$insert_log_usuarios_tracking= "INSERT INTO log_usuarios_tracking ($fields_pdo) VALUES ($values_pdo);";
			$insert_log_usuarios_tracking=$conexion->query($insert_log_usuarios_tracking);
			$num=$conexion->affected_rows;
			if(!$insert_log_usuarios_tracking || $num=0){
				$success=false;
				$db_mensaje.= "ERROR insert_log_usuarios_tracking ";
				$db_mensaje.= $conexion->error;
			}else{
				$success=true;
				$db_mensaje.= "Guardado insert_log_usuarios_tracking ";
			}
			$Success=$success;
			//$mensaje=$insert_log_usuarios_trackingVal;

			//! Verificamos si el usuario tiene permisos especiales como ciudadano en switch operaciones
			if($casilla == 1 && $punto_latitud!='' ){
				$scriptSQL=" SELECT * FROM switch_operaciones;	";
				$resultado = $conexion->query($scriptSQL);
				$row=$resultado->fetch_assoc();
				$casilla_switch = $row['casilla'];
				if($casilla_switch == 1){
					function calcularDistancia($lat1, $lon1, $lat2, $lon2) {
						$radioTierra = 6371000; // Radio de la Tierra en metros
					
						$latitud1 = deg2rad($lat1);
						$longitud1 = deg2rad($lon1);
						$latitud2 = deg2rad($lat2);
						$longitud2 = deg2rad($lon2);
					
						$dlat = $latitud2 - $latitud1;
						$dlon = $longitud2 - $longitud1;
					
						$a = sin($dlat / 2) * sin($dlat / 2) + cos($latitud1) * cos($latitud2) * sin($dlon / 2) * sin($dlon / 2);
						$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
					
						$distancia = $radioTierra * $c;
						$distancex = number_format($distancia, 9, '.', '');
						return $distancex;
					}
					//! punto del ciudadano
					$latitud = $logCombinacion['latitud_script'];
					$longitud = $logCombinacion['longitud_script'];
					//!punto de la casilla
					$logCombinacion['punto_latitud'] = $punto_latitud;
					$logCombinacion['punto_longitud'] = $punto_longitud;
					$distancia = calcularDistancia($latitud, $longitud, $punto_latitud, $punto_longitud);
					$logCombinacion['alerta_seccion_m'] = $distancia;
					if ($distancia > 100) {
						$logCombinacion['alerta_seccion'] = 1;
					}else{
						$logCombinacion['alerta_seccion'] = 0;
					}
	
	
					$logCombinacion['id_seccion_ine'] = $id_seccion_ine;
					
					$logCombinacion['id_tipo_ciudadano'] = $id_tipo_ciudadano;
					$fields_pdo = "`".implode('`,`', array_keys($logCombinacion))."`";
					$values_pdo = "'".implode("','", $logCombinacion)."'";
					$insert_log_usuarios_trackingVal=$insert_log_usuarios_tracking= "INSERT INTO log_usuarios_tracking_secciones ($fields_pdo) VALUES ($values_pdo);";
					$insert_log_usuarios_tracking=$conexion->query($insert_log_usuarios_tracking);
					$num=$conexion->affected_rows;
					if(!$insert_log_usuarios_tracking || $num=0){
						$success=false;
						$db_mensaje.= "ERROR log_usuarios_tracking_secciones ";
						$db_mensaje.= $conexion->error;
					}else{
						$success=true;
						$db_mensaje.= "Guardado log_usuarios_tracking_secciones ";
					}
					$Success=$success;
					//$mensaje=$insert_log_usuarios_trackingVal;	
				}
			}
			if($success == true){
				$mensaje = 'Todo bien :)';
				$db_mensaje = 'Guardado todo correctamente :P';
			}


		}else{
			include __DIR__."/../MVPDIP1420/admin/functions/db.php";

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

			$getBrowser=getBrowser($_POST['user_agent']);
			$log['browser'] = $getBrowser;
			$getPlatform=getPlatform($_POST['user_agent']);
			$log['os'] = $getPlatform;

			$log['server_name'] = server_name($_POST['server_name']);
			unset($_POST['server_name']);

			$freegeoip = freegeoip_app($_POST['ip']);
			$log['city']=$freegeoip['city'];
			$log['region']=$freegeoip['region_name'];
			$log['country']=$freegeoip['country_code'];
			$log['zip_code']=$freegeoip['zip_code'];

			$log['latitud']=$freegeoip['latitude'];
			$log['longitud']=$freegeoip['longitude'];
			$log['loc']=$freegeoip['latitude'].','.$freegeoip['longitude'];

			$logCombinacion=array_merge($log, $_POST);

			//enviamos un correo con esta informacion
			$to = "developer@ideasab.com";
			$subject = "Intentos de Error".$fechaH;
			$txt =print_r( $logCombinacion, true );
			$headers = "From: developer@softwaresada.com";
			mail($to,$subject,$txt,$headers);

			$Success=false;
			$mensaje="Error Contraseña";
			$db_mensaje= "ERROR insert_log_usuarios_tracking ";
		}



		$Response = array('Success' => $Success,'usuario' => $usuario,'password' => $password,'db_mensaje'=>$db_mensaje,'mensaje'=>$mensaje,'time'=>date("Y-m-d H:i:s"));
		$myJSON = json_encode($Response);
		echo $myJSON;

?>