<?php
	include __DIR__."/../functions/security.php";
	if(!empty($_POST)){
		include __DIR__."/../functions/timemex.php";
		include __DIR__."/../functions/tools.php";
		include __DIR__."/../functions/secciones_ine_ciudadanos.php";
		include __DIR__."/../functions/db.php";
		


		$mensaje = "";

		
		$scanner = $_POST['scaneo'][0]['scanner'];
		$http_validacion = strpos($scanner, "https://");
		$datos_array = explode("?", $scanner);
		$cots_string = $datos_array[1];
		$cots_array = explode("&", $cots_string);
		if(count($datos_array) >1 && $http_validacion == 0 ){
			$tipo = 'mp';
			foreach ($cots_array as $key => $value_string) {
				$value_array = explode("=", $value_string);
				if($value_array[0] == 'cot'){
					$value_array[0] = 'id_seccion_ine_ciudadano';
				}elseif ($value_array[0] == 'ck') {
					$value_array[0] = 'codigo_seccion_ine_ciudadano';
				}elseif ($value_array[0] == 'hex') {
					$value_array[0] = 'id_militante';
				}
				$datos[$value_array[0]] = $value_array[1];
			}
		}else{
			//aqui lo decodificamos
			$tipo = 'sic';
			//$tipo= $scanner;
			$cots_array = explode("-", $scanner);
			$datos['codigo_seccion_ine_ciudadano'] = $cots_array[0];
			$id_seccion_ine_ciudadano = preg_replace('/[a-zA-Z]/', '', $cots_array[1]);
			$datos['id_seccion_ine_ciudadano'] = $cots_array[1];
		}

		foreach($datos as $keyPrincipal => $atributo) {
			$datos[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}
		if(!empty($datos['codigo_seccion_ine_ciudadano']) && !empty($datos['id_seccion_ine_ciudadano'])  ){
			if($tipo=='mp' && !empty($datos['id_militante'])){
				$sql = "
					SELECT 
						sic.id,
						sic.clave AS clave_ciudadano,
						sic.clave_elector,
						sic.folio,
						sic.curp,
						mp.clave AS clave_militante,
						sic.nombre,
						sic.apellido_paterno,
						sic.apellido_materno,
						s.clave AS seccion,
						m.municipio
					FROM secciones_ine_ciudadanos sic
					INNER JOIN militantes_partidos mp
					ON sic.id = mp.id_seccion_ine_ciudadano
					INNER JOIN secciones_ine s
					ON sic.id_seccion_ine = s.id
					INNER JOIN municipios m
					ON sic.id_municipio = m.id
					WHERE codigo_seccion_ine_ciudadano LIKE '".$datos['codigo_seccion_ine_ciudadano']."%' AND mp.id='".$datos['id_militante']."' AND sic.id='".$datos['id_seccion_ine_ciudadano']."'
				";
			}else{
				$sql = "
					SELECT 
						sic.id,
						sic.clave AS clave_ciudadano,
						sic.clave_elector,
						sic.folio,
						sic.curp,
						sic.nombre,
						sic.apellido_paterno,
						sic.apellido_materno,
						s.clave AS seccion,
						m.municipio
					FROM secciones_ine_ciudadanos sic
					INNER JOIN secciones_ine s
					ON sic.id_seccion_ine = s.id
					INNER JOIN municipios m
					ON sic.id_municipio = m.id
					WHERE codigo_seccion_ine_ciudadano LIKE '".$datos['codigo_seccion_ine_ciudadano']."%' AND sic.id='".$datos['id_seccion_ine_ciudadano']."'
				";
			}
			
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			if(!empty($row['id'])){

				// Palabra clave para encriptar y desencriptar
				$palabra_clave = "sistemaRadarAB";
				// Algoritmo de encriptación
				$algoritmo = "AES-256-CBC";
				// Vector de inicialización
				$iv = 'AB';
				$otra_variable = $row["id"];
				$otra_variable = urlencode(openssl_encrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));

				$data = array(
					'status' => 1,
					'id' => $row['id'],
					'expediente' => $otra_variable,
					'clave_ciudadano' => $row['clave_ciudadano'],
					'folio' => $row['folio'],
					'clave_elector' => $row['clave_elector'],
					'curp' => $row['curp'],
					'nombre' => $row['nombre'],
					'apellido_paterno' => $row['apellido_paterno'],
					'apellido_materno' => $row['apellido_materno'],
					'seccion' => $row['seccion'],
					'municipio' => $row['municipio'],
					'mensaje' => 'OK!',
					'tipo' => $tipo,
				);
			}else{
				$data = array(
					'status' => 0,
					'id' => '',
					'expediente' => '',
					'clave_ciudadano' => 'NO ENCONTRADO',
					'folio' => 'NO ENCONTRADO',
					'clave_elector' => 'NO ENCONTRADO',
					'curp' => 'NO ENCONTRADO',
					'nombre' => 'NO ENCONTRADO',
					'apellido_paterno' => 'NO ENCONTRADO',
					'apellido_materno' => 'NO ENCONTRADO',
					'seccion' => 'NO ENCONTRADO',
					'municipio' => 'NO ENCONTRADO',
					'mensaje' => 'NO ENCONTRADO!',
					'tipo' => $tipo,
				);
			}
		}else{
			$data = array(
				'status' => 0,
				'id' => '',
				'expediente' => '',
				'clave_ciudadano' => 'NO ENCONTRADO',
				'folio' => 'NO ENCONTRADO',
				'clave_elector' => 'NO ENCONTRADO',
				'curp' => 'NO ENCONTRADO',
				'nombre' => 'NO ENCONTRADO',
				'apellido_paterno' => 'NO ENCONTRADO',
				'apellido_materno' => 'NO ENCONTRADO',
				'seccion' => 'NO ENCONTRADO',
				'municipio' => 'NO ENCONTRADO',
				'mensaje' => 'INCOMPLETO!',
				'tipo' => $tipo,
			);
		}
	}else{
		$data = array(
			'status' => 0,
			'id' => '',
			'expediente' => '',
			'clave_ciudadano' => 'NO ENCONTRADO',
			'folio' => 'NO ENCONTRADO',
			'clave_elector' => 'NO ENCONTRADO',
			'curp' => 'NO ENCONTRADO',
			'nombre' => 'NO ENCONTRADO',
			'apellido_paterno' => 'NO ENCONTRADO',
			'apellido_materno' => 'NO ENCONTRADO',
			'seccion' => 'NO ENCONTRADO',
			'municipio' => 'NO ENCONTRADO',
			'mensaje' => 'ERROR!',
			'tipo' => $tipo,
		);
	}

	header('Content-Type: application/json');
	echo json_encode($data);



?>