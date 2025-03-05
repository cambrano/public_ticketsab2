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

	$sql_mailing='
	SELECT
		siccmp.id,
		siccmp.id_seccion_ine_ciudadano,

		cmc.cuerpo,
		cmc.asunto,

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
		cmr.servidor d_servidor,
		cmr.puerto d_puerto,
		cmr.cifrado d_cifrado,
		cmr.usuario d_usuario,
		cmr.password d_password,
		cmr.de d_de,
		cmr.correo_electronico u_correo_electronico,

		siccmp.identificador u_identificador,
		sic.codigo_seccion_ine_ciudadano u_codigo_unico


	FROM secciones_ine_ciudadanos_campanas_mailing_programadas siccmp
	LEFT JOIN secciones_ine_ciudadanos sic
	ON sic.id = siccmp.id_seccion_ine_ciudadano
	LEFT JOIN campanas_mailing cm
	ON cm.id = siccmp.id_campana_mailing
	LEFT JOIN correos_mailing cmr
	ON cmr.id = cm.id_correo_mailing
	LEFT JOIN campanas_mailing_cuerpos cmc
	ON cmc.id_campana_mailing = cm.id

	WHERE siccmp.status=0 AND siccmp.tipo IN (1,2,3)  AND cm.status=1 AND cmr.status=1 LIMIT 1;
	';

	/*
	$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
	mysqli_set_charset($conexion, "utf8mb4"); 
	if ($conexion->connect_error){
		echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
	}
	*/


	//verificar si esta activo el envio de correos
	$scriptSQL=" SELECT * FROM api_mailing LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	// 30 * 30
	if($row['status'] == 0){
		echo "offline :(";
		die;
	} 
	$url_mailing = $row['url_mailing'];
	$tiempo_espera_segundos = $row['tiempo_espera_segundos'];
	$correos_a_enviar = $row['correos_a_enviar'];
	$inicio=date("Y-m-d H:i:s");
	$mifecha = new DateTime(); 
	$mifecha->modify('+ '.$row['tiempo_script'].' seconds'); 
	$final = $mifecha->format('d-m-Y H:i:s');
	$startTime = strtotime($inicio);
	$endTime = strtotime($final);

	//verificar si esta activo el envio de correos
	$scriptSQL=" SELECT mailing FROM switch_operaciones LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	$mailing = $row['mailing'];
	if($mailing ==0){
		echo "offline";
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
	*/

	function correoEnvioMailing($datos,$mensaje,$asunto,$para,$archivo=null,$unsubscribe=null){
		//Librerías para el envío de mail
		include_once('class.phpmailer.php');
		include_once('class.smtp.php');
		//Recibir todos los parámetros del formulario
		//Este bloque es importante
		//convierte en UTF-8 el subject
		$fechaH=date('Y-m-d H:i:s');
		$servidor=$datos['servidor'];
		$puerto=$datos['puerto'];
		$cifrado=$datos['cifrado'];
		$usuario=$datos['usuario'];
		$password=$datos['password'];
		$de=$datos['de'];
		$mensaje=$mensaje;
		$asunto=$asunto;
		
		$correo_unsubscribe=$datos['correo_electronico'];
		$codigo_unico=$unsubscribe['codigo_unico'];
		$identificador=$unsubscribe['identificador'];
		$correo_electronico_reply = $datos['correo_electronico'];
		// Godaddy
		/*
		$servidor="p3plcpnl0942.prod.phx3.secureserver.net";
		$puerto=465;
		$cifrado="ssl";
		$usuario="miagenciadeviajes@creesuniendoatabasco.org.mx";
		$password="a07080444c";
		*/
		//$para="soporte@softwaresada.com";
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = $servidor;
		$mail->Port = $puerto;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = $cifrado;
		$mail->SMTPDebug = 0; 
		$mail->SMTPAutoTLS = true; 

		//$message_id="ASsadasda131e";
		//$mail->addCustomHeader('In-Reply-To', $message_id);
		//$mail->MessageID = $message_id;
		//$mail->addCustomHeader('References', $message_id);
		

		$mail->Username =$usuario;
		$mail->Password = $password;
		$mail->FromName = $de;
		$mail->From = $usuario; 
		$mail->AddReplyTo($correo_electronico_reply, 'Responder '.$de);
		//$mail->AddCustomHeader("List-Unsubscribe: <mailto:support@rosresurs.net?subject=Unsubscribe>, <http://rosresurs.net/escript/unsubscribe.php?token=12312312>");
		$mail->AddCustomHeader("List-Unsubscribe-Post: List-Unsubscribe=One-Click");
		$mail->AddCustomHeader("List-Unsubscribe:<mailto:".$correo_unsubscribe."?subject=Unsubscribe>, <".$unsubscribe['url_mailing']."unsubscribe.php?cot=".$codigo_unico."&cot1=unsubscribe&cot2=".$identificador.">");
		$mail->AddCustomHeader("Precedence: bulk");

		//Agregar destinatario
		$mail->AddAddress($para);
		//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
		//$mail->AddBCC("@financialgroup.mx",'Ticket Copia');
		//$asunto='=?UTF-8?B?'.base64_encode($asunto).'?=';
		$mail->Subject = $asunto;
		$mail->Body = $mensaje;
		//Para adjuntar archivo
		///$mail->AddAttachment($archivo['tmp_name'], $archivo['name']);
		$mail->MsgHTML($mensaje); 
		$mail->CharSet = 'UTF-8';
		$mail->IsHTML(true);
		if($archivo != ""){
			$mail->addStringAttachment($archivo['file'],$archivo['nombre'], $encoding = $archivo['encoding'], $type = $archivo['type']);
		}

		if($mail->Send()){
			return $valsmpt="1";
		}else{ 
			$valsmpt = "";
			$valsmpt .= "Mailer Error: " . $mail->ErrorInfo; 
			return $valsmpt;

		}
	}

	/*
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

	
	//loop
	///configuracion_sistema
	//verificar si esta activo el envio de correos
	$sql='SELECT nombre,slogan,url_base FROM configuracion WHERE 1 = 1 LIMIT 1';
	$resultado = $conexion->query($sql);
	$configuracionDatos=$resultado->fetch_assoc();
	$img_logo='<img src="'.$configuracionDatos['url_base'].'ops/imagen.php?id_img=logo_principal.png" height="90px" >';

	for ( $i = $startTime; $i < $endTime; $i = $i + $tiempo_espera_segundos ) {
		$correos_a_enviar;
		for ($n=1; $n <= $correos_a_enviar; $n++) {
			$resultadoT = $conexion->query($sql_mailing);
			$rowMail=$resultadoT->fetch_assoc();
			if(!empty($rowMail)){
				$enviados_succes = true;
				$fecha_hora = array(
					"[*__Fecha__*]" => $fechaSF,
					"[*__Fecha_WDDMMAAA__*]" => fechaNormalSimpleWDDMMAA_ES($fechaSF),
					"[*__Hora__*]" => $fechaSH,
					"[*__Hora_AMPM__*]" => convertidorAMPM($fechaSH,'','mayuscula'),
					"[*__Hora_ampm__*]" => convertidorAMPM($fechaSH,'',''),
				);

				$correo_electronico = array(
					"[*__Correo_Electronico_Repuesta__*]" => $rowMail['correo_electronico'],
					"[*__Correo_Electronico_Envio__*]" => $rowMail['usuario'],
					"[*__URL__*]" => $configuracionDatos['url_base'],
					"[*__Nombre_Sistema__*]" => $configuracionDatos['nombre'],
					"[*__Slogan_Sistema__*]" => $configuracionDatos['slogan'],
					"[*__Logo_Sistema__*]" => $img_logo,
					"[*__Correo_Vista_Web__*]" => 'demo',
				);

				$datos_ciudadano = array(
					"[*__Tipo_Ciudadano__*]" => $rowMail['tipo_ciudadano'],
					"[*__Nombre_Completo__*]" => $rowMail['nombre_completo'],
					"[*__Nombre__*]" => $rowMail['nombre'],
					"[*__Apellido_Paterno__*]" => $rowMail['apellido_paterno'],
					"[*__Apellido_Materno__*]" => $rowMail['apellido_materno'],
					"[*__Fecha_Nacimiento__*]" => $rowMail['fecha_nacimiento'],
					"[*__Fecha_Nacimiento_Texto__*]" => fechaNormalSimpleWDDMMAA_ES($rowMail['fecha_nacimiento']),
					"[*__Edad__*]" => $rowMail['edad'],
					"[*__Sexo__*]" => $rowMail['sexo'],
					"[*__Relacionado__*]" => $rowMail['nombre_completo']==''?'No tiene':$rowMail['nombre_completo'],
					"[*__Whatsapp__*]" => $rowMail['whatsapp'],
					"[*__Telefono__*]" => $rowMail['telefono'],
					"[*__Celular__*]" => $rowMail['celular'],
					"[*__Correo_Electronico__*]" => $rowMail['correo_electronico'],
				);

				$datos_ciudadano_usuario = array(
					"[*__Usuario__*]" => $rowMail['usuario'],
					"[*__Password__*]" => $rowMail['password'],
					"[*__Status__*]" => $rowMail['status']=='1'?'Online':'Offline',
				);

				$datos_ciudadano_cartografia = array(
					"[*__Estado__*]" => $rowMail['estado'],
					"[*__Municipio__*]" => $rowMail['municipio'],
					"[*__Localidad__*]" => $rowMail['localidad'],
					"[*__Distrito_Local__*]" => $rowMail['distrito_local'],
					"[*__Distrito_Federal__*]" => $rowMail['distrito_federal'],
					"[*__Seccion__*]" => $rowMail['seccion'],
				);
				$cuerpo = $rowMail['cuerpo'];
				$asunto = $rowMail['asunto'];
				$bodyHTML = strtr($cuerpo, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
				$asuntoHTML = strtr($asunto, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
				$servidor['servidor'] = $rowMail['d_servidor'];
				$servidor['puerto'] = $rowMail['d_puerto'];
				$servidor['cifrado'] = $rowMail['d_cifrado'];
				$servidor['usuario'] = $rowMail['d_usuario'];
				$servidor['password'] = $rowMail['d_password'];
				$servidor['de'] = $rowMail['d_de'];
				$servidor['correo_electronico'] = $rowMail['u_correo_electronico'];

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
				$unsubscribe['codigo_unico'] = $rowMail['u_codigo_unico'];
				$unsubscribe['identificador'] = $identificador;
				$unsubscribe['url_mailing'] = $url_mailing;
				$para = $rowMail['correo_electronico']='cambranoy@gmail.com';
				$bodyHTML .= '
				<table style="background-color: #fafbfd; color: gray;text-align: center;width:100%" cellpadding="0" cellspacing="0" width="100%" >
					<tr>
						<td colspan="2" style="text-align: center;font-size:18px" >
							<a href="'.$url_mailing.'mail/view.php?cot='.$unsubscribe['codigo_unico'].'&cot1='.$unsubscribe['identificador'].'">Abrir en Navegador</a><br><br>
						</td> 
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;font-size:15px" >
							Da click <a href="'.$url_mailing.'mail/view.php?cot='.$unsubscribe['codigo_unico'].'&cot1='.$unsubscribe['identificador'].'">aquí</a>  si no puedes ver el mensaje.<br>
						</td> 
					</tr> 
					<tr>
						<td colspan="2" style="text-align: justify;font-size:11px" >
							Este sitio contiene información dirigida únicamente al destinatario. Si usted no es el destinatario y ha entrado a este sitio por error, no debe por ningún medio retransmitir, divulgar, publicar, copiar, ni hacer del conocimiento de terceros el contenido, por lo que le pedimos notifique de inmediato al correo electrónico del remitente que contenía este vínculo y elimine cualquier copia del mismo.<br><br>
						</td> 
					</tr>
					<tr>
						<td colspan="2" style="text-align:center">
							<img src="'.$url_mailing.'mail/imagen.php?id_img=verificacion_email.png&cot='.$unsubscribe['codigo_unico'].'&cot1='.$unsubscribe['identificador'].'" style="width: 120px">
						</td>
					</tr>
				</table>'; 
				//$bodyHTML='cot='.$unsubscribe['codigo_unico']."<br>cot2=".$unsubscribe['identificador'];

				$correoEnvioMailing = correoEnvioMailing($servidor,$bodyHTML,$asuntoHTML,$para,'',$unsubscribe);
				if($correoEnvioMailing=='1'){
					///cambiamos el status del ciudadano
					$id_seccion_ine_ciudadano_campana_mailing_programada = $rowMail['id'];
					$update_correo_mailing ='UPDATE secciones_ine_ciudadanos_campanas_mailing_programadas SET status = "1" , fecha_envio="'.$fechaSF.'" , hora_envio="'.$fechaSH.'" , fecha_hora_envio="'.$fechaH.'" , identificador="'.$identificador.'"  WHERE ( id = "'.$id_seccion_ine_ciudadano_campana_mailing_programada.'" );';
					$update_correo_mailing=$conexion->query($update_correo_mailing);
					if(!$update_correo_mailing || $num=0){
						$success=false;
						echo "ERROR update_correo_mailing"; 
						var_dump($conexion->error);
					}
				}else{
					///cambiamos el status del servidor de correos
					$id_correo_mailing = $rowMail['d_id'];
					$update_correo_mailing ='UPDATE correos_mailing SET status = "0" WHERE ( id = "'.$id_correo_mailing.'" );';
					$update_correo_mailing=$conexion->query($update_correo_mailing);
					if(!$update_correo_mailing || $num=0){
						$success=false;
						echo "ERROR update_correo_mailing"; 
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
		$para      = 'cambrano@gmail.com';
		$titulo    = 'Error Cozumel';
		$mensaje   = 'Error en cozumel checar los valores';
		$cabeceras = 'From: developer@ideasab.com' . "\r\n" .
		    'Reply-To: developer@ideasab.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

		mail($para, $titulo, $mensaje, $cabeceras);
	}
	$conexion->close();
	die;
?>