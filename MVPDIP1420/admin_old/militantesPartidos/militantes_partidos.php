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
		1 =>'clave_elector',
		2 =>'nombre_completo',
		3 =>'fecha_hora',
		4 =>'folio',
		5 =>'partido',
		6 =>'status',
	);



	$id_seccion_ine_ciudadano = $_SESSION['id_seccion_ine_ciudadano'];

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM militantes_partidos WHERE 1 = 1   ";
	if($id_seccion_ine_ciudadano!=''){
		$sql.= " AND id_seccion_ine_ciudadano ='{$id_seccion_ine_ciudadano}';";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT
				sicpa.id,
				sicpa.id_seccion_ine_ciudadano,
				sicpa.clave,
				(SELECT pa.nombre_corto FROM partidos_legados pa WHERE pa.id =  sicpa.id_partido_legado) partido,
				sicpa.fecha_hora,
				sicpa.folio,
				sic.nombre_completo,
				sic.clave_elector,
				IF(sicpa.status=1,'Activo','No Activo') status
			FROM militantes_partidos sicpa
			LEFT JOIN secciones_ine_ciudadanos sic
			ON sic.id = sicpa.id_seccion_ine_ciudadano
			WHERE 1 = 1";
	// getting records as per search parameters

	if($id_seccion_ine_ciudadano!=''){
		$sql.=" AND sicpa.id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' ";
		$sqlContador .= " AND sicpa.id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' ";
	}

	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND sicpa.clave LIKE '%{$clave}%' ";
	} 
	$folio=$_SESSION['searchTable']['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND sicpa.folio LIKE '%{$folio}%' ";
	} 

	$id_partido_legado=$_SESSION['searchTable']['id_partido_legado'];
	if( $id_partido_legado!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.id_partido_legado  = '{$id_partido_legado}' ";
		$sqlContador .= " AND sicpa.id_partido_legado = '{$id_partido_legado}' ";
	}

	$status=$_SESSION['searchTable']['status'];
	if( $status!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.status = '{$status}' ";
		$sqlContador .= " AND sicpa.status = '{$status}' ";
	}

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	//$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','militantes_partidos',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["clave_elector"];
		$nestedData[] = $row["nombre_completo"];
		$nestedData[] = $row["fecha_hora"];
		$nestedData[] = $row["partido"];
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
		$ciudadano_militante_partidos_credencial='<button class="btn btn-primary bt_responsive"  onClick="militantes_partidosCredenciales('.$row["id"].');" >Credencial</button>';
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";
		$nestedData[] =  "<div class='opciones_botones_1'>{$edit}{$ciudadano_militante_partidos_credencial}{$delete}{$select}</div>";

		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM militantes_partidos sicpa WHERE 1 = 1  "; 
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
