<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	
	$modulosPermiso = modulosPermiso('sistema_unico_beneficiarios','',$_COOKIE["id_usuario"]);

	if($modulosPermiso['secciones_ine_ciudadanos_categorias'] || $modulosPermiso['all'] ){
		$modulo_ciudadano_categoria = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_encuestas'] || $modulosPermiso['all'] ){
		$modulo_encuestas = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_seguimientos'] || $modulosPermiso['all'] ){
		$modulo_seguimientos = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_campañas_mailing'] || $modulosPermiso['all'] ){
		$modulo_campanas_mailing1 = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_campañas_sms'] || $modulosPermiso['all'] ){
		$modulo_campanas_sms1 = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_campañas_whatsapp'] || $modulosPermiso['all'] ){
		$modulo_campanas_whatsapp1 = true;
	}
	if($modulosPermiso['documentos_oficiales'] || $modulosPermiso['all'] ){
		$modulo_documentos_oficiales = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_programas_apoyos'] || $modulosPermiso['all'] ){
		$modulo_programas_apoyos = true;
	}
	if($modulosPermiso['militantes_partidos'] || $modulosPermiso['all'] ){
		$modulo_militantes_partidos = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_grupos'] || $modulosPermiso['all'] ){
		$modulo_secciones_ine_ciudadanos_grupos = true;
	}
	if($modulosPermiso['secciones_ine_ciudadanos_giras'] || $modulosPermiso['all'] ){
		$modulo_secciones_ine_ciudadanos_giras = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}

	if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
		$option_download = true;
	}
	$option_download = true;


	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];

	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave',
		1 =>'plataforma',
		2 =>'folio',
		3 =>'curp',
		4 =>'clave_elector',
		5 =>'tipo_seccion',
		6 =>'seccion',
		7 =>'manzana',
		8 =>'distrito_local',
		9 =>'distrito_federal',
		10 =>'distancia_km',
		11 =>'tipo_ciudadano',
		12 =>'nombre_completo',
		13 =>'sexo',
		14 =>'fecha_nacimiento',
		15 =>'whatsapp',
		16 =>'celular',
		17 =>'telefono',
		18 =>'correo_electronico',
		19 =>'municipio',
		20 =>'localidad',
		21 =>'categorias',
		22 =>'medio_registro',
		23 =>'distancia_alert',
		24 =>'seguimientos',
		25 =>'status_verificacion',
		26 =>'documentos_oficiales',
		27 =>'programas_apoyos',
		28 =>'programas_apoyos_categorias',
		29 =>'militantes_partidos',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos WHERE 1 = 1   ";
	if($RowPlataforma['tipo']!='x'){
		$sql .= " AND codigo_plataforma = '{$codigo_plataforma}' ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	//! este codigo esta en el excel recuerda modificarlo igual
	$sql = "
	SELECT 
		e.id,
		e.id_seccion_ine_ciudadano_compartido,
		e.clave,
		e.folio,
		e.curp,
		e.clave_elector,
		e.ocr,
		(SELECT IF(s.tipo=1,'Urbano','Rural') FROM secciones_ine s WHERE s.id = e.id_seccion_ine) tipo_seccion,
		(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
		e.manzana,
		(SELECT si.numero FROM distritos_locales si WHERE si.id = e.id_distrito_local) distrito_local,
		(SELECT si.numero FROM distritos_federales si WHERE si.id = e.id_distrito_federal) distrito_federal,
		e.distancia_km,
		(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = e.id_tipo_ciudadano) tipo_ciudadano,
		IF( 
			e.id_seccion_ine_ciudadano_compartido !='',
			(SELECT sim.nombre_completo FROM secciones_ine_ciudadanos sim WHERE sim.id = e.id_seccion_ine_ciudadano_compartido),
			'NO TIENE'
		) relacionado,
		e.nombre_completo,
		e.sexo,
		e.fecha_nacimiento,
		e.whatsapp,
		e.celular,
		e.telefono,
		e.correo_electronico,

		
		CONCAT_WS(', ',e.calle,e.colonia ) direccion,
		(SELECT m.municipio FROM municipios m WHERE m.id = e.id_municipio) municipio,
		(SELECT l.localidad FROM localidades l WHERE l.id = e.id_localidad) localidad,
		e.latitud,
		e.longitud,
		(
			IF(
				(SELECT sicc.id FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id LIMIT 1 ) IS NULL,
				'Sin Categorías',
				(
					SELECT GROUP_CONCAT(tcc.nombre) categoriast
					FROM secciones_ine_ciudadanos_categorias sicc
					LEFT JOIN tipos_categorias_ciudadanos tcc
					ON tcc.id = sicc.id_tipo_categoria_ciudadano
					WHERE sicc.id_seccion_ine_ciudadano = e.id
					GROUP BY sicc.id_seccion_ine_ciudadano
				)
			)
		) categorias,
		CASE 
			WHEN e.medio_registro = 1 THEN 'CIUDADANO'
			WHEN e.medio_registro = 2 THEN 'SISTEMA'
			ELSE 'IMPORTACION'
		END AS medio_registro,
		IF(e.distancia_alert=0,'NO TIENE','DISTANCIA') distancia_alert,
		(SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) seguimientos,
		CASE
			WHEN e.status_verificacion = '1' THEN 'Encontrado'
			WHEN e.status_verificacion = '2' THEN 'Verificado'
			WHEN e.status_verificacion = '3' THEN 'Por Validar'
			ELSE 'No Encontrado'
		END as status_verificacion,
		(SELECT count(do.id) FROM documentos_oficiales do WHERE do.id_seccion_ine_ciudadano = e.id) documentos_oficiales,
		(SELECT count(do.id) FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id) programas_apoyos,
		(SELECT GROUP_CONCAT(DISTINCT cpa.nombre) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN programas_apoyos_categorias pac ON sicpa.id_programa_apoyo = pac.id_programa_apoyo LEFT JOIN categorias_programas_apoyos cpa ON cpa.id = pac.id_categoria_programa_apoyo WHERE sicpa.id_seccion_ine_ciudadano = e.id) programas_apoyos_categorias,
		(SELECT pl.nombre_corto FROM militantes_partidos mp LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) militantes_partidos,

		e.observaciones
	FROM secciones_ine_ciudadanos e
	WHERE 1 = 1 ";
	$sql = "
		SELECT
			e.id,
			e.id_seccion_ine_ciudadano_compartido,
			e.clave,
			(SELECT p.nombre FROM plataformas p WHERE p.plataforma = e.codigo_plataforma) plataforma,
			e.folio,
			e.curp,
			e.clave_elector,
			e.ocr,
			IF(s.tipo = 1, 'Urbano', 'Rural') AS tipo_seccion,
			si.numero AS seccion,
			e.manzana,
			dl.numero AS distrito_local,
			df.numero AS distrito_federal,
			e.distancia_km,
			tc.nombre AS tipo_ciudadano,
			IF(e.id_seccion_ine_ciudadano_compartido != '', CONCAT((SELECT tc1.nombre FROM tipos_ciudadanos tc1 WHERE tc1.id = sim.id_tipo_ciudadano ),'--',sim.nombre_completo,'--',sim.clave_elector), 'NO TIENE') AS relacionado,
			e.nombre_completo,
			e.sexo,
			e.fecha_nacimiento,
			e.whatsapp,
			e.celular,
			e.telefono,
			e.correo_electronico,
			CONCAT_WS(', ', e.calle, e.colonia) AS direccion,
			m.municipio,
			l.localidad,
			e.latitud,
			e.longitud,
			(
				IF(
					(SELECT sicc.id FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id LIMIT 1) IS NULL,
					'Sin Categorías',
					(
						SELECT GROUP_CONCAT(tcc.nombre) categoriast
						FROM secciones_ine_ciudadanos_categorias sicc
						LEFT JOIN tipos_categorias_ciudadanos tcc
						ON tcc.id = sicc.id_tipo_categoria_ciudadano
						WHERE sicc.id_seccion_ine_ciudadano = e.id
					)
				)
			) AS categorias,
			CASE
				WHEN e.medio_registro = 1 THEN 'CIUDADANO'
				WHEN e.medio_registro = 2 THEN 'SISTEMA'
				ELSE 'IMPORTACION'
			END AS medio_registro,
			IF(e.distancia_alert = 0, 'NO TIENE', 'DISTANCIA') AS distancia_alert,
			(SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id) AS seguimientos,
			CASE
				WHEN e.status_verificacion = '1' THEN 'Encontrado'
				WHEN e.status_verificacion = '2' THEN 'Verificado'
				WHEN e.status_verificacion = '3' THEN 'Por Validar'
				ELSE 'No Encontrado'
			END AS status_verificacion,
			(SELECT count(do.id) FROM documentos_oficiales do WHERE do.id_seccion_ine_ciudadano = e.id) AS documentos_oficiales,
			(SELECT count(do.id) FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id) AS programas_apoyos,
			(SELECT GROUP_CONCAT(DISTINCT cpa.nombre) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN programas_apoyos_categorias pac ON sicpa.id_programa_apoyo = pac.id_programa_apoyo LEFT JOIN categorias_programas_apoyos cpa ON cpa.id = pac.id_categoria_programa_apoyo WHERE sicpa.id_seccion_ine_ciudadano = e.id) AS programas_apoyos_categorias,
			(
				SELECT pl.nombre_corto
				FROM militantes_partidos mp
				LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id
				WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1
				ORDER BY pl.id DESC
				LIMIT 1
			) AS militantes_partidos,
			e.observaciones
		FROM secciones_ine_ciudadanos e
		LEFT JOIN secciones_ine s ON s.id = e.id_seccion_ine
		LEFT JOIN secciones_ine si ON si.id = e.id_seccion_ine
		LEFT JOIN distritos_locales dl ON dl.id = e.id_distrito_local
		LEFT JOIN distritos_federales df ON df.id = e.id_distrito_federal
		LEFT JOIN tipos_ciudadanos tc ON tc.id = e.id_tipo_ciudadano
		LEFT JOIN secciones_ine_ciudadanos sim ON sim.id = e.id_seccion_ine_ciudadano_compartido
		LEFT JOIN municipios m ON m.id = e.id_municipio
		LEFT JOIN localidades l ON l.id = e.id_localidad
		WHERE 1 = 1 
	";
	if($RowPlataforma['tipo']!='x'){
		$sql .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
	}
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	} 
	$plataforma=$search_database['plataforma'];
	if( $plataforma!="" ){   //name
		$post_search=true;
		$sql.=" AND e.codigo_plataforma = '{$plataforma}' ";
		$sqlContador .= " AND e.codigo_plataforma = '{$plataforma}' ";
	} 

	$clave_elector=$search_database['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador .= " AND e.clave_elector LIKE '%{$clave_elector}%' ";
	} 

	$curp=$search_database['curp'];
	if( $curp!="" ){   //name
		$post_search=true;
		$sql.=" AND e.curp LIKE '%{$curp}%' ";
		$sqlContador .= " AND e.curp LIKE '%{$curp}%' ";
	} 

	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND e.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND e.folio LIKE '%{$folio}%' ";
	} 

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND e.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND e.nombre LIKE '%{$nombre}%' ";
	} 

	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador .= " AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	} 

	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador .= " AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
	} 

	$nombre_completo=$search_database['nombre_completo'];
	if( $nombre_completo!="" ){   //name
		$post_search=true;
		$sql.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
		$sqlContador.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
	}

	$sexo=$search_database['sexo'];
	if( $sexo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.sexo LIKE '%{$sexo}%' ";
		$sqlContador.=" AND e.sexo LIKE '%{$sexo}%' ";
	}

	$id_seccion_ine_ciudadano_compartido=$search_database['id_seccion_ine_ciudadano_compartido'];
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}

	$fecha_nacimiento_1=$search_database['fecha_nacimiento_1'];
	$fecha_nacimiento_2=$search_database['fecha_nacimiento_2'];
	if( $fecha_nacimiento_1 != '' && $fecha_nacimiento_2 == ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento <= '{$fecha_nacimiento_1}' ";
		$sqlContador.=" AND e.fecha_nacimiento <= '{$fecha_nacimiento_1}' ";
	}

	if( $fecha_nacimiento_1 == '' && $fecha_nacimiento_2 != ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento >= '{$fecha_nacimiento_2}' ";
		$sqlContador.=" AND e.fecha_nacimiento >= '{$fecha_nacimiento_2}' ";
	}
	if( $fecha_nacimiento_1 != '' && $fecha_nacimiento_2 != ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento BETWEEN '{$fecha_nacimiento_1}' AND '{$fecha_nacimiento_2}' ";
		$sqlContador.=" AND e.fecha_nacimiento BETWEEN '{$fecha_nacimiento_1}' AND '{$fecha_nacimiento_2}' ";
	}

	$fecha_nacimiento_dia=$search_database['fecha_nacimiento_dia'];
	if( $fecha_nacimiento_dia!="" ){   //name
		$post_search=true;
		$sql.=" AND DAY(e.fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
		$sqlContador.=" AND DAY(e.fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
	}

	$fecha_nacimiento_mes=$search_database['fecha_nacimiento_mes'];
	if( $fecha_nacimiento_mes!="" ){   //name
		$post_search=true;
		$sql.=" AND MONTH(e.fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
		$sqlContador.=" AND MONTH(e.fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
	}

	$fecha_nacimiento_edad=$search_database['fecha_nacimiento_edad'];
	if( $fecha_nacimiento_edad!="" ){   //name
		$post_search=true;
		$sql.=" AND TIMESTAMPDIFF(YEAR,e.fecha_nacimiento,CURDATE()) = '{$fecha_nacimiento_edad}' ";
		$sqlContador.=" AND TIMESTAMPDIFF(YEAR,e.fecha_nacimiento,CURDATE()) = '{$fecha_nacimiento_edad}' ";
	}

	$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND e.id_seccion_ine = $id_seccion_ine ";
		$sqlContador =  " AND e.id_seccion_ine = $id_seccion_ine ";
	}

	$id_cuartel=$search_database['id_cuartel'];
	if( $id_cuartel!="" ){
		$post_search=true;
		$sql.=" AND e.id_cuartel IN ($id_cuartel) ";
		$sqlContador.=" AND e.id_cuartel IN ($id_cuartel) ";
	}

	$id_tipo_ciudadano=$search_database['id_tipo_ciudadano'];
	if( $id_tipo_ciudadano!="" ){
		$post_search=true;
		$sql.=" AND e.id_tipo_ciudadano IN ($id_tipo_ciudadano) ";
		$sqlContador.=" AND e.id_tipo_ciudadano IN ($id_tipo_ciudadano) ";
	}

	$status_verificacion=$search_database['status_verificacion'];
	if( $status_verificacion!="" ){   //name
		$post_search=true;
		$sql.=" AND e.status_verificacion = '{$status_verificacion}' ";
		$sqlContador.=" AND e.status_verificacion = '{$status_verificacion}' ";
	}

	$info_vigente=$search_database['info_vigente'];
	if( $info_vigente!="" ){   //name
		$ano = date("Y");
		$post_search=true;
		if($info_vigente==1){
			$sql.=" AND e.vigencia < '{$ano}' ";
			$sqlContador.=" AND e.vigencia < '{$ano}' ";
		}else{
			$sql.=" AND e.vigencia >= '{$ano}' ";
			$sqlContador.=" AND e.vigencia >= '{$ano}' ";
		}
	}

	$relacion=$search_database['relacion'];
	if( $relacion!="" ){   //name
		$post_search=true;
		if($relacion==1){
			$sql.=" AND e.id_seccion_ine_ciudadano_compartido > 0 ";
			$sqlContador.=" AND e.id_seccion_ine_ciudadano_compartido > 0 ";
		}else{
			$sql.=" AND (e.id_seccion_ine_ciudadano_compartido IS NULL OR e.id_seccion_ine_ciudadano_compartido =0 ) ";
			$sqlContador.=" AND (e.id_seccion_ine_ciudadano_compartido IS NULL OR e.id_seccion_ine_ciudadano_compartido =0 ) ";
		}
	}

	$solo_padre=$search_database['solo_padre'];
	if( $solo_padre!="" ){   //name
		$post_search=true;
		if($solo_padre==1){
			$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos sicc WHERE sicc.id_seccion_ine_ciudadano_compartido = e.id ) > 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos sicc WHERE sicc.id_seccion_ine_ciudadano_compartido = e.id ) > 0 ";
		}else{
			$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos sicc WHERE sicc.id_seccion_ine_ciudadano_compartido = e.id ) = 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos sicc WHERE sicc.id_seccion_ine_ciudadano_compartido = e.id ) = 0 ";
		}
	}

	$documentos_oficiales=$search_database['documentos_oficiales'];
	if( $documentos_oficiales!="" ){   //name
		$post_search=true;
		if($documentos_oficiales==1){
			$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
		}else{
			$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
		}
	}


	$vigencia_documentos_oficiales=$search_database['vigencia_documentos_oficiales'];
	if( $vigencia_documentos_oficiales!="" ){   //name
		$post_search=true;
		if($vigencia_documentos_oficiales==1){
			$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.fecha_vigencia < '{$fechaSF}' ) > 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.fecha_vigencia < '{$fechaSF}' ) > 0 ";
		}else{
			$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.fecha_vigencia < '{$fechaSF}' ) = 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.fecha_vigencia < '{$fechaSF}' ) = 0 ";
		}
	}

	$programas_apoyos=$search_database['programas_apoyos'];
	if( $programas_apoyos!="" ){   //name
		$post_search=true;
		if($programas_apoyos==1){
			$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
		}else{
			$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
		}
	}

	$id_programa_apoyo=$search_database['id_programa_apoyo'];
	if( $id_programa_apoyo!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
		$sqlContador.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
	}

	$num_seguimiento=$search_database['num_seguimiento'];
	if( $num_seguimiento!="" ){   //name
		$post_search=true;
		$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) = ".$num_seguimiento;
		$sqlContador.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) = ".$num_seguimiento;
	}

	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){
		$post_search=true;
		$sql.=" AND e.id_municipio IN ($id_municipio) ";
		$sqlContador.=" AND e.id_municipio IN ($id_municipio) ";
	}

	$id_seccion_ine_grupo=$search_database['id_seccion_ine_grupo'];
	if( $id_seccion_ine_grupo!="" ){
		$post_search=true;
		$sql.=" AND (SELECT pl.id FROM secciones_ine_ciudadanos_grupos mp LEFT JOIN secciones_ine_grupos pl ON mp.id_seccion_ine_grupo = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_seccion_ine_grupo}) ";
		$sqlContador.=" AND (SELECT pl.id FROM secciones_ine_ciudadanos_grupos mp LEFT JOIN secciones_ine_grupos pl ON mp.id_seccion_ine_grupo = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_seccion_ine_grupo}) ";
	}

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){
		$post_search=true;
		$sql.=" AND e.id_localidad IN ($id_localidad) ";
		$sqlContador.=" AND e.id_localidad IN ($id_localidad) ";
	}

	$id_partido_legado=$search_database['id_partido_legado'];
	if( $id_partido_legado!="" ){
		///buscar los militantes con partido segun el value recuerda solo el que tenga status 1 que es activo
		$post_search=true;
		$sql.="  AND (SELECT pl.id FROM militantes_partidos mp LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_partido_legado})";
		$sqlContador.="  AND (SELECT pl.id FROM militantes_partidos mp LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_partido_legado})";
	}

	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_local IN ($id_distrito_local) ";
		$sqlContador.=" AND e.id_distrito_local IN ($id_distrito_local) ";
	}
	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
		$sqlContador.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
	}

	$id_tipo_categoria_ciudadano=$search_database['id_tipo_categoria_ciudadano'];
	if( $id_tipo_categoria_ciudadano!="" ){
		$porciones = explode(",", $id_tipo_categoria_ciudadano);
		if (in_array("0", $porciones)) {
			if(count($porciones)==1){
				$post_search=true;
				//solo muestra 0
				$sql .= " AND NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) ";
				$sqlContador .= " AND NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) ";
			}else{
				$post_search=true;
				/// muestra mas 
				$sql.= " AND ( EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano)) OR NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id )) ";
				$sqlContador.= " AND ( EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano)) OR NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id )) ";
			}
		}else{
			$post_search=true;
			$sql.= " AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano) )";
			$sqlContador.= " AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano) )";
		}
	}

	if($columns[$requestData['order'][0]['column']]=="relacionado"){
		$columns[$requestData['order'][0]['column']] = "id_seccion_ine_ciudadano_compartido";
	}

	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
		$sqlContador.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
	}

	$tipo_seccion=$search_database['tipo_seccion'];
	if( $tipo_seccion!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT do.id FROM secciones_ine do WHERE do.id = e.id_seccion_ine AND do.tipo IN ({$tipo_seccion})) ";
		$sqlContador.=" AND EXISTS (SELECT do.id FROM secciones_ine do WHERE do.id = e.id_seccion_ine AND do.tipo IN ({$tipo_seccion})) ";
	}
	

	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["plataforma"];
		$nestedData[] = $row["folio"];
		$nestedData[] = $row["curp"];
		$url = '../../expediente.php?cot='.$row["clave_elector"];
		$link = '<a href="#" onclick="window.open(\'' . $url . '\', \'miVentana\', \'width=600,height=400\');">'.$row["clave_elector"].'</a>';
		//$nestedData[] = $link;
		$nestedData[] = $row["clave_elector"];
		$nestedData[] = $row["tipo_seccion"];
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["manzana"];
		$nestedData[] = $row["distrito_local"];
		$nestedData[] = $row["distrito_federal"];
		$nestedData[] = $row["distancia_km"];
		$nestedData[] = $row["relacionado"];
		$nestedData[] = $row["tipo_ciudadano"];
		//$nestedData[] = "<div style='text-transform: none;'>".$row["relacionado"]."<div>";
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo"]."<div>";
		$nestedData[] = $row["sexo"];
		$nestedData[] = $row["fecha_nacimiento"];
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$row['whatsapp']."&text&app_absent=0' target='_blank'>".$row['whatsapp']."</a>";
		$nestedData[] = $row["celular"];
		$nestedData[] = $row["telefono"];
		$nestedData[] = "<div style='text-transform: none;'>".$row["correo_electronico"]."<div>";
		$nestedData[] = $row["municipio"];
		$nestedData[] = $row["localidad"];
		$nestedData[] = $row["categorias"];
		$nestedData[] = $row["medio_registro"];
		$nestedData[] = $row["distancia_alert"];
		$nestedData[] = $row["seguimientos"];
		$nestedData[] = $row["status_verificacion"];
		$nestedData[] = $row["documentos_oficiales"];
		$nestedData[] = $row["programas_apoyos"];
		$nestedData[] = $row["programas_apoyos_categorias"];
		$nestedData[] = $row["militantes_partidos"];
		
		//$row['id'] = 1473;
		if($option_delete){
			$delete='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
		}
		if($option_edit){
			$edit='<button class="btn btn-info bt_responsive"  onClick="edit('.$row["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Editar</span></button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' row='".$row['id']."'/>";
		if($modulo_ciudadano_categoria){
			$ciudadano_categoria='<button class="btn btn-primary bt_responsive"  onClick="ciudadano_categoria('.$row["id"].');" >Categoria</button>';
		}
		if($modulo_encuestas){
			$ciudadano_encuesta='<button class="btn btn-primary bt_responsive"  onClick="encuestas('.$row["id"].');" >Encuestas</button>';
		}

		if($modulo_seguimientos){
			$ciudadano_seguimiento='<button class="btn btn-primary bt_responsive"  onClick="seguimientos('.$row["id"].');" >Seguimientos</button>';
		}

		if($modulo_campanas_mailing){
			$ciudadano_mailing1='<button class="btn btn-primary bt_responsive"  onClick="campanas_mailing_ciudadano('.$row["id"].');" >Mailing</button>';
		}

		if($modulo_campanas_sms){
			$ciudadano_sms1='<button class="btn btn-primary bt_responsive"  onClick="campanas_sms_ciudadano('.$row["id"].');" >SMS</button>';
		}

		if($modulo_campanas_whatsapp){
			$ciudadano_whatsapp='<button class="btn btn-primary bt_responsive"  onClick="campanas_whatsapp_ciudadano('.$row["id"].');" >Whatsapp</button>';
		}

		if($modulo_documentos_oficiales){
			if($row['documentos_oficiales'] > 0){
				$ciudadano_documentos_oficiales='<button class="btn btn-primary bt_responsive"  onClick="documentos_oficiales('.$row["id"].');" >Con Documentos</button>';
			}else{
				$ciudadano_documentos_oficiales='<button class="btn btn-warning bt_responsive"  onClick="documentos_oficiales('.$row["id"].');" >Sin Documentos</button>';
			}
		}

		if($modulo_programas_apoyos){
			if($row['programas_apoyos'] > 0){
				$ciudadano_programas_apoyos='<button class="btn btn-primary bt_responsive"  onClick="programas_apoyos('.$row["id"].');" >Con Programa Apoyo</button>';
			}else{
				$ciudadano_programas_apoyos='<button class="btn btn-warning bt_responsive"  onClick="programas_apoyos('.$row["id"].');" >Sin Programa Apoyo</button>';
			}
		}

		if($modulo_militantes_partidos){
			$ciudadano_militante_partidos='<button class="btn btn-primary bt_responsive"  onClick="militantes_partidos('.$row["id"].');" >Militante</button>';
		}

		if($modulo_secciones_ine_ciudadanos_giras){
			$ciudadano_giras='<button class="btn btn-primary bt_responsive"  onClick="secciones_ine_gira('.$row["id"].');" >Participación Agenda</button>';
		}

		$google_maps='<a href="https://www.google.com/maps?q='.$row["latitud"].','.$row["longitud"].'" target="_blank"><button class="btn btn-info bt_responsive" >
						<span class="btnImage"><img class="bntImageSize" src="img/Google_Maps_Logo_2020.png"></span>
						<span class="btnText">GoogleMaps</span></button></a>';

		if($modulo_secciones_ine_ciudadanos_grupos){
			$ciudadanos_grupos='<button class="btn btn-primary bt_responsive"  onClick="secciones_ine_ciudadanos_grupos('.$row["id"].');" >Grupos Afiliado</button>';
		}
		if($option_download){
			// Palabra clave para encriptar y desencriptar
			$palabra_clave = "sistemaRadarAB";
			// Algoritmo de encriptación
			$algoritmo = "AES-256-CBC";
			// Vector de inicialización
			$iv = 'AB';
			$otra_variable = $row["id"];
			$otra_variable = urlencode(openssl_encrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));
			$ciudadanos_expediente = '<button class="btn btn-primary bt_responsive" onClick="secciones_ine_ciudadanos_expediente(\''. $otra_variable .'\');">Expediente</button>';
			$ciudadanos_credencial_ciudadano = '<button class="btn btn-primary bt_responsive" onClick="secciones_ine_ciudadanos_credencial(\''. $otra_variable .'\');">Imprimir Credencial</button>';
			if($row['id_seccion_ine_ciudadano_compartido'] == ""){
				$ciudadanos_familia = '<button class="btn btn-warning bt_responsive" ">Estructura</button>';
			}else{
				$ciudadanos_familia = '<button class="btn btn-primary bt_responsive" onClick="secciones_ine_ciudadanos_estructura(\''. $row["id"] .'\');">Estructura</button>';
			}
		}
		$nestedData[] =  "<div class='opciones_botones'>{$edit}{$ciudadanos_familia}{$ciudadanos_expediente}{$ciudadanos_credencial_ciudadano}{$ciudadano_documentos_oficiales}{$ciudadano_giras}{$ciudadano_militante_partidos}{$ciudadano_programas_apoyos}{$ciudadano_categoria}{$ciudadano_encuesta}{$ciudadano_seguimiento}{$ciudadanos_grupos}{$ciudadano_mailing}{$ciudadano_sms}{$ciudadano_whatsapp}{$google_maps}{$delete}{$select}</div>";

		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos e WHERE 1 = 1   "; 
		if($RowPlataforma['tipo']!='x'){
			$sqlContadorScript .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
		}
		$sqlContadorScript .= $sqlContador;
		$resultado = $conexion->query($sqlContadorScript);
		$row=$resultado->fetch_assoc();
		$totalFiltered=$row['total']; 
	}else{
		$totalFiltered = $totalData; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	}
	////////////////////////////
	////////////////////////////
	//paginas
	//muestra todas las que se filtro con where
	$json_data = array(
		"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval( $totalData ),  // total number of records
		"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
		);
	echo json_encode($json_data);  // send data as json format
	$conexion->close();

?>
