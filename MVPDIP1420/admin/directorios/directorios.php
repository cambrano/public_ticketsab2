<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$modulosPermiso = modulosPermiso('operatividad','',$_COOKIE["id_usuario"]);

	if($modulosPermiso['equipos'] || $modulosPermiso['all'] ){
		$modulo_equipo = true;
	}
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave',
		1 =>'dependencia',
		2 =>'sub_dependencia',
		3 =>'area',
		4 =>'puesto',
		5 =>'apellido_paterno',
		6 =>'apellido_materno',
		7 =>'nombre',
		8 =>'correo_electronico',
		9 =>'whatsapp',
		10 =>'telefono',
		11 =>'telefono_ext',
		12 =>'celular',
		13 =>'fecha_nacimiento',
		14 =>'sexo',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM directorios d WHERE 1 = 1   ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				d.id,
				d.clave,
				(SELECT dp.nombre FROM dependencias dp WHERE dp.id = d.id_dependencia) dependencia,
				(SELECT sdp.nombre FROM sub_dependencias sdp WHERE sdp.id = d.id_sub_dependencia) sub_dependencia,
				d.area,
				d.puesto,
				d.apellido_paterno,
				d.apellido_materno,
				d.nombre,
				d.correo_electronico,
				d.whatsapp,
				d.telefono,
				d.telefono_ext,
				d.celular,
				d.fecha_nacimiento,
				(SELECT sx.nombre FROM sexos sx WHERE sx.id = d.id_sexo) sexo
			FROM directorios d WHERE 1 = 1  "; 
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND d.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND d.clave LIKE '%{$clave}%' ";
	} 

	$id_dependencia=$search_database['id_dependencia'];
	if( $id_dependencia!="" ){   //name
		$post_search=true;
		$sql.=" AND id_dependencia = '{$id_dependencia}' ";
		$sqlContador .= " AND id_dependencia = '{$id_dependencia}' ";
	}

	$id_sub_dependencia=$search_database['id_sub_dependencia'];
	if( $id_sub_dependencia!="" ){   //name
		$post_search=true;
		$sql.=" AND id_sub_dependencia = '{$id_sub_dependencia}' ";
		$sqlContador .= " AND id_sub_dependencia = '{$id_sub_dependencia}' ";
	}

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND d.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND d.nombre LIKE '%{$nombre}%' ";
	} 

	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND d.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador .= " AND d.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	} 

	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND d.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador .= " AND d.apellido_materno LIKE '%{$apellido_materno}%' ";
	} 

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND d.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND d.nombre LIKE '%{$nombre}%' ";
	} 
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','directorios',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["dependencia"]; 
		$nestedData[] = $row["sub_dependencia"]; 
		$nestedData[] = $row["area"]; 
		$nestedData[] = $row["puesto"]; 
		$nestedData[] = $row["apellido_paterno"]; 
		$nestedData[] = $row["apellido_materno"]; 
		$nestedData[] = $row["nombre"]; 
		$nestedData[] = $row["correo_electronico"]; 
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$row['whatsapp']."&text&app_absent=0' target='_blank'>".$row['whatsapp']."</a>";
		$nestedData[] = $row["telefono"]; 
		$nestedData[] = $row["telefono_ext"]; 
		$nestedData[] = $row["celular"]; 
		$nestedData[] = $row["fecha_nacimiento"]; 
		$nestedData[] = $row["sexo"]; 
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
		if($modulo_equipo){
			$directorio_equipos='<button class="btn btn-primary bt_responsive"  onClick="equipos_directorios('.$row["id"].');" >Equipos</button>';
		}
		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";

		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$directorio_equipos}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM directorios d WHERE 1 = 1   "; 
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
