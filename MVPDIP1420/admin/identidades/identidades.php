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
		// datatable column index  => database column name
		0 =>'clave', 
		1 =>'tipo',
		2 =>'sexo',
		3 =>'nombre_identidad',
		4 =>'fecha_nacimiento',
		5 =>'clave_elector',
		6 =>'curp',
		7 =>'rfc',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM identidades WHERE 1 = 1   "; 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
			e.id,
			e.clave,
			e.sexo,
			e.fecha_nacimiento,
			e.clave_elector,
			e.curp,
			e.rfc,
			e.tipo,
			CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) nombre_identidad,
			IF(
				(SELECT ce.id FROM correos_electronicos ce WHERE ce.id_identidad = e.id limit 1 ) IS NULL,
				'no',
				'si'
			) correos_electronicos,

			IF(
				(SELECT crs.id FROM cuentas_redes_sociales crs WHERE crs.id_identidad = e.id limit 1) IS NULL,
				'no',
				'si'
			) cuentas_redes_sociales,
			IF(
				(SELECT do.id FROM documentos_oficiales do WHERE do.id_identidad = e.id limit 1 ) IS NULL,
				'no',
				'si'
			) documentos_oficiales
		FROM identidades e 
		WHERE 1 = 1  "; 
		// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND clave LIKE '%{$clave}%' ";
	}
	$nombre=$search_database['nombre'];
	if( $nombre_identidad!="" ){   //name
		$post_search=true;
		$sql.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_identidad}%' ";
		$sqlContador.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_identidad}%' ";
	}

	$sexo=$search_database['sexo'];
	if( $sexo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.sexo LIKE '%{$sexo}%' ";
		$sqlContador.=" AND e.sexo LIKE '%{$sexo}%' ";
	}

	$clave_elector=$search_database['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
	}

	$curp=$search_database['curp'];
	if( $curp!="" ){   //name
		$post_search=true;
		$sql.=" AND e.curp LIKE '%{$curp}%' ";
		$sqlContador.=" AND e.curp LIKE '%{$curp}%' ";
	}

	$rfc=$search_database['rfc'];
	if( $rfc!="" ){   //name
		$post_search=true;
		$sql.=" AND e.rfc LIKE '%{$rfc}%' ";
		$sqlContador.=" AND e.rfc LIKE '%{$rfc}%' ";
	}

	$id_estado=$search_database['id_estado'];
	if( $id_estado!="" ){     //name
		$post_search=true;
		$sql.=" AND e.id_estado = '{$id_estado}' ";
		$sqlContador.=" AND e.id_estado = '{$id_estado}' ";
	}

	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){    //name
		$post_search=true;
		$sql.=" AND e.id_municipio = '{$id_municipio}' ";
		$sqlContador.=" AND e.id_municipio = '{$id_municipio}' ";
	}

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_localidad = '{$id_localidad}' ";
		$sqlContador.=" AND e.id_localidad = '{$id_localidad}' ";
	}

	$fecha_nacimiento_1=$search_database['fecha_nacimiento_1'];
	$fecha_nacimiento_2=$search_database['fecha_nacimiento_2'];
	if( $fecha_nacimiento_1 != '' && $fecha_nacimiento_2 == ''){
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento <= '{$fecha_nacimiento_1}' ";
		$sqlContador.=" AND e.fecha_nacimiento <= '{$fecha_nacimiento_1}' ";
	}

	if( $fecha_nacimiento_1 == '' && $fecha_nacimiento_2 != ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento >= '{$fecha_nacimiento_2}' ";
		$sqlContador.=" AND e.fecha_nacimiento >= '{$fecha_nacimiento_2}' ";
	}

	if( $fecha_nacimiento_1 != '' && $fecha_nacimiento_2 != ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento BETWEEN '{$fecha_nacimiento_1}' AND '{$fecha_nacimiento_2}' ";
		$sqlContador.=" AND e.fecha_nacimiento BETWEEN '{$fecha_nacimiento_1}' AND '{$fecha_nacimiento_2}' ";
	}

	$tipo=$search_database['tipo'];
	if( $tipo!="" ){     //name
		$post_search=true;
		$sql.=" AND e.tipo = '{$tipo}' ";
		$sqlContador.=" AND e.tipo = '{$tipo}' ";
	}

	$correos_electronicos=$search_database['correos_electronicos'];
	if( $correos_electronicos!="" ){     //name
		$post_search=true;
		$sql.=" AND IF((SELECT ce.id FROM correos_electronicos ce WHERE ce.id_identidad = e.id ) IS NULL,'no','si') = '{$correos_electronicos}' ";
		$sqlContador.=" AND IF((SELECT ce.id FROM correos_electronicos ce WHERE ce.id_identidad = e.id ) IS NULL,'no','si') = '{$correos_electronicos}' ";
	}

	$cuentas_redes_sociales=$search_database['cuentas_redes_sociales'];
	if( $cuentas_redes_sociales!="" ){     //name
		$post_search=true;
		$sql.=" AND IF((SELECT ce.id FROM correos_electronicos ce WHERE ce.id_identidad = e.id ) IS NULL,'no','si') = '{$cuentas_redes_sociales}' ";
		$sqlContador.=" AND IF((SELECT ce.id FROM correos_electronicos ce WHERE ce.id_identidad = e.id ) IS NULL,'no','si') = '{$cuentas_redes_sociales}' ";
	}

	$documentos_oficiales=$search_database['documentos_oficiales'];
	if( $documentos_oficiales!="" ){     //name
		$post_search=true;
		$sql.=" AND IF((SELECT do.id FROM documentos_oficiales do WHERE do.id_identidad = e.id ) IS NULL,'no','si') = '{$documentos_oficiales}' ";
		$sqlContador.=" AND IF((SELECT do.id FROM documentos_oficiales do WHERE do.id_identidad = e.id ) IS NULL,'no','si') = '{$documentos_oficiales}' ";
	}
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];


	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','identidades',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}

	$modulosPermiso = modulosPermiso('perfiles','',$_COOKIE["id_usuario"]);
	if($modulosPermiso['documentos_oficiales'] || $modulosPermiso['all'] ){
		$documentos_oficiales_modulo = true;
	}
	if($modulosPermiso['correos_electronicos'] || $modulosPermiso['all'] ){
		$correos_electronicos_modulo = true;
	}
	if($modulosPermiso['cuentas_redes_sociales'] || $modulosPermiso['all'] ){
		$cuentas_redes_sociales_modulo = true;
	}
	if($modulosPermiso['cuentas_actividades'] || $modulosPermiso['all'] ){
		$cuentas_actividades_modulo = true;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["tipo"];
		$nestedData[] = $row["sexo"];
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_identidad"]."<div>";
		$nestedData[] = $row["fecha_nacimiento"];
		$nestedData[] = $row["correos_electronicos"];
		$nestedData[] = $row["cuentas_redes_sociales"];
		$nestedData[] = $row["documentos_oficiales"];
		$nestedData[] = $row["clave_elector"];
		$nestedData[] = $row["curp"];
		$nestedData[] = $row["rfc"];
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
		if($documentos_oficiales_modulo){
			$documentos_oficiales = '<button class="btn btn-primary bt_responsive"  onClick="documentos_oficiales('.$row["id"].');" >Doc Oficiales</button>';
		}
		if($correos_electronicos_modulo){
			$correos_electronicos='<button class="btn btn-primary bt_responsive"  onClick="correos_electronicos('.$row["id"].');" >Correos</button>';
		}
		if($cuentas_redes_sociales_modulo || $cuentas_actividades_modulo){
			$cuentas_redes_sociales='<button class="btn btn-primary bt_responsive"  onClick="cuentas_redes_sociales('.$row["id"].');" >Redes Sociales</button>';
		}

		$nestedData[] =  "<div class='opciones_botones'>{$edit}{$documentos_oficiales}{$correos_electronicos}{$cuentas_redes_sociales}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM identidades e WHERE 1 = 1   "; 
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