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
		'fechaR',
		'server_name',
		'usuario',
		'password',
		'alerta',
		'alerta_m',
		'tipo',
		'tipo_intento',
		'os',
		'browser',
		'ip',
		'loc',
		'loc_script',
		'ip_type',
		'type',
		'direccion_completa',
		'hostname',
		'isp',
		'org',
		'domain',
		'user_agent'
	);
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
	$sql = "SELECT 
				fechaR,
				server_name,
				usuario,
				`password`,
				case alerta 
					when 0 then 'Amigo'  
					when 1 then 'Hostil'  
					when 2 then 'Neutro'  
					when 3 then 'Interés'  
				end AS alerta,
				alerta_m,
				tipo,
				tipo_intento,
				os,
				browser,
				ip,
				loc,
				loc_script,
				ip_type,
				`type`,
				direccion_completa,
				hostname,
				isp,
				org,
				domain,
				user_agent FROM log_sesiones WHERE 1 = 1  "; 
	$sql .= ' AND id_usuario !=1 ';
	// getting records as per search parameters
	$sqlContador = "";
	$browser=$search_database['browser'];
	if($browser!=""){
		$post_search=true;
		$sql.= " AND browser = '{$browser}' ";
		$sqlContador .= " AND browser = '{$browser}' ";
	}

	$os=$search_database['os'];
	if($os!=""){
		$post_search=true;
		$sql.= " AND os = '{$os}' ";
		$sqlContador .= " AND os = '{$os}' ";
	}

	$city=$search_database['city'];
	if($city!=""){
		$post_search=true;
		$sql.= " AND city = '{$city}' ";
		$sqlContador .= " AND city = '{$city}' ";
	}

	$region=$search_database['region'];
	if($region!=""){
		$post_search=true;
		$sql.= " AND region = '{$region}' ";
		$sqlContador .= " AND region = '{$region}' ";
	}

	$country=$search_database['country'];
	if($country!=""){
		$post_search=true;
		$sql.= " AND country = '{$country}' ";
		$sqlContador .= " AND country = '{$country}' ";
	}

	$alerta=$search_database['alerta'];
	if($alerta!=""){
		if($alerta == 'x'){
			$post_search=true;
			$sql.= " AND alerta IS NULL ";
			$sqlContador.= " AND alerta IS NULL ";
		}else{
			$post_search=true;
			$sql.= " AND alerta = '{$alerta}' ";
			$sqlContador .= " AND alerta = '{$alerta}' ";
		}
	}

	$tipo=$search_database['tipo'];
	if($tipo!=""){
		$post_search=true;
		$sql.= " AND tipo = '{$tipo}' ";
		$sqlContador .= " AND tipo = '{$tipo}' ";
	}

	$tipo_intento=$search_database['tipo_intento'];
	if($tipo_intento!=""){
		$post_search=true;
		$sql.= " AND tipo_intento = '{$tipo_intento}' ";
		$sqlContador .= " AND tipo_intento = '{$tipo_intento}' ";
	}

	$fecha_1=$search_database['fecha_1'];
	$fecha_2=$search_database['fecha_2'];
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