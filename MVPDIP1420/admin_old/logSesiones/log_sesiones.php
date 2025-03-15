<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = $_SESSION['reporte_Sistema']['nombres'];

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM log_sesiones WHERE 1 = 1  "; 
	$sql .= ' AND id_usuario !=1 ';
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT * FROM log_sesiones WHERE 1 = 1  "; 
	$sql .= ' AND id_usuario !=1 ';
	// getting records as per search parameters
	$sqlContador = "";
	$browser=$_SESSION['searchTable']['browser'];
	if($browser!=""){
		$post_search=true;
		$sql.= " AND browser = '{$browser}' ";
		$sqlContador .= " AND browser = '{$browser}' ";
	}

	$os=$_SESSION['searchTable']['os'];
	if($os!=""){
		$post_search=true;
		$sql.= " AND os = '{$os}' ";
		$sqlContador .= " AND os = '{$os}' ";
	}

	$city=$_SESSION['searchTable']['city'];
	if($city!=""){
		$post_search=true;
		$sql.= " AND city = '{$city}' ";
		$sqlContador .= " AND city = '{$city}' ";
	}

	$region=$_SESSION['searchTable']['region'];
	if($region!=""){
		$post_search=true;
		$sql.= " AND region = '{$region}' ";
		$sqlContador .= " AND region = '{$region}' ";
	}

	$country=$_SESSION['searchTable']['country'];
	if($country!=""){
		$post_search=true;
		$sql.= " AND country = '{$country}' ";
		$sqlContador .= " AND country = '{$country}' ";
	}

	$fecha_1=$_SESSION['searchTable']['fecha_1'];
	$fecha_2=$_SESSION['searchTable']['fecha_2'];
	if( $fecha_1 != '' && $fecha_2 == ''){ 
		$post_search=true;
		$sql.=" AND fechaR <= '{$fecha_1} 23:59:59' ";
		$sqlContador .= " AND fechaR <= '{$fecha_1} 23:59:59' ";
	}
	if( $fecha_1 == '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND fechaR >= '{$fecha_2} 00:00:00' ";
		$sqlContador .= " AND fechaR >= '{$fecha_2} 00:00:00' ";
	}
	if( $fecha_1 != '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND fechaR BETWEEN '{$fecha_1} 00:00:00' AND '{$fecha_2} 23:59:59' ";
		$sqlContador .= " AND fechaR BETWEEN '{$fecha_1} 00:00:00' AND '{$fecha_2} 23:59:59' ";
	}


	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	////////////////////////////
	//$_SESSION['reporte_Sistema']['sql'] = $sql;
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		foreach ($columns as $key => $value) {
			if($value=="id"){
				$nestedData[] = $row[$value];
			}else{
				$nestedData[] = "<div style='text-transform: none;'>".$row[$value]."</div>";
			}
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";

		$nestedData[] =  "{$edit}{$delete}{$select}";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM log_sesiones WHERE 1 = 1  "; 
		$sqlContadorScript .= ' AND id_usuario !=1 ';
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