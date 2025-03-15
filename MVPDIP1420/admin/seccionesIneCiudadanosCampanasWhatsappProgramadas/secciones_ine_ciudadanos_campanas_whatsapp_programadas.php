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
		0 =>'fechaR',
		1 =>'tipo',
		2 =>'nombre',
		3  =>'nombre_completo',
		4  =>'whatsapp',
		5  =>'fecha_hora_envio',
		6  =>'fecha_hora_entrega',
		7  =>'fecha_hora_leido',
		8  =>'municipio',
		9  =>'distrito_local',
		10  =>'distrito_federal',
		11 =>'seccion',
		12 =>'mensaje_proveedor',
		13  =>'status',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_campanas_whatsapp_programadas WHERE 1 = 1   "; 
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
			CASE
				WHEN siccmp.tipo = 1 THEN 'bienvenida'
				WHEN siccmp.tipo = 3 THEN 'encuesta'
				ELSE 'programada'
			END tipo,

			(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) nombre_completo,
			(SELECT sic.whatsapp FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) whatsapp,
			(SELECT cm.nombre FROM campanas_whatsapp cm WHERE cm.id= siccmp.id_campana_whatsapp) nombre,
			siccmp.fecha_hora_envio,
			siccmp.fecha_hora_entrega,
			siccmp.fecha_hora_leido,
			(SELECT s.numero FROM secciones_ine s WHERE s.id =siccmp.id_seccion_ine) seccion,
			(SELECT dl.numero FROM distritos_locales dl WHERE dl.id =siccmp.id_distrito_local) distrito_local,
			(SELECT df.numero FROM distritos_federales df WHERE df.id =siccmp.id_distrito_federal) distrito_federal,
			(SELECT m.municipio FROM municipios m WHERE m.id =siccmp.id_municipio) municipio,
			siccmp.status,
			siccmp.mensaje_proveedor
		FROM secciones_ine_ciudadanos_campanas_whatsapp_programadas siccmp WHERE 1 = 1  "; 
	// getting records as per search parameters


	$status=$search_database['status'];
	if( $status!="" ){   //name
		$post_search=true;
		$sql.=" AND siccmp.status = '{$status}' ";
		$sql_excel.=" AND siccmp.status = '{$status}' ";
		$sqlContador .= " AND siccmp.status = '{$status}' ";
	}

	$tipo=$search_database['tipo'];
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND siccmp.tipo = '{$tipo}' ";
		$sql_excel.=" AND siccmp.tipo = '{$tipo}' ";
		$sqlContador .= " AND siccmp.tipo = '{$tipo}' ";
	}

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND siccmp.id_seccion_ine IN ($id_seccion_ine) ";
		$sql_excel.=" AND siccmp.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND siccmp.id_seccion_ine IN ($id_seccion_ine) ";
	}
	

	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campanas_whatsapp_programadas',$_COOKIE["id_usuario"]);
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
		$nestedData[] = "<div style='text-transform: none;'>".$row["whatsapp"]."<div>";
		$nestedData[] = $row["fecha_hora_envio"] ==''? '-' : $row["fecha_hora_envio"];
		$nestedData[] = $row["fecha_hora_entrega"] ==''? '-' : $row["fecha_hora_entrega"];
		$nestedData[] = $row["fecha_hora_leido"] ==''? '-' : $row["fecha_hora_leido"];
		$nestedData[] = $row["municipio"]; 
		$nestedData[] = $row["distrito_local"]; 
		$nestedData[] = $row["distrito_federal"]; 
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["mensaje_proveedor"];
		if($row["status"]=='0'){
			$status='<img class="bntImageSize" src="img/circulo_check_gray.png">';
		}elseif($row["status"]=='1'){
			$status='<img class="bntImageSize" src="img/circulo_check_gray.png">';
		}elseif($row["status"]=='2'){
			$status='<img class="bntImageSize" src="img/circulo_check_gray.png"><img class="bntImageSize" src="img/circulo_check_gray.png">';
		}elseif($row["status"]=='3'){
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_campanas_whatsapp_programadas siccmp WHERE 1 = 1   "; 
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
