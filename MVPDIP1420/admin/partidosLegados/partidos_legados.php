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
		1 =>'nombre_corto',
		2 =>'nombre',
		3 =>'militantes_activos',
	);
	$validar_codigo_plataforma = validar_codigo_plataforma($codigo_plataforma);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM partidos_legados pl WHERE 1 = 1   "; 
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
		$sql_ciudadano .= " AND mp.codigo_plataforma = '{$codigo_plataforma}' ";
	}

	$sql = "
		SELECT 
			pl.id,
			pl.clave,
			pl.nombre_corto,
			pl.nombre,
			(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE mp.id_partido_legado = pl.id AND mp.status = 1 {$sql_ciudadano} ) militantes_activos,
			(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE mp.id_partido_legado = pl.id AND mp.status = 0 {$sql_ciudadano} ) militantes_no_activos
		FROM partidos_legados pl WHERE 1 = 1  "; 
	// getting records as per search parameters

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND pl.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND pl.nombre LIKE '%{$nombre}%' ";
	}

	$nombre_corto=$search_database['nombre_corto'];
	if( $nombre_corto!="" ){   //name
		$post_search=true;
		$sql.=" AND nombre_corto LIKE '%{$nombre_corto}%' ";
		$sqlContador .= " AND nombre_corto LIKE '%{$nombre_corto}%' ";
	}

	$sql.=" ORDER BY pl.". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($sql), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','partidos_legados',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_militantes_partidos = true;
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
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";
		if($modulo_militantes_partidos){
			$ciudadanos_militante_partidos='<button class="btn btn-primary bt_responsive"  onClick="militantes_partidos_totales('.$row["id"].');" >Militantes</button>';
		}

		$nestedData[] =  "<div class='opciones_botones_3'>{$edit}{$delete}{$ciudadanos_militante_partidos}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM partidos_legados pl WHERE 1 = 1   "; 
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
