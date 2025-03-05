<?php
	/* Database connection start */
	@session_start(); 
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_permisos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/usuarios.php";

	$seccion_ine_ciudadano_permisosDatos = seccion_ine_ciudadano_permisosDatos('','',$_COOKIE["id_usuario"]);
	$switch_operacionesPermisos = switch_operacionesPermisos();

	$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
	$id_seccion_ine_ciudadano_compartido = $usuarioDatos['id_seccion_ine_ciudadano'];
	$seccion_ine_ciudadanoDatos=seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano_compartido);

	$id_seccion_ine = $seccion_ine_ciudadanoDatos['id_seccion_ine'];

	/* Database connection end */
	// storing  request (ie, get/post) global array to a variable  
	$requestData= $_REQUEST;
	$search_database = $_POST['postData']['searchTable'][0];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave', 
		1 =>'seccion',
		2 =>'manzana',
		3 =>'nombre_completo',
		4 =>'whatsapp',
		5 =>'celular',
		6 =>'calle',
		7 =>'check_out_hora',
		//2 =>'status',
	);

	////////////////////////////
	////////////////////////////
	/// Para saber el total
	// getting total number records without any search
	// obteneos el numero total de tablas 
	$sql = "SELECT count(*) total FROM secciones_ine_ciudadanos WHERE 1=1  AND codigo_plataforma='{$codigo_plataforma}' "; 
	if( $id_seccion_ine!="" ){   //name
		$sql.=" AND id_seccion_ine = '{$id_seccion_ine}' ";
	}

 
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totalData=$row['total']; 
	////////////////////////////
	////////////////////////////
	////////////////////////////
	$sql = "
		SELECT 
			e.id,
			e.clave,
			e.whatsapp,
			e.celular,
			e.calle,
			e.colonia,
			e.nombre_completo,
			(SELECT s.numero FROM secciones_ine s WHERE s.id = e.id_seccion_ine) seccion,
			e.manzana,
			(SELECT sicc2024.check_out FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id  ) check_out,
			(SELECT sicc2024.check_out_hora FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id  ) check_out_hora
		FROM secciones_ine_ciudadanos e 
		WHERE 1=1  "; 
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
		$sql.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
		$sqlContador.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
	}

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND e.nombre LIKE '%{$nombre}%' ";
		$sqlContador.=" AND e.nombre LIKE '%{$nombre}%' ";
	}

	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
		$sqlContador.=" AND e.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	}

	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$post_search=true;
		$sql.=" AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
		$sqlContador.=" AND e.apellido_materno LIKE '%{$apellido_materno}%' ";
	}

	$manzana=$search_database['manzana'];
	if( $manzana!="" ){   //name
		$post_search=true;
		$sql.=" AND e.manzana LIKE '%{$manzana}%' ";
		$sqlContador.=" AND e.manzana LIKE '%{$manzana}%' ";
	}

	$sexo=$search_database['sexo'];
	if( $sexo!="" ){   //name
		$post_search=true;
		$sql.=" AND e.sexo = '{$sexo}' ";
		$sqlContador.=" AND e.sexo = '{$sexo}' ";
	}

	if( $id_seccion_ine!="" ){   //name
		$sql.=" AND e.id_seccion_ine = '{$id_seccion_ine}' ";
		$sqlContador.=" AND e.id_seccion_ine = '{$id_seccion_ine}' ";
	}

	/*
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$post_search=true;
		$sql.=" OR e.id = '{$id_seccion_ine_ciudadano_compartido}' ";
		$sqlContador.=" OR e.id = '{$id_seccion_ine_ciudadano_compartido}' ";
	}
	*/
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

	$checks=$search_database['checks'];
	$porciones = explode(",", $checks);
	$cont=1;
	$tipo_check = false;
	$sqlx='';
	foreach ($porciones as $keyT => $valueT) {
		if($valueT==1){
			$post_search=true;
			$tipo_check = true;
			$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND check_in = 1 )";
		}
		if($valueT==2){
			$post_search=true;
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
			}else{
				$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
			}
		}

		if($valueT==3){
			$post_search=true;
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND sicc2024.check_out = 1 AND sicc2024.check_in = 1  )";
			}else{
				$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND sicc2024.check_out = 1 AND sicc2024.check_in = 1  )";
			}
		}

		if($valueT==4){
			$post_search=true;
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id  )";
			}else{
				$sqlx.=" AND NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id  )";
			}
		}		
	}
	$sql.= $sqlx;
	$sqlContador.= $sqlx;



	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];

	$sql.=" LIMIT ".$requestData['start']." ,".$requestData['length'].";";

	
	if($switch_operacionesPermisos['entrega'] && $seccion_ine_ciudadano_permisosDatos['entrega'] == "1"){
		$entrega = true;
	}else{
		$entrega = false;
	}

	if($switch_operacionesPermisos['recibe'] && $seccion_ine_ciudadano_permisosDatos['recibe'] == "1"){
		$recibe = true;
	}else{
		$recibe = false;
	}


	$data = array();
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$nestedData=array(); 
		$nestedData[] = $row["clave"];
		$nestedData[] = $row["seccion"];
		$nestedData[] = $row["manzana"];
		$nestedData[] = "<div style='text-transform: none;'>".$row["nombre_completo"]."<div>";
		$nestedData[] = "<a href='https://api.whatsapp.com/send/?phone=52".$row['whatsapp']."&text&app_absent=0' target='_blank'>".$row['whatsapp']."</a>";
		$nestedData[] = '<a href="tel:'.$row["celular"].'">'.$row["celular"].'</a>'; 
		$nestedData[] = $row["calle"].','.$row["colonia"];
		
		
		//$nestedData[] = statusGeneralNombre($row["status"]);
		//$edit='<button  type="button" class="btn btn-info"  onClick="edit('.$row["id"].');" >Editar</button>';

		if( $entrega){
			if($row["check_in"]==1){
				$entrega='<center style="padding:0px;margin:0px">
								<button class="btn btn-success" style="cursor:not-allowed;" disabled>
								<span class="btnImage"><img class="bntImageSize" src="img/pasajero20.png"></span>
								<span class="btnText">Editar</span></button>
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
		}

		if( $recibe){
			if($row["check_out"]==1){
				$recibe='<center style="padding:0px;margin:0px">
								<button class="btn btn-success" style="cursor:not-allowed;" disabled>
								<span class="btnImage"><img class="bntImageSize" src="img/pasajero20.png"></span>
								<span class="btnText">Editar</span></button>
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
		}


		//$nestedData[] = "<center style='padding:0px;margin:0px'>".$entrega."";
		//$nestedData[] = $row["check_out_hora"];

		//$select="<input type='radio' name='id'  class='checkselected' value='".$row['id']."'/>";


		$data[] = $nestedData;
	}
	////////////////////////////
	///numero total de filtrados
	if($post_search){
		$sqlContadorScript = "SELECT count(*) total FROM secciones_ine_ciudadanos e WHERE 1=1  AND codigo_plataforma='{$codigo_plataforma}' "; 
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
