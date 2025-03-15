<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/usuarios.php";
	include __DIR__."/../functions/empleados_dependencias.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array(
		0 =>'clave',
		1 =>'tipo_gira',
		2 =>'dependencia_coordinadora',
		3 =>'eje_gobierno',
		4 =>'nombre',
		5 =>'fecha_hora',
		6 =>'num_beneficiarios',
		7 =>'num_asistentes',
		8 =>'observaciones',
		9 =>'municipio',
		10 =>'localidad',
		11 =>'colonia',
		12 =>'seccion',
		13 =>'distrito_local',
		14 =>'distrito_federal',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_agendas_gobierno sia  WHERE 1 = 1   "; 
	
	//vemos si esta en la dependencia
	$usuarioDatos = usuarioDatos($_COOKIE['id_usuario']);	
	$id_empleado = $usuarioDatos['id_empleado'];

	$usuario_permisoDatos = usuario_permisoDatos('','',81,126,$status=null,$id_usaurio=null,$id_empleado=null);
	if($id_empleado != ""){
		if(empty($usuario_permisoDatos)){
			$empleado_dependenciaIdsDependenciasDatos = empleado_dependenciaIdsDependenciasDatos('',$id_empleado);
			if(!empty($empleado_dependenciaIdsDependenciasDatos['ids_dependencias'])){
				$sql.= " AND sia.id_dependencia IN ({$empleado_dependenciaIdsDependenciasDatos['ids_dependencias']}) ";
			}
		}
	}
	
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "
		SELECT
				sia.id,
				sia.clave,
				sia.folio,
				(SELECT d.nombre FROM tipos_giras d WHERE d.id = sia.id_tipo_gira) tipo_gira,
				(SELECT d.nombre FROM dependencias d WHERE d.id = sia.id_dependencia) dependencia_coordinadora,
				sia.nombre,
				sia.observaciones,
				(
					SELECT GROUP_CONCAT(d.nombre ORDER BY d.id SEPARATOR ',<br><br> ') 
					FROM dependencias d 
					WHERE FIND_IN_SET(d.id, sia.ids_dependencias) > 0
				) AS dependencias_colaborativas,
			(SELECT e.nombre FROM ejes_gobierno e WHERE e.id = sia.id_eje_gobierno ) eje_gobierno,
			num_beneficiarios,
			num_asistentes,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.colonia ORDER BY loc.colonia SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS colonia,
			(
				SELECT GROUP_CONCAT(DISTINCT m.municipio ORDER BY m.municipio SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN municipios m
				ON loc.id_municipio = m.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS municipio,
            (
				SELECT GROUP_CONCAT(DISTINCT l.localidad ORDER BY l.localidad SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN localidades l
				ON loc.id_localidad = l.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS localidad,
            (
				SELECT GROUP_CONCAT(DISTINCT loc.fecha_hora ORDER BY loc.fecha_hora SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS fecha_hora,
            (
				SELECT GROUP_CONCAT(DISTINCT s.numero ORDER BY s.numero SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN secciones_ine s
				ON loc.id_seccion_ine = s.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS seccion,
			(SELECT loc.latitud FROM secciones_ine_agendas_gobierno_locaciones loc WHERE loc.id_seccion_ine_agenda_gobierno = sia.id LIMIT 1) latitud,
			(SELECT loc.longitud FROM secciones_ine_agendas_gobierno_locaciones loc WHERE loc.id_seccion_ine_agenda_gobierno = sia.id LIMIT 1) longitud,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.id_distrito_local ORDER BY loc.id_distrito_local SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS distrito_local,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.id_distrito_federal ORDER BY loc.id_distrito_federal SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS distrito_federal
			FROM secciones_ine_agendas_gobierno sia
			WHERE 1 = 1 ";
	// getting records as per search parameters

	if($id_empleado != ""){
		$empleado_dependenciaIdsDependenciasDatos = empleado_dependenciaIdsDependenciasDatos('',$id_empleado);
		if(!empty($empleado_dependenciaIdsDependenciasDatos['ids_dependencias'])){
			$post_search=true;
			$sql.= " AND sia.id_dependencia IN ({$empleado_dependenciaIdsDependenciasDatos['ids_dependencias']}) ";
			$sqlContador.= " AND sia.id_dependencia IN ({$empleado_dependenciaIdsDependenciasDatos['ids_dependencias']}) ";
		}
	}
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND sia.clave LIKE '%{$clave}%' ";
	} 

	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		//$post_search=true;
		//$sql.=" AND sia.folio LIKE '%{$folio}%' ";
		//$sqlContador .= " AND sia.folio LIKE '%{$folio}%' ";
	}

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND sia.nombre LIKE '%{$nombre}%' ";
	} 

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		//$sql.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
		//$sqlContador.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_seccion_ine IN ($id_seccion_ine) )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_seccion_ine IN ($id_seccion_ine) )";
	}

	$id_eje_gobierno=$search_database['id_eje_gobierno'];
	if( $id_eje_gobierno!="" ){
		$post_search=true;
		$sql.=" AND sia.id_eje_gobierno = $id_eje_gobierno ";
		$sqlContador.=" AND sia.id_eje_gobierno = $id_eje_gobierno ";
	}

	$id_dependencias=$search_database['id_dependencias'];
	$id_dependencias = str_replace('\\', '', $id_dependencias);
	if( $id_dependencias!="" ){
		$post_search=true;
		$sql.=" AND sia.id_dependencia IN ($id_dependencias) ";
		$sqlContador.=" AND sia.id_dependencia IN ($id_dependencias) ";
	}

	$id_dependencias_colaborativas=$search_database['id_dependencias_colaborativas'];
	$id_dependencias_colaborativas = str_replace('\\', '', $id_dependencias_colaborativas);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $id_dependencias_colaborativas!="" ){   //name
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_dependencias sigd WHERE sigd.id_dependencia IN ($id_dependencias_colaborativas) AND sigd.id_seccion_ine_agenda_gobierno = sia.id )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_dependencias sigd WHERE sigd.id_dependencia IN ($id_dependencias_colaborativas) AND sigd.id_seccion_ine_agenda_gobierno = sia.id )";
	}
	
	$id_dependencias_general=$search_database['id_dependencias_general'];
	$id_dependencias_general = str_replace('\\', '', $id_dependencias_general);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $id_dependencias_general!="" ){   //name
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_dependencias_generales sigd WHERE sigd.id_dependencia IN ($id_dependencias_general) AND sigd.id_seccion_ine_agenda_gobierno = sia.id )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_dependencias_generales sigd WHERE sigd.id_dependencia IN ($id_dependencias_general) AND sigd.id_seccion_ine_agenda_gobierno = sia.id )";
	}


	$tipo=$search_database['tipo'];
	$tipo = str_replace('\\', '', $tipo);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.tipo IN ($tipo) ";
		$sqlContador.=" AND sia.tipo IN ($tipo) ";
	}

	$id_tipo_gira=$search_database['id_tipo_gira'];
	$id_tipo_gira = str_replace('\\', '', $id_tipo_gira);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $id_tipo_gira!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_tipo_gira IN ($id_tipo_gira) ";
		$sqlContador.=" AND sia.id_tipo_gira IN ($id_tipo_gira) ";
	}

	$fecha_1=$search_database['fecha_1'];
	$fecha_2=$search_database['fecha_2'];
	if( $fecha_1 != '' && $fecha_2 == ''){ 
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha <= '{$fecha_1}' )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha <= '{$fecha_1}' )";
	}

	if( $fecha_1 == '' && $fecha_2 != ''){
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha  >= '{$fecha_2}' )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha  >= '{$fecha_2}' )";
	}
	if( $fecha_1 != '' && $fecha_2 != ''){ 
		$post_search=true;
		//$sql.=" AND sia.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
		//$sqlContador.=" AND sia.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' )";
	}


	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){   //name
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_municipio = $id_municipio )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_municipio = $id_municipio )";
		//$sql.=" AND sia.id_municipio IN ({$id_municipio}) ";
		//$sqlContador.=" AND sia.id_municipio IN ({$id_municipio}) ";
	}

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){   //name
		//$post_search=true;
		//$sql.=" AND sia.id_localidad IN ({$id_localidad}) ";
		//$sqlContador.=" AND sia.id_localidad IN ({$id_localidad}) ";
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_localidad = $id_localidad )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_localidad = $id_localidad )";
	}

	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		//$post_search=true;
		//$sql.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
		//$sqlContador.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_local = $id_distrito_local )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_local = $id_distrito_local )";
	}

	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		//$post_search=true;
		//$sql.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
		//$sqlContador.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_federal = $id_distrito_federal )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_federal = $id_distrito_federal )";
	}
	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_agendas_gobierno',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_giras',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_secciones_ine_ciudadanos_giras = true;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		foreach ($columns as $key => $value) {
			if($value == 'monto_total'){
				$nestedData[] = number_format($row[$value],2,".",",");
			}elseif($value == 'beneficiarios'){
				$nestedData[] = number_format($row[$value],0,"",",");
			}elseif($value == 'meta_cantidad'){
				$nestedData[] = number_format($row[$value],0,"",",");
			}elseif($value == 'fecha_hora'){
				$fechas = explode(", ", $row[$value]);
				$fecha_hora_con_br = "--".implode("<br><br>--", $fechas);
				$nestedData[] = $fecha_hora_con_br;
			}else{
				$nestedData[] = $row[$value];
			}
		}
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
		if($modulo_secciones_ine_ciudadanos_giras){
			$ciudadano_secciones_ine_ciudadanos_giras='<button class="btn btn-primary bt_responsive"  onClick="secciones_ine_ciudadanos_giras_totales('.$row["id"].');" >Ciudadanos</button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";
		$google_maps='<a href="https://www.google.com/maps?q='.$row["latitud"].','.$row["longitud"].'" target="_blank"><button class="btn btn-info bt_responsive" >
						<span class="btnImage"><img class="bntImageSize" src="img/Google_Maps_Logo_2020.png"></span>
						<span class="btnText">GoogleMaps</span></button></a>';
		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$google_maps}{$ciudadano_secciones_ine_ciudadanos_giras}{$delete}{$select}</div>";

		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_agendas_gobierno sia WHERE 1 = 1   "; 
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
