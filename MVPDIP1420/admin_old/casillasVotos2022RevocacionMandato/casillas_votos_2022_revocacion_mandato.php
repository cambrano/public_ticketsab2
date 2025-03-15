<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave',
		1 =>'municipio',
		2 =>'Distrito Local',
		3 =>'Distrito Federal',
		4 =>'seccion',
		5 =>'tipo_casilla',
		6 =>'codigo',
		7 =>'lista_nominal',
		8 =>'clave',
		9 =>'', 
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM casillas_votos_2022_revocacion_mandato WHERE 1 = 1   "; 
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND id_distrito_federal ='{$id_distrito_federal}' ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT * ,
			(SELECT m.municipio FROM municipios m WHERE m.id= cv.id_municipio AND m.id_estado='$id_estado' ) municipio,
			(SELECT m.numero FROM distritos_locales m WHERE m.id= cv.id_distrito_local) distrito_local,
			(SELECT m.numero FROM distritos_federales m WHERE m.id= cv.id_distrito_federal) distrito_federal,
			(SELECT s.numero FROM secciones_ine s WHERE s.id= cv.id_seccion_ine) seccion,
			(SELECT tc.clave FROM tipos_casillas tc WHERE tc.id= cv.id_tipo_casilla) tipo_casilla,
			CASE
				WHEN cv.tipo = '0' THEN 'ayuntamiento'
				WHEN cv.tipo = '1' THEN 'distrito local'
				ELSE 'distrito federal'
			END as tipo
			FROM casillas_votos_2022_revocacion_mandato cv WHERE 1 = 1 "; 
	// getting records as per search parameters
	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND cv.clave LIKE '%{$clave}%' ";
	} 

	$tipo=$_SESSION['searchTable']['tipo'];
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.tipo LIKE '{$tipo}' ";
		$sqlContador .= " AND cv.tipo LIKE '{$tipo}' ";
	} 

	$id_seccion_ine=$_SESSION['searchTable']['id_seccion_ine'];
	if( $id_seccion_ine!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND cv.id_seccion_ine IN ($id_seccion_ine) ";
	}

	/*
	$id_municipio=$_SESSION['searchTable']['id_municipio'];
	if( $id_municipio!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_municipio IN ($id_municipio) ";
		$sqlContador.=" AND cv.id_municipio IN ($id_municipio) ";
	}
	*/
	/*
	$id_distrito_local=$_SESSION['searchTable']['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_distrito_local IN ($id_distrito_local) ";
		$sqlContador.=" AND cv.id_distrito_local IN ($id_distrito_local) ";
	}
	*/
	/*
	$id_distrito_federal=$_SESSION['searchTable']['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_distrito_federal IN ($id_distrito_federal) ";
		$sqlContador.=" AND cv.id_distrito_federal IN ($id_distrito_federal) ";
	}
	*/
	

	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND cv.id_municipio ='{$id_municipio}' ";
		$sqlContador.= " AND cv.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND cv.id_distrito_local ='{$id_distrito_local}' ";
		$sqlContador.= " AND cv.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND cv.id_distrito_federal ='{$id_distrito_federal}' ";
		$sqlContador.= " AND cv.id_distrito_federal ='{$id_distrito_federal}' ";
	}

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	//$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["municipio"];
		$nestedData[] = $row["distrito_local"];
		$nestedData[] = $row["distrito_federal"];
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["tipo_casilla"];
		$nestedData[] = $row["codigo"];
		$nestedData[] = $row["lista_nominal"];
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

		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM casillas_votos_2022_revocacion_mandato cv WHERE 1 = 1   "; 
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
