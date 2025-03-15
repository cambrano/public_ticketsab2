<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'id_seccion_ine',
		1 =>'id_tipo_ciudadano',
		2 =>'status',
	);



	$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM tipos_ciudadanos_secciones_avance_semaforo WHERE 1 = 1   "; 
	$sql.= " AND id_seccion_ine ='{$id_seccion_ine}';";

	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
			tcsas.id,
			s.clave as seccion, 
			tc.nombre as tipo_ciudadano,
			IF(tcsas.status = 1 , 'Activo', 'No Activo'  ) status
			FROM tipos_ciudadanos_secciones_avance_semaforo tcsas
			LEFT JOIN secciones_ine s
			ON tcsas.id_seccion_ine = s.id
			LEFT JOIN tipos_ciudadanos tc
			ON tcsas.id_tipo_ciudadano = tc.id WHERE 1 = 1";
	$sql.= " AND id_seccion_ine ='{$id_seccion_ine}' ";
	// getting records as per search parameters



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
		$nestedData[] = $row["tipo_ciudadano"];
		$nestedData[] = $row["status"]; 
		
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
		$sqlContadorScript = "SELECT count(*) total FROM tipos_ciudadanos_secciones_avance_semaforo sia WHERE id_seccion_ine ='{$id_seccion_ine}'  "; 
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
