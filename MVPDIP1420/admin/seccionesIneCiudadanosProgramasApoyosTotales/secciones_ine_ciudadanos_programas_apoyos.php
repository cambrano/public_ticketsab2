<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/plataformas.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array(
		0 =>'clave',
		1 =>'folio',
		2 =>'fecha_hora',
		3 =>'clave_elector',
		4 =>'curp',
		5 =>'nombre_completo',
		6 =>'repetido',
		7 =>'sexo',
		8 =>'fecha_nacimiento',
		9 =>'correo_electronico',
		10 =>'telefono',
		11 =>'celular',
		12 =>'whatsapp',
		13 =>'colonia',
		14 =>'localidad',
		15 =>'seccion',
		16 =>'distrito_local',
		17 =>'distrito_federal',
	);
	$validar_codigo_plataforma = validar_codigo_plataforma($codigo_plataforma);


	$id_programa_apoyo = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sic.id = sicpa.id_seccion_ine_ciudadano WHERE 1 = 1   ";
	if($id_programa_apoyo!=''){
		$sql.= " AND sicpa.id_programa_apoyo ='{$id_programa_apoyo}' ";
	}
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND sic.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND sic.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND sic.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	if($validar_codigo_plataforma == false){
		$sql .= " AND sicpa.codigo_plataforma = '{$codigo_plataforma}' ";
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
				sicpa.fecha_hora,
				sic.clave_elector,
				sic.curp,
				sic.nombre_completo,
				IF((SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '4')>1,'repetido','sin repetir') repetido,
				sic.sexo,
				sic.fecha_nacimiento,
				sic.correo_electronico,
				sic.telefono,
				sic.celular,
				sic.whatsapp,
				sic.colonia,
				(SELECT l.localidad FROM localidades l WHERE l.id = sic.id_localidad) localidad,
				(SELECT si.numero FROM secciones_ine si WHERE si.id = sic.id_seccion_ine) seccion,
				(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
				(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
				sicpa.observaciones
			FROM secciones_ine_ciudadanos_programas_apoyos sicpa
			LEFT JOIN secciones_ine_ciudadanos sic
			ON sic.id = sicpa.id_seccion_ine_ciudadano
			WHERE 1 = 1";
	// getting records as per search parameters
	if($validar_codigo_plataforma == false){
		$sql .= " AND sicpa.codigo_plataforma = '{$codigo_plataforma}' ";
	}
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND sic.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND sic.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND sic.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	if($id_programa_apoyo!=''){
		$sql.=" AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}' ";
		$sqlContador .= " AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}' ";
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
	$curp=$search_database['curp'];
	if( $curp!="" ){   //name
		$post_search=true;
		$sql.=" AND sic.curp LIKE '%{$curp}%' ";
		$sqlContador .= " AND sic.curp LIKE '%{$curp}%' ";
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



	$status=$search_database['status'];
	if( $status!="" ){   //name
		$post_search=true;
		$sql.=" AND sic.status LIKE '%{$status}%' ";
		$sqlContador .= " AND sic.status LIKE '%{$status}%' ";
	} 

	$repetidos=$search_database['repetidos'];
	if( $repetidos!="" ){   //name
		if($repetidos == 1){
			$post_search=true;
			$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}'  ) > 1 ";
			$sqlContador.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}'  ) > 1 ";
		}else{
			$post_search=true;
			$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}'  ) = 1 ";
			$sqlContador.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}' = 1  ) > 1 ";
		}
	}

	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_programas_apoyos',$_COOKIE["id_usuario"]);
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sic.id = sicpa.id_seccion_ine_ciudadano WHERE 1 = 1 "; 
		if($tipo_uso_plataforma=='municipio'){
			$sqlContadorScript.= " AND sic.id_municipio ='{$id_municipio}' ";
		}elseif($tipo_uso_plataforma=='distrito_local'){
			$sqlContadorScript.= " AND sic.id_distrito_local ='{$id_distrito_local}' ";
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			$sqlContadorScript.= " AND sic.id_distrito_federal ='{$id_distrito_federal}' ";
		}
		if($validar_codigo_plataforma == false){
			$sqlContadorScript .= " AND sicpa.codigo_plataforma = '{$codigo_plataforma}' ";
		}
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
