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
		0 =>'clave',
		1 =>'folio',
		2 =>'nombre',
		3 =>'numero_contrato',
		4 =>'observaciones',
		5 =>'cedula',
		6 =>'empresa_adjudicada',
		7 =>'supervisor',
		8 =>'fecha_inicio',
		9 =>'fecha_final',
		10 =>'monto_total',
		11 =>'beneficiarios',
		12 =>'meta_cantidad',
		13 =>'unidad_completa',
		14 =>'tipo',
		15 =>'tipo_infraestructura',
		16 =>'municipio',
		17 =>'localidad',
		18 =>'colonia',
		19 =>'seccion',
		20 =>'distrito_local',
		21 =>'distrito_federal',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_actividades sia LEFT JOIN secciones_ine si ON si.id = sia.id_seccion_ine WHERE 1 = 1   "; 
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND sia.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND si.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND si.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "
		SELECT
			sia.id,
			sia.clave,
			sia.folio,
			sia.nombre,
			sia.numero_contrato,
			sia.observaciones,
			sia.cedula,
			(SELECT ea.nombre FROM empresas_adjudicadas ea WHERE ea.id = sia.id_empresa_adjudicada ) empresa_adjudicada,
			(SELECT s.nombre FROM supervisores s WHERE s.id = sia.id_supervisor ) supervisor,
			sia.fecha_inicio,
			sia.fecha_final,
			sia.monto_total,
			sia.beneficiarios,
			sia.meta_cantidad,
			um.unidad_completa,
			sia.tipo,
			(SELECT ti.nombre FROM tipos_infraestructuras ti WHERE ti.id = sia.id_tipo_infraestructura) tipo_infraestructura,
			m.municipio,
			(SELECT l.localidad FROM localidades l WHERE l.id = sia.id_localidad ) localidad,
			sia.colonia,
			si.numero seccion,
			dl.numero distrito_local,
			df.numero distrito_federal

		FROM secciones_ine_actividades sia
		LEFT JOIN secciones_ine si on sia.id_seccion_ine = si.id
		LEFT JOIN distritos_locales dl on si.id_distrito_local = dl.id
		LEFT JOIN distritos_federales df on si.id_distrito_federal = df.id
		LEFT JOIN municipios m on sia.id_municipio = m.id
		LEFT JOIN unidades_medidas um ON sia.id_unidad_medida = um.id
		WHERE m.id_estado='{$id_estado}' ";
	// getting records as per search parameters
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND sia.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND si.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND si.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND sia.clave LIKE '%{$clave}%' ";
	} 

	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND sia.folio LIKE '%{$folio}%' ";
	}

	$cedula=$search_database['cedula'];
	if( $cedula!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.cedula LIKE '%{$cedula}%' ";
		$sqlContador .= " AND sia.cedula LIKE '%{$cedula}%' ";
	}
	$numero_contrato=$search_database['numero_contrato'];
	if( $numero_contrato!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.numero_contrato LIKE '%{$numero_contrato}%' ";
		$sqlContador .= " AND sia.numero_contrato LIKE '%{$numero_contrato}%' ";
	}

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND sia.nombre LIKE '%{$nombre}%' ";
	} 

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
	}

	$tipo=$search_database['tipo'];
	$tipo = str_replace('\\', '', $tipo);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.tipo IN ($tipo) ";
		$sqlContador.=" AND sia.tipo IN ($tipo) ";
	}


	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_municipio IN ({$id_municipio}) ";
		$sqlContador.=" AND sia.id_municipio IN ({$id_municipio}) ";
	}

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_localidad IN ({$id_localidad}) ";
		$sqlContador.=" AND sia.id_localidad IN ({$id_localidad}) ";
	}

	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		$post_search=true;
		$sql.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
		$sqlContador.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
	}

	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		$post_search=true;
		$sql.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
		$sqlContador.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
	}

	$id_tipo_infraestructura=$search_database['id_tipo_infraestructura'];
	if( $id_tipo_infraestructura!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_tipo_infraestructura IN ({$id_tipo_infraestructura}) ";
		$sqlContador.=" AND sia.id_tipo_infraestructura IN ({$id_tipo_infraestructura}) ";
	}
	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_actividades',$_COOKIE["id_usuario"]);
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
		foreach ($columns as $key => $value) {
			if($value == 'monto_total'){
				$nestedData[] = number_format($row[$value],2,".",",");
			}elseif($value == 'beneficiarios'){
				$nestedData[] = number_format($row[$value],0,"",",");
			}elseif($value == 'meta_cantidad'){
				$nestedData[] = number_format($row[$value],0,"",",");
			}else{
				$nestedData[] = $row[$value];
			}
		}
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
		$google_maps='<a href="https://www.google.com/maps?q='.$row["latitud"].','.$row["longitud"].'" target="_blank"><button class="btn btn-info bt_responsive" >
						<span class="btnImage"><img class="bntImageSize" src="img/Google_Maps_Logo_2020.png"></span>
						<span class="btnText">GoogleMaps</span></button></a>';
		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$google_maps}{$delete}{$select}</div>";

		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_actividades sia LEFT JOIN secciones_ine si ON sia.id_seccion_ine = si.id WHERE 1 = 1   "; 
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
