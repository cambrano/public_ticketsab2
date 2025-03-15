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
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos WHERE 1 = 1  "; 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "SELECT 
			e.clave,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
			e.distancia_km,
			(SELECT sim.nombre_completo FROM secciones_ine_ciudadanos sim WHERE sim.id = e.id_seccion_ine_ciudadano_compartido) relacionado,
			e.nombre_completo,
			(SELECT sicc2021.check_in_hora FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  ) check_in_hora,
			(SELECT sicc2021.check_out_hora FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  ) check_out_hora,
			e.fecha_nacimiento,
			e.whatsapp,
			e.celular
		FROM secciones_ine_ciudadanos e
		WHERE 1 = 1"; 
	// getting records as per search parameters
	$clave=$_SESSION['searchTable']['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	} 

	$nombre_completo=$_SESSION['searchTable']['nombre_completo'];
	if( $nombre_completo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.nombre_completo LIKE '%{$nombre_completo}%' ";
		$sqlContador.=" AND e.nombre_completo LIKE '%{$nombre_completo}%' ";
	}

	 

	$id_seccion_ine_ciudadano_compartido=$_SESSION['searchTable']['id_seccion_ine_ciudadano_compartido'];
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}


	$id_seccion_ine=$_SESSION['searchTable']['id_seccion_ine'];
	//$id_seccion_ine=$_SESSION['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
	}
	$id_cuartel=$_SESSION['searchTable']['id_cuartel'];
	if( $id_cuartel!="" ){
		$post_search=true;
		$sql.=" AND e.id_cuartel IN ($id_cuartel) ";
		$sqlContador.=" AND e.id_cuartel IN ($id_cuartel) ";
	}

	$id_municipio=$_SESSION['searchTable']['id_municipio'];
	if( $id_municipio!="" ){
		$post_search=true;
		$sql.=" AND e.id_municipio IN ($id_municipio) ";
		$sqlContador.=" AND e.id_municipio IN ($id_municipio) ";
	}
	$id_localidad=$_SESSION['searchTable']['id_localidad'];
	if( $id_localidad!="" ){
		$post_search=true;
		$sql.=" AND e.id_localidad IN ($id_localidad) ";
		$sqlContador.=" AND e.id_localidad IN ($id_localidad) ";
	}
	$id_distrito_local=$_SESSION['searchTable']['id_distrito_local'];
	if( $id_distrito_local!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_local IN ($id_distrito_local) ";
		$sqlContador.=" AND e.id_distrito_local IN ($id_distrito_local) ";
	}
	$id_distrito_federal=$_SESSION['searchTable']['id_distrito_federal'];
	if( $id_distrito_federal!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
		$sqlContador.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
	}

	$checks=$_SESSION['searchTable']['checks'];
	$porciones = explode(",", $checks);
	$cont=1;
	$tipo_check = false;
	$sqlx='';
	foreach ($porciones as $keyT => $valueT) {
		if($valueT==1){
			$post_search=true;
			$tipo_check = true;
			$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND check_in = 1 )";
		}
		if($valueT==2){
			$post_search=true;
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
			}else{
				$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
			}
		}

		if($valueT==3){
			$post_search=true;
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND sicc2021.check_out = 1 AND sicc2021.check_in = 1  )";
			}else{
				$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND sicc2021.check_out = 1 AND sicc2021.check_in = 1  )";
			}
		}

		if($valueT==4){
			$post_search=true;
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  )";
			}else{
				$sqlx.=" AND NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  )";
			}
		}		
	}
	$sql.= $sqlx;
	$sqlContador.= $sqlx;

	$checks=$_SESSION['searchTable']['checks'];
	$checks=$_SESSION['checks'];




	if($columns[$requestData['order'][0]['column']]=="relacionado"){
		$columns[$requestData['order'][0]['column']] = "id_seccion_ine_ciudadano_compartido";
	}

	$sql.=" ORDER BY e.". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	$_SESSION['reporte_Sistema']['sql'] = $sql;

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";
 


	$data = array();
	foreach ($_SESSION['reporte_Sistema']['database'] as $key => $value) {
		$nestedData=array(); 
		$nestedData[] = $value["clave"];
		$nestedData[] = $value["seccion"];
		$nestedData[] = $value["distancia_km"];
		$nestedData[] = "<div style='text-transform: none;'>".$value["relacionado"]."<div>";
		$nestedData[] = "<div style='text-transform: none;'>".$value["nombre_completo"]."<div>";

		if($value["check_in"]==1){
			$entrega='<center style="padding:0px;margin:0px">
							<button class="btn btn-success" style="cursor:not-allowed;" disabled>
							<span class="btnImage"><img class="bntImageSize" src="img/pasajero20.png"></span>
							<span class="btnText">Check IN</span></button>
					</center>
					<div id="entrega_hora_'.$value["id"].'" style="background-color:green; color: white;padding:2px;margin-top:2px;text-align:center">
					'.$value["check_in_hora"].'
					</div>
			';
		}else{
			$entrega='<center style="padding:0px;margin:0px">
						<button class="btn btn-warning"  onClick="entrega('.$value["id"].');" id = "entrega_'.$value["id"].'"  >
						<span class="btnImage"><img id = "entrega_img_'.$value["id"].'" class="bntImageSize" src="img/pasajero20_gray.png"></span>
						<span class="btnText">Check IN</span></button>
					</center>
					<div id="entrega_hora_'.$value["id"].'" style="background-color:none; color: white;padding:2px;margin-top:2px;text-align:center"></div>
			';
		}
		$nestedData[] =  "{$entrega}";
		if($value["check_out"]==1){
			$recibe='<center style="padding:0px;margin:0px">
							<button class="btn btn-success" style="cursor:not-allowed;" disabled>
							<span class="btnImage"><img class="bntImageSize" src="img/pasajero20.png"></span>
							<span class="btnText">Check OUT</span></button>
					</center>
					<div id="recibe_hora_'.$value["id"].'" style="background-color:green; color: white;padding:2px;margin-top:2px;text-align:center">
					'.$value["check_out_hora"].'
					</div>
			';
		}else{
			$recibe='<center style="padding:0px;margin:0px">
						<button class="btn btn-warning"  onClick="recibe('.$value["id"].');" id = "recibe_'.$value["id"].'"  >
						<span class="btnImage"><img id = "recibe_img_'.$value["id"].'" class="bntImageSize" src="img/pasajero20_gray.png"></span>
						<span class="btnText">Check OUT</span></button>
					</center>
					<div id="recibe_hora_'.$value["id"].'" style="background-color:none; color: white;padding:2px;margin-top:2px;text-align:center"></div>
			';
		}
		$nestedData[] =  "{$recibe}";


		$nestedData[] = $value["fecha_nacimiento"];
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$value['whatsapp']."&text&app_absent=0' target='_blank'>".$value['whatsapp']."</a>";
		$nestedData[] = $value["celular"];

		$data[] = $nestedData;
	}

	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos e WHERE 1 = 1  "; 
		$sqlContadorScript .= $sqlContador;
		$sqlContadorScript;
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
