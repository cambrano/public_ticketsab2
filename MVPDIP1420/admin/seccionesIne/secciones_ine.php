<?php
	/* Database connection start */
	@session_start();
	include __DIR__."/../functions/security.php"; 
	include __DIR__."/../functions/usuario_permisos.php";

	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;


	$clave = $_SESSION['clave'];
	$numero = $_SESSION['numero'];


	$columns = $_SESSION['reporte_Sistema']['nombres'];

	// getting total number records without any search
	$sql = "SELECT count(*) contador FROM secciones_ine WHERE 1 = 1 ";

	$resultado = $conexion->query($sql); 
	$row=$resultado->fetch_assoc();

	 
	//$query=mysqli_query(  $conexion, $sql) or die("secciones_ine.php: get secciones_ine-tabla");
	//$totalData = mysqli_num_rows($query);
	//$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$totalData = $row['contador'];


	$sql = "SELECT * FROM secciones_ine WHERE 1 = 1 ";
	// getting records as per search parameters

	if($numero!=""){
		$sql.= " AND numero like '%{$numero}%' ";
	}

	if($clave!=""){
		$sql.= " AND clave like '%{$clave}%' ";
	}




	//$query=mysqli_query(  $conexion, $sql) or die("secciones_ine.php: get secciones_ine-tabla");
	//$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	//$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	//$query=mysqli_query(  $conexion, $sql) or die("secciones_ine.php: get secciones_ine-tabla");
	$sql.="ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."";


	$sqlContador = "SELECT count(*) contador FROM secciones_ine WHERE 1 = 1 ";
	// getting records as per search parameters

	if($numero!=""){
		$sqlContador.= " AND numero like '%{$numero}%' ";
	}

	if($clave!=""){
		$sqlContador.= " AND clave like '%{$clave}%' ";
	}



	$resultadoContador = $conexion->query($sqlContador); 
	$rowContador=$resultadoContador->fetch_assoc();
	$totalFiltered = $rowContador['contador'];

	$data = array();
	$result = $conexion->query($sql);  
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 

		foreach ($columns as $key => $value) {
			if($value=="id"){
				$nestedData[] = $row[$value];
			}else{
				$nestedData[] = "<div style='text-transform: none;'>".$row[$value]."</div>";
			}
		}

		if( moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'Delete')==true || 
			moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'All')==true
		){
			$delete='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
		}


		if( moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'View')==true || 
			moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'Update')==true || 
			moduloAccion('sistema_unico_beneficiarios','secciones_ine',$_COOKIE["id_usuario"],'All')==true
		){
			$edit='<button class="btn btn-info bt_responsive"  onClick="edit('.$row["id"].');" >
					<span class="btnImage"><img class="bntImageSize" src="img/editar20.png"></span>
					<span class="btnText">Editar</span></button>';
		}
		//$nestedData[] =  "{$edit}{$permiso}{$select}";
		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$delete}{$select}</div>";

		$data[] = $nestedData;
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
