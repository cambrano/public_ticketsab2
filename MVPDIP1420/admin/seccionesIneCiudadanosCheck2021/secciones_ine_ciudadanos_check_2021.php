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
		1 =>'seccion',
		2 =>'distancia_km',
		3 =>'relacionado',
		4 =>'nombre_completo',
		5 =>'check_out_fecha_hora',
		6 =>'fecha_nacimiento',
		7 =>'whatsapp',
		8 =>'celular',
	);

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
				e.id,
				e.clave,
				si.numero AS seccion,
				e.distancia_km,
				sim.nombre_completo AS relacionado,
				e.nombre_completo,
				sicc2021.check_in,
				sicc2021.check_out,
				sicc2021.check_in_hora,
				sicc2021.check_out_hora,
				e.fecha_nacimiento,
				e.whatsapp,
				e.telefono,
				e.celular,
				e.calle,
				e.colonia,
				e.codigo_postal,
				e.fecha_nacimiento,
				e.latitud,
				e.longitud,
				e.distancia_km
			FROM
				secciones_ine_ciudadanos e
			LEFT JOIN
				secciones_ine si ON e.id_seccion_ine = si.id
			LEFT JOIN
				secciones_ine_ciudadanos sim ON e.id_seccion_ine_ciudadano_compartido = sim.id
			LEFT JOIN
				secciones_ine_ciudadanos_check_2021 sicc2021 ON sicc2021.id_seccion_ine_ciudadano = e.id
			WHERE
				1 = 1
"; 
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND e.clave LIKE '%{$clave}%' ";
	} 

	$nombre_completo=$search_database['nombre_completo'];
	if( $nombre_completo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.nombre_completo LIKE '%{$nombre_completo}%' ";
		$sqlContador.=" AND e.nombre_completo LIKE '%{$nombre_completo}%' ";
	}

	$id_seccion_ine_ciudadano_compartido=$search_database['id_seccion_ine_ciudadano_compartido'];
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}


	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$post_search=true;
		$sql.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
		$sqlContador.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
	}
	$id_cuartel=$search_database['id_cuartel'];
	if( $id_cuartel!="" ){
		$post_search=true;
		$sql.=" AND e.id_cuartel IN ($id_cuartel) ";
		$sqlContador.=" AND e.id_cuartel IN ($id_cuartel) ";
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
	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_local IN ($id_distrito_local) ";
		$sqlContador.=" AND e.id_distrito_local IN ($id_distrito_local) ";
	}
	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){
		$post_search=true;
		$sql.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
		$sqlContador.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
	}

	$checks=$search_database['checks'];
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




	if($columns[$requestData['order'][0]['column']]=="relacionado"){
		$columns[$requestData['order'][0]['column']] = "id_seccion_ine_ciudadano_compartido";
	}

	$sql.= $order = " ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	setcookie("AB32BA51", encrypt_ab_checkSin($order), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["distancia_km"];
		$nestedData[] = "<div style='text-transform: none;'>".$row["relacionado"]."<div>";
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo"]."<div>";

		if($row["check_in"]==1){
			$entrega='<center style="padding:0px;margin:0px">
							<button class="btn btn-success" style="cursor:not-allowed;" disabled>
							<span class="btnImage"><img class="bntImageSize" src="img/pasajero20.png"></span>
							<span class="btnText">Check IN</span></button>
					</center>
					<div id="entrega_hora_'.$row["id"].'" style="background-color:green; color: white;padding:2px;margin-top:2px;text-align:center">
					'.$row["check_in_hora"].'
					</div>
			';
		}else{
			$entrega='<center style="padding:0px;margin:0px">
						<button class="btn btn-warning"  onClick="entrega('.$row["id"].');" id = "entrega_'.$row["id"].'"  >
						<span class="btnImage"><img id = "entrega_img_'.$row["id"].'" class="bntImageSize" src="img/pasajero20_gray.png"></span>
						<span class="btnText">Check IN</span></button>
					</center>
					<div id="entrega_hora_'.$row["id"].'" style="background-color:none; color: white;padding:2px;margin-top:2px;text-align:center"></div>
			';
		}
		//$nestedData[] =  "{$entrega}";
		if($row["check_out"]==1){
			$recibe='<center style="padding:0px;margin:0px">
							<button class="btn btn-success" style="cursor:not-allowed;" disabled>
							<span class="btnImage"><img class="bntImageSize" src="img/pasajero20.png"></span>
							<span class="btnText">Check OUT</span></button>
					</center>
					<div id="recibe_hora_'.$row["id"].'" style="background-color:green; color: white;padding:2px;margin-top:2px;text-align:center">
					'.$row["check_out_hora"].'
					</div>
			';
		}else{
			$recibe='<center style="padding:0px;margin:0px">
						<button class="btn btn-warning"  onClick="recibe('.$row["id"].');" id = "recibe_'.$row["id"].'"  >
						<span class="btnImage"><img id = "recibe_img_'.$row["id"].'" class="bntImageSize" src="img/pasajero20_gray.png"></span>
						<span class="btnText">Check OUT</span></button>
					</center>
					<div id="recibe_hora_'.$row["id"].'" style="background-color:none; color: white;padding:2px;margin-top:2px;text-align:center"></div>
			';
		}
		$nestedData[] =  "{$recibe}";


		$nestedData[] = $row["fecha_nacimiento"];
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$row['whatsapp']."&text&app_absent=0' target='_blank'>".$row['whatsapp']."</a>";
		$nestedData[] = $row["celular"];

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
