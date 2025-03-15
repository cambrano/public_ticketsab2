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
		3 =>'nombre_red_social',
		4 =>'nombre_tipo',
	);

	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM cuentas_redes_sociales_actividades WHERE 1 = 1 "; 
	$id_cuenta_red_social = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_cuenta_red_social !=""){
		$sql.= " AND id_cuenta_red_social = '{$id_cuenta_red_social}' ;";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	$sql = "
		SELECT
			*,
			(SELECT CONCAT_WS(' ',i.nombre,i.apellido_paterno,i.apellido_materno) FROM identidades i WHERE i.id = crsa.id_identidad ) nombre_identidad,
			(SELECT rs.nombre FROM redes_sociales rs WHERE rs.id = crsa.id_red_social ) nombre_red_social,
			(SELECT ta.nombre FROM tipos_actividades ta WHERE ta.id = crsa.id_tipo_actividad ) nombre_tipo
		FROM cuentas_redes_sociales_actividades crsa WHERE 1 = 1"; 
		$id_cuenta_red_social = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_cuenta_red_social !=""){
		$sql.= " AND crsa.id_cuenta_red_social = '{$id_cuenta_red_social}'";
	}

	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$sql.=" AND crsa.clave LIKE '%{$clave}%' ";
	}

	$fecha_emision_1=$search_database['fecha_emision_1'];
	$fecha_emision_2=$search_database['fecha_emision_2'];
	if( $fecha_emision_1 != '' && $fecha_emision_2 == ''){ 
		$sql.=" AND crsa.fecha_emision <= '{$fecha_emision_1}' ";
	}
	if( $fecha_emision_1 == '' && $fecha_emision_2 != ''){ 
		$sql.=" AND crsa.fecha_emision >= '{$fecha_emision_2}' ";
	}
	if( $fecha_emision_1 != '' && $fecha_emision_2 != ''){ 
		$sql.=" AND crsa.fecha_emision BETWEEN '{$fecha_emision_1}' AND '{$fecha_emision_2}' ";
	}


	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$moduloAccionPermisos = moduloAccionPermisos('perfiles','cuentas_actividades',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["nombre_red_social"];
		$nestedData[] = $row["nombre_tipo"];
		if($option_delete){
			$delete='<button class="btn btn-danger"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
		}
		if($option_edit){
			$edit='<button class="btn btn-info"  onClick="edit('.$row["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Editar</span></button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";
		$nestedData[] =  "{$edit}{$delete}{$select}";
		$data[] = $nestedData;
	}
	//paginas
	if($post_search){
		//muestra todas sin where
		$totalFiltered = count($data); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	}else{
		//muestra todas las que se filtro con where
		$totalFiltered = $totalData; // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	}
	$json_data = array(
		"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval( $totalData ),  // total number of records
		"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
		);
	echo json_encode($json_data);  // send data as json format
	$conexion->close();

?>
