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
		1 =>'folio',
		2 =>'seccion',
		3 =>'distancia_km',
		4 =>'manzana',
		5 =>'curp',
		6 =>'clave_elector',
		7 =>'nombre_completo',
		8 =>'fecha_nacimiento',
		9 =>'sexo',
		10 =>'whatsapp',
		11 =>'celular',
		12 =>'telefono',
		13 =>'corre_electronico',
		14 =>'direccion_completa',
		15 =>'colonia',
		16 =>'municipio',
		17 =>'localidad',
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
		SELECT
			e.id,
			e.clave,
			e.folio,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
			e.manzana,
			e.distancia_km,
			e.curp,
			e.clave_elector,
			e.nombre_completo,
			e.fecha_nacimiento,
			e.sexo,
			e.whatsapp,
			e.celular,
			e.telefono,
			e.correo_electronico,
			CONCAT(e.calle,' Num Ext: ',e.num_ext,' Num Int: ',IFNULL(e.num_int,'S/N')       ) direccion_completa,
			e.colonia,
			(SELECT m.municipio FROM municipios m WHERE m.id = e.id_municipio) municipio,
			(SELECT l.localidad FROM localidades l WHERE l.id = e.id_localidad ) localidad,
			(SELECT count(do.id) FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id) programas_apoyos
			
		FROM secciones_ine_ciudadanos e 
		WHERE 1=1  "; 
	// getting records as per search parameters

	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}

	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador.=" AND e.clave LIKE '%{$clave}%' ";
	}

	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND e.folio LIKE '%{$folio}%' ";
		$sqlContador.=" AND e.folio LIKE '%{$folio}%' ";
	}

	$clave_elector=$search_database['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador.=" AND e.clave_elector LIKE '%{$clave_elector}%' ";
	}

	$curp=$search_database['curp'];
	if( $curp!="" ){   //name
		$post_search=true;
		$sql.=" AND e.curp LIKE '%{$curp}%' ";
		$sqlContador.=" AND e.curp LIKE '%{$curp}%' ";
	}

	$info_vigente=$search_database['info_vigente'];
	if( $info_vigente!="" ){   //name
		$ano = date("Y");
		$post_search=true;
		if($info_vigente==1){
			$sql.=" AND e.vigencia < '{$ano}' ";
			$sqlContador.=" AND e.vigencia < '{$ano}' ";
		}else{
			$sql.=" AND e.vigencia >= '{$ano}' ";
			$sqlContador.=" AND e.vigencia >= '{$ano}' ";
		}
	}

	$id_tipo_ciudadano=$search_database['id_tipo_ciudadano'];
	if( $id_tipo_ciudadano!="" ){
		$post_search=true;
		$sql.=" AND e.id_tipo_ciudadano IN ($id_tipo_ciudadano) ";
		$sqlContador.=" AND e.id_tipo_ciudadano IN ($id_tipo_ciudadano) ";
	}

	$id_tipo_categoria_ciudadano=$search_database['id_tipo_categoria_ciudadano'];
	if( $id_tipo_categoria_ciudadano!="" ){
		$porciones = explode(",", $id_tipo_categoria_ciudadano);
		if (in_array("0", $porciones)) {
			if(count($porciones)==1){
				$post_search=true;
				//solo muestra 0
				$sql .= " AND NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) ";
				$sqlContador .= " AND NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) ";
			}else{
				$post_search=true;
				/// muestra mas 
				$sql.= " AND ( EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano)) OR NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id )) ";
				$sqlContador.= " AND ( EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano)) OR NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id )) ";
			}
		}else{
			$post_search=true;
			$sql.= " AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano) )";
			$sqlContador.= " AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($id_tipo_categoria_ciudadano) )";
		}
	}

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND e.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND e.nombre LIKE '%{$nombre}%' ";
	} 

	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador .= " AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	} 

	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador .= " AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
	}

	$sexo=$search_database['sexo'];
	if( $sexo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.sexo = '{$sexo}' ";
		$sqlContador.=" AND e.sexo = '{$sexo}' ";
	}

	$fecha_nacimiento_dia=$search_database['fecha_nacimiento_dia'];
	if( $fecha_nacimiento_dia!="" ){   //name
		$post_search=true;
		$sql.=" AND DAY(e.fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
		$sqlContador.=" AND DAY(e.fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
	}

	$fecha_nacimiento_mes=$search_database['fecha_nacimiento_mes'];
	if( $fecha_nacimiento_mes!="" ){   //name
		$post_search=true;
		$sql.=" AND MONTH(e.fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
		$sqlContador.=" AND MONTH(e.fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
	}

	$fecha_nacimiento_edad=$search_database['fecha_nacimiento_edad'];
	if( $fecha_nacimiento_edad!="" ){   //name
		$post_search=true;
		$sql.=" AND TIMESTAMPDIFF(YEAR,e.fecha_nacimiento,CURDATE()) = '{$fecha_nacimiento_edad}' ";
		$sqlContador.=" AND TIMESTAMPDIFF(YEAR,e.fecha_nacimiento,CURDATE()) = '{$fecha_nacimiento_edad}' ";
	}

	$fecha_nacimiento_1=$search_database['fecha_nacimiento_1'];
	$fecha_nacimiento_2=$search_database['fecha_nacimiento_2'];
	if( $fecha_nacimiento_1 != '' && $fecha_nacimiento_2 == ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento <= '{$fecha_nacimiento_1}' ";
		$sqlContador.=" AND e.fecha_nacimiento <= '{$fecha_nacimiento_1}' ";
	}

	if( $fecha_nacimiento_1 == '' && $fecha_nacimiento_2 != ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento >= '{$fecha_nacimiento_2}' ";
		$sqlContador.=" AND e.fecha_nacimiento >= '{$fecha_nacimiento_2}' ";
	}
	if( $fecha_nacimiento_1 != '' && $fecha_nacimiento_2 != ''){ 
		$post_search=true;
		$sql.=" AND e.fecha_nacimiento BETWEEN '{$fecha_nacimiento_1}' AND '{$fecha_nacimiento_2}' ";
		$sqlContador.=" AND e.fecha_nacimiento BETWEEN '{$fecha_nacimiento_1}' AND '{$fecha_nacimiento_2}' ";
	}

	$programas_apoyos=$search_database['programas_apoyos'];
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

	$id_programa_apoyo=$search_database['id_programa_apoyo'];
	if( $id_programa_apoyo!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
		$sqlContador.=" AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$id_programa_apoyo})) ";
	}

	$num_seguimiento=$search_database['num_seguimiento'];
	if( $num_seguimiento!="" ){   //name
		$post_search=true;
		$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) = ".$num_seguimiento;
		$sqlContador.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) = ".$num_seguimiento;
	}

	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){
		$post_search=true;
		$sql.=" AND e.id_municipio IN ($id_municipio) ";
		$sqlContador.=" AND e.id_municipio IN ($id_municipio) ";
	}

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){
		$post_search=true;
		$sql.=" AND e.id_localidad IN ($id_localidad) ";
		$sqlContador.=" AND e.id_localidad IN ($id_localidad) ";
	}

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
	}
	/*
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" OR e.id = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" OR e.id = '{$id_seccion_ine_ciudadano_compartido}' ";
	}
	*/



	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

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
		$nestedData[] = $row["folio"];
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["distancia_km"];
		$nestedData[] = $row["manzana"];
		$nestedData[] = $row["curp"];
		$nestedData[] = $row["clave_elector"];
		$nestedData[] = $row["nombre_completo"];
		$nestedData[] = $row["fecha_nacimiento"];
		$nestedData[] = $row["sexo"];
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$row['whatsapp']."&text&app_absent=0' target='_blank'>".$row['whatsapp']."</a>";
		$nestedData[] = '<a href="tel:'.$row["celular"].'">'.$row["celular"].'</a>'; 
		$nestedData[] = '<a href="tel:'.$row["telefono"].'">'.$row["telefono"].'</a>'; 
		$nestedData[] = $row["correo_electronico"];
		$nestedData[] = $row["direccion_completa"];
		$nestedData[] = $row["colonia"];
		$nestedData[] = $row["municipio"];
		$nestedData[] = $row["localidad"];
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos e WHERE 1 = 1  "; 
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