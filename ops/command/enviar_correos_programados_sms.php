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

	$sql_sms='
		SELECT
			siccmp.id,
			siccmp.id_seccion_ine_ciudadano,

			cmc.cuerpo,
			cmc.asunto,

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
			cmr.key_produccion d_key_produccion,
			cmr.key_sandbox d_key_sandbox,
			cmr.modo d_modo,


			siccmp.identificador u_identificador,
			sic.codigo_seccion_ine_ciudadano u_codigo_unico


		FROM secciones_ine_ciudadanos_campanas_sms_programadas siccmp
		LEFT JOIN secciones_ine_ciudadanos sic
		ON sic.id = siccmp.id_seccion_ine_ciudadano
		LEFT JOIN campanas_sms cm
		ON cm.id = siccmp.id_campana_sms
		LEFT JOIN api_sms cmr
		ON cmr.id = cm.id_api_sms
		LEFT JOIN campanas_sms_cuerpos cmc
		ON cmc.id_campana_sms = cm.id

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
	$scriptSQL=" SELECT * FROM api_sms LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	// 30 * 30
	if($row['status'] == 0){
		echo "offline :( api_sms";
		die;
	} 
	$url = $row['url'];
	
	$tiempo_espera_segundos = $row['tiempo_espera_segundos'];
	$mensajes_a_enviar = $row['mensajes_a_enviar'];
	$key_produccion = $row['key_produccion'];
	$key_sandbox = $row['key_sandbox'];
	$modo = $row['modo'];

	$inicio=date("Y-m-d H:i:s");
	$mifecha = new DateTime(); 
	$mifecha->modify($row['tiempo_script']); 
	$final = $mifecha->format('d-m-Y H:i:s');
	$startTime = strtotime($inicio);
	$endTime = strtotime($final);

	//verificar si esta activo el envio de correos
	$scriptSQL=" SELECT sms FROM switch_operaciones LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	$sms = $row['sms'];
	if($sms ==0){
		echo "offline switch_operaciones";
		die;
	}

	/*
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
	*/

	$sql='SELECT nombre,slogan,url_base FROM configuracion WHERE 1 = 1 LIMIT 1';
	$resultado = $conexion->query($sql);
	$configuracionDatos=$resultado->fetch_assoc();
	$img_logo='<img src="'.$configuracionDatos['url_base'].'ops/imagen.php?id_img=logo_principal.png" height="90px" >';


	for ( $i = $startTime; $i < $endTime; $i = $i + $tiempo_espera_segundos ) {
		for ($n=1; $n <= $mensajes_a_enviar; $n++) {
			$resultado = $conexion->query($sql_sms);
			$rowSMS=$resultado->fetch_assoc();
			if(!empty($rowSMS)){

				$key_sandbox = $rowSMS['d_key_sandbox'];
				$key_produccion = $rowSMS['d_key_produccion'];
				$para = $rowSMS['celular'];
				$modo = $rowSMS['d_modo'];
				$nombre_campana = $rowSMS['nombre_campana'];
				$url_sms = $rowSMS['d_url'];
				$id_seccion_ine_ciudadano_campana_sms_programada = $rowSMS['id'];
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

					$correo_electronico = array(
						"[*__Correo_Electronico_Repuesta__*]" => $rowSMS['correo_electronico'],
						"[*__Correo_Electronico_Envio__*]" => $rowSMS['usuario'],
						"[*__URL__*]" => $configuracionDatos['url_base'],
						"[*__Nombre_Sistema__*]" => $configuracionDatos['nombre'],
						"[*__Slogan_Sistema__*]" => $configuracionDatos['slogan'],
						"[*__Logo_Sistema__*]" => $img_logo,
						"[*__Correo_Vista_Web__*]" => 'demo',
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
					$bodyHTML = strtr($cuerpo, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
					//$asuntoHTML = strtr($asunto, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
					if($modo==1){
						$key_sms = $key_sandbox;
					}else{
						$key_sms = $key_produccion;
					}

					$info = array(
						"message" => $bodyHTML,
						"numbers" => $para,
						"country_code" => 52,
						"sandbox" => $modo,
						"name" => $nombre_campana,
					);

					$headers = array(
						"apikey: {$key_sms}"
					);
					$url = $url_sms;

					$ch = curl_init($url);
					$var =http_build_query($info);
					$jsonDataEncoded = json_encode($jsonData);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$var);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$result = curl_exec($ch);
					$obj = json_decode($result,true);

					unset($api_sms_envios);
					$api_sms_envios['success'] = $obj['success'];
					$mensaje_proveedor = $api_sms_envios['message'] = $obj['message'];
					$api_sms_envios['status'] = $obj['status'];
					$code = $api_sms_envios['code'] = $obj['code'];
					
					$api_sms_envios['total_messages'] = $obj['total_messages'];
					$identificador = $api_sms_envios['reference'] = $obj['references'][0]['reference'];
					$api_sms_envios['number'] = $obj['references'][0]['number'];
					$api_sms_envios['id_api_sms'] = $rowSMS['d_id'];
					$api_sms_envios['id_seccion_ine_ciudadano_campana_sms_programada'] = $id_seccion_ine_ciudadano_campana_sms_programada;
					$api_sms_envios['id_seccion_ine_ciudadano'] = $id_seccion_ine_ciudadano;
					$api_sms_envios['modo'] = $modo;
					//checamos mensaje para verificar que hacer
					$scriptVerificarAPIAlert=" SELECT * FROM api_sms_status WHERE codigo ='{$code}' ";
					$resultadoVerificar = $conexion->query($scriptVerificarAPIAlert);
					$rowCode=$resultadoVerificar->fetch_assoc();

					if($rowCode['tipo']=='offline'){
						/// cambiamos el status del api status_proveedor y de status por 0
						echo "offline";
						$id_api_sms = $rowSMS['d_id'];
						$update_correo_sms ='UPDATE api_sms SET status = "0", status_proveedor = "0", mensaje_proveedor="'.$mensaje_proveedor.'"  WHERE ( id = "'.$id_api_sms.'" );';
						$update_correo_sms=$conexion->query($update_correo_sms);
						if(!$update_correo_sms || $num=0){
							$success=false;
							echo "ERROR update_correo_sms"; 
							var_dump($conexion->error);
						}
					}else{
						if($api_sms_envios['success']==''){
							die;
							$update_correo_sms ='
								UPDATE secciones_ine_ciudadanos_campanas_sms_programadas 
									SET 
										status = "2" , 
										fecha_envio="'.$fechaSF.'" ,
										hora_envio="'.$fechaSH.'" ,
										fecha_hora_envio="'.$fechaH.'" ,
										identificador="'.$identificador.'",
										mensaje_proveedor="'.$mensaje_proveedor.'"
									WHERE ( id = "'.$id_seccion_ine_ciudadano_campana_sms_programada.'" );';
							$update_correo_sms=$conexion->query($update_correo_sms);
							if(!$update_correo_sms || $num=0){
								$success=false;
								echo "ERROR update_correo_sms"; 
								var_dump($conexion->error);
							}

						}else{
							// aqui todo bien para los envios
							$update_correo_sms ='
								UPDATE secciones_ine_ciudadanos_campanas_sms_programadas 
									SET 
										status = "1" , 
										fecha_envio="'.$fechaSF.'" ,
										hora_envio="'.$fechaSH.'" ,
										fecha_hora_envio="'.$fechaH.'" ,
										identificador="'.$identificador.'",
										mensaje_proveedor="'.$mensaje_proveedor.'"
									WHERE ( id = "'.$id_seccion_ine_ciudadano_campana_sms_programada.'" );';
							$update_correo_sms=$conexion->query($update_correo_sms);
							if(!$update_correo_sms || $num=0){
								$success=false;
								echo "ERROR update_correo_sms"; 
								var_dump($conexion->error);
							}

							$fields_pdo = "`".implode('`,`', array_keys($api_sms_envios))."`";
							$values_pdo = "'".implode("','", $api_sms_envios)."'";
							$insert_api_sms_envios= "INSERT INTO api_sms_envios ($fields_pdo) VALUES ($values_pdo);";
							$insert_api_sms_envios=$conexion->query($insert_api_sms_envios);
							$num=$conexion->affected_rows;
							if(!$insert_api_sms_envios || $num=0){
								$success=false;
								echo "ERROR insert_api_sms_envios"; 
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
						$alert_error[] .= 'sin número celular';
					}
					if(!is_numeric($para) ){
						$alert_error[] .= 'celular no contiene número';
					}
					if(strlen($para) != 10){
						$alert_error[] .= 'celular no tiene 10 dígitos';
					}
					$mensaje_proveedor = implode(", ", $alert_error)." ";
					$update_correo_sms ='
						UPDATE secciones_ine_ciudadanos_campanas_sms_programadas 
							SET 
							status = "2" , 
							fecha_envio="'.$fechaSF.'" , 
							hora_envio="'.$fechaSH.'" , 
							fecha_hora_envio="'.$fechaH.'" , 
							identificador="'.$identificador.'" ,
							mensaje_proveedor="'.$mensaje_proveedor.'"  
							WHERE ( id = "'.$id_seccion_ine_ciudadano_campana_sms_programada.'" );';
					
					$update_correo_sms=$conexion->query($update_correo_sms);
					if(!$update_correo_sms || $num=0){
						$success=false;
						echo "ERROR update_correo_sms"; 
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

	echo "1";