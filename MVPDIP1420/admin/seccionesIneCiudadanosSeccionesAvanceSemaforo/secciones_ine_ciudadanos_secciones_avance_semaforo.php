<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'seccion',
		1 =>'reg',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine WHERE 1 = 1   "; 
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
	$sql = "SELECT 
				si.id,
				si.numero AS seccion,
				si.id_municipio,
				m.municipio,
				si.id_distrito_local,
				dl.numero AS distrito_local,
				si.id_distrito_federal,
				df.numero AS distrito_federal,
				sicsavs.status reg
				FROM secciones_ine si
				LEFT JOIN municipios m
				ON si.id_municipio = m.id
				LEFT JOIN distritos_locales dl
				ON si.id_distrito_local = dl.id
				LEFT JOIN distritos_federales df
				ON si.id_distrito_federal = df.id 
				LEFT JOIN secciones_ine_ciudadanos_secciones_avance_semaforo sicsavs
				ON si.id = sicsavs.id_seccion_ine
				WHERE 1 = 1  
	";
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND si.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND si.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND si.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	// getting records as per search parameters
	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){   //name
		$post_search=true;
		$sql.=" AND si.id = $id_seccion_ine ";
		$sqlContador.=" AND si.id = $id_seccion_ine ";
	}

	$semaforo_status=$search_database['semaforo_general'];
	if( $semaforo_status!="" ){   //name
		$post_search=true;
		if($semaforo_status ==1){
			$sql.=" AND sicsavs.status = 1 ";
			$sqlContador.=" AND sicsavs.status = 1 ";
		}else{
			$sql.=" AND sicsavs.status = 0 ";
			$sqlContador.=" AND sicsavs.status = 0 ";
		}
	} 

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_secciones_avance_semaforo',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["municipio"];
		$nestedData[] = $row["distrito_local"];
		$nestedData[] = $row["distrito_federal"];
		if($row["reg"] == 1){
			$status = 'Activo';
		}else{
			$status = 'No Activo';
		}
		$nestedData[] = $status;
		
		if($option_delete){
			/*
			$delete='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
						*/
		}
		if($option_edit){
			$edit='<button class="btn btn-info bt_responsive"  onClick="edit('.$row["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Editar</span></button>';
			$edit='<button class="btn btn-primary bt_responsive"  onClick="edit('.$row["id"].');" >Semáforo General</button>';
			$edit.='<button class="btn btn-primary bt_responsive"  onClick="semaforoTiposCiudadano('.$row["id"].');" >Semáforo Tipos Ciudadanos</button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";

		$nestedData[] =  "<div class='opciones_botones_1'>{$edit}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "
			SELECT count(*) total 
			FROM secciones_ine si 
			LEFT JOIN secciones_ine_ciudadanos_secciones_avance_semaforo sicsavs
			ON si.id = sicsavs.id_seccion_ine
			WHERE 1 = 1   "; 
		if($tipo_uso_plataforma=='municipio'){
			$sqlContador.= " AND si.id_municipio ='{$id_municipio}' ";
		}elseif($tipo_uso_plataforma=='distrito_local'){
			$sqlContador.= " AND si.id_distrito_local ='{$id_distrito_local}' ";
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			$sqlContador.= " AND si.id_distrito_federal ='{$id_distrito_federal}' ";
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
