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
	$id_directorio = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave',
		1 =>'folio',
		2 =>'tipo_equipo',
		3 =>'ubicacion',
		4 =>'procesador',
		5 =>'ram'
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM equipos e WHERE 1 ";
	if($id_directorio!=''){
		$sql.= " AND NOT EXISTS (SELECT * FROM equipos_directorios ed WHERE e.id = ed.id_equipo AND ed.status = 1 AND ed.id_directorio = '{$id_directorio}' ) ";
		$sqlContador.= " AND NOT EXISTS (SELECT * FROM equipos_directorios ed WHERE e.id = ed.id_equipo AND ed.status = 1 AND ed.id_directorio = '{$id_directorio}' ) ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				e.id,
				e.clave,
				(SELECT u.nombre FROM ubicaciones u WHERE u.id = e.id_ubicacion) ubicacion,
				(SELECT te.nombre FROM tipos_equipos te WHERE te.id = e.id_tipo_equipo) tipo_equipo,
				(SELECT ed.id FROM equipos_directorios ed WHERE ed.id_equipo = e.id AND ed.id_directorio = '{$id_directorio}' AND ed.status = 1 ORDER BY id DESC LIMIT 1 ) id_equipo_directorio,
				e.folio,
				e.marca,
				e.modelo,
				e.ram,
				e.procesador
			FROM equipos e 
			WHERE 1 = 1"; 
	$sql.= " AND NOT EXISTS (SELECT * FROM equipos_directorios ed WHERE e.id = ed.id_equipo AND ed.status = 1 AND ed.id_directorio = '{$id_directorio}' ) ";
	



	// getting records as per search parameters
	$id_sistema_operativo=$search_database['id_sistema_operativo'];
	if( $id_sistema_operativo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_sistema_operativo = '{$id_sistema_operativo}' ";
		$sqlContador .= " AND e.id_sistema_operativo = '{$id_sistema_operativo}' ";
	}

	$id_software=$search_database['id_software'];
	if( $id_software!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_software = '{$id_software}' ";
		$sqlContador .= " AND e.id_software = '{$id_software}' ";
	}
	
	$id_tipo_equipo=$search_database['id_tipo_equipo'];
	if( $id_tipo_equipo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_tipo_equipo = '{$id_tipo_equipo}' ";
		$sqlContador .= " AND e.id_tipo_equipo = '{$id_tipo_equipo}' ";
	}

	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave = '%{$clave}%' ";
	}

	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND e.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND e.folio = '%{$folio}%' ";
	}

	$procesador=$search_database['procesador'];
	if( $procesador!="" ){   //name
		$post_search=true;
		$sql.=" AND e.procesador LIKE '%{$procesador}%' ";
		$sqlContador .= " AND e.procesador = '%{$procesador}%' ";
	}

	$ram=$search_database['ram'];
	if( $ram!="" ){   //name
		$post_search=true;
		$sql.=" AND e.ram LIKE '%{$ram}%' ";
		$sqlContador .= " AND e.ram = '%{$ram}%' ";
	}

	$modelo=$search_database['modelo'];
	if( $modelo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.modelo LIKE '%{$modelo}%' ";
		$sqlContador .= " AND e.modelo = '%{$modelo}%' ";
	}

	$marca=$search_database['marca'];
	if( $marca!="" ){   //name
		$post_search=true;
		$sql.=" AND e.marca LIKE '%{$marca}%' ";
		$sqlContador .= " AND e.marca = '%{$marca}%' ";
	}


	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];


	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','lista_nominal',$_COOKIE["id_usuario"]);
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
		$boton_asignar = '<button class="btn btn-info bt_responsive"  onClick="asignarFolio(%asginar_folio%);" >Asignar</button>';
		/*$row["clave_elector"] = $row["clave_elector"]."Z";*/
		$valor_asignar = "'{$row["folio"]},{$row["clave"]}'";
		$nestedData[] = str_replace("%asginar_folio%",$valor_asignar, $boton_asignar);
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["folio"]; 
		$nestedData[] = $row["ubicacion"];
		$nestedData[] = $row["tipo_equipo"];
		$nestedData[] = $row["marca"];
		$nestedData[] = $row["modelo"];
		$nestedData[] = $row["ram"];
		$nestedData[] = $row["procesador"];
        
        
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

		//$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
        $sqlContadorScript = "SELECT count(*) total FROM equipos e WHERE 1  ";
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
