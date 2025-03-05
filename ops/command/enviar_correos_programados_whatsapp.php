<?php

	///envios
	//include __DIR__."/../../MVPDIP1420/admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php";
	include __DIR__."/../db.php";


	$cuerpo ='Datos Ciudadano<br>
		-[*__Tipo_Ciudadano__*]<br>
		-[*__Nombre_Completo__*]<br>
		-[*__Nombre__*]<br>
		-[*__Apellido_Paterno__*]<br>
		-[*__Apellido_Materno__*]<br>
		-[*__Fecha_Nacimiento__*]<br>
		-[*__Fecha_Nacimiento_Texto__*]<br>
		-[*__Edad__*]<br>
		-[*__Sexo__*]<br>
		-[*__Relacionado__*]<br>
		-[*__Whatsapp__*]<br>
		-[*__Telefono__*]<br>
		-[*__Celular__*]<br>
		-[*__Correo_Electronico__*]<br><br>
		<hr>
		Datos Ciudadano Usuario<br>
		-[*__Usuario__*]<br>
		-[*__Password__*]<br>
		-[*__Status__*]<br><br>
		<hr>
		Datos Ciudadano Cartografia<br>
		-[*__Estado__*]<br>
		-[*__Municipio__*]<br>
		-[*__Localidad__*]<br>
		-[*__Distrito_Local__*]<br>
		-[*__Distrito_Federal__*]<br><br>
		<hr>
		Datos Fecha Hora<br>
		-[*__Fecha__*]<br>
		-[*__Fecha_WDDMMAAA__*]<br>
		-[*__Hora__*]<br>
		-[*__Hora_AMPM__*]<br>
		-[*__Hora_ampm__*]<br><br>
		<hr>
		Datos del Correo<br>
		-[*__Correo_Electronico_Repuesta__*]<br>
		-[*__Correo_Electronico_Envio__*]<br>
		-[*__URL__*]<br>
		-[*__Nombre_Sistema__*]<br>
		-[*__Slogan_Sistema__*]<br>
		-[*__Logo_Sistema__*]<br>
		-[*__Correo_Vista_Web__*]<br><br>';

	$sql_whatsapp='
		SELECT
			siccmp.id,
			siccmp.id_seccion_ine_ciudadano,

			cmc.cuerpo,
			cmc.asunto,
			cmc.MediaUrl,

			cm.nombre nombre_campana,

			sic.nombre_completo,
			sic.nombre,
			sic.apellido_paterno,
			sic.apellido_materno,
			sic.fecha_nacimiento,
			sic.edad,
			sic.sexo,
			(SELECT sicr.nombre_completo FROM secciones_ine_ciudadanos sicr WHERE sicr.id = sic.id_seccion_ine_ciudadano_compartido ) relacionado,
			sic.whatsapp,
			sic.telefono,
			sic.celular,
			sic.correo_electronico,
			(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano ) tipo_ciudadano,

			(SELECT u.usuario FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) usuario,
			(SELECT u.password FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) password,
			(SELECT u.status FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) status,
			(SELECT e.estado FROM estados e WHERE e.id=sic.id_estado) estado,
			(SELECT m.municipio FROM municipios m WHERE m.id=sic.id_estado) municipio,
			(SELECT l.localidad FROM localidades l WHERE l.id=sic.id_localidad) localidad,
			(SELECT dl.numero FROM distritos_locales dl WHERE dl.id=sic.id_distrito_local) distrito_local,
			(SELECT df.numero FROM distritos_federales df WHERE df.id=sic.id_distrito_federal) distrito_federal,
			(SELECT s.numero FROM secciones_ine s WHERE s.id=sic.id_seccion_ine) seccion,

			cmr.id d_id,
			cmr.url d_url,
			cmr.account_sid d_account_sid,
			cmr.auth_token d_auth_token,
			cmr.de d_de,
			cmr.statusCallback_plantillas d_statusCallback,

			siccmp.identificador u_identificador,
			sic.codigo_seccion_ine_ciudadano u_codigo_unico


		FROM secciones_ine_ciudadanos_campanas_whatsapp_programadas siccmp
		LEFT JOIN secciones_ine_ciudadanos sic
		ON sic.id = siccmp.id_seccion_ine_ciudadano
		LEFT JOIN campanas_whatsapp cm
		ON cm.id = siccmp.id_campana_whatsapp
		LEFT JOIN api_whatsapp cmr
		ON cmr.id = cm.id_api_whatsapp
		LEFT JOIN campanas_whatsapp_cuerpos cmc
		ON cmc.id_campana_whatsapp = cm.id

		WHERE siccmp.status=0 AND siccmp.tipo IN (1,2,3)  AND cm.status=1 AND cmr.status=1
		LIMIT 1;
	';

	/*
	$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
	mysqli_set_charset($conexion, "utf8mb4"); 
	if ($conexion->connect_error){
		echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
	}
	*/
	//verificar si esta activo el envio de correos
	$scriptSQL=" SELECT * FROM api_whatsapp LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	// 30 * 30
	if($row['status'] == 0){
		echo "offline :( api_whatsapp";
		die;
	} 
	$url = $row['url'];
	
	$tiempo_espera_segundos = $row['tiempo_espera_segundos'];
	$mensajes_a_enviar = $row['mensajes_a_enviar'];
	
	$account_sid = $row['account_sid'];
	$auth_token = $row['auth_token'];
	$de = $row['de'];
	$statusCallback = $row['statusCallback'];

	$inicio=date("Y-m-d H:i:s");
	$mifecha = new DateTime(); 
	$mifecha->modify($row['tiempo_script']); 
	$final = $mifecha->format('d-m-Y H:i:s');
	$startTime = strtotime($inicio);
	$endTime = strtotime($final);

	//verificar si esta activo el envio de correos
	$scriptSQL=" SELECT whatsapp FROM switch_operaciones LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	$whatsapp = $row['whatsapp'];
	if($whatsapp ==0){
		echo "offline switch_operaciones";
		die;
	}


	$sql='SELECT nombre,slogan,url_base FROM configuracion WHERE 1 = 1 LIMIT 1';
	$resultado = $conexion->query($sql);
	$configuracionDatos=$resultado->fetch_assoc();
	$img_logo='<img src="'.$configuracionDatos['url_base'].'ops/imagen.php?id_img=logo_principal.png" height="90px" >';

	for ( $i = $startTime; $i < $endTime; $i = $i + $tiempo_espera_segundos ) {
		for ($n=1; $n <= $mensajes_a_enviar; $n++) {
			$resultado = $conexion->query($sql_whatsapp);
			$rowSMS=$resultado->fetch_assoc();
			if(!empty($rowSMS)){

				$auth_token = $rowSMS['d_auth_token'];
				$account_sid = $rowSMS['d_account_sid'];
				$statusCallback = $rowSMS['d_statusCallback'];
				$para = $rowSMS['whatsapp'];
				$de = $rowSMS['d_de'];
				$nombre_campana = $rowSMS['nombre_campana'];
				$url_whatsapp = $rowSMS['d_url'];
				
				$id_seccion_ine_ciudadano_campana_whatsapp_programada = $rowSMS['id'];
				$id_seccion_ine_ciudadano = $rowSMS['id_seccion_ine_ciudadano'];

				unset($alert_error);
				if(!empty($para) && is_numeric($para) && strlen($para) == 10 ){
					$enviados_succes = true;
					$fecha_hora = array(
						"[*__Fecha__*]" => $fechaSF,
						"[*__Fecha_WDDMMAAA__*]" => fechaNormalSimpleWDDMMAA_ES($fechaSF),
						"[*__Hora__*]" => $fechaSH,
						"[*__Hora_AMPM__*]" => convertidorAMPM($fechaSH,'','mayuscula'),
						"[*__Hora_ampm__*]" => convertidorAMPM($fechaSH,'',''),
					);

					$plataforma = array(
						"[*__URL__*]" => $configuracionDatos['url_base'],
						"[*__Nombre_Sistema__*]" => $configuracionDatos['nombre'],
						"[*__Slogan_Sistema__*]" => $configuracionDatos['slogan'],
						"[*__Logo_Sistema__*]" => $img_logo, 
					);

					$datos_ciudadano = array(
						"[*__Tipo_Ciudadano__*]" => $rowSMS['tipo_ciudadano'],
						"[*__Nombre_Completo__*]" => $rowSMS['nombre_completo'],
						"[*__Nombre__*]" => $rowSMS['nombre'],
						"[*__Apellido_Paterno__*]" => $rowSMS['apellido_paterno'],
						"[*__Apellido_Materno__*]" => $rowSMS['apellido_materno'],
						"[*__Fecha_Nacimiento__*]" => $rowSMS['fecha_nacimiento'],
						"[*__Fecha_Nacimiento_Texto__*]" => fechaNormalSimpleWDDMMAA_ES($rowSMS['fecha_nacimiento']),
						"[*__Edad__*]" => $rowSMS['edad'],
						"[*__Sexo__*]" => $rowSMS['sexo'],
						"[*__Relacionado__*]" => $rowSMS['nombre_completo']==''?'No tiene':$rowSMS['nombre_completo'],
						"[*__Whatsapp__*]" => $rowSMS['whatsapp'],
						"[*__Telefono__*]" => $rowSMS['telefono'],
						"[*__Celular__*]" => $rowSMS['celular'],
						"[*__Correo_Electronico__*]" => $rowSMS['correo_electronico'],
					);

					$datos_ciudadano_usuario = array(
						"[*__Usuario__*]" => $rowSMS['usuario'],
						"[*__Password__*]" => $rowSMS['password'],
						"[*__Status__*]" => $rowSMS['status']=='1'?'Online':'Offline',
					);

					$datos_ciudadano_cartografia = array(
						"[*__Estado__*]" => $rowSMS['estado'],
						"[*__Municipio__*]" => $rowSMS['municipio'],
						"[*__Localidad__*]" => $rowSMS['localidad'],
						"[*__Distrito_Local__*]" => $rowSMS['distrito_local'],
						"[*__Distrito_Federal__*]" => $rowSMS['distrito_federal'],
						"[*__Seccion__*]" => $rowSMS['seccion'],
					);


					$cuerpo = $rowSMS['cuerpo'];
					$asunto = $rowSMS['asunto'];
					$MediaUrl = $rowSMS['MediaUrl'];
					$bodyHTML = strtr($cuerpo, array_merge($fecha_hora,$plataforma,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
					//$asuntoHTML = strtr($asunto, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));

					$from = 'whatsapp:'.$de; // twilio trial verified number
					$url = str_replace('$account_sid', $account_sid, $url_whatsapp);

					$to = "whatsapp:+521".$para;
					$body = $bodyHTML;

					if($MediaUrl==''){
						$data = array (
							'From' => $from,
							'To' => $to,
							'Body' => $body,
							'statusCallback' => $statusCallback,
						);
					}else{
						$data = array (
							'MediaUrl'=> $MediaUrl,
							'From' => $from,
							'To' => $to,
							'Body' => $body,
							'statusCallback' => $statusCallback,
						);
					}

					$ch = curl_init($url);
					$var =http_build_query($data);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch, CURLOPT_USERPWD, "$account_sid:$auth_token");
					curl_setopt($ch, CURLOPT_POSTFIELDS,$var);
					$result = curl_exec($ch);
					$obj = json_decode($result,true);


					unset($api_whatsapp_envios);
					$api_whatsapp_envios = $obj;
					$mensaje_proveedor = $obj['message'];

					$api_whatsapp_envios['id_api_whatsapp'] = $rowSMS['d_id'];
					$api_whatsapp_envios['id_seccion_ine_ciudadano_campana_whatsapp_programada'] = $id_seccion_ine_ciudadano_campana_whatsapp_programada;
					$api_whatsapp_envios['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
					// 0 plantilla // 1 reply /// 2 ciudadano
					$api_whatsapp_envios['tipo'] = '0';
					$api_whatsapp_envios['whatsapp'] = $para;
					$api_whatsapp_envios['fechaR'] = $fechaH;
					$api_whatsapp_envios['subresource_uris'] = $api_whatsapp_envios['subresource_uris']['media'];


					$identificador = $obj['sid'];
					//checamos mensaje para verificar que hacer
					$scriptVerificarAPIAlert=" SELECT * FROM api_whatsapp_status WHERE codigo ='{$code}' ";
					$resultadoVerificar = $conexion->query($scriptVerificarAPIAlert);
					$rowCode=$resultadoVerificar->fetch_assoc();


					if($rowCode['tipo']=='offline'){
						/// cambiamos el status del api status_proveedor y de status por 0
						echo "offline";
						$id_api_whatsapp = $rowSMS['d_id'];
						$update_correo_whatsapp ='UPDATE api_whatsapp SET status = "0", status_proveedor = "0", mensaje_proveedor="'.$mensaje_proveedor.'"  WHERE ( id = "'.$id_api_whatsapp.'" );';
						$update_correo_whatsapp=$conexion->query($update_correo_whatsapp);
						if(!$update_correo_whatsapp || $num=0){
							$success=false;
							echo "ERROR update_correo_whatsapp"; 
							var_dump($conexion->error);
						}
					}else{
						if($api_whatsapp_envios['status']!='queued'){
							$update_correo_whatsapp ='
								UPDATE secciones_ine_ciudadanos_campanas_whatsapp_programadas 
									SET 
										status = "2" , 
										fecha_envio="'.$fechaSF.'" ,
										hora_envio="'.$fechaSH.'" ,
										fecha_hora_envio="'.$fechaH.'" ,
										identificador="'.$identificador.'",
										mensaje_proveedor="'.$mensaje_proveedor.'"
									WHERE ( id = "'.$id_seccion_ine_ciudadano_campana_whatsapp_programada.'" );';
							$update_correo_whatsapp=$conexion->query($update_correo_whatsapp);
							if(!$update_correo_whatsapp || $num=0){
								$success=false;
								echo "ERROR update_correo_whatsapp"; 
								var_dump($conexion->error);
							}
						}else{
							// aqui todo bien para los envios
							
							$update_correo_whatsapp ='
								UPDATE secciones_ine_ciudadanos_campanas_whatsapp_programadas 
									SET 
										status = "1" , 
										fecha_envio="'.$fechaSF.'" ,
										hora_envio="'.$fechaSH.'" ,
										fecha_hora_envio="'.$fechaH.'" ,
										identificador="'.$identificador.'",
										mensaje_proveedor="'.$mensaje_proveedor.'"
									WHERE ( id = "'.$id_seccion_ine_ciudadano_campana_whatsapp_programada.'" );';
							$update_correo_whatsapp=$conexion->query($update_correo_whatsapp);
							if(!$update_correo_whatsapp || $num=0){
								$success=false;
								echo "ERROR update_correo_whatsapp"; 
								var_dump($conexion->error);
							}
							

							$fields_pdo = "`".implode('`,`', array_keys($api_whatsapp_envios))."`";
							$values_pdo = "'".implode("','", $api_whatsapp_envios)."'";
							$insert_api_whatsapp_envios= "INSERT INTO api_whatsapp_envios ($fields_pdo) VALUES ($values_pdo);";
							$insert_api_whatsapp_envios=$conexion->query($insert_api_whatsapp_envios);
							$num=$conexion->affected_rows;
							if(!$insert_api_whatsapp_envios || $num=0){
								$success=false;
								echo "ERROR insert_api_whatsapp_envios"; 
								var_dump($conexion->error);
							}
							$id_whatsapp_envio = $conexion->insert_id;

							/////metemos en mensajes
							unset($api_whatsapp_mensaje);
							$api_whatsapp_mensaje['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
							$api_whatsapp_mensaje['id_whatsapp_envio'] = $id_whatsapp_envio;
							$api_whatsapp_mensaje['tipo'] = '0';
							$api_whatsapp_mensaje['fechaR'] = $fechaH;
							$api_whatsapp_mensaje['sid'] = $obj['sid'];
							$api_whatsapp_mensaje['ProfileName'] = 'Sistema';
							$api_whatsapp_mensaje['whatsapp'] = $para;
							$api_whatsapp_mensaje['body'] = $obj['body'];
							$api_whatsapp_mensaje['MediaUrl0'] = $MediaUrl;
							$api_whatsapp_mensaje['fecha_hora_envio'] = $fechaH;
							$api_whatsapp_mensaje['fecha_envio'] = $fechaSF;
							$api_whatsapp_mensaje['hora_envio'] = $fechaSH;
							$api_whatsapp_mensaje['fecha_hora_leido'];
							$api_whatsapp_mensaje['fecha_hora_entrega'];
							$api_whatsapp_mensaje['fecha_leido'];
							$api_whatsapp_mensaje['fecha_entrega'];
							$api_whatsapp_mensaje['hora_leido'];
							$api_whatsapp_mensaje['hora_entrega'];
							$api_whatsapp_mensaje['Latitude'] ;
							$api_whatsapp_mensaje['Longitude'];

							$fields_pdo = "`".implode('`,`', array_keys($api_whatsapp_mensaje))."`";
							$values_pdo = "'".implode("','", $api_whatsapp_mensaje)."'";
							$insert_api_whatsapp_mensajes= "INSERT INTO api_whatsapp_mensajes ($fields_pdo) VALUES ($values_pdo);";
							$insert_api_whatsapp_mensajes=$conexion->query($insert_api_whatsapp_mensajes);
							$num=$conexion->affected_rows;
							if(!$insert_api_whatsapp_mensajes || $num=0){
								$success=false;
								echo "ERROR insert_api_whatsapp_mensajes"; 
								var_dump($conexion->error);
							}
						}
					}


				}else{
					///codigo_ciudadano
					$length=20; 
					$mk_id=time();
					$identificador = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length); 
					$identificador .= $mk_id;
					$length=10; 
					$mk_id=time()*2*36*12/3;
					$identificador .= substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
					$identificador .= $mk_id;
					$identificador;
					if(empty($para)){
						$alert_error[] .= 'sin número whatsapp';
					}
					if(!is_numeric($para) ){
						$alert_error[] .= 'whatsapp no contiene número';
					}
					if(strlen($para) != 10){
						$alert_error[] .= 'whatsapp no tiene 10 dígitos';
					}
					$mensaje_proveedor = implode(", ", $alert_error)." ";
					$update_correo_whatsapp ='
						UPDATE secciones_ine_ciudadanos_campanas_whatsapp_programadas 
							SET 
							status = "2" , 
							fecha_envio="'.$fechaSF.'" , 
							hora_envio="'.$fechaSH.'" , 
							fecha_hora_envio="'.$fechaH.'" , 
							identificador="'.$identificador.'" ,
							mensaje_proveedor="'.$mensaje_proveedor.'"  
							WHERE ( id = "'.$id_seccion_ine_ciudadano_campana_whatsapp_programada.'" );';
					
					$update_correo_whatsapp=$conexion->query($update_correo_whatsapp);
					if(!$update_correo_whatsapp || $num=0){
						$success=false;
						echo "ERROR update_correo_whatsapp"; 
						var_dump($conexion->error);
					}
				}

			}
		}
		//sleep($tiempo_espera_segundos);
	}
	if($enviados_succes){
		echo "enviados con exito";
	}else{
		echo "no hay";
	}
	$conexion->close();
	die; 