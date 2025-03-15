<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/timemex.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'seccion',
		1 =>'distrito_local',
		2 =>'distrito_federal',
		3 =>'seccion',
		4 =>'militante_registro',
		/*2 =>'militante_registro',*/
		5 =>'clave_elector',
		6 =>'curp',
		7 =>'nombre',
		8 =>'apellido_paterno',
		9 =>'apellido_materno',
		10 =>'fecha_nacimiento',
		11 =>'calle',
		12 =>'num_int',
		13 =>'num_ext',
		14 =>'colonia',
		15 =>'municipio',
		16 =>'localidad',
		17 =>'gps',
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
				(SELECT s.numero FROM secciones_ine s WHERE s.id = l.id_seccion_ine ) seccion,
				(SELECT dl.clave FROM distritos_locales dl WHERE dl.id = l.id_distrito_local ) distrito_local,
				(SELECT df.clave FROM distritos_federales df WHERE df.id = l.id_distrito_federal ) distrito_federal,
				l.militante_partido,
				l.militante_registro,
				l.clave_elector,
				l.curp,
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
				CONCAT(l.latitud,',',l.longitud) gps
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
		$sqlContador .= " AND l.clave_elector LIKE '%{$clave_elector}%' ";
	} 

	$curp=$search_database['curp'];
	if( $curp!="" ){   //name
		$post_search=true;
		$sql.=" AND l.curp LIKE '%{$curp}%' ";
		$sqlContador .= " AND l.curp LIKE '%{$curp}%' ";
	} 

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){   //name
		$post_search=true;
		$sql.=" AND l.id_seccion_ine = '{$id_seccion_ine}' ";
		$sqlContador .= " AND l.id_seccion_ine = '{$id_seccion_ine}' ";
	} 
	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){   //name
		$post_search=true;
		$sql.=" AND l.id_municipio = '{$id_municipio}' ";
		$sqlContador .= " AND l.id_municipio = '{$id_municipio}' ";
	} 

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){   //name
		$post_search=true;
		$sql.=" AND l.id_localidad = '{$id_localidad}' ";
		$sqlContador .= " AND l.id_localidad = '{$id_localidad}' ";
	} 

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND l.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND l.nombre LIKE '%{$nombre}%' ";
	}
	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND l.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador .= " AND l.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	}

	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$post_search=true;
		$sql.=" AND l.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador .= " AND l.apellido_materno LIKE '%{$apellido_materno}%' ";
	}
	$militante_partido=$search_database['militante_partido'];
	if( $militante_partido!="" ){   //name
		$post_search=true;
		$sql.=" AND l.militante_partido LIKE '%{$militante_partido}%' ";
		$sqlContador .= " AND l.militante_partido LIKE '%{$militante_partido}%' ";
	}
	$padrones_especificos=$search_database['padrones_especificos'];
	if( $padrones_especificos=="1" ){   //name
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT p.id_lista_nominal FROM padron_bienestar_65_03_a_04_2023 p WHERE p.id_lista_nominal = l.id)";
		$sqlContador.= " AND EXISTS ( SELECT p.id_lista_nominal FROM padron_bienestar_65_03_a_04_2023 p WHERE p.id_lista_nominal = l.id)";
	}
	if( $padrones_especificos=="2" ){   //name
		$post_search=true;
		$sql.= " AND EXISTS ( SELECT p.id_lista_nominal FROM padron_bienestar_B_J_01_a_02_2023 p WHERE p.id_lista_nominal = l.id)";
		$sqlContador.= " AND EXISTS ( SELECT p.id_lista_nominal FROM padron_bienestar_B_J_01_a_02_2023 p WHERE p.id_lista_nominal = l.id)";
	}
	$tipo_ciudadano=$search_database['tipo_ciudadano'];
	if( $tipo_ciudadano!="" ){   //name
		$estado_abrev = str_pad($id_estado, 2, "0", STR_PAD_LEFT);
		if($tipo_ciudadano==1){
			$post_search=true;
			$sql.=" AND SUBSTRING(l.clave_elector, 13, 2) = '{$estado_abrev}' ";
			$sqlContador.=" AND SUBSTRING(l.clave_elector, 13, 2) = '{$estado_abrev}' ";
		}
		if($tipo_ciudadano==2){
			$post_search=true;
			$sql.=" AND SUBSTRING(l.clave_elector, 13, 2) != '{$estado_abrev}' ";
			$sqlContador.=" AND SUBSTRING(l.clave_elector, 13, 2) != '{$estado_abrev}' ";
		}
	}
	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		$post_search=true;
		$sql.=" AND l.id_distrito_local = '{$id_distrito_local}' ";
		$sqlContador.=" AND l.id_distrito_local = '{$id_distrito_local}' ";
	}

	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		$post_search=true;
		$sql.=" AND l.id_distrito_federal = '{$id_distrito_local}' ";
		$sqlContador.=" AND l.id_distrito_federal = '{$id_distrito_local}' ";
	}

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];


	if($requestData['length']>0){
		$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	}
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
		if($row["militante_partido"] != ''){
			$nestedData[] = $row["militante_partido"]."<br>".fechaNormalSimpleDDMMAA_ES($row["militante_registro"]);
		}else{
			$nestedData[] = "-";
		}
		//$nestedData[] = '<input type="text" value="'.$row["clave_elector"].'" >'; 
		//$nestedData[] = '<input type="text" value="'.$row["curp"].'">'; 
		$nestedData[] = $row["clave_elector"]; 
		$nestedData[] = $row["curp"]; 
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
		$nestedData[] = $row["distrito_local"]; 
		$nestedData[] = $row["distrito_federal"]; 
		$nestedData[] = '<a href="https://www.google.com/maps?q='.$row["gps"].'" target="_blank"><button class="btn btn-info bt_responsive" >
						<span class="btnImage"><img class="bntImageSize" src="img/Google_Maps_Logo_2020.png"></span>
						<span class="btnText">GoogleMaps</span></button></a>';


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
		$sqlContadorScript = "SELECT count(*) total FROM lista_nominal l WHERE 1 = 1   "; 
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
