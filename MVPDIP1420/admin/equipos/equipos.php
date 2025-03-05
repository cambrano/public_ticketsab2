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
		1 =>'folio',
		2 =>'ubicacion',
		3 =>'tipo_equipo',
		4 =>'dependencia',
		5 =>'sub_dependencia',
		6 =>'puesto',
		7 =>'nombre_directorio',
		8 =>'marca',
		9 =>'modelo',
		10 =>'ram',
		11 =>'procesador',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM equipos e WHERE 1 = 1   ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				e.id,
				e.clave,
				u.nombre AS ubicacion,
				te.nombre AS tipo_equipo,
				ed.id AS id_equipo_directorio,
				CONCAT(d.nombre, ' ', d.apellido_paterno, ' ', d.apellido_materno) AS nombre_directorio,
				(SELECT dp.nombre FROM dependencias dp WHERE dp.id = d.id_dependencia ) dependencia,
				(SELECT dp.nombre FROM sub_dependencias dp WHERE dp.id = d.id_sub_dependencia ) sub_dependencia,
				d.id_dependencia,
				d.id_sub_dependencia,
				d.puesto,
				d.whatsapp,
				e.folio,
				e.marca,
				e.modelo,
				e.ram,
				e.procesador
			FROM equipos e
			LEFT JOIN ubicaciones u ON u.id = e.id_ubicacion
			LEFT JOIN tipos_equipos te ON te.id = e.id_tipo_equipo
			LEFT JOIN equipos_directorios ed ON ed.id_equipo = e.id 
				AND ed.status = 1
			LEFT JOIN directorios d ON ed.id_directorio = d.id
			WHERE 1 = 1 
			
		"; 
	// getting records as per search parameters
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	}
	
	$id_tipo_equipo=$search_database['id_tipo_equipo'];
	if( $id_tipo_equipo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_tipo_equipo = '{$id_tipo_equipo}' ";
		$sqlContador .= " AND e.id_tipo_equipo = '{$id_tipo_equipo}' ";
	}

	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND e.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND e.folio LIKE '%{$folio}%' ";
	}

	$procesador=$search_database['procesador'];
	if( $procesador!="" ){   //name
		$post_search=true;
		$sql.=" AND e.procesador LIKE '%{$procesador}%' ";
		$sqlContador .= " AND e.procesador LIKE '%{$procesador}%' ";
	}

	$ram=$search_database['ram'];
	if( $ram!="" ){   //name
		$post_search=true;
		$sql.=" AND e.ram LIKE '%{$ram}%' ";
		$sqlContador .= " AND e.ram LIKE '%{$ram}%' ";
	}

	$modelo=$search_database['modelo'];
	if( $modelo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.modelo LIKE '%{$modelo}%' ";
		$sqlContador .= " AND e.modelo LIKE '%{$modelo}%' ";
	}

	$marca=$search_database['marca'];
	if( $marca!="" ){   //name
		$post_search=true;
		$sql.=" AND e.marca LIKE '%{$marca}%' ";
		$sqlContador .= " AND e.marca LIKE '%{$marca}%' ";
	}

	
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','equipos',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["folio"]; 
		$nestedData[] = $row["ubicacion"]; 
		$nestedData[] = $row["tipo_equipo"]; 
		$nestedData[] = $row["dependencia"]; 
		$nestedData[] = $row["sub_dependencia"]; 
		$nestedData[] = $row["puesto"]; 
		$nestedData[] = $row["nombre_directorio"]; 
		$nestedData[] = $row["marca"]; 
		$nestedData[] = $row["modelo"]; 
		$nestedData[] = $row["procesador"]; 
		$nestedData[] = $row["ram"]; 
		if($option_delete){
			$delete1='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
		}
		if($option_delete){
			if($row["id_equipo_directorio"]==""){
				$delete='<button class="btn btn-danger bt_responsive"disabled>Eliminar Asignacion Equipos</button>';
			}else{
				$delete='<button class="btn btn-danger bt_responsive" onClick="borrar('.$row["id_equipo_directorio"].');" >Eliminar Asignacion Equipos</button>';
			}
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
		$sqlContadorScript = "SELECT count(*) total FROM equipos e WHERE 1 = 1   "; 
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
