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
		0 =>'seccion',
		1 =>'seccion',
		2 =>'clave_elector',
		3 =>'curp',
		4 =>'nombre',
		5 =>'apellido_paterno',
		6 =>'apellido_materno',
		7 =>'calle',
		8 =>'num_int',
		9 =>'num_ext',
		10 =>'colonia',
		11 =>'localidad',
		12 =>'codigo_postal',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM lista_nominal WHERE 1 = 1   ";
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND id_distrito_federal ='{$id_distrito_federal}' ";
	} 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				l.clave_elector,
				l.curp,
				(SELECT s.numero FROM secciones_ine s WHERE s.id = l.id_seccion_ine ) seccion,
				l.nombre,
				l.apellido_paterno,
				l.apellido_materno,
				l.fecha_nacimiento,
				l.calle,
				l.num_int,
				l.num_ext,
				colonia,
				(SELECT lc.municipio FROM municipios lc WHERE lc.id = l.id_municipio) municipio,
				(SELECT lc.localidad FROM localidades lc WHERE lc.id = l.id_localidad) localidad,
				l.codigo_postal
			FROM lista_nominal l WHERE 1"; 

	if($tipo_uso_plataforma=='municipio'){
		//$sql.= " AND l.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		//$sql.= " AND l.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		//$sql.= " AND l.id_distrito_federal ='{$id_distrito_federal}' ";
	}


	// getting records as per search parameters
	$clave_elector=$search_database['clave_elector'];
	if( $clave_elector!="" ){   //name
		$post_search=true;
		$sql.=" AND l.clave_elector LIKE '%{$clave_elector}%' ";
		$sqlContador .= " AND clave_elector LIKE '%{$clave_elector}%' ";
	} 

	$curp=$search_database['curp'];
	if( $curp!="" ){   //name
		$post_search=true;
		$sql.=" AND l.curp LIKE '%{$curp}%' ";
		$sqlContador .= " AND curp LIKE '%{$curp}%' ";
	} 

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){   //name
		$post_search=true;
		$sql.=" AND l.id_seccion_ine = '{$id_seccion_ine}' ";
		$sqlContador .= " AND id_seccion_ine = '{$id_seccion_ine}' ";
	} 

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND l.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND nombre LIKE '%{$nombre}%' ";
	}
	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND l.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador .= " AND apellido_paterno LIKE '%{$apellido_paterno}%' ";
	}

	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$post_search=true;
		$sql.=" AND l.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador .= " AND apellido_materno LIKE '%{$apellido_materno}%' ";
	}

	$fecha_nacimiento_dia=$search_database['fecha_nacimiento_dia'];
	if( $fecha_nacimiento_dia!="" ){   //name
		$post_search=true;
		$sql.=" AND DAY(l.fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
		$sqlContador.=" AND DAY(fecha_nacimiento) = '{$fecha_nacimiento_dia}' ";
	}

	$fecha_nacimiento_mes=$search_database['fecha_nacimiento_mes'];
	if( $fecha_nacimiento_mes!="" ){   //name
		$post_search=true;
		$sql.=" AND MONTH(l.fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
		$sqlContador.=" AND MONTH(fecha_nacimiento) = '{$fecha_nacimiento_mes}' ";
	}

	$fecha_nacimiento_ano=$search_database['fecha_nacimiento_ano'];
	if( $fecha_nacimiento_ano!="" ){   //name
		$post_search=true;
		$sql.=" AND YEAR(l.fecha_nacimiento) = '{$fecha_nacimiento_ano}' ";
		$sqlContador.=" AND YEAR(fecha_nacimiento) = '{$fecha_nacimiento_ano}' ";
	}


	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];


	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','lista_nominal',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["seccion"]; 
		$boton_asignar = '<button class="btn btn-info bt_responsive"  onClick="asignarClaveElector(%asginar_clave_elector%);" >Asignar</button>';
		$valor_asignar ="'{$row["clave_elector"]}'";
		$nestedData[] = str_replace("%asginar_clave_elector%",$valor_asignar, $boton_asignar);
		$nestedData[] = '<input type="text" value="'.$row["clave_elector"].'" >'; 
		$nestedData[] = '<input type="text" value="'.$row["curp"].'">'; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["apellido_paterno"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["apellido_materno"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["fecha_nacimiento"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["calle"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["num_int"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["num_ext"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["colonia"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["municipio"]."<div>"; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["localidad"]."<div>"; 
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
		$sqlContadorScript = "SELECT count(*) total FROM lista_nominal WHERE 1 = 1   "; 
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
