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
		0 =>'clave',
		1 =>'folio',
		2 =>'tipo_grupo',
		3 =>'nombre',
		4 =>'tipo_nombramiento',
		5 =>'fecha_inicio',
		6 =>'fecha_final',
		7 =>'colonia',
		8 =>'localidad',
		9 =>'seccion',
		10 =>'distrito_local',
		11 =>'distrito_federal',
		12 =>'status',
	);



	$id_seccion_ine_ciudadano = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_grupos WHERE 1 = 1   "; 
	$sql.= " AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadano}';";

	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT
				sicpa.id,
				sicpa.clave,
				sicpa.folio,
				(SELECT l.nombre FROM tipos_nombramientos l WHERE l.id = sicpa.id_tipo_nombramiento) tipo_nombramiento,
				(SELECT l.nombre FROM tipos_secciones_ine_grupos l WHERE l.id = sig.id_tipo_seccion_ine_grupo) tipo_grupo,
				sig.nombre,
				sicpa.fecha_inicio,
				sicpa.fecha_final,
				sig.colonia,
				(SELECT l.localidad FROM localidades l WHERE l.id = sig.id_localidad) localidad,
				(SELECT si.numero FROM secciones_ine si WHERE si.id = sig.id_seccion_ine) seccion,
				(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = si.id_distrito_local) distrito_local,
				(SELECT df.numero FROM distritos_federales df WHERE df.id = si.id_distrito_federal) distrito_federal,
				IF(sicpa.status=1,'Activo','No Activo') status,
				sicpa.observaciones
			FROM secciones_ine_ciudadanos_grupos sicpa
			LEFT JOIN secciones_ine_grupos sig
			ON sig.id = sicpa.id_seccion_ine_grupo
			LEFT JOIN secciones_ine si
			ON si.id = sig.id_seccion_ine
			WHERE 1 = 1";
	$sql.= " AND sicpa.id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadano}' ";
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
	$status=$search_database['status'];
	if( $status!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.status = '{$status}' ";
		$sqlContador .= " AND sicpa.status = '{$status}' ";
	}



	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];


	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_grupos',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["folio"];
		$nestedData[] = $row["tipo_grupo"];
		$nestedData[] = $row["nombre"];
		$nestedData[] = $row["tipo_nombramiento"];
		$nestedData[] = $row["fecha_inicio"];
		$nestedData[] = $row["fecha_final"];
		$nestedData[] = $row["colonia"];
		$nestedData[] = $row["localidad"];
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["distrito_local"];
		$nestedData[] = $row["distrito_federal"];
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_grupos sicpa WHERE 1 = 1   "; 
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
