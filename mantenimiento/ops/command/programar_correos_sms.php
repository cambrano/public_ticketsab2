<?php

	$dir= __DIR__;
	//include __DIR__."/../../MVPDIP1420/admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php";
	include __DIR__."/../db.php";
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


	$sql =' 
		SELECT 
			cm.id,
			cm.tipo,
			cm.nombre,
			cmp.fecha_hora,
			cmp.fecha,
			cmp.hora,
			scm.status api_status,
			cm.status campana_status,
			cm.envio envio_status
		FROM campanas_sms cm 
		LEFT JOIN campanas_sms_programadas cmp
		ON cm.id = cmp.id_campana_sms
		LEFT JOIN api_sms scm
		ON cm.id_api_sms = scm.id
		WHERE cm.status = 1 AND cm.envio = 0 AND scm.status =1 AND cmp.fecha_hora <= "'.$fechaH.'" ;
	';
	/*
	$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
	mysqli_set_charset($conexion, "utf8mb4"); 
	if ($conexion->connect_error){
		echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
	}
	*/

	//verificar si esta activo el envio de apis
	$scriptSQL=" SELECT * FROM api_sms LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	// 30 * 30
	if($row['status'] == 0){
		echo "offline :(";
		die;
	} 



	$success = true;

	$result = $conexion->query($sql);  
	while($row=$result->fetch_assoc()){
		$envia = true;

		$id_campana_sms = $row['id'];
		$tipo = $row['tipo'];
		
		$sql = "SELECT * FROM campanas_sms_encuestas WHERE id_campana_sms='{$id_campana_sms}' ";
		$resultado = $conexion->query($sql);
		$campana_sms_encuestaDatos=$resultado->fetch_assoc(); 

		$sql = "SELECT * FROM campanas_sms_cartografias WHERE id_campana_sms='{$id_campana_sms}' ";
		$resultado = $conexion->query($sql);
		$campana_sms_cartografiaDatos=$resultado->fetch_assoc();

		$sql = "SELECT * FROM campanas_sms_tipos_ciudadanos WHERE id_campana_sms='{$id_campana_sms}' ";
		$resultado = $conexion->query($sql);
		while($row=$resultado->fetch_assoc()){
			$campanas_sms_tipos_ciudadanosIdDatos[$row['id_tipo_ciudadano']]=$row;
		}

		$sql = "SELECT * FROM campanas_sms_tipos_categorias_ciudadanos  WHERE id_campana_sms='{$id_campana_sms}' ";
		$resultado = $conexion->query($sql);
		while($row=$resultado->fetch_assoc()){
			$campanas_sms_tipos_categorias_ciudadanosIdDatos[$row['id_tipo_categoria_ciudadano']]=$row;
		}

		$sql = "INSERT INTO secciones_ine_ciudadanos_campanas_sms_programadas
			(id_seccion_ine_ciudadano, id_seccion_ine, id_distrito_local, id_distrito_federal, id_estado, id_municipio, id_campana_sms, id_campana_sms_cuerpo, id_campana_sms_programada, status, fechaR, codigo_plataforma, codigo_seccion_ine_ciudadano, identificador, asunto, cuerpo, fecha_registro, hora_registro, fecha_hora_registro,tipo)
			SELECT 
			sic.id id_seccion_ine_ciudadano,
			sic.id_seccion_ine,
			sic.id_distrito_local,
			sic.id_distrito_federal,
			sic.id_estado,
			sic.id_municipio,
			/*sic.id_campana_sms,*/
			(SELECT cm.id from campanas_sms cm limit 1) id_campana_sms,
			/*sic.id_campana_sms_cuerpo,*/
			(SELECT cmp.id from campanas_sms_cuerpos cmp limit 1) id_campana_sms_cuerpo,

			NULL id_campana_sms_programada,
			'0' status,
			sic.fechaR,
			sic.codigo_plataforma,
			sic.codigo_seccion_ine_ciudadano,
			'1' identificador,
			/*sic.asunto,*/
			/*(SELECT cmp.asunto from campanas_sms_cuerpos cmp limit 1) asunto,*/
			NULL asunto,
			/*sic.cuerpo,*/
			/*(SELECT cmp.cuerpo from campanas_sms_cuerpos cmp limit 1) cuerpo,*/
			NULL cuerpo,
			'{$fechaSF}' fecha_registro,
			'{$fechaSH}' hora_registro,
			'{$fechaH}' fecha_hora_registro,
			'{$tipo}' tipo
			FROM secciones_ine_ciudadanos sic
			WHERE 1 
		";

		if($campana_sms_cartografiaDatos['tipo_cartografia']=='municipios'){
			$sql .= ' AND sic.id_municipio =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
		}elseif ($campana_sms_cartografiaDatos['tipo_cartografia']=='distritos_locales') {
			$sql .= ' AND sic.id_distrito_local =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
		}elseif ($campana_sms_cartografiaDatos['tipo_cartografia']=='distritos_federales') {
			$sql .= ' AND sic.id_distrito_federal =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
		}elseif ($campana_sms_cartografiaDatos['tipo_cartografia']=='secciones_ine') {
			$sql .= ' AND sic.id_seccion_ine =  '.$campana_sms_cartografiaDatos['id_tipo_cartografia'];
		}else{}

		if(!empty($campanas_sms_tipos_ciudadanosIdDatos)){
			foreach ($campanas_sms_tipos_ciudadanosIdDatos as $key => $value) {
				$tipos_ciudadanos[]=$value['id_tipo_ciudadano'];
			}
			if(!empty($tipos_ciudadanos)){
				$id_tipos_ciudadanos = "'".implode("','", $tipos_ciudadanos)."'";
				$sql .=" AND sic.id_tipo_ciudadano IN ({$id_tipos_ciudadanos}) ";
			}
		}

		if(!empty($campanas_sms_tipos_categorias_ciudadanosIdDatos)){
			foreach ($campanas_sms_tipos_categorias_ciudadanosIdDatos as $key => $value) {
				$tipos_categorias_ciudadanos[]=$value['id_tipo_categoria_ciudadano'];
			}
			if(!empty($tipos_categorias_ciudadanos)){
				$id_tipos_categorias_ciudadanos = "'".implode("','", $tipos_categorias_ciudadanos)."'";
				$sql .=" AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id IN ({$id_tipos_categorias_ciudadanos})  AND sic.id_tipo_ciudadano = sicc.id ) ";
			}
		}

		if(!empty($campana_sms_encuestaDatos)){
			$sql .=" AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_encuestas sice WHERE sice.id_encuesta = '{$campana_sms_encuestaDatos['id_encuesta']}' AND sice.id_seccion_ine_ciudadano = sic.id ) ";
		}

		$conexion->autocommit(FALSE);
		$update_secciones_ine_ciudadanos_campanas_sms_programadas=$conexion->query($sql);
		$num=$conexion->affected_rows;
		if(!$update_secciones_ine_ciudadanos_campanas_sms_programadas || $num=0){
			$success=false;
			echo "<br>";
			echo "ERROR update_secciones_ine_ciudadanos_campanas_sms_programadas"; 
			var_dump($conexion->error);
		}

		/// decimos que estan enviados
		$update_api_sms ='UPDATE campanas_sms SET envio = "1" AND id = "'.$id_campana_sms.'" ;';
		$update_api_sms=$conexion->query($update_api_sms);
		if(!$update_api_sms || $num=0){
			$success=false;
			echo "ERROR update_api_sms"; 
			var_dump($conexion->error);
		}
	}

	if($envia){
		if($success){
			echo "SI";
			$conexion->commit();
			$conexion->close();
		}else{
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		} 
	}else{
		echo 'ningún envío';
	}












?>