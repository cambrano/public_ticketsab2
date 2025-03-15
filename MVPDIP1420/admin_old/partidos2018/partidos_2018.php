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
		1 =>'tipo',
		2 =>'nombre_corto',
		3 =>'nombre',
		4 =>'coaliciones',
		5 =>'icono',
		6 =>'principal', 
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM partidos_2018 WHERE 1 = 1   "; 
	if($tipo_uso_plataforma=='municipio'){
		$sql.=" AND tipo = '0' ";
		$_SESSION['searchTable']['tipo'] = 0;
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.=" AND tipo = '1' ";
		$_SESSION['searchTable']['tipo'] = 1;
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.=" AND tipo = '2' ";
		$_SESSION['searchTable']['tipo'] = 2;
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "
		SELECT 
			id,
			clave,
			nombre_corto,
			nombre,
			clave_partidos_coaliciones,
			logo,
			icono,
			IF(principal = 1, 'SI','NO') 'principal',
			CASE
				WHEN tipo = '0' THEN 'ayuntamiento'
				WHEN tipo = '1' THEN 'distrito local'
				ELSE 'distrito federal'
			END as tipo
		FROM partidos_2018 WHERE 1 = 1  "; 
	// getting records as per search parameters
	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND clave LIKE '%{$clave}%' ";
	} 

	$nombre=$_SESSION['searchTable']['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND nombre LIKE '%{$nombre}%' ";
	}

	$nombre_corto=$_SESSION['searchTable']['nombre_corto'];
	if( $nombre_corto!="" ){   //name
		$post_search=true;
		$sql.=" AND nombre_corto LIKE '%{$nombre_corto}%' ";
		$sqlContador .= " AND nombre_corto LIKE '%{$nombre_corto}%' ";
	}

	$tipo=$_SESSION['searchTable']['tipo'];
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND tipo = '{$tipo}' ";
		$sqlContador.=" AND tipo = '{$tipo}' ";
	}
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	//$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','partidos_2018',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["tipo"]; 
		$nestedData[] = $row["nombre_corto"]; 
		$nestedData[] = $row["nombre"]; 
		$nestedData[] = $row["clave_partidos_coaliciones"]; 
		
		$nestedData[] = $row["principal"];
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
		$sqlContadorScript = "SELECT count(*) total FROM partidos_2018 WHERE 1 = 1   "; 
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
