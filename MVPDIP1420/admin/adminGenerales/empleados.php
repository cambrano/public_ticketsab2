<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/plataformas.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave', 
		1 =>'nombre_usuario',  
		2 =>'usuario',  
		3 =>'empleado_grupo',
		4 =>'status',
	);
	$validar_codigo_plataforma = validar_codigo_plataforma($codigo_plataforma);
	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql   = "SELECT count(*) total FROM empleados e WHERE EXISTS (SELECT * FROM usuarios u WHERE u.id_perfil_usuario=3 AND e.id = u.id_empleado ) "; 
	if($validar_codigo_plataforma == false){
		$sql .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql   = "
		SELECT *,
		CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) nombre_usuario,
		e.clave,
		IF( e.status=1,
			'activo',
			'no activo'
		) status,
		(SELECT u.usuario FROM usuarios u WHERE u.id_empleado=e.id) usuario ,
		( SELECT eg.nombre FROM empleados_grupos eg WHERE eg.id = e.id_empleado_grupo ) empleado_grupo
		FROM empleados e WHERE EXISTS (SELECT * FROM usuarios u WHERE u.id_perfil_usuario=3 AND e.id = u.id_empleado  ) "; 
	// getting records as per search parameters
	if($validar_codigo_plataforma == false){
		$sql .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
	}
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	} 

	$nombre_usuario=$search_database['nombre_usuario'];
	if( !empty($nombre_usuario) ){   //name
		$post_search=true;
		$sql.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_usuario}%' ";
		$sqlContador.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_usuario}%' ";
	}

	$id_empleado_grupo=$search_database['id_empleado_grupo'];
	if( !empty($id_empleado_grupo) ){   //name
		$post_search=true;
		$sql.=" AND e.id_empleado_grupo  = '{$id_empleado_grupo}' ";
		$sqlContador.=" AND e.id_empleado_grupo  = '{$id_empleado_grupo}' ";
	}

	$usuario=$search_database['usuario'];
	if( !empty($usuario) ){   //name
		$post_search=true;
		$sql.=" AND (SELECT u.usuario FROM usuarios u WHERE u.id_empleado=e.id) LIKE '%{$usuario}%' ";
		$sqlContador.=" AND (SELECT u.usuario FROM usuarios u WHERE u.id_empleado=e.id) LIKE '%{$usuario}%' ";
	}

	$status=$search_database['status'];
	if( !empty($status) ){   //name
		$post_search=true;
		if($status=="x"){
			$sql.=" AND e.status = '0' ";
			$sqlContador.=" AND e.status = '0' ";
		}else{
			$sql.=" AND e.status = '{$status}' ";
			$sqlContador.=" AND e.status = '{$status}' ";
		}
	}


	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";


	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
		$option_delete = true;
	}

	if( $moduloAccionPermisos['view'] || $moduloAccionPermisos['update'] || $moduloAccionPermisos['all'] ){
		$option_edit = true;
	}

	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_permisos',$_COOKIE["id_usuario"]);
	if(!empty($moduloAccionPermisos)){
		$modulo_empleados_permisos = true;
	}

	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		//$row['id'] = 4;
		$nestedData[] = $row["clave"];
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_usuario"]."<div>";
		$nestedData[] = "<div style='text-transform: none;'>".$row["usuario"]."<div>";
		$nestedData[] = $row["empleado_grupo"];
		$nestedData[] = $row["status"];
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

		if($modulo_empleados_permisos){
			$empleados_permisos='<button class="btn btn-primary bt_responsive"  onClick="permisos('.$row["id"].');" >Permisos</button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";

		$nestedData[] =  "<div class='opciones_botones_3'>{$edit}{$empleados_permisos}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM empleados e WHERE 1 = 1  "; 
		if($validar_codigo_plataforma == false){
			$sqlContadorScript .= " AND e.codigo_plataforma = '{$codigo_plataforma}' ";
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
