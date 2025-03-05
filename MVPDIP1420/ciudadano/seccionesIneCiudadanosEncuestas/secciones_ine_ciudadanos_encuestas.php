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
		0 =>'clave',
		1 =>'nombre',
		2 =>'fecha_hora_seccion_ine_ciudadano_encuesta',
	);
	$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM encuestas e WHERE 1 = 1   "; 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				e.id,
				e.clave,
				e.nombre,
				(SELECT sice.id FROM secciones_ine_ciudadanos_encuestas sice WHERE sice.id_encuesta = e.id AND sice.id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}' LIMIT 1) id_seccion_ine_ciudadano_encuesta,
				(SELECT sice.clave FROM secciones_ine_ciudadanos_encuestas sice WHERE sice.id_encuesta = e.id AND sice.id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}' LIMIT 1) clave_seccion_ine_ciudadano_encuesta,
				(
					IF(
						(SELECT sice.clave FROM secciones_ine_ciudadanos_encuestas sice WHERE sice.id_encuesta = e.id AND sice.id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}' LIMIT 1) IS NULL ,
						'No Realizada',
						(SELECT sice.fecha_hora FROM secciones_ine_ciudadanos_encuestas sice WHERE sice.id_encuesta = e.id AND sice.id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}' LIMIT 1) )
					) fecha_hora_seccion_ine_ciudadano_encuesta
			FROM encuestas e 
			WHERE 1 = 1  "; 
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	} 

	$id_encuesta=$search_database['id_encuesta'];
	if( $id_encuesta!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id LIKE '%{$id_encuesta}%' ";
		$sqlContador .= " AND e.id LIKE '%{$id_encuesta}%' ";
	}
	

	$sql.=" ORDER BY e.". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if( $switch_operacionesPermisos['evaluacion'] == true){
		$option_delete = $option_edit = true;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["clave"]; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre"]."<div>"; 
		$nestedData[] = $row["fecha_hora_seccion_ine_ciudadano_encuesta"]; 
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

		$nestedData[] =  "<div class='opciones_botones_3'>{$edit}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM encuestas e WHERE 1 = 1   "; 
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
