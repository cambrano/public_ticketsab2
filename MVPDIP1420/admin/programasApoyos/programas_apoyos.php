<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/plataformas.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array(
		0 =>'clave',
		1 =>'folio',
		2 =>'nombre',
		3 =>'fecha_inicio',
		4 =>'fecha_final',
		5 =>'total_beneficiados',
		6 =>'tipos_territorios',
		7 =>'dependencias',
	);
	$validar_codigo_plataforma = validar_codigo_plataforma($codigo_plataforma);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM programas_apoyos pa WHERE 1 = 1   "; 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	if($tipo_uso_plataforma=='municipio'){
		$sql_ciudadano = " AND sic.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql_ciudadano = " AND sic.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql_ciudadano =  " AND sic.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	if($validar_codigo_plataforma == false){
		$sql_ciudadano .= " AND sicpa.codigo_plataforma = '{$codigo_plataforma}' ";
	}

	$sql = "SELECT 
				pa.id,
				pa.clave,
				pa.folio,
				pa.nombre,
				pa.fecha_inicio,
				pa.fecha_final,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sicpa.id_programa_apoyo = pa.id {$sql_ciudadano} ) total_beneficiados,
				(SELECT GROUP_CONCAT(tt.nombre SEPARATOR ' | ') FROM programas_apoyos_territorios pat LEFT JOIN tipos_territorios tt ON pat.id_tipo_territorio = tt.id WHERE pat.id_programa_apoyo = pa.id) tipos_territorios,
				(SELECT GROUP_CONCAT(d.nombre SEPARATOR ' | ') FROM programas_apoyos_dependencias pad LEFT JOIN dependencias d ON pad.id_dependencia = d.id WHERE pad.id_programa_apoyo = pa.id) dependencias,
				pa.descripcion
			FROM programas_apoyos pa
			WHERE 1 = 1 "; 
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND pa.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND pa.clave LIKE '%{$clave}%' ";
	}
	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND pa.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND pa.folio LIKE '%{$folio}%' ";
	}
	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND pa.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND pa.nombre LIKE '%{$nombre}%' ";
	}

	$id_tipo_territorio=$search_database['id_tipo_territorio'];
	if( $id_tipo_territorio!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT * FROM programas_apoyos_territorios pat WHERE pat.id_programa_apoyo = pa.id AND pat.id_tipo_territorio IN ({$id_tipo_territorio}) ) ";
		$sqlContador.=" AND EXISTS (SELECT * FROM programas_apoyos_territorios pat WHERE pat.id_programa_apoyo = pa.id AND pat.id_tipo_territorio IN ({$id_tipo_territorio}) ) ";
	}

	$id_dependencia=$search_database['id_dependencia'];
	if( $id_dependencia!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT * FROM programas_apoyos_dependencias pat WHERE pat.id_programa_apoyo = pa.id AND pat.id_dependencia IN ({$id_dependencia}) ) ";
		$sqlContador.=" AND EXISTS (SELECT * FROM programas_apoyos_dependencias pat WHERE pat.id_programa_apoyo = pa.id AND pat.id_dependencia IN ({$id_dependencia}) ) ";
	}

	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','programas_apoyos',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_programas_apoyos',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_programas_apoyos = true;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		foreach ($columns as $key => $value) {
			$nestedData[] = $row[$value];
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
		if($modulo_programas_apoyos){
			$ciudadano_programas_apoyos='<button class="btn btn-primary bt_responsive"  onClick="programas_apoyos_totales('.$row["id"].');" >Ciudadanos</button>';
			//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";
		}
		

		$nestedData[] =  "<div class='opciones_botones_1'>{$edit}{$delete}{$ciudadano_programas_apoyos}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM programas_apoyos pa WHERE 1 = 1   "; 
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
