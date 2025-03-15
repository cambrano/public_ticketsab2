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
		2 =>'municipio',
		3 =>'Distrito Local',
		4 =>'Distrito Federal',
		5 =>'seccion',
		6 =>'tipo_casilla',
		7 =>'codigo',
		8 =>'lista_nominal',
		9 =>'tipo_seccion',
		10 =>'status_data',
		11 =>'check_in',
		12 =>'', 
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM casillas_votos_2021 WHERE 1 = 1   "; 
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND id_distrito_federal ='{$id_distrito_federal}' ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT * ,
			(SELECT m.municipio FROM municipios m WHERE m.id= cv.id_municipio AND m.id_estado='$id_estado' ) municipio,
			(SELECT m.numero FROM distritos_locales m WHERE m.id= cv.id_distrito_local) distrito_local,
			(SELECT m.numero FROM distritos_federales m WHERE m.id= cv.id_distrito_federal) distrito_federal,
			(SELECT s.numero FROM secciones_ine s WHERE s.id= cv.id_seccion_ine) seccion,
			(SELECT tc.clave FROM tipos_casillas tc WHERE tc.id= cv.id_tipo_casilla) tipo_casilla,
			CASE
				WHEN cv.tipo = '0' THEN 'municipal'
				WHEN cv.tipo = '1' THEN 'distrito local'
				ELSE 'distrito federal'
			END as tipo,
			CASE
				WHEN (SELECT cs.status FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ORDER BY fecha_hora DESC LIMIT 1 ) = 1 THEN 'Abierto'
				WHEN (SELECT cs.status FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ORDER BY fecha_hora DESC LIMIT 1 ) = 2 THEN 'Cerrado Con Gente'
				WHEN (SELECT cs.status FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ORDER BY fecha_hora DESC LIMIT 1 ) = 3 THEN 'Cerrado'
				WHEN (SELECT cs.status FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ORDER BY fecha_hora DESC LIMIT 1 ) = 4 THEN 'Inicio Conteo'
				ELSE 'Sin Estatus'
			END as status_data,
			( SELECT CONCAT(ch.hora,' ',FORMAT(ch.distancia_m,2),'m') FROM casillas_votos_2021_checks ch WHERE ch.id_seccion_ine = cv.id_seccion_ine ORDER BY fecha_hora DESC LIMIT 1 ) check_in,
			( SELECT ch.alerta FROM casillas_votos_2021_checks ch WHERE ch.id_seccion_ine = cv.id_seccion_ine ORDER BY fecha_hora DESC LIMIT 1 ) check_in_alert
			FROM casillas_votos_2021 cv WHERE 1 = 1  "; 
	// getting records as per search parameters
	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND cv.clave LIKE '%{$clave}%' ";
	} 

	$tipo_seccion=$_SESSION['searchTable']['tipo_seccion'];
	if( $tipo_seccion!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.tipo_seccion = '$tipo_seccion' ";
		$sqlContador .= " AND cv.tipo_seccion = '$tipo_seccion' ";
	} 

	$status_data=$_SESSION['searchTable']['status_data'];
	if( $status_data!="" ){   //name
		if($status_data=='x'){
			$post_search=true;
			$sql.=" AND NOT EXISTS (SELECT * FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ) ";
			$sqlContador .= "AND NOT EXISTS (SELECT * FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ) ";
		}else{
			$post_search=true;
			$sql.=" AND (SELECT cs.status FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ORDER BY fecha_hora DESC LIMIT 1 ) = '{$status_data}' ";
			$sqlContador.=" AND (SELECT cs.status FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id ORDER BY fecha_hora DESC LIMIT 1 ) = '{$status_data}' ";
			//$sql.=" AND EXISTS (SELECT * FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id AND cs.status='$status_data' ) ";
			//$sqlContador .= "AND EXISTS (SELECT * FROM casillas_votos_2021_status cs WHERE cs.id_casilla_voto_2021 = cv.id AND cs.status='$status_data' ) ";
		}
	} 

	$check_in=$_SESSION['searchTable']['check_in'];
	if( $check_in!="" ){   //name
		if($check_in=='0'){
			$post_search=true;
			$sql.=" AND NOT EXISTS ( SELECT * FROM casillas_votos_2021_checks ck WHERE ck.id_seccion_ine = cv.id_seccion_ine ) ";
			$sqlContador.=" AND NOT EXISTS ( SELECT * FROM casillas_votos_2021_checks ck WHERE ck.id_seccion_ine = cv.id_seccion_ine ) ";
		}else{
			$post_search=true;
			$sql.=" AND ( SELECT count(*) FROM casillas_votos_2021_checks ck WHERE ck.id_seccion_ine = cv.id_seccion_ine )  > 0 ";
			$sqlContador.=" AND ( SELECT count(*) FROM casillas_votos_2021_checks ck WHERE ck.id_seccion_ine = cv.id_seccion_ine ) > 0 ";
		}
	}

	$tipo=$_SESSION['searchTable']['tipo'];
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.tipo LIKE '{$tipo}' ";
		$sqlContador .= " AND cv.tipo LIKE '{$tipo}' ";
	} 

	$id_seccion_ine=$_SESSION['searchTable']['id_seccion_ine'];
	if( $id_seccion_ine!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND cv.id_seccion_ine IN ($id_seccion_ine) ";
	}
	/*
	$id_municipio=$_SESSION['searchTable']['id_municipio'];
	if( $id_municipio!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_municipio IN ($id_municipio) ";
		$sqlContador.=" AND cv.id_municipio IN ($id_municipio) ";
	}
	*/
	/*
	$id_distrito_local=$_SESSION['searchTable']['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_distrito_local IN ($id_distrito_local) ";
		$sqlContador.=" AND cv.id_distrito_local IN ($id_distrito_local) ";
	}
	*/
	/*
	$id_distrito_federal=$_SESSION['searchTable']['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		$post_search=true;
		$sql.=" AND cv.id_distrito_federal IN ($id_distrito_federal) ";
		$sqlContador.=" AND cv.id_distrito_federal IN ($id_distrito_federal) ";
	}
	*/
	

	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND cv.id_municipio ='{$id_municipio}' ";
		$sqlContador.= " AND cv.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND cv.id_distrito_local ='{$id_distrito_local}' ";
		$sqlContador.= " AND cv.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND cv.id_distrito_federal ='{$id_distrito_federal}' ";
		$sqlContador.= " AND cv.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	//$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2021',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["tipo_casilla"];
		$nestedData[] = $row["codigo"];
		$nestedData[] = $row["lista_nominal"];
		$nestedData[] = $row["tipo_seccion"];
		$nestedData[] = $row["status_data"];

		if($row['check_in_alert']=='1' || $row['check_in']==''){
			$nestedData[] = "<div style='background-color: red;padding: 2px;color: white'>{$row['check_in']}&nbsp;</div>";
		}else{
			$nestedData[] = "<div style='background-color: green;padding: 2px;color: white'>{$row['check_in']}</div>";
		}

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
		$casilla_status='<button class="btn btn-primary bt_responsive"  onClick="casillaStatus('.$row["id"].');" >Estatus</button>';
		$casilla_incidencias='<button class="btn btn-primary bt_responsive"  onClick="casillaIncidencias('.$row["id"].');" >Incidencias</button>';
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";

		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$delete}{$select}{$casilla_status}{$casilla_incidencias}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM casillas_votos_2021 cv WHERE 1 = 1   "; 
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
