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
		'usuario',
		'nombre_completo',
		'id_tipo_ciudadano',
		'server_name',
		'alerta',
		'alerta_m',
		'alerta_seccion',
		'id_seccion_ine',
		'alerta_seccion_m',
		'ip',
		'loc',
		'loc_script',
		'direccion_completa',
		'user_agent'
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM log_usuarios_tracking_secciones WHERE 1 = 1  "; 
	$sql .= ' AND id_usuario !=1 ';
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				ltc.fechaR,
				ltc.usuario,
				ltc.tipo_usuario,
				ltc.server_name,
				ltc.nombre_completo,
				case ltc.alerta 
					when 0 then 'Amigo'  
					when 1 then 'Hostil'  
					when 2 then 'Neutro'  
					when 3 then 'Interés'  
				end AS alerta,
				ltc.alerta_m,
				ltc.Paguinasub,
				ltc.paguinaId,
				ltc.os,
				ltc.browser,
				ltc.ip,
				ltc.loc,
				ltc.loc_script,
				ltc.ip_type,
				ltc.type,
				ltc.direccion_completa,
				ltc.hostname,
				ltc.isp,
				ltc.org,
				ltc.domain,
				ltc.user_agent,
				tc.nombre AS id_tipo_ciudadano,
				case ltc.alerta_seccion 
					when 1 then 'NO ESTA'  
				end AS alerta_seccion,
				ltc.alerta_seccion_m,
				s.numero AS id_seccion_ine
			FROM log_usuarios_tracking_secciones ltc
			LEFT JOIN tipos_ciudadanos tc
			ON ltc.id_tipo_ciudadano = tc.id
			LEFT JOIN secciones_ine s 
			ON ltc.id_seccion_ine = s.id
			WHERE 1 = 1  "; 
	// getting records as per search parameters
	$sqlContador = "";


	$id_usuario=$search_database['id_usuario'];
	if($id_usuario!=""){
		$post_search=true;
		$sql.= " AND ltc.id_usuario = '{$id_usuario}' ";
		$sqlContador .= " AND ltc.id_usuario = '{$id_usuario}' ";
	}else{
		$sql .= ' AND ltc.id_usuario !=1 ';
	}

	$id_tipo_ciudadano=$search_database['id_tipo_ciudadano'];
	if($id_tipo_ciudadano!=""){
		$post_search=true;
		$sql.= " AND ltc.id_tipo_ciudadano = '{$id_tipo_ciudadano}' ";
		$sqlContador .= " AND ltc.id_tipo_ciudadano = '{$id_tipo_ciudadano}' ";
	}

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if($id_seccion_ine!=""){
		$post_search=true;
		$sql.= " AND ltc.id_seccion_ine = '{$id_seccion_ine}' ";
		$sqlContador .= " AND ltc.id_seccion_ine = '{$id_seccion_ine}' ";
	}

	$alerta_seccion=$search_database['alerta_seccion'];
	if($alerta_seccion!=""){
		$post_search=true;
		$sql.= " AND ltc.alerta_seccion = '{$alerta_seccion}' ";
		$sqlContador .= " AND ltc.alerta_seccion = '{$alerta_seccion}' ";
	}

	$alerta=$search_database['alerta'];
	if($alerta!=""){
		if($alerta == 'x'){
			$post_search=true;
			$sql.= " AND ltc.alerta IS NULL ";
			$sqlContador.= " AND ltc.alerta IS NULL ";
		}else{
			$post_search=true;
			$sql.= " AND ltc.alerta = '{$alerta}' ";
			$sqlContador .= " AND ltc.alerta = '{$alerta}' ";
		}
	}

	$fecha_1=$search_database['fecha_1'];
	$fecha_2=$search_database['fecha_2'];
	if( $fecha_1 != '' && $fecha_2 == ''){ 
		$post_search=true;
		$sql.=" AND ltc.fechaR <= '{$fecha_1} 23:59:59' ";
		$sqlContador .= " AND ltc.fechaR <= '{$fecha_1} 23:59:59' ";
	}
	if( $fecha_1 == '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND ltc.fechaR >= '{$fecha_2} 00:00:00' ";
		$sqlContador .= " AND ltc.fechaR >= '{$fecha_2} 00:00:00' ";
	}
	if( $fecha_1 != '' && $fecha_2 != ''){ 
		$post_search=true;
		$sql.=" AND ltc.fechaR BETWEEN '{$fecha_1} 00:00:00' AND '{$fecha_2} 23:59:59' ";
		$sqlContador .= " AND ltc.fechaR BETWEEN '{$fecha_1} 00:00:00' AND '{$fecha_2} 23:59:59' ";
	}


	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	////////////////////////////

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
		$sqlContadorScript = "SELECT count(*) total FROM log_usuarios_tracking_secciones ltc WHERE 1 = 1  "; 
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