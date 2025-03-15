<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/usuarios.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
	$id_seccion_ine_ciudadano_compartido = $usuarioDatos['id_seccion_ine_ciudadano'];
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave', 
		1 =>'seccion',
		2 =>'nombre_completo',
		3 =>'sexo',
		4 =>'whatsapp',
		5 =>'celular',
		6 =>'calle',
		//2 =>'status',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos WHERE 1 = 1  ";
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$sql.=" AND id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total'];////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "
		SELECT *,
			e.nombre_completo,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion
		FROM secciones_ine_ciudadanos e 
		WHERE 1=1  "; 
	// getting records as per search parameters

	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}

	$clave_elector=$_SESSION['searchTable']['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
	}

	$sexo=$_SESSION['searchTable']['sexo'];
	if( $sexo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.sexo = '{$sexo}' ";
		$sqlContador.=" AND e.sexo = '{$sexo}' ";
	}

	$nombre_completo=$_SESSION['searchTable']['nombre_completo'];
	if( $nombre_completo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.nombre_completo LIKE '%{$nombre_completo}%' ";
		$sqlContador.=" AND e.nombre_completo LIKE '%{$nombre_completo}%' ";
	}


	/*
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" OR e.id = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" OR e.id = '{$id_seccion_ine_ciudadano_compartido}' ";
	}
	*/

	$programas_apoyos=$_SESSION['searchTable']['programas_apoyos'];
	if( $programas_apoyos!="" ){   //name
		$post_search=true;
		if($programas_apoyos==1){
			$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
		}else{
			$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
			$sqlContador.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
		}
	}

	$id_programa_apoyo=$_SESSION['searchTable']['id_programa_apoyo'];
	if( $id_programa_apoyo!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
		$sqlContador.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
	}

	$id_seccion_ine=$_SESSION['searchTable']['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
	}



	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";


	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['registro']){
		$option_delete = true;
		$option_edit = true;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["seccion"];
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo"]."<div>";
		$nestedData[] = $row["sexo"];
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$row['whatsapp']."&text&app_absent=0' target='_blank'>".$row['whatsapp']."</a>";
		$nestedData[] = '<a href="tel:'.$row["celular"].'">'.$row["celular"].'</a>'; 
		$nestedData[] = $row["calle"].','.$row["colonia"];
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
		
		if($switch_operacionesPermisos['evaluacion']){
			$ciudadano_categoria='<button class="btn btn-primary bt_responsive"  onClick="ciudadano_categoria('.$row["id"].');" >Categoria</button>';
			$modulo_encuestas='<button class="btn btn-primary bt_responsive"  onClick="encuestas('.$row["id"].');" >Encuestas</button>';
			$ciudadano_seguimiento='<button class="btn btn-primary bt_responsive"  onClick="seguimientos('.$row["id"].');" >Seguimientos</button>';
			if($value['programas_apoyos'] > 0){
				$ciudadano_programas_apoyos='<button class="btn btn-primary bt_responsive"  onClick="programas_apoyos('.$row["id"].');" >Con Programa Apoyo</button>';
			}else{
				$ciudadano_programas_apoyos='<button class="btn btn-warning bt_responsive"  onClick="programas_apoyos('.$row["id"].');" >Sin Programa Apoyo</button>';
			}

		}
		$nestedData[] =  "<div class='opciones_botones'>{$edit}{$ciudadano_categoria}{$modulo_encuestas}{$ciudadano_seguimiento}{$ciudadano_programas_apoyos}{$delete}{$select}</div>";

		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos e WHERE 1=1  "; 
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