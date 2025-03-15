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
		0 =>'clave',
		1 =>'folio',
		2 =>'clave_elector',
		3 =>'tipo_nombramiento',
		4 =>'nombre_completo',
		5 =>'fecha_inicio',
		6 =>'fecha_final',
		7 =>'colonia',
		8 =>'localidad',
		9 =>'seccion',
		10 =>'distrito_local',
		11 =>'distrito_federal',
		12 =>'status', 
	);



	$id_seccion_ine_grupo = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_grupos WHERE 1 = 1   ";
	if($id_seccion_ine_grupo!=''){
		$sql.= " AND id_seccion_ine_grupo ='{$id_seccion_ine_grupo}';";
	}
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
				sic.clave_elector,
				(SELECT l.nombre FROM tipos_nombramientos l WHERE l.id = sicpa.id_tipo_nombramiento) tipo_nombramiento,
				sic.nombre_completo,
				sicpa.fecha_inicio,
				sicpa.fecha_final,
				sic.colonia,
				(SELECT l.localidad FROM localidades l WHERE l.id = sic.id_localidad) localidad,
				(SELECT si.numero FROM secciones_ine si WHERE si.id = sic.id_seccion_ine) seccion,
				(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
				(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
				IF(sicpa.status=1,'Activo','No Activo') status,
				sicpa.observaciones
			FROM secciones_ine_ciudadanos_grupos sicpa
			LEFT JOIN secciones_ine_ciudadanos sic
			ON sic.id = sicpa.id_seccion_ine_ciudadano
			WHERE 1 = 1";
	// getting records as per search parameters

	if($id_seccion_ine_grupo!=''){
		$sql.=" AND sicpa.id_seccion_ine_grupo = '{$id_seccion_ine_grupo}' ";
		$sqlContador .= " AND sicpa.id_seccion_ine_grupo = '{$id_seccion_ine_grupo}' ";
	}


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
	$clave_elector=$search_database['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND sic.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador .= " AND sic.clave_elector LIKE '%{$clave_elector}%' ";
	}
	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND sic.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND sic.nombre LIKE '%{$nombre}%' ";
	}
	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND sic.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador .= " AND sic.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	}
	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$post_search=true;
		$sql.=" AND sic.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador .= " AND sic.apellido_materno LIKE '%{$apellido_materno}%' ";
	}
	$id_tipo_nombramiento=$search_database['id_tipo_nombramiento'];
	if( $id_tipo_nombramiento!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.id_tipo_nombramiento = '{$id_tipo_nombramiento}' ";
		$sqlContador .= " AND sicpa.id_tipo_nombramiento = '{$id_tipo_nombramiento}' ";
	}
	$status=$search_database['status'];
	if( $status!="" ){   //name
		$post_search=true;
		$sql.=" AND sicpa.status = '{$status}' ";
		$sqlContador .= " AND sicpa.status = '{$status}' ";
	}
	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
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
		foreach ($columns as $key => $value) {
			$nestedData[] = $row[$value];
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
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";
		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$delete}{$select}</div>";

		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_grupos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sic.id = sicpa.id_seccion_ine_ciudadano WHERE 1 = 1 "; 
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
