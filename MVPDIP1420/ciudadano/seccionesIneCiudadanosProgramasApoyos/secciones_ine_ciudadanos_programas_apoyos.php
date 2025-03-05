<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave',
		1 =>'fecha_hora',
		2 =>'folio',
		3 =>'nombre_programa_apoyo',
	);


	$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_programas_apoyos WHERE 1 = 1   "; 
	$sql.= " AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}';";

	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT  sicpa.id,sicpa.clave,
					id_programa_apoyo,
					(SELECT pa.nombre FROM programas_apoyos pa WHERE pa.id =  sicpa.id_programa_apoyo) nombre_programa_apoyo,
					sicpa.fecha_hora,folio
			FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE 1 = 1";
	$sql.= " AND sicpa.id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadanox}' ";
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND sicpa.clave LIKE '%{$clave}%' ";
	} 
	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND sicpa.folio LIKE '%{$folio}%' ";
	} 

	$id_programa_apoyo=$search_database['id_programa_apoyo'];
	if( $id_programa_apoyo!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.id_programa_apoyo  = '{$id_programa_apoyo}' ";
		$sqlContador .= " AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}' ";
	} 

	$fecha_1=$search_database['fecha_1'];
	$fecha_2=$search_database['fecha_2'];
	if( $fecha_1 != '' && $fecha_2 == ''){ 
		$post_search=true;
		$sql.=" AND e.fecha <= '{$fecha_1}' ";
		$sqlContador.=" AND e.fecha <= '{$fecha_1}' ";
	}

	if( $fecha_1 == '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND sicpa.fecha >= '{$fecha_2}' ";
		$sqlContador.=" AND sicpa.fecha >= '{$fecha_2}' ";
	}
	if( $fecha_1 != '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND sicpa.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
		$sqlContador.=" AND sicpa.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
	}


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
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["fecha_hora"];
		$nestedData[] = $row["folio"];
		$nestedData[] = $row["nombre_programa_apoyo"];

		
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE 1 = 1   "; 
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
