<?php
	@session_start();
	/* Database connection start */
	include "../functions/security.php";
	include "../functions/log_usuarios.php";

	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'fechaR', 
		1 =>'nombre_usuario',  
		2 =>'tabla',
		3 =>'operacion',
	);

	// getting total number records without any search
	//$sql   = "SELECT * FROM log_usuarios lu WHERE NOT EXISTS (SELECT * FROM usuarios u WHERE u.id_perfil_usuario=1 AND u.id=lu.id_usuario )"; 
	$sql   = "SELECT * FROM log_usuarios lu WHERE 1 = 1   "; 
	$query=mysqli_query(  $conexion, $sql) or die("auditoria_usuarios.php: get auditoria_usuarios-tabla");
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
	$sql   = "
		SELECT 
		lu.fechaR,
		lu.id_usuario,
		lu.tabla,
		lu.operacion,
		u.id_empleado,
		IF (
			u.id_empleado IS NULL ,
			IF(
				CONCAT_WS(' ', sic.nombre,sic.apellido_paterno,sic.apellido_materno) = '',
				'soporte',
				CONCAT_WS(' ', sic.nombre,sic.apellido_paterno,sic.apellido_materno)
			),
			CONCAT_WS(' ', e.nombre,e.apellido_paterno,e.apellido_materno) 
		) nombre_usuario
		FROM log_usuarios lu
		LEFT JOIN usuarios u ON u.id = lu.id_usuario
		LEFT JOIN empleados e ON u.id_empleado = e.id
		LEFT JOIN secciones_ine_ciudadanos sic ON sic.id = u.id_seccion_ine_ciudadano
		WHERE 1 = 1	"; 

		$fechaR=$search_database['fechaR'];
		$id_usuario=$search_database['id_usuario'];
		$tabla=$search_database['tabla'];
		$operacion=$search_database['operacion']; 
		if( !empty($fechaR) ){   //name
			$sql.=" AND lu.fechaR LIKE '%{$fechaR}%' ";
		}
		if( !empty($id_usuario) ){   //name
			$sql.=" AND lu.id_usuario = '{$id_usuario}' ";
		}
		if( !empty($tabla) ){   //name
			$sql.=" AND lu.tabla  = '{$tabla}' ";
		}
		if( !empty($operacion) ){   //name
			$sql.=" AND lu.operacion = '{$operacion}' ";
		}
		

	$query=mysqli_query(  $conexion, $sql) or die("auditoria_usuarios.php: get auditoria_usuarios-tabla");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 



	$query=mysqli_query($conexion, $sql) or die("auditoria_usuarios.php: get employees");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql.=" ORDER BY lu.id   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
	$query=mysqli_query($conexion, $sql) or die("auditoria_usuarios.php: get employees");

	

	$data = array();
	while( $row=mysqli_fetch_assoc($query) ) {  // preparing an array
		$nestedData=array(); 
		$nestedData[] = $row["fechaR"];
		$nestedData[] = $row["nombre_usuario"];
		 
		$nombre=str_replace("_"," ",$row['tabla']); 
		$nestedData[] = ucfirst($nombre);
		$nestedData[] = tipoOperacion($row["operacion"]);
		//$nestedData[] = '<input type="button" value="Ver Mas..." onClick="view('.$row["id"].');">  ';
		//$nestedData[] = '<button  type="button" class="btn btn-primary"  onClick="view('.$row["id"].');" >Ver Mas..</button>';
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
