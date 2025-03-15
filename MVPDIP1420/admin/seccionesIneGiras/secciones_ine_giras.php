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
		1 =>'folio',
		2 =>'tipo_gira',
		3 =>'dependencia_coordinadora',
		4 =>'eje_gobierno',
		5 =>'num_beneficiarios',
		6 =>'num_asistentes',
		/*6 =>'participantes',*/
		7 =>'nombre',
		8 =>'fecha',
		9 =>'hora',
		10 =>'observaciones',
		11 =>'municipio',
		12 =>'localidad',
		13 =>'colonia',
		14 =>'seccion',
		15 =>'distrito_local',
		16 =>'distrito_federal',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_giras sia LEFT JOIN secciones_ine si ON si.id = sia.id_seccion_ine WHERE 1 = 1   "; 
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND sia.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND si.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND si.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	//vemos si esta en la dependencia
	$usuarioDatos = usuarioDatos($_COOKIE['id_usuario']);	
	$id_empleado = $usuarioDatos['id_empleado'];
	if($id_empleado != ""){
		$empleado_dependenciaIdsDependenciasDatos = empleado_dependenciaIdsDependenciasDatos('',$id_empleado);
		if(!empty($empleado_dependenciaIdsDependenciasDatos['ids_dependencias'])){
			$sql.= " AND sia.id_dependencia IN ({$empleado_dependenciaIdsDependenciasDatos['ids_dependencias']}) ";
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
			sia.tipo,
			(SELECT d.nombre FROM tipos_giras d WHERE d.id = sia.id_tipo_gira) tipo_gira,
			(SELECT d.nombre FROM dependencias d WHERE d.id = sia.id_dependencia) dependencia_coordinadora,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_giras sicg WHERE sicg.id_seccion_ine_gira = sia.id) participantes,
			sia.nombre,
			sia.fecha,
			sia.hora,
			sia.observaciones,
			m.municipio,
			(SELECT l.localidad FROM localidades l WHERE l.id = sia.id_localidad ) localidad,
			sia.colonia,
			si.numero seccion,
			dl.numero distrito_local,
			df.numero distrito_federal,
			sia.num_beneficiarios,
			sia.num_asistentes,
			sia.latitud,
			sia.longitud,
			sia.calle,
			sia.num_ext,
			sia.num_int,
			sia.codigo_postal,
			(
				SELECT GROUP_CONCAT(d.nombre ORDER BY d.id SEPARATOR ',<br><br> ') 
				FROM dependencias d 
				WHERE FIND_IN_SET(d.id, sia.ids_dependencias) > 0
			) AS dependencias_colaborativas,
		(SELECT e.nombre FROM ejes_gobierno e WHERE e.id = sia.id_eje_gobierno ) eje_gobierno

		FROM secciones_ine_giras sia
		LEFT JOIN secciones_ine si on sia.id_seccion_ine = si.id
		LEFT JOIN distritos_locales dl on si.id_distrito_local = dl.id
		LEFT JOIN distritos_federales df on si.id_distrito_federal = df.id
		LEFT JOIN municipios m on sia.id_municipio = m.id
		WHERE m.id_estado='{$id_estado}' ";
	// getting records as per search parameters
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND sia.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND si.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND si.id_distrito_federal ='{$id_distrito_federal}' ";
	}

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
		$post_search=true;
		$sql.=" AND sia.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND sia.folio LIKE '%{$folio}%' ";
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
		$sql.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
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
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_giras_dependencias sigd WHERE sigd.id_dependencia IN ($id_dependencias_colaborativas) AND sigd.id_seccion_ine_gira = sia.id )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_giras_dependencias sigd WHERE sigd.id_dependencia IN ($id_dependencias_colaborativas) AND sigd.id_seccion_ine_gira = sia.id )";
	}
	
	$id_dependencias_general=$search_database['id_dependencias_general'];
	$id_dependencias_general = str_replace('\\', '', $id_dependencias_general);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $id_dependencias_general!="" ){   //name
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_giras_dependencias_generales sigd WHERE sigd.id_dependencia IN ($id_dependencias_general) AND sigd.id_seccion_ine_gira = sia.id )";
		$sqlContador.= " AND EXISTS ( SELECT * FROM secciones_ine_giras_dependencias_generales sigd WHERE sigd.id_dependencia IN ($id_dependencias_general) AND sigd.id_seccion_ine_gira = sia.id )";
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
		$sql.=" AND sia.fecha <= '{$fecha_1}' ";
		$sqlContador.=" AND sia.fecha <= '{$fecha_1}' ";
	}

	if( $fecha_1 == '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND sia.fecha >= '{$fecha_2}' ";
		$sqlContador.=" AND sia.fecha >= '{$fecha_2}' ";
	}
	if( $fecha_1 != '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND sia.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
		$sqlContador.=" AND sia.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
	}


	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_municipio IN ({$id_municipio}) ";
		$sqlContador.=" AND sia.id_municipio IN ({$id_municipio}) ";
	}

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_localidad IN ({$id_localidad}) ";
		$sqlContador.=" AND sia.id_localidad IN ({$id_localidad}) ";
	}

	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		$post_search=true;
		$sql.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
		$sqlContador.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
	}

	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		$post_search=true;
		$sql.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
		$sqlContador.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
	}
	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_giras',$_COOKIE["id_usuario"]);
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_giras sia LEFT JOIN secciones_ine si ON sia.id_seccion_ine = si.id WHERE 1 = 1   "; 
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
