<?php
	include __DIR__."/../functions/security.php";
	if(!empty($_POST)){
		include __DIR__."/../functions/timemex.php";
		include __DIR__."/../functions/tools.php";
		include __DIR__."/../functions/secciones_ine_ciudadanos.php";
		include __DIR__."/../functions/db.php";
		


		$mensaje = "";
		foreach($_POST["buscador"][0] as $keyPrincipal => $atributo) {
			$_POST["buscador"][0][$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		}

		if(!empty($_POST) && !empty($_POST['buscador'][0]['clave'])  ){
			$datos = $_POST['buscador'][0];
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
				WHERE sic.clave = '".$datos['clave']."';
			";
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