<?php
		@session_start();
		/* Database connection start */
		include "../functions/db.php";
		include "../functions/status.php";
		include "../functions/log_usuarios.php";

		/* Database connection end */
		// storing  request (ie, get/post) global array to a variable  
		$requestData= $_REQUEST;
		
		$columns = array( 
			// datatable column index  => database column name
			0 =>'fechaR', 
			1 =>'nombre_usuario',  
			2 =>'tabla',
			3 =>'operacion',
		);

		// getting total number records without any search
		//$sql   = "SELECT * FROM log_usuarios lu WHERE NOT EXISTS (SELECT * FROM usuarios u WHERE u.id_perfil_usuario=1 AND u.id=lu.id_usuario )"; 
		$sql   = "SELECT * FROM log_usuarios lu WHERE 1 = 1 AND EXISTS (SELECT * FROM usuarios u WHERE u.id_perfil_usuario=1 )  "; 
		$query=mysqli_query(  $conexion, $sql) or die("auditoria_usuarios.php: get auditoria_usuarios-tabla");
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


		$sql   = "SELECT *,
		(
			SELECT
				IF(
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) IS NULL,
					u.usuario,
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) 
				)
				 nombreCompledo 

				FROM usuarios u WHERE u.id=lu.id_usuario AND (u.codigo_plataforma='{$codigo_plataforma}' OR u.codigo_plataforma='x') 
		) nombre_usuario
		FROM log_usuarios lu WHERE 1 = 1 AND EXISTS (SELECT * FROM usuarios u WHERE u.id_perfil_usuario=1 )  "; 
		$query=mysqli_query(  $conexion, $sql) or die("auditoria_usuarios.php: get auditoria_usuarios-tabla");
		$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 


		$requestData['columns'][0]['search']['value']=$_SESSION['fechaR'];
		$requestData['columns'][1]['search']['value']=$_SESSION['id_usuario'];
		$requestData['columns'][2]['search']['value']=$_SESSION['tabla'];
		$requestData['columns'][3]['search']['value']=$_SESSION['operacion']; 
		if( !empty($requestData['columns'][0]['search']['value']) ){   //name
			$sql.=" AND fechaR LIKE '%".$requestData['columns'][0]['search']['value']."%' ";
		}
		if( !empty($requestData['columns'][1]['search']['value']) ){   //name
			$sql.=" AND id_usuario = '".$requestData['columns'][1]['search']['value']."' ";
		}
		if( !empty($requestData['columns'][2]['search']['value']) ){   //name
			$sql.=" AND tabla = '".$requestData['columns'][2]['search']['value']."' ";
		}
		if( !empty($requestData['columns'][3]['search']['value']) ){   //name
			$sql.=" AND operacion = '".$requestData['columns'][3]['search']['value']."' ";
		}
		
		 



		$query=mysqli_query($conexion, $sql) or die("auditoria_usuarios.php: get employees");
		$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
		/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
		$query=mysqli_query($conexion, $sql) or die("auditoria_usuarios.php: get employees");


		$data = array();
		while( $row=mysqli_fetch_assoc($query) ) {  // preparing an array
			$nestedData=array(); 
			$nestedData[] = $row["fechaR"];
			$nestedData[] = $row["nombre_usuario"];
			 
			$nombre=str_replace("_"," ",$row['tabla']);
			$nombre=str_replace("empleados","tatuadores",$nombre);
			$nestedData[] = ucwords($nombre);
			$nestedData[] = tipoOperacion($row["operacion"]);
			//$nestedData[] = '<input type="button" value="Ver Mas..." onClick="view('.$row["id"].');">  ';
			//$nestedData[] = '<button  type="button" class="btn btn-primary"  onClick="view('.$row["id"].');" >Ver Mas..</button>';
			$nestedData[]="";
			$data[] = $nestedData;
		}



		$json_data = array(
					"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
					"recordsTotal"    => intval( $totalData ),  // total number of records
					"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
					"data"            => $data   // total data array
					);

		echo json_encode($json_data);  // send data as json format

?>
