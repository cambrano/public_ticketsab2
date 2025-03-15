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
		3 =>'usuario',
		4 =>'password',
		5 =>'status',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos WHERE 1 = 1   "; 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				sc.id,
				sc.clave,
				sc.clave_elector,
				sc.nombre_completo,
				u.usuario,
				u.password,
				IF( u.status=1,
					'activo',
					'no activo'
				) status
			FROM secciones_ine_ciudadanos sc
			LEFT JOIN usuarios u
			ON u.id_seccion_ine_ciudadano = sc.id
			WHERE 1 = 1  "; 
	// getting records as per search parameters
	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND sc.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND sc.clave LIKE '%{$clave}%' ";
	} 

	$clave_elector=$_SESSION['searchTable']['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND sc.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador .= " AND sc.clave_elector LIKE '%{$clave_elector}%' ";
	} 

	$nombre_completo=$_SESSION['searchTable']['nombre_completo'];
	if( $nombre_completo!="" ){   //name
		$post_search=true;
		$sql.=" AND sc.nombre_completo LIKE '%{$nombre_completo}%' ";
		$sqlContador .= " AND sc.nombre_completo LIKE '%{$nombre_completo}%' ";
	}

	$usuario=$_SESSION['searchTable']['usuario'];
	if( $usuario!="" ){   //name
		$post_search=true;
		$sql.=" AND u.usuario LIKE '%{$usuario}%' ";
		$sqlContador .= " AND u.usuario LIKE '%{$usuario}%' ";
	}
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	//$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["clave_elector"]; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["usuario"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["password"]."<div>"; 
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
		$sqlContadorScript = " SELECT count(*) FROM secciones_ine_ciudadanos sc	LEFT JOIN usuarios u ON u.id_seccion_ine_ciudadano = sc.id WHERE 1 = 1  "; 
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
