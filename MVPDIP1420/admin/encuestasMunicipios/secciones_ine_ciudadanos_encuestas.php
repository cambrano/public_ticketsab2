<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/usuario_permisos.php";
	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/cuestionarios.php";
	include __DIR__."/../functions/cuestionarios_respuestas.php";
	include __DIR__."/../functions/tool_xhpzab.php";
	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'fecha_hora',
		1 =>'clave',
		2 =>'municipio',
		3 =>'seccion',
		4 =>'nombre_completo',
		5 =>'sexo',
		6 =>'edad',
		7 =>'nombre_completo_compartido',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos_encuestas e WHERE 1 = 1   "; 
	$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if( $id_encuesta != "" ){   //name
		$post_search=true;
		$sql.=" AND e.id_encuesta = '{$id_encuesta}' ";
		$sqlContador .= " AND e.id_encuesta = '{$id_encuesta}' ";
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
				e.fecha_hora,
				(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id = e.id_seccion_ine_ciudadano) nombre_completo,
				IF(e.sexo=1,'Hombre','Mujer') sexo,
				e.edad,
				(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id = e.id_seccion_ine_ciudadano_compartido) nombre_completo_compartido,
				(SELECT s.numero FROM secciones_ine s WHERE s.id= e.id_seccion_ine) seccion,
				(SELECT m.municipio FROM municipios m WHERE m.id = e.id_municipio) municipio
			FROM secciones_ine_ciudadanos_encuestas e 
			WHERE 1 = 1  "; 
	// getting records as per search parameters
	if( $id_encuesta != "" ){   //name
		$post_search=true;
		$sql.=" AND e.id_encuesta = '{$id_encuesta}' ";
		$sqlContador .= " AND e.id_encuesta = '{$id_encuesta}' ";

	}

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
	}

	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	} 

	$sexo=$search_database['sexo'];
	if( $sexo!="" ){   //name
		if($sexo=='Hombre'){
			$sexo=1;
		}else{
			$sexo=2;
		}
		$post_search=true;
		$sql.=" AND e.sexo = '{$sexo}' ";
		$sqlContador .= " AND e.sexo = '{$sexo}' ";
	} 

	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){
		$post_search=true;
		$sql.=" AND e.id_municipio IN ($id_municipio) ";
		$sqlContador.=" AND e.id_municipio IN ($id_municipio) ";
	}
	
	$edad=$search_database['edad'];
	switch ($edad) {
		case '1':
			$sql.=" AND e.edad = 18 ";
			$sqlContador .= " AND e.edad = 18 ";
			break;
		case '2':
			$sql.=" AND e.edad = 19 ";
			$sqlContador .= " AND e.edad = 19 ";
			break;
		case '3':
			$sql.=" AND e.edad BETWEEN 20 AND 24 ";
			$sqlContador.=" AND e.edad BETWEEN 20 AND 24 ";
			break;
		case '4':
			$sql.=" AND e.edad BETWEEN 25 AND 29 ";
			$sqlContador.=" AND e.edad BETWEEN 25 AND 29 ";
			break;
		case '5':
			$sql.=" AND e.edad BETWEEN 30 AND 34 ";
			$sqlContador.=" AND e.edad BETWEEN 30 AND 34 ";
			break;
		case '6':
			$sql.=" AND e.edad BETWEEN 35 AND 39 ";
			$sqlContador.=" AND e.edad BETWEEN 35 AND 39 ";
			break;
		case '7':
			$sql.=" AND e.edad BETWEEN 40 AND 44 ";
			$sqlContador.=" AND e.edad BETWEEN 40 AND 44 ";
			break;
		case '8':
			$sql.=" AND e.edad BETWEEN 45 AND 49 ";
			$sqlContador.=" AND e.edad BETWEEN 45 AND 49 ";
			break;
		case '9':
			$sql.=" AND e.edad BETWEEN 50 AND 54 ";
			$sqlContador.=" AND e.edad BETWEEN 50 AND 54 ";
			break;
		case '10':
			$sql.=" AND e.edad BETWEEN 55 AND 59 ";
			$sqlContador.=" AND e.edad BETWEEN 55 AND 59 ";
			break;
		case '11':
			$sql.=" AND e.edad BETWEEN 60 AND 64 ";
			$sqlContador.=" AND e.edad BETWEEN 60 AND 64 ";
			break;
		case '12':
			$sql.=" AND e.edad > 64 ";
			$sqlContador.=" AND e.edad > 64 ";
			break;
	}

	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
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
		$nestedData[] = $row["fecha_hora"]; 
		$nestedData[] = $row["clave"]; 
		$nestedData[] = $row["municipio"]; 
		$nestedData[] = $row["seccion"]; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo"]."<div>"; 
		$nestedData[] = $row["sexo"]; 
		$nestedData[] = $row["edad"]; 
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo_compartido"]."<div>"; 
		if($option_delete){
			$delete='<button class="btn btn-danger bt_responsive"  onClick="borrar('.$row["id"].');" >
						<span class="btnImage"><img class="bntImageSize" src="img/eliminar20.png"></span>
						<span class="btnText">Eliminar</span></button>';
			unset($delete);
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
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos_encuestas e WHERE 1 = 1   "; 
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
