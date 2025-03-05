<?php
		include '../functions/security.php'; 
		include '../functions/localidades.php';
		include '../functions/municipios.php'; 
		//include '../functions/estados.php';
		//include '../functions/paises.php'; 
		include '../functions/sucursales.php'; 
		if(!empty($_POST)){
			



			$tipo=$_POST['direccion_completa'][0]['tipo'];

			if($tipo=="datos_formulario"){

				$id_pais=$_POST['direccion_completa'][0]['id_pais'];
				$id_estado=$_POST['direccion_completa'][0]['id_estado'];
				$id_municipio=$_POST['direccion_completa'][0]['id_municipio'];
				$id_localidad=$_POST['direccion_completa'][0]['id_localidad'];

				$calle=$_POST['direccion_completa'][0]['calle'];
				$num_ext=$_POST['direccion_completa'][0]['num_ext'];
				$num_int=$_POST['direccion_completa'][0]['num_int'];
				$colonia=$_POST['direccion_completa'][0]['colonia'];
				$codigo_postal=$_POST['direccion_completa'][0]['codigo_postal'];
				/*
				echo urlencode(paisNombre($id_pais));
				echo "+";
				echo urlencode(estadoNombre($id_estado));
				echo "+";
				echo urlencode(municipioNombre($id_municipio));
				echo "+";
				echo urlencode(localidadNombre($id_localidad));
				*/
				//$pais = paisNombre($id_pais);
				$pais = 'México';
				//var_dump($pais);
				//$estado = estadoNombre($id_estado);
				$estado = 'Yucatán';
				$municipio = municipioNombre($id_municipio);
				$localidad = localidadNombre($id_localidad);
				$queryString = http_build_query([
					'address' => $calle.' '.$num_ext.' '.$num_int.' '.$colonia.','.$localidad.','.$municipio.','.$estado.','.$codigo_postal,
					'language' => 'es',
					'country' => $pais,
				]);
				//var_dump("https://trueway-geocoding.p.rapidapi.com/Geocode?address=".$queryString);
				$curl = curl_init();
				curl_setopt_array($curl, [
					CURLOPT_URL => "https://trueway-geocoding.p.rapidapi.com/Geocode?address=".$queryString,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => [
						"x-rapidapi-host: trueway-geocoding.p.rapidapi.com",
						"x-rapidapi-key: e26fb146d1msh6318fa934a67e3ap123eefjsn7c1ea8c0e9f5"
					],
				]);
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if ($err) {
					$mensaje = "cURL Error #:" . $err;
					$myObj->location = NULL;
				} else {
					$mensaje = "OK";
					$response = json_decode($response,true);
				}
				$myObj->mensaje = $mensaje;
				$myObj->location = $response['results'][0]['location'];
				$myObj->api_mensaje = $response['message'];
				$myJSON = json_encode($myObj);
				echo $myJSON;
			}
		}