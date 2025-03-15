<?php
	@session_start();
	$pageService=$_POST['pageService'];
	if($pageService=="" || $_POST['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}else{
		$_COOKIE['pageService'];
	}
	$start_time = microtime(true);
	include_once("../../librerias/excel/xlsxwriter.class.php");
	include_once "../../functions/security.php";
	include_once "../../functions/usuario_permisos.php";
	include_once "../../functions/tool_xhpzab.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
	}else{
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$filename = 'Ciudadanos_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../ftpFiles/documentosExport/';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Ciudadanos','Data','Información');
	$writer->setTitle('Ciudadanos');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	$columa = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'plataforma' ,'nombre' => 'Plataforma' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'curp' ,'nombre' => 'C.U.R.P' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'clave_elector' ,'nombre' => 'Clave Elector' ,'tipo' => 'string','mostrar' => 1 ),
		5 => array('row' => 'vigencia' ,'nombre' => 'vigencia' ,'tipo' => 'string','mostrar' => 1),
		6 => array('row' => 'ocr' ,'nombre' => 'OCR' ,'tipo' => 'string','mostrar' => 0 ),
		7 => array('row' => 'tipo_seccion' ,'nombre' => 'Tipo Sección','tipo' => 'string','mostrar' => 1),
		8 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1),
		9 => array('row' => 'manzana' ,'nombre' => 'Manzana' ,'tipo' => 'string','mostrar' => 1),
		10 => array('row' => 'distrito_local' ,'nombre' => 'D. Local' ,'tipo' => 'string','mostrar' => 1),
		11 => array('row' => 'distrito_federal' ,'nombre' => 'D. Federal' ,'tipo' => 'string','mostrar' => 1),
		12 => array('row' => 'distancia_km' ,'nombre' => 'D.(km) Aprox' ,'tipo' => 'string','mostrar' => 1),
		13 => array('row' => 'relacionado' ,'nombre' => 'Relacionado' ,'tipo' => 'string','mostrar' => 0),
		14 => array('row' => 'tipo_ciudadano' ,'nombre' => 'Tipo Ciudadano' ,'tipo' => 'string','mostrar' => 1),
		15 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo','tipo' => 'string','mostrar' => 1),
		16 => array('row' => 'sexo' ,'nombre' => 'Sexo' ,'tipo' => 'string','mostrar' => 1),
		17 => array('row' => 'fecha_nacimiento' ,'nombre' => 'F. Nacimiento' ,'tipo' => 'date','mostrar' => 1),
		18 => array('row' => 'whatsapp' ,'nombre' => 'Whatsapp' ,'tipo' => 'string','mostrar' => 1),
		19 => array('row' => 'celular' ,'nombre' => 'Celular' ,'tipo' => 'string','mostrar' => 1),
		20 => array('row' => 'telefono' ,'nombre' => 'Teléfono' ,'tipo' => 'string','mostrar' => 1),
		21 => array('row' => 'correo_electronico' ,'nombre' => 'Correo Electrónico' ,'tipo' => 'string','mostrar' => 1),
		22 => array('row' => 'direccion' ,'nombre' => 'Dirección' ,'tipo' => 'string','mostrar' => 0),
		23 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string','mostrar' => 1),
		24 => array('row' => 'localidad' ,'nombre' => 'Localidad' ,'tipo' => 'string','mostrar' => 1),
		25 => array('row' => 'latitud' ,'nombre' => 'Latitud' ,'tipo' => 'string','mostrar' => 0),
		26 => array('row' => 'longitud' ,'nombre' => 'Longitud' ,'tipo' => 'string','mostrar' => 0),
		27 => array('row' => 'categorias' ,'nombre' => 'Categorías' ,'tipo' => 'string','mostrar' => 1),
		28 => array('row' => 'medio_registro' ,'nombre' => 'Medio Registro' ,'tipo' => 'string','mostrar' => 1),
		29 => array('row' => 'distancia_alert' ,'nombre' => 'Alerta Distancia' ,'tipo' => 'string','mostrar' => 1),
		30 => array('row' => 'seguimientos' ,'nombre' => 'Seguimientos' ,'tipo' => 'string','mostrar' => 1),
		31 => array('row' => 'status_verificacion' ,'nombre' => 'Verificación' ,'tipo' => 'string','mostrar' => 1),
		32 => array('row' => 'documentos_oficiales' ,'nombre' => 'Documentos Oficiales' ,'tipo' => 'string','mostrar' => 1),
		33 => array('row' => 'programas_apoyos' ,'nombre' => 'Programas Apoyos' ,'tipo' => 'string','mostrar' => 1),
		34 => array('row' => 'programas_apoyos_categorias' ,'nombre' => 'Programas Apoyos Categorías' ,'tipo' => 'string','mostrar' => 1),
		35 => array('row' => 'militantes_partidos' ,'nombre' => 'Militante' ,'tipo' => 'string','mostrar' => 1),
		36 => array('row' => 'observaciones' ,'nombre' => 'Observaciones' ,'tipo' => 'string','mostrar' => 0),
	);
	foreach ($columa as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}

	$search_database = $_POST['excel']['0'];
	$sql = "
		SELECT
			e.id,
			e.clave,
			(SELECT p.nombre FROM plataformas p WHERE p.plataforma = e.codigo_plataforma) plataforma,
			e.folio,
			e.curp,
			e.clave_elector,
			e.vigencia,
			e.ocr,
			IF(s.tipo = 1, 'Urbano', 'Rural') AS tipo_seccion,
			si.numero AS seccion,
			e.manzana,
			dl.numero AS distrito_local,
			df.numero AS distrito_federal,
			e.distancia_km,
			IF(e.id_seccion_ine_ciudadano_compartido != '', CONCAT((SELECT tc1.nombre FROM tipos_ciudadanos tc1 WHERE tc1.id = sim.id_tipo_ciudadano ),' -- ',sim.nombre_completo), 'NO TIENE') AS relacionado,
			tc.nombre AS tipo_ciudadano,
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
		unset($search_database['plataforma']);
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

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador =  " AND e.id_seccion_ine IN ($id_seccion_ine) ";
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
	$decryptedQuery = decrypt_ab_checkSin($_COOKIE['AB32BA51']);


	$numero =1;
	$page = 1;
	$result = $conexion->query($sql.$decryptedQuery);
	$color_reg = 1;
	if($result->num_rows ==0){
		echo "NINGUN REGISTRO ENCONTRADO, VERIFIQUE SUS FILTROS.";
		die;
	}
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){
			sleep(1);
			$txt = "Ciudadanos Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $header, ['auto_filter'=>true, 'fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'] );
		}
		$numero ++;
		unset($row['id']);
		unset($styleRow);
		$marco = $color_reg % 2;
		foreach ($row as $key => $value) {
			if($marco == 0){
				$styleRow[] = array('fill' => '#e9e9e9','color'=>'#000000','border'=>'left,right,top,bottom');
			}else{
				$styleRow[] = array('color'=>'#000000','border'=>'left,right,top,bottom');
			}
			
		}
		$color_reg ++;

		$writer->writeSheetRow($txt, $row,$styleRow);
	}
	$writer->writeToFile($ruta_ftpExport.$filename); 
	//$writer->writeToStdOut();
	//echo '#'.floor((memory_get_peak_usage())/1024/1024)."MB"."\n";
	//exit();
	$end_time = microtime(true);
	$duration = $end_time - $start_time;
	$minutes = (int)($duration/60)-$hours*60;
	$seconds = (int)$duration-$hours*60*60-$minutes*60; 
	$tiempo =  "Tiempo para generar el archivo: <strong>" .$minutes.' minutos y '.$seconds.' segundos.</strong>';
?>
	<?= $tiempo ?>
	<br>
	<a href="<?= $ruta_ftpExport.$filename ?>" download="<?= $filename ?>"><?= $filename ?></a>
	<script type="text/javascript">
		document.getElementById("loader").style.display = "none";
		$('#text_sub').html('De click al link. Gracias');
		document.getElementById("stop").value=1;
		document.title = "Listo. descargelo";
	</script>