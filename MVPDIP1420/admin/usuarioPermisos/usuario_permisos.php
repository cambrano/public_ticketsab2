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
		// datatable column index  => database column name
		0 =>'nombre_seccion', 
		1 =>'nombre_modulo', 
		2 =>'nombre_permiso',
	);

	$id_empleado = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	$validar_codigo_plataforma = validar_codigo_plataforma($codigo_plataforma);
	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM usuarios_permisos WHERE 1 = 1   "; 
	if($id_empleado!=''){
		$sql.=" AND id_empleado = '{$id_empleado}' ";
	}
	if($validar_codigo_plataforma == false){
		//$sql .= " AND codigo_plataforma = '{$codigo_plataforma}' ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql   = "SELECT * ,
		(SELECT s.seccion FROM secciones s WHERE s.id=up.id_seccion ) nombre_seccion,
		(SELECT m.modulo FROM modulos m WHERE m.id=up.id_modulo ) nombre_modulo,
		(SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) nombre_permiso2,
		CASE
			WHEN (SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) ='view' THEN 'vista'
			WHEN (SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) ='insert' THEN 'inserción'
			WHEN (SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) ='update' THEN 'modificación'
			WHEN (SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) ='delete' THEN 'eliminación'
			WHEN (SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) ='download' THEN 'descargar'
			WHEN (SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) ='all' THEN 'total'
			WHEN (SELECT p.permiso FROM permisos p WHERE p.id=up.id_permiso ) ='captura' THEN 'captura'
		END nombre_permiso,
		IF( up.status=1,
			'activo',
			'no activo'
		) status
		FROM usuarios_permisos up WHERE up.id_empleado='{$id_empleado}' ";
	// getting records as per search parameters
	if($validar_codigo_plataforma == false){
		$sql .= " AND up.codigo_plataforma = '{$codigo_plataforma}' ";
	}
	$id_seccion=$search_database['id_seccion'];
	if( $id_seccion!="" ){   //name
		$post_search=true;
		$sql.=" AND up.id_seccion = '{$id_seccion}' ";
		$sqlContador.=" AND up.id_seccion = '{$id_seccion}' ";
	} 

	$id_modulo=$search_database['id_modulo'];
	if( $id_modulo!="" ){   //name
		$post_search=true;
		$sql.=" AND up.id_modulo = '{$id_modulo}' ";
		$sqlContador.=" AND up.id_modulo = '{$id_modulo}' ";
	}

	$id_permiso=$search_database['id_permiso'];
	if( $id_permiso!="" ){   //name
		$post_search=true;
		$sql.=" AND id_permiso = '{$id_permiso}' ";
		$sqlContador.=" AND id_permiso = '{$id_permiso}' ";
	}
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];


	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_permisos',$_COOKIE["id_usuario"]);
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
		$nestedData[] = str_replace("_", " ", $row["nombre_seccion"]);
		$nestedData[] = str_replace("_", " ", $row["nombre_modulo"]);
		$nestedData[] = str_replace("_", " ", $row["nombre_permiso"]);
		$nestedData[] = $row["status"];
		if($option_delete){
			$delete='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$row["id_usuario_modulo"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
		}
		if($option_edit){
			$edit='<button class="btn btn-info bt_responsive"  onClick="edit('.$row["id_usuario_modulo"].');" >
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
		$sqlContadorScript = "SELECT count(*) total FROM usuarios_permisos up WHERE 1 = 1 "; 
		if($id_empleado!=''){
			$sqlContadorScript.=" AND id_empleado = '{$id_empleado}' ";
		}
		if($validar_codigo_plataforma == false){
			$sqlContadorScript .= " AND up.codigo_plataforma = '{$codigo_plataforma}' ";
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
