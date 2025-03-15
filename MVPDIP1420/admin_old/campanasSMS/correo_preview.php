<?php
	include __DIR__."/../functions/security.php";
	if(!empty($_POST)){

		include __DIR__."/../functions/api_sms.php";
		include __DIR__."/../functions/timemex.php";
		include __DIR__."/../functions/configuracion.php";
		include __DIR__."/../functions/secciones_ine_ciudadanos.php";
		include __DIR__."/../functions/tipos_ciudadanos.php";
		include __DIR__."/../functions/usuarios.php";
		include __DIR__."/../functions/estados.php";
		include __DIR__."/../functions/municipios.php";
		include __DIR__."/../functions/localidades.php";
		include __DIR__."/../functions/distritos_locales.php";
		include __DIR__."/../functions/distritos_federales.php";
		include __DIR__."/../functions/secciones_ine.php";

		$para = $_POST['correo_electronico_prueba'][0]['correo_prueba'];
		$asunto = $_POST['correo_electronico_prueba'][0]['asunto'];
		$cuerpo = $_POST['correo_electronico_prueba'][0]['cuerpo'];
		
		$id_api_sms = $_POST['correo_electronico_prueba'][0]['id_api_sms'];
		$correo_smsDatos = correo_smsDatos($id_api_sms);

		$unsubscribe['identificador'] = 'AZTST';
		$unsubscribe['codigo_unico'] = 'X2ksa2ASz123';
		//modificamos el ausnto

		//datos fecha_hora
		$fecha_hora = array(
			"[*__Fecha__*]" => $fechaSF,
			"[*__Fecha_WDDMMAAA__*]" => fechaNormalSimpleWDDMMAA_ES($fechaSF),
			"[*__Hora__*]" => $fechaSH,
			"[*__Hora_AMPM__*]" => convertidorAMPM($fechaSH,'','mayuscula'),
			"[*__Hora_ampm__*]" => convertidorAMPM($fechaSH,'',''),
		);

		$configuracionDatos = configuracionDatos();
		$img_logo='<img src="../../../ops/imagen.php?id_img=logo_principal.png" height="90px" >';
		$correo_electronico = array(
			"[*__Correo_Electronico_Repuesta__*]" => $correo_smsDatos['correo_electronico'],
			"[*__Correo_Electronico_Envio__*]" => $correo_smsDatos['usuario'],
			"[*__URL__*]" => $configuracionDatos['url_base'],
			"[*__Nombre_Sistema__*]" => $configuracionDatos['nombre'],
			"[*__Slogan_Sistema__*]" => $configuracionDatos['slogan'],
			"[*__Logo_Sistema__*]" => $img_logo,
			"[*__Correo_Vista_Web__*]" => 'demo',
		);

		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos(3);

		$id_seccion_ine_ciudadano = $seccion_ine_ciudadanoDatos['id'];
		$id_seccion_ine_ciudadano_compartido = $seccion_ine_ciudadanoDatos['id_seccion_ine_ciudadano_compartido'];

		if($id_seccion_ine_ciudadano_compartido!=''){
			$seccion_ine_ciudadanoDatos_compartido = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano_compartido);
		}else{
			$seccion_ine_ciudadanoDatos_compartido['nombre_completo']='No tiene';
		}
		$tipo_ciudadanoDatos = tipo_ciudadanoDatos($seccion_ine_ciudadanoDatos['id_tipo_ciudadano']);
		$datos_ciudadano = array(
			"[*__Tipo_Ciudadano__*]" => $tipo_ciudadanoDatos['nombre'],
			"[*__Nombre_Completo__*]" => $seccion_ine_ciudadanoDatos['nombre_completo'],
			"[*__Nombre__*]" => $seccion_ine_ciudadanoDatos['nombre'],
			"[*__Apellido_Paterno__*]" => $seccion_ine_ciudadanoDatos['apellido_paterno'],
			"[*__Apellido_Materno__*]" => $seccion_ine_ciudadanoDatos['apellido_materno'],
			"[*__Fecha_Nacimiento__*]" => $seccion_ine_ciudadanoDatos['fecha_nacimiento'],
			"[*__Fecha_Nacimiento_Texto__*]" => fechaNormalSimpleWDDMMAA_ES($seccion_ine_ciudadanoDatos['fecha_nacimiento']),
			"[*__Edad__*]" => $seccion_ine_ciudadanoDatos['edad'],
			"[*__Sexo__*]" => $seccion_ine_ciudadanoDatos['sexo'],
			"[*__Relacionado__*]" => $seccion_ine_ciudadanoDatos_compartido['nombre_completo'],
			"[*__Whatsapp__*]" => $seccion_ine_ciudadanoDatos['whatsapp'],
			"[*__Telefono__*]" => $seccion_ine_ciudadanoDatos['telefono'],
			"[*__Celular__*]" => $seccion_ine_ciudadanoDatos['celular'],
			"[*__Correo_Electronico__*]" => $seccion_ine_ciudadanoDatos['correo_electronico'],
		);

		$usuarioDatos = usuarioDatos('','',$id_seccion_ine_ciudadano);
		if($usuarioDatos['status']==1){
			$usuarioDatos['status']='online';
		}else{
			$usuarioDatos['status']='offline';
		}
		$datos_ciudadano_usuario = array(
			"[*__Usuario__*]" => $usuarioDatos['usuario'],
			"[*__Password__*]" => $usuarioDatos['password'],
			"[*__Status__*]" => $usuarioDatos['status'],
		);

		$estadoNombre = estadoNombre($seccion_ine_ciudadanoDatos['id_estado']);
		$municipioNombre = municipioNombre($seccion_ine_ciudadanoDatos['id_municipio']);
		$localidadNombre = localidadNombre($seccion_ine_ciudadanoDatos['id_localidad']);
		$distrito_federalDatos = distrito_federalDatos($seccion_ine_ciudadanoDatos['id_distrito_federal']);
		$distrito_localDatos = distrito_localDatos($seccion_ine_ciudadanoDatos['id_distrito_local']);
		$seccion_ineDatos = seccion_ineDatos($seccion_ine_ciudadanoDatos['id_seccion']);
		$datos_ciudadano_cartografia = array(
			"[*__Estado__*]" => $estadoNombre,
			"[*__Municipio__*]" => $municipioNombre,
			"[*__Localidad__*]" => $localidadNombre,
			"[*__Distrito_Local__*]" => $distrito_localDatos['numero'],
			"[*__Distrito_Federal__*]" => $distrito_federalDatos['numero'],
			"[*__Seccion__*]" => $seccion_ineDatos['numero'],
		);

		$bodyHTML = strtr($cuerpo, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
		$asuntoHTML = strtr($asunto, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
		echo $correoEnvioMailing = correoEnvioMailing($correo_smsDatos,$bodyHTML,$asuntoHTML,$para,'',$unsubscribe);
	}