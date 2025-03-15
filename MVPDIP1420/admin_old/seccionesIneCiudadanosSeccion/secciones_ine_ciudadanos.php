<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/plataformas.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = $_SESSION['reporte_Sistema']['columnas_sql'];
	$id_seccion_ine = $_SESSION['id_seccion_ine']; 
	$validar_codigo_plataforma = validar_codigo_plataforma($codigo_plataforma);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos e WHERE e.id_seccion_ine = '{$id_seccion_ine}' ";
	if($tipo_uso_plataforma=='municipio'){
		//$sql.= " AND id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		//$sql.= " AND id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		//$sql.= " AND id_distrito_federal ='{$id_distrito_federal}' ";
	}
	if($validar_codigo_plataforma == false){
		$sql .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
	}
	if($RowUser['id_perfil_usuario']=='3'){
		//$sql .= ' AND EXISTS (SELECT * FROM log_usuarios lg WHERE lg.id_columna = e.id AND lg.tabla="secciones_ine_ciudadanos" AND lg.operacion="Insert" AND lg.id_usuario= "'.$RowUser['id'].'"  )';
	}
	
	

	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
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
		WHERE e.id_seccion_ine = '{$id_seccion_ine}' "; 
	if($validar_codigo_plataforma == false){
		$sql .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
	}
	if($RowUser['id_perfil_usuario']=='3'){
		//$sql .= ' AND EXISTS (SELECT * FROM log_usuarios lg WHERE lg.id_columna = e.id AND lg.tabla="secciones_ine_ciudadanos" AND lg.operacion="Insert" AND lg.id_usuario= "'.$RowUser['id'].'"  )';
		//$sqlContador .= ' AND EXISTS (SELECT * FROM log_usuarios lg WHERE lg.id_columna = e.id AND lg.tabla="secciones_ine_ciudadanos" AND lg.operacion="Insert" AND lg.id_usuario= "'.$RowUser['id'].'"  )';
	}

	// getting records as per search parameters
	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	} 

	$clave_elector=$_SESSION['searchTable']['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador .= " AND e.clave_elector LIKE '%{$clave_elector}%' ";
	} 

	$curp=$_SESSION['searchTable']['curp'];
	if( $curp!="" ){   //name
		$post_search=true;
		$sql.=" AND e.curp LIKE '%{$curp}%' ";
		$sqlContador .= " AND e.curp LIKE '%{$curp}%' ";
	} 

	$folio=$_SESSION['searchTable']['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND e.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND e.folio LIKE '%{$folio}%' ";
	} 

	$nombre_completo=$_SESSION['searchTable']['nombre_completo'];
	if( $nombre_completo!="" ){   //name
		$post_search=true;
		$sql.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
		$sqlContador.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
	}

	$nombre=$_SESSION['searchTable']['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND e.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND e.nombre LIKE '%{$nombre}%' ";
	} 

	$apellido_paterno=$_SESSION['searchTable']['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador .= " AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	} 

	$apellido_materno=$_SESSION['searchTable']['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador .= " AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
	}

	$sexo=$_SESSION['searchTable']['sexo'];
	if( $sexo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.sexo LIKE '%{$sexo}%' ";
		$sqlContador.=" AND e.sexo LIKE '%{$sexo}%' ";
	}

	$id_seccion_ine_ciudadano_compartido=$_SESSION['searchTable']['id_seccion_ine_ciudadano_compartido'];
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}

	$fecha_nacimiento_1=$_SESSION['searchTable']['fecha_nacimiento_1'];
	$fecha_nacimiento_2=$_SESSION['searchTable']['fecha_nacimiento_2'];
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

	$fecha_nacimiento_dia=$_SESSION['searchTable']['fecha_nacimiento_dia'];
	if( $fecha_nacimiento_dia!="" ){   //name
		$post_search=true;
		$sql.=" AND DAY(e.fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
		$sqlContador.=" AND DAY(e.fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
	}

	$fecha_nacimiento_mes=$_SESSION['searchTable']['fecha_nacimiento_mes'];
	if( $fecha_nacimiento_mes!="" ){   //name
		$post_search=true;
		$sql.=" AND MONTH(e.fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
		$sqlContador.=" AND MONTH(e.fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
	}

	$fecha_nacimiento_edad=$_SESSION['searchTable']['fecha_nacimiento_edad'];
	if( $fecha_nacimiento_edad!="" ){   //name
		$post_search=true;
		$sql.=" AND TIMESTAMPDIFF(YEAR,e.fecha_nacimiento,CURDATE()) = '{$fecha_nacimiento_edad}' ";
		$sqlContador.=" AND TIMESTAMPDIFF(YEAR,e.fecha_nacimiento,CURDATE()) = '{$fecha_nacimiento_edad}' ";
	}


	$id_cuartel=$_SESSION['searchTable']['id_cuartel'];
	if( $id_cuartel!="" ){
		$post_search=true;
		$sql.=" AND e.id_cuartel IN ($id_cuartel) ";
		$sqlContador.=" AND e.id_cuartel IN ($id_cuartel) ";
	}

	$id_tipo_ciudadano=$_SESSION['searchTable']['id_tipo_ciudadano'];
	if( $id_tipo_ciudadano!="" ){
		$post_search=true;
		$sql.=" AND e.id_tipo_ciudadano IN ($id_tipo_ciudadano) ";
		$sqlContador.=" AND e.id_tipo_ciudadano IN ($id_tipo_ciudadano) ";
	}

	$status_verificacion=$_SESSION['searchTable']['status_verificacion'];
	if( $status_verificacion!="" ){   //name
		$post_search=true;
		$sql.=" AND e.status_verificacion = '{$status_verificacion}' ";
		$sqlContador.=" AND e.status_verificacion = '{$status_verificacion}' ";
	}

	$info_vigente=$_SESSION['searchTable']['info_vigente'];
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

	$relacion=$_SESSION['searchTable']['relacion'];
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

	$solo_padre=$_SESSION['searchTable']['solo_padre'];
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

	$documentos_oficiales=$_SESSION['searchTable']['documentos_oficiales'];
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


	$vigencia_documentos_oficiales=$_SESSION['searchTable']['vigencia_documentos_oficiales'];
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

	$programas_apoyos=$_SESSION['searchTable']['programas_apoyos'];
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

	$id_programa_apoyo=$_SESSION['searchTable']['id_programa_apoyo'];
	if( $id_programa_apoyo!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
		$sqlContador.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
	}

	$num_seguimiento=$_SESSION['searchTable']['num_seguimiento'];
	if( $num_seguimiento!="" ){   //name
		$post_search=true;
		$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) = ".$num_seguimiento;
		$sqlContador.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) = ".$num_seguimiento;
	}

	$id_municipio=$_SESSION['searchTable']['id_municipio'];
	if( $id_municipio!="" ){
		$post_search=true;
		$sql.=" AND e.id_municipio IN ($id_municipio) ";
		$sqlContador.=" AND e.id_municipio IN ($id_municipio) ";
	}

	$id_seccion_ine_grupo=$_SESSION['searchTable']['id_seccion_ine_grupo'];
	if( $id_seccion_ine_grupo!="" ){
		$post_search=true;
		$sql.=" AND (SELECT pl.id FROM secciones_ine_ciudadanos_grupos mp LEFT JOIN secciones_ine_grupos pl ON mp.id_seccion_ine_grupo = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_seccion_ine_grupo}) ";
		$sqlContador.=" AND (SELECT pl.id FROM secciones_ine_ciudadanos_grupos mp LEFT JOIN secciones_ine_grupos pl ON mp.id_seccion_ine_grupo = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_seccion_ine_grupo}) ";
	}

	$id_localidad=$_SESSION['searchTable']['id_localidad'];
	if( $id_localidad!="" ){
		$post_search=true;
		$sql.=" AND e.id_localidad IN ($id_localidad) ";
		$sqlContador.=" AND e.id_localidad IN ($id_localidad) ";
	}

	$id_partido_legado=$_SESSION['searchTable']['id_partido_legado'];
	if( $id_partido_legado!="" ){
		///buscar los militantes con partido segun el value recuerda solo el que tenga status 1 que es activo
		$post_search=true;
		$sql.="  AND (SELECT pl.id FROM militantes_partidos mp LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_partido_legado})";
		$sqlContador.="  AND (SELECT pl.id FROM militantes_partidos mp LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id ORDER BY pl.id DESC LIMIT 1 ) IN ({$id_partido_legado})";
	}

	$id_distrito_local=$_SESSION['searchTable']['id_distrito_local'];
	if( $id_distrito_local!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_local IN ($id_distrito_local) ";
		$sqlContador.=" AND e.id_distrito_local IN ($id_distrito_local) ";
	}
	$id_distrito_federal=$_SESSION['searchTable']['id_distrito_federal'];
	if( $id_distrito_federal!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
		$sqlContador.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
	}


	$id_tipo_categoria_ciudadano=$_SESSION['searchTable']['id_tipo_categoria_ciudadano'];
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

	$tipo_seccion=$_SESSION['searchTable']['tipo_seccion'];
	if( $tipo_seccion!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT do.id FROM secciones_ine do WHERE do.id = e.id_seccion_ine AND do.tipo IN ({$tipo_seccion})) ";
		$sqlContador.=" AND EXISTS (SELECT do.id FROM secciones_ine do WHERE do.id = e.id_seccion_ine AND do.tipo IN ({$tipo_seccion})) ";
	}

	if($columns[$requestData['order'][0]['column']]=="relacionado"){
		$columns[$requestData['order'][0]['column']] = "id_seccion_ine_ciudadano_compartido";
	}

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_categorias',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_ciudadano_categoria = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_encuestas',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_encuestas = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_seguimientos',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_seguimientos = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campañas_mailing',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_campanas_mailing = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campañas_sms',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_campanas_sms = true;
	}


	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campañas_whatsapp',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_campanas_whatsapp = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','documentos_oficiales',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_documentos_oficiales = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_programas_apoyos',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_programas_apoyos = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_militantes_partidos = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_grupos',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_secciones_ine_ciudadanos_grupos = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_giras',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_secciones_ine_ciudadanos_giras = true;
	}


	$data = array();
	foreach ($_SESSION['reporte_Sistema']['database'] as $key => $value) {
		$nestedData=array(); 
		$nestedData[] = $value["clave"];
		$nestedData[] = $value["folio"];
		$nestedData[] = $value["curp"];
		$nestedData[] = $value["clave_elector"];
		$nestedData[] = $value["tipo_seccion"];
		$nestedData[] = $value["seccion"];
		$nestedData[] = $value["manzana"];
		$nestedData[] = $value["distrito_local"];
		$nestedData[] = $value["distrito_federal"];
		$nestedData[] = $value["distancia_km"];
		$nestedData[] = $value["tipo_ciudadano"];
		//$nestedData[] = "<div style='text-transform: none;'>".$value["relacionado"]."<div>";
		$nestedData[] = "<div style='text-transform: none;'>".$value["nombre_completo"]."<div>";
		$nestedData[] = $value["sexo"];
		$nestedData[] = $value["fecha_nacimiento"];
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$value['whatsapp']."&text&app_absent=0' target='_blank'>".$value['whatsapp']."</a>";
		$nestedData[] = $value["celular"];
		$nestedData[] = $value["telefono"];
		$nestedData[] = "<div style='text-transform: none;'>".$value["correo_electronico"]."<div>";
		$nestedData[] = $value["municipio"];
		$nestedData[] = $value["localidad"];
		$nestedData[] = $value["categorias"];
		$nestedData[] = $value["medio_registro"];
		$nestedData[] = $value["distancia_alert"];
		$nestedData[] = $value["seguimientos"];
		$nestedData[] = $value["status_verificacion"];
		$nestedData[] = $value["documentos_oficiales"];
		$nestedData[] = $value["programas_apoyos"];
		$nestedData[] = $value["programas_apoyos_categorias"];
		$nestedData[] = $value["militantes_partidos"];
		
		//$value['id'] = 1473;
		if($option_delete){
			$delete='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$value["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
		}
		if($option_edit){
			$edit='<button class="btn btn-info bt_responsive"  onClick="edit('.$value["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Editar</span></button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$value['id']."'/>";
		if($modulo_ciudadano_categoria){
			$ciudadano_categoria='<button class="btn btn-primary bt_responsive"  onClick="ciudadano_categoria('.$value["id"].');" >Categoria</button>';
		}
		if($modulo_encuestas){
			$ciudadano_encuesta='<button class="btn btn-primary bt_responsive"  onClick="encuestas('.$value["id"].');" >Encuestas</button>';
		}

		if($modulo_seguimientos){
			$ciudadano_seguimiento='<button class="btn btn-primary bt_responsive"  onClick="seguimientos('.$value["id"].');" >Seguimientos</button>';
		}

		if($modulo_campanas_mailing){
			$ciudadano_mailing1='<button class="btn btn-primary bt_responsive"  onClick="campanas_mailing_ciudadano('.$value["id"].');" >Mailing</button>';
		}

		if($modulo_campanas_sms){
			$ciudadano_sms1='<button class="btn btn-primary bt_responsive"  onClick="campanas_sms_ciudadano('.$value["id"].');" >SMS</button>';
		}

		if($modulo_campanas_whatsapp){
			$ciudadano_whatsapp='<button class="btn btn-primary bt_responsive"  onClick="campanas_whatsapp_ciudadano('.$value["id"].');" >Whatsapp</button>';
		}

		if($modulo_documentos_oficiales){
			if($value['documentos_oficiales'] > 0){
				$ciudadano_documentos_oficiales='<button class="btn btn-primary bt_responsive"  onClick="documentos_oficiales('.$value["id"].');" >Con Documentos</button>';
			}else{
				$ciudadano_documentos_oficiales='<button class="btn btn-warning bt_responsive"  onClick="documentos_oficiales('.$value["id"].');" >Sin Documentos</button>';
			}
		}

		if($modulo_programas_apoyos){
			if($value['programas_apoyos'] > 0){
				$ciudadano_programas_apoyos='<button class="btn btn-primary bt_responsive"  onClick="programas_apoyos('.$value["id"].');" >Con Programa Apoyo</button>';
			}else{
				$ciudadano_programas_apoyos='<button class="btn btn-warning bt_responsive"  onClick="programas_apoyos('.$value["id"].');" >Sin Programa Apoyo</button>';
			}
		}

		if($modulo_militantes_partidos){
			$ciudadano_militante_partidos='<button class="btn btn-primary bt_responsive"  onClick="militantes_partidos('.$value["id"].');" >Militante</button>';
		}

		if($modulo_secciones_ine_ciudadanos_giras){
			$ciudadano_giras='<button class="btn btn-primary bt_responsive"  onClick="secciones_ine_gira('.$value["id"].');" >Participación Giras</button>';
		}

		$google_maps='<a href="https://www.google.com/maps?q='.$value["latitud"].','.$value["longitud"].'" target="_blank"><button class="btn btn-info bt_responsive" >
						<span class="btnImage"><img class="bntImageSize" src="img/Google_Maps_Logo_2020.png"></span>
						<span class="btnText">GoogleMaps</span></button></a>';

		if($modulo_secciones_ine_ciudadanos_grupos){
			$ciudadanos_grupos='<button class="btn btn-primary bt_responsive"  onClick="secciones_ine_ciudadanos_grupos('.$value["id"].');" >Grupos Afiliado</button>';
		}
		$nestedData[] =  "<div class='opciones_botones'>{$edit}{$ciudadano_documentos_oficiales}{$ciudadano_giras}{$ciudadano_militante_partidos}{$ciudadano_programas_apoyos}{$ciudadano_categoria}{$ciudadano_encuesta}{$ciudadano_seguimiento}{$ciudadanos_grupos}{$ciudadano_mailing}{$ciudadano_sms}{$ciudadano_whatsapp}{$google_maps}{$delete}{$select}</div>";

		$data[] = $nestedData;
	}

	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos e WHERE e.id_seccion_ine = '{$id_seccion_ine}'  "; 
		if($validar_codigo_plataforma == false){
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