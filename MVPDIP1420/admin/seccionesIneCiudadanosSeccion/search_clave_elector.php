<?php

	if(!empty($_POST)){
		include __DIR__.'/../functions/security.php'; 
		include __DIR__.'/../functions/db.php'; 
		@session_start();
		$clave_elector = trim($_POST['search_clave_elector']);
		$clave_elector = mysqli_real_escape_string($conexion,$clave_elector);
		//bucamos en la lista_nominal

		$sql="
			SELECT 
				ln.nombre,
				ln.apellido_paterno,
				ln.apellido_materno,
				ln.fecha_nacimiento,
				ln.sexo,
				ln.ocr,
				ln.curp,
				ln.codigo_postal,
				ln.calle,
				ln.estado,
				(SELECT e.abreviacion_2 FROM estados e WHERE e.id = ln.estado) estado_dig_2,
				(SELECT s.numero FROM secciones_ine s WHERE s.id= ln.id_seccion_ine) seccion,
				ln.colonia,
				ln.id_seccion_ine,
				ln.manzana,
				ln.id_localidad,
				ln.id_municipio,
				ln.num_int,
				ln.num_ext,
				ln.latitud,
				ln.longitud
			FROM lista_nominal ln
			WHERE ln.clave_elector ='{$clave_elector}'
			";
		$result = $conexion->query($sql);
		$row=$result->fetch_assoc();

		//// buescar si existe en el sistema
		$sqlClave = "SELECT id FROM secciones_ine_ciudadanos WHERE clave_elector = '{$clave_elector}' ";
		$resultClave = $conexion->query($sqlClave);
		$rowClave=$resultClave->fetch_assoc();

		if(!empty($row)){
			/*
			$data = "<div class='data_interior_left'>
					Nombre: <b>{$row['nombre']}</b><br>
					Apellido Paterno: <b>{$row['apellido_paterno']}</b><br>
					Apellido Materno: <b>{$row['apellido_materno']}</b><br>
					Fecha Nacimiento: <b>{$row['fecha_nacimiento']}</b><br>
					Sexo: <b>{$row['sexo']}</b><br>
					Sección: <b>{$row['seccion']}</b><br>
				</div>
				<div class='data_interior'>
					Calle: <b>{$row['calle']}</b><br>
					No. Exterior: <b>No Encontrado</b><br>
					No. Interior: <b>No Encontrado</b><br>
					Colonia: <b>{$row['colonia']}</b><br>
					Código Postal: <b>{$row['codigo_postal']}</b><br>
				</div>";*/
			$myObj->status_verificacion = 1;
			$myObj->nombre = $row['nombre'];
			$myObj->apellido_paterno = $row['apellido_paterno'];
			$myObj->apellido_materno = $row['apellido_materno'];
			$myObj->fecha_nacimiento = $row['fecha_nacimiento'];
			$myObj->sexo = $row['sexo'];
			$myObj->seccion = $row['seccion'];
			$myObj->manzana = $row['manzana'];
			$myObj->calle = $row['calle'];
			$myObj->ocr = $row['ocr'];
			$myObj->curp = $row['curp'];
			$myObj->estado_dig_2 = $row['estado_dig_2'];
			if($row['num_int']==''){
				$row['num_int']='';
			}
			if($row['num_ext']==''){
				$row['num_ext']='';
			}
			$myObj->no_exterior = $row['num_ext'];
			$myObj->no_interior = $row['num_int'];
			$myObj->colonia = $row['colonia'];
			$myObj->codigo_postal = (string)((int)($row['codigo_postal']));;
			$myObj->id_seccion_ine = $row['id_seccion_ine'];
			$myObj->id_localidad = $row['id_localidad'];
			$myObj->id_municipio = $row['id_municipio'];
			$myObj->id_estado = $row['id_estado'];
			$myObj->latitud = $row['latitud'];
			#$myObj->latitud = 27.95222070628325;
			$myObj->longitud = $row['longitud'];
			#$myObj->longitud = -101.21469880432129;

		}else{
			/*
			$data = "<div class='data_interior_left'>
					Nombre: <b>No Encontrado</b><br>
					Apellido Paterno: <b>No Encontrado</b><br>
					Apellido Materno: <b>No Encontrado</b><br>
					Fecha Nacimiento: <b id>No Encontrado</b><br>
					Sexo: <b>No Encontrado</b><br>
					Sección: <b>No Encontrado</b><br>
				</div>
				<div class='data_interior'>
					Calle: <b>No Encontrado</b><br>
					No. Exterior: <b>No Encontrado</b><br>
					No. Interior: <b>No Encontrado</b><br>
					Colonia: <b>No Encontrado</b><br>
					Código Postal: <b>No Encontrado</b><br>
				</div>";*/
			$myObj->status_verificacion = 0;
			$myObj->nombre = 'No Encontrado';
			$myObj->apellido_paterno = 'No Encontrado';
			$myObj->apellido_materno = 'No Encontrado';
			$myObj->fecha_nacimiento = 'No Encontrado';
			$myObj->sexo = 'No Encontrado';
			$myObj->seccion = 'No Encontrado';
			$myObj->manzana = 'No Encontrado';
			$myObj->calle = 'No Encontrado';
			$myObj->no_exterior = 'No Encontrado';
			$myObj->no_interior = 'No Encontrado';
			$myObj->colonia = 'No Encontrado';
			$myObj->codigo_postal = 'No Encontrado';
			$myObj->id_localidad = 'No Encontrado';
			$myObj->estado_dig_2 = 'No Encontrado';
		}
		if($rowClave['id']!=""){
			$myObj->disponible = false;
		}else{
			$myObj->disponible = true;
		}
		$myJSON = json_encode($myObj);
		echo $myJSON;
	}
?>