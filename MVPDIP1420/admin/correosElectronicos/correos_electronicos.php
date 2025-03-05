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
		0 =>'clave', 
		1 =>'fecha_hora_emision', 
		2 =>'nombre_identidad',
		3 =>'nombre_servidor',
		4 =>'usuario',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM correos_electronicos WHERE 1 = 1   "; 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "
		SELECT 
			ce.id,
			ce.clave,
			ce.fecha_hora_emision,
			ce.usuario,
			(SELECT CONCAT_WS(' ',i.nombre,i.apellido_paterno,i.apellido_materno ) FROM identidades i WHERE i.id= ce.id_identidad ) nombre_identidad,
			(SELECT sc.nombre FROM servidores_correos sc WHERE sc.id= ce.id_servidor_correo ) nombre_servidor
		FROM correos_electronicos ce WHERE 1 = 1 
	"; 
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND ce.clave LIKE '%{$clave}%' ";
		$sqlContador.=" AND ce.clave LIKE '%{$clave}%' ";
	} 

	if($search_database['id_identidad']!=""){
		$id_identidad=$search_database['id_identidad'];
		$post_search=true;
	}else{
		$id_identidad = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}
	
	if($id_identidad !=""){
		$post_search=true;
		$sql.= " AND ce.id_identidad = '{$id_identidad}'";
		$sqlContador.= " AND ce.id_identidad = '{$id_identidad}'";
	}

	$id_servidor_correo=$search_database['id_servidor_correo'];
	if($id_servidor_correo !=""){
		$post_search=true;
		$sql.= " AND ce.id_servidor_correo = '{$id_servidor_correo}'";
		$sqlContador.= " AND ce.id_servidor_correo = '{$id_servidor_correo}'";
	}

	$usuario=$search_database['usuario'];
	if($usuario !=""){
		$post_search=true;
		$sql.= " AND ce.usuario LIKE '%{$usuario}%'";
		$sqlContador.= " AND ce.usuario LIKE '%{$usuario}%'";
	}

	$fecha_emision_1=$search_database['fecha_emision_1'];
	$fecha_emision_2=$search_database['fecha_emision_2'];
	if( $fecha_emision_1 != '' && $fecha_emision_2 == ''){ 
		$post_search=true;
		$sql.=" AND ce.fecha_emision <= '{$fecha_emision_1}' ";
		$sqlContador.=" AND ce.fecha_emision <= '{$fecha_emision_1}' ";
	}
	if( $fecha_emision_1 == '' && $fecha_emision_2 != ''){ 
		$post_search=true;
		$sql.=" AND ce.fecha_emision >= '{$fecha_emision_2}' ";
		$sqlContador.=" AND ce.fecha_emision >= '{$fecha_emision_2}' ";
	}
	if( $fecha_emision_1 != '' && $fecha_emision_2 != ''){ 
		$post_search=true;
		$sql.=" AND ce.fecha_emision BETWEEN '{$fecha_emision_1}' AND '{$fecha_emision_2}' ";
		$sqlContador.=" AND ce.fecha_emision BETWEEN '{$fecha_emision_1}' AND '{$fecha_emision_2}' ";
	}

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];


	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','correos_electronicos',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["fecha_hora_emision"];
		$nestedData[] = $row["nombre_identidad"];
		$nestedData[] = $row["nombre_servidor"];
		$nestedData[] = "<div style='text-transform: none;'>".$row["usuario"]."<div>";
		if($option_delete){
			$delete='<button class="btn btn-info bt_responsive"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
		}
		if($option_edit){
			$edit='<button class="btn btn-danger bt_responsive"  onClick="edit('.$row["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Editar</span></button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";

		//$nestedData[] =  "{$edit}{$delete}{$select}";
		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM correos_electronicos ce WHERE 1 = 1   "; 
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