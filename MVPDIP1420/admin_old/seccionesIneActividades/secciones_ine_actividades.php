<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$columns = $_SESSION['reporte_Sistema']['columnas_sql'];

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
	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND sia.clave LIKE '%{$clave}%' ";
	} 

	$folio=$_SESSION['searchTable']['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND sia.folio LIKE '%{$folio}%' ";
	}

	$cedula=$_SESSION['searchTable']['cedula'];
	if( $cedula!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.cedula LIKE '%{$cedula}%' ";
		$sqlContador .= " AND sia.cedula LIKE '%{$cedula}%' ";
	}
	$numero_contrato=$_SESSION['searchTable']['numero_contrato'];
	if( $numero_contrato!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.numero_contrato LIKE '%{$numero_contrato}%' ";
		$sqlContador .= " AND sia.numero_contrato LIKE '%{$numero_contrato}%' ";
	}

	$nombre=$_SESSION['searchTable']['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND sia.nombre LIKE '%{$nombre}%' ";
	} 

	$id_seccion_ine=$_SESSION['searchTable']['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND sia.id_seccion_ine IN ($id_seccion_ine) ";
	}

	$tipo=$_SESSION['searchTable']['tipo'];
	$porciones = explode(",", $tipo);
	$values_pdo = "'".implode("','", $porciones)."'";
	if( $tipo!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.tipo IN ($values_pdo) ";
		$sqlContador.=" AND sia.tipo IN ($values_pdo) ";
	}


	$id_municipio=$_SESSION['searchTable']['id_municipio'];
	if( $id_municipio!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_municipio IN ({$id_municipio}) ";
		$sqlContador.=" AND sia.id_municipio IN ({$id_municipio}) ";
	}

	$id_localidad=$_SESSION['searchTable']['id_localidad'];
	if( $id_localidad!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_localidad IN ({$id_localidad}) ";
		$sqlContador.=" AND sia.id_localidad IN ({$id_localidad}) ";
	}

	$id_distrito_local=$_SESSION['searchTable']['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		$post_search=true;
		$sql.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
		$sqlContador.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
	}

	$id_distrito_federal=$_SESSION['searchTable']['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		$post_search=true;
		$sql.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
		$sqlContador.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
	}

	$id_tipo_infraestructura=$_SESSION['searchTable']['id_tipo_infraestructura'];
	if( $id_tipo_infraestructura!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_tipo_infraestructura IN ({$id_tipo_infraestructura}) ";
		$sqlContador.=" AND sia.id_tipo_infraestructura IN ({$id_tipo_infraestructura}) ";
	}

	$sql.=" ORDER BY sia.". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	$_SESSION['reporte_Sistema']['sql'] = $sql;

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
		foreach ($_SESSION['reporte_Sistema']['columnas_sql'] as $key => $value) {
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
