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
		0 =>'siccmp.fechaR',
		1 =>'siccmp.tipo',
		2 =>'siccmp.nombre',
		3  =>'nombre_completo',
		4  =>'correo_electronico',
		5  =>'siccmp.fecha_hora_envio',
		6  =>'siccmp.fecha_hora_leido',
		7  =>'siccmp.ip',
		8  =>'municipio',
		9  =>'distrito_local',
		10  =>'distrito_federal',
		11 =>'seccion',
		12 =>'siccmp.loc',
		13 =>'siccmp.loc_script',
		14  =>'siccmp.status',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_campanas_mailing_programadas WHERE 1 = 1   "; 
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
	$sql = "
		SELECT 
			siccmp.fechaR,
			siccmp.id,
			siccmp.loc,
			siccmp.loc_script,
			CASE
				WHEN siccmp.tipo = 1 THEN 'bienvenida'
				WHEN siccmp.tipo = 3 THEN 'encuesta'
				ELSE 'programada'
			END tipo,

			(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) nombre_completo,
			(SELECT sic.correo_electronico FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) correo_electronico,
			(SELECT cm.nombre FROM campanas_mailing cm WHERE cm.id= siccmp.id_campana_mailing) nombre,
			siccmp.fecha_hora_envio,
			siccmp.fecha_hora_leido,
			siccmp.ip,
			(SELECT s.numero FROM secciones_ine s WHERE s.id =siccmp.id_seccion_ine) seccion,
			(SELECT dl.numero FROM distritos_locales dl WHERE dl.id =siccmp.id_distrito_local) distrito_local,
			(SELECT df.numero FROM distritos_federales df WHERE df.id =siccmp.id_distrito_federal) distrito_federal,
			(SELECT m.municipio FROM municipios m WHERE m.id =siccmp.id_municipio) municipio,
			CASE
				WHEN siccmp.status = 1 THEN 'Enviado'
				WHEN siccmp.status = 2 THEN 'No Enviado'
				WHEN siccmp.status = 3 THEN 'Leido'
				WHEN siccmp.status = 4 THEN 'Cancelado'
				ELSE 'pendiente'
			END status,

			siccmp.status status_check
		FROM secciones_ine_ciudadanos_campanas_mailing_programadas siccmp WHERE 1 = 1  "; 
		if($tipo_uso_plataforma=='municipio'){
			$sql.= " AND siccmp.id_municipio ='{$id_municipio}' ";
		}elseif($tipo_uso_plataforma=='distrito_local'){
			$sql.= " AND siccmp.id_distrito_local ='{$id_distrito_local}' ";
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			$sql.= " AND siccmp.id_distrito_federal ='{$id_distrito_federal}' ";
		}
	// getting records as per search parameters


	$status=$search_database['status'];
	if( $status!="" ){   //name
		$post_search=true;
		$sql.=" AND siccmp.status = '{$status}' ";
		$sqlContador .= " AND siccmp.status = '{$status}' ";
	}

	$tipo=$search_database['tipo'];
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND siccmp.tipo = '{$tipo}' ";
		$sqlContador .= " AND siccmp.tipo = '{$tipo}' ";
	}

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND siccmp.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND siccmp.id_seccion_ine IN ($id_seccion_ine) ";
	}
	

	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campanas_mailing_programadas',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["fechaR"]; 
		$nestedData[] = $row["tipo"]; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["correo_electronico"]."<div>"; 
		$nestedData[] = $row["fecha_hora_envio"] ==''? '-' : $row["fecha_hora_envio"] ; 
		$nestedData[] = $row["fecha_hora_leido"] ==''? '-' : $row["fecha_hora_leido"] ; 
		$nestedData[] = $row["ip"] ==''? '-' : $row["ip"] ; 
		$nestedData[] = $row["municipio"]; 
		$nestedData[] = $row["distrito_local"]; 
		$nestedData[] = $row["distrito_federal"]; 
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["loc"]; 
		$nestedData[] = $row["loc_script"]; 
		if($row["status_check"]=='0'){
			$status='<img class="bntImageSize" src="img/circulo_check_gray.png"><img class="bntImageSize" src="img/circulo_check_gray.png">';
		}elseif($row["status_check"]=='1'){
			$status='<img class="bntImageSize" src="img/circulo_check_green.png"><img class="bntImageSize" src="img/circulo_check_gray.png">';
		}elseif($row["status_check"]=='2'){
			$status='<img class="bntImageSize" src="img/circulo_info.png">';
		}elseif($row["status_check"]=='3'){
			$status='<img class="bntImageSize" src="img/circulo_check_green.png"><img class="bntImageSize" src="img/circulo_check_green.png">';
		}else{
			$status='<img class="bntImageSize" src="img/circulo_cancel.png">';
		}
		$nestedData[] = $status; 
		if($option_delete){
			$delete='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Cancelar</span></button>';
		}
		if($option_edit){
			$edit='<button class="btn btn-info bt_responsive"  onClick="edit('.$row["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Reiniciar</span></button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";

		$nestedData[] =  "<div class='opciones_botones_2'>{$edit} {$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_campanas_mailing_programadas siccmp WHERE 1 = 1   "; 
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
