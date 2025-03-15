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
		0 =>'id',
		1 =>'clave_nivel_puesto',
		2 =>'tipo_integrante_sujeto_obligado',
		3 =>'area_adscripcion',
		4 =>'denominacion_puesto',
		5 =>'denominacion_cargo',
		6 =>'nombre',
		7 =>'primer_apellido',
		8 =>'segundo_apellido',
		9 =>'sexo',
		10 =>'monto_remuneracion_mensual_bruta',
		11 =>'monto_remuneracion_mensual_neta',
		12 =>'areas_responsables',
		13 =>'nota',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM nomina WHERE 1 = 1   ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
				id,
				clave_nivel_puesto,
				tipo_integrante_sujeto_obligado,
				area_adscripcion,
				denominacion_puesto,
				denominacion_cargo,
				nombre,
				primer_apellido,
				segundo_apellido,
				sexo,
				monto_remuneracion_mensual_bruta,
				monto_remuneracion_mensual_neta,
				areas_responsables,
				nota 
			FROM nomina WHERE 1 = 1  "; 
	// getting records as per search parameters
	

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND nombre LIKE '%{$nombre}%' ";
	}

	$primer_apellido=$search_database['primer_apellido'];
	if( $primer_apellido!="" ){   //name
		$post_search=true;
		$sql.=" AND primer_apellido LIKE '%{$primer_apellido}%' ";
		$sqlContador .= " AND primer_apellido LIKE '%{$primer_apellido}%' ";
	} 

	$segundo_apellido=$search_database['segundo_apellido'];
	if( $segundo_apellido!="" ){   //name
		$post_search=true;
		$sql.=" AND segundo_apellido LIKE '%{$segundo_apellido}%' ";
		$sqlContador .= " AND segundo_apellido LIKE '%{$segundo_apellido}%' ";
	} 

	$tipo_integrante_sujeto_obligado=$search_database['tipo_integrante_sujeto_obligado'];
	if( $tipo_integrante_sujeto_obligado!="" ){   //name
		$post_search=true;
		$sql.=" AND tipo_integrante_sujeto_obligado = '{$tipo_integrante_sujeto_obligado}' ";
		$sqlContador .= " AND tipo_integrante_sujeto_obligado = '{$tipo_integrante_sujeto_obligado}' ";
	}

	$denominacion_puesto=$search_database['denominacion_puesto'];
	if( $denominacion_puesto!="" ){   //name
		$post_search=true;
		$sql.=" AND denominacion_puesto = '{$denominacion_puesto}' ";
		$sqlContador .= " AND denominacion_puesto = '{$denominacion_puesto}' ";
	}
	
	$area_adscripcion=$search_database['area_adscripcion'];
	if( $area_adscripcion!="" ){   //name
		$post_search=true;
		$sql.=" AND area_adscripcion = '{$area_adscripcion}' ";
		$sqlContador .= " AND area_adscripcion = '{$area_adscripcion}' ";
	}

	$areas_responsables=$search_database['areas_responsables'];
	if( $areas_responsables!="" ){   //name
		$post_search=true;
		$sql.=" AND areas_responsables = '{$areas_responsables}' ";
		$sqlContador .= " AND areas_responsables = '{$areas_responsables}' ";
	}

	
	
	

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','nomina',$_COOKIE["id_usuario"]);
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
			if($value == 'monto_remuneracion_mensual_bruta'){
				$nestedData[] = number_format($row[$value],2,'.',','); 	
			}elseif ($value == 'monto_remuneracion_mensual_neta') {
				$nestedData[] = number_format($row[$value],2,'.',','); 	
			}else{
				$nestedData[] = $row[$value]; 
			}
		}
		
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre"]."<div>"; 
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

		$nestedData[] =  "<div class='opciones_botones_2'>{$edit}{$delete}{$select}</div>";
		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM nomina WHERE 1 = 1   "; 
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
