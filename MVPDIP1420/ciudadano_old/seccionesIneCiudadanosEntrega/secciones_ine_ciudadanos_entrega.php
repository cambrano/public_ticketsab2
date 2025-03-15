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
	$columns = array( 
		// datatable column index  => database column name
		0 =>'clave', 
		1 =>'nombre_completo',
		2 =>'whatsapp',
		3 =>'celular',
		4 =>'calle',
		5 =>'check_in_hora',
		6 =>'check_out_hora',
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

			(SELECT sicc2021.check_in FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  ) check_in,
			(SELECT sicc2021.check_in_hora FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  ) check_in_hora,
			(SELECT sicc2021.check_out FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  ) check_out,
			(SELECT sicc2021.check_out_hora FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  ) check_out_hora
		FROM secciones_ine_ciudadanos e 
		WHERE 1=1  "; 
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
		$sql.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
		$sqlContador.=" AND CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno ) LIKE '%{$nombre_completo}%' ";
	}

	$sexo=$_SESSION['searchTable']['sexo'];
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



	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."  ".$requestData['order'][0]['dir'];
	$_SESSION['reporte_Sistema']['sql'] = $sql;

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
			$nestedData[] =  "{$entrega}";
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
