<?php
	@session_start();
	if($_COOKIE['Az801AB']!=""){
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) =='xmlhttprequest' && !empty($_POST) ){
			ini_set("display_errors", false); 
			include 'MVPDIP1420/admin/functions/db.php';
			include 'MVPDIP1420/admin/keySistema/key.php';
			include 'MVPDIP1420/admin/functions/switch_operaciones.php';
			$respuesta = false;
			$usuariox=mysqli_real_escape_string($conexion,$_POST['usuario']);
			$passwordx=mysqli_real_escape_string($conexion,$_POST['password']);
			$latitud_scriptx=mysqli_real_escape_string($conexion,$_POST['latitud_script']);
			$longitud_scriptx=mysqli_real_escape_string($conexion,$_POST['longitud_script']);
			$precision_scriptx=mysqli_real_escape_string($conexion,$_POST['precision_script']);
			$loc_scriptx=mysqli_real_escape_string($conexion,$_POST['loc_script']);
			$tipox=mysqli_real_escape_string($conexion,$_POST['tipo']);
			if($tipox==$_COOKIE["ch201AB"]){
				if ($stmt = mysqli_prepare($conexion,"SELECT id,id_perfil_usuario,status,identificador,codigo_plataforma FROM usuarios WHERE usuario = ? AND password = ?  ")) {
				/* ligar parámetros para marcadores */
					mysqli_stmt_bind_param($stmt,"ss", $usuariox,$passwordx);
					 /* execute query */
					mysqli_stmt_execute($stmt);
					/* bind result variables */
					mysqli_stmt_bind_result($stmt,$id,$id_perfil_usuario,$status,$identificador,$codigo_plataformadb);
					/* fetch value */
					mysqli_stmt_fetch($stmt);
					/* close statement */
					mysqli_stmt_close($stmt);
					
					if($id == null){ 
						echo "NO6";
						$tipo_error = 'no_existe';
						$tipo_sesion = 'usuario';
					}else{
						if($id_perfil_usuario==4){
							//usuarios ciudadano
							$switch_operacionesDatos = switch_operacionesDatos();
							if($switch_operacionesDatos['usuarios']=="1"){
								// importante
								//aqui va cuando todo esta bien para entrar como usuario al sistema
								
								//* Si usamos balanceador deja la opcion bien esto es para impedir que inicie session desde el servidor de uso administrativo
								$respuesta = true;
								echo "SI";
								$tipo_error = 'session';
								$tipo_sesion = 'ciudadano';

								//!!!! si no usamos balanceador de carga entonces debemos usar la forma comentada 
								#echo "NO";
								#$tipo_error = 'error_seguridad_admin';
								#$tipo_sesion = 'alerta_maxima';

							}else{
								echo "NO5";
								$tipo_error = 'ciudadanos_cerrado';
								$tipo_sesion = 'ciudadano';
							}
						}elseif ($id_perfil_usuario==3) {
							if ($codigo_plataformadb==$codigo_plataforma ) {
								//usuarios normales
								if($status==1){
									$respuesta = true;
									echo "SI";
								}else{
									echo "NO7";
									$tipo_error = 'usuario_status';
									$tipo_sesion = 'alerta_maxima';
								}
							}else{
								echo "NO8";
								$tipo_error = 'error_seguridad_plataforma';
								$tipo_sesion = 'alerta_maxima';
							}
						}elseif ($id_perfil_usuario==1) {
							if($status==1){
								$respuesta = true;
								echo "SI";
							}else{
								echo "NO7";
								$tipo_error = 'usuario_status';
								$tipo_sesion = 'usuario';
							}
						}else{
							if($status==1){
								$respuesta = true;
								echo "SI";
							}else{
								echo "NO7";
								$tipo_error = 'usuario_status';
								$tipo_sesion = 'usuario';
							}
						}
						
					}
				}
			}else{
				echo "NO3";
				$tipo_error = 'error_seguridad_id';
				$tipo_sesion = 'alerta_maxima';
			}
		}else{
			echo "NO2";
			$tipo_error = 'error_http';
			$tipo_sesion = 'alerta_maxima';
		}
	}else{
		echo "NO1";
			$tipo_error = 'error_scaneo';
			$tipo_sesion = 'alerta_maxima';
	}

	if($tipo_error!=''){
		include 'MVPDIP1420/admin/functions/tool_xhpzab.php';
		include 'MVPDIP1420/admin/functions/apis_ip_geodata.php';
		include 'MVPDIP1420/admin/functions/apis_geocoding.php';
		include 'MVPDIP1420/admin/functions/tools.php';
		include 'MVPDIP1420/admin/functions/timemex.php';
		///error de login 
		///erronaeo
		if($passwordx == ''){
			$passwordx = '-';
		}
		if($usuariox == ''){
			$usuariox = '-';
		}
		$log['codigo_plataforma']=$codigo_plataforma;
		$log['usuario']=$usuariox;
		$log['password']=$passwordx;
		$log['fechaR']=$fechaH;
		$log['codigo_plataforma']=$codigo_plataforma;
		$log['fechaR']=$fechaH;

		$detectUsuarioDatos=detectUsuarioDatos();
		$log['browser']=$detectUsuarioDatos['browser'];
		$log['os']=$detectUsuarioDatos['os'];
		$log['version']=$detectUsuarioDatos['version'];
		$log['user_agent']=$detectUsuarioDatos['user_agent'];
		$log['ip']=$detectUsuarioDatos['ip'];
		$log['hostname']=$detectUsuarioDatos['hostname'];
		$log['server_name']=$_SERVER['SERVER_NAME'];
		$log['request_method']=$_SERVER['REQUEST_METHOD'];
		$log['request_uri']=$_SERVER['REQUEST_URI'];
		$log['script_name']=$_SERVER['SCRIPT_NAME'];
		$log['php_self']=$_SERVER['PHP_SELF'];
		$log['php_self']=$_SERVER['PHP_SELF'];
		$log['loc_script']=$loc_scriptx;
		$log['latitud_script']=$latitud_scriptx;
		$log['longitud_script']=$longitud_scriptx;
		$log['precision_script']=$precision_scriptx;
		$log['precision_script']=$precision_scriptx;
		$log['loc_script']=$loc_scriptx;
		$log['tipo']=$tipo_sesion;
		$log['tipo_intento']=$tipo_error;

		if( $log['ip'] == '::1'){
			$log['ip'] = '187.147.187.148';
		}

		$sql="SELECT * FROM ips_georeferenciaciones WHERE ip='{$log['ip']}' LIMIT 1  ";
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
			$log['ip']=$log['ip'];
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

			$freegeoip = freegeoip_app($log['ip']);
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
			$extreme_ip_lookup = extreme_ip_lookup($log['ip']);
			$log['ipName']=$extreme_ip_lookup['ipName'];
			$log['ip_type']=$extreme_ip_lookup['ipType'];
			$log['isp']=$extreme_ip_lookup['isp'];
			$log['org']=$extreme_ip_lookup['org'];

			$ip_api = ip_api($log['ip']);
			$log['asname']=$ip_api['asname'];
			$log['hosting']=$ip_api['hosting'];
			$log['proxy']=$ip_api['proxy'];
			$log['mobile']=$ip_api['mobile'];

			$ipdata = api_ipdata($log['ip']);
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
		if($log['ipName']==""){
			$log['ipName'] = $log['hostname'];
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

		if($log['loc_script']!=""){
			$latitudApi = $log['latitud_script'];
			$longirudApi = $log['longitud_script'];
			$latitudApi=str_replace(',', '.', $log['latitud_script']);
			$longirudApi=str_replace(',', '.', $log['longitud_script']);
			$log['loc_script'] = $latitudApi.','.$longirudApi;
		}else{
			$latitudApi = $log['latitud'];
			$longirudApi = $log['longitud'];
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

		$log['direccion_completa'] = $obj['direccion_completa'];
		$log['direccion_numero'] = $obj['direccion_numero'];
		$log['direccion_calle'] = $obj['direccion_calle'];
		$log['direccion_colonia'] = $obj['direccion_colonia'];
		$log['city'] = $obj['city'];
		$log['region'] = $obj['region'];
		$log['zip_code'] = $obj['zip_code'];
		$log['country'] = strtoupper($obj['country']);
		if($id==''){
			$id=0;
		}
		foreach ($log as $clave => $valor) {
			if (empty($valor) && $valor !== 0) {
				unset($log[$clave]);
			}
		}

		$log['id_usuario']=$id;
		foreach($log as $keyPrincipal => $atributo) {
			$log[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		if($log['latitud_script']!=""){
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

			$latitud = $log['latitud_script'];
			$longitud = $log['longitud_script'];
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
				$log['alerta'] = $zona_mas_cercana['tipo'];
				$log['alerta_m'] = $distancia_zona_mas_cercana;
				if($log['alerta']==1){
					///Manda mensaje 
					$log['key'] = "z:e>xByJ^v4`82m|Zk'1%/O";
					$fields_string = http_build_query($log);
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
		foreach($log as $keyPrincipal => $atributo) {
			$log[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		$fields_pdo = "`".implode('`,`', array_keys($log))."`";
		$values_pdo = "'".implode("','", $log)."'";
		$inset_sesion= "INSERT INTO log_sesiones ($fields_pdo) VALUES ($values_pdo);";
		$inset_sesion=$conexion->query($inset_sesion);
		$num=$conexion->affected_rows;
		if(!$inset_sesion || $num=0){
			$success=false;
			echo "ERROR inset_sesion"; 
			//var_dump($conexion->error);
		}
		$conexion->commit();
		$conexion->close();
	}
	//! validamos para ver si ya tiene muchos intentos y borrar
	if($respuesta==false){
		
	}
?>