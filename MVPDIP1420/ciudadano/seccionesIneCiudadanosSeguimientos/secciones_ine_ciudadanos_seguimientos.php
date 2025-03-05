<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
		// datatable column index  => database column name
		0 =>'fechaR',
		1 =>'asunto',
		2 =>'distancia_km',
		3 =>'distancia_alert',
		4 =>'medio_registro', 
	);



	$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_seguimientos WHERE 1 = 1   "; 
	$sql.= " AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}';";

	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
	id,fechaR,distancia_km,
	IF(distancia_alert=0,'NO TIENE','DISTANCIA') distancia_alert,asunto,
	CASE 
		WHEN medio_registro = 1 THEN 'CIUDADANO'
		WHEN medio_registro = 2 THEN 'SISTEMA'
		ELSE 'IMPORTACION'
	END AS medio_registro
	FROM secciones_ine_ciudadanos_seguimientos WHERE 1 = 1";
	$sql.= " AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}' ";
	// getting records as per search parameters



	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if( $switch_operacionesPermisos['evaluacion'] == true){
		$option_delete = $option_edit = true;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["fechaR"];
		$nestedData[] = $row["asunto"]; 
		
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_seguimientos sia WHERE 1 = 1   "; 
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
