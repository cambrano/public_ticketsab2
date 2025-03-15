<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";

	if(!empty($_POST)){

		if($_POST['datos'][0]['tipo'] == "nombre_completo" && $_POST['datos'][0]['metodo'] == "json"){
			$id_identidad = $_POST['datos'][0]['id_identidad'];
			$identidadDatos = identidadDatos($id_identidad);
			if(!empty($identidadDatos)){
				//bien
				$identidad_nombre_completo = $identidadDatos['nombre']." ".$identidadDatos['apellido_paterno']." ".$identidadDatos['apellido_materno'];
				$correo_sugeridoSIN = explode(' ', $identidad_nombre_completo);
				$$correo_sugerido = "";
				foreach ($correo_sugeridoSIN as $key => $value) {
					$correo_sugerido .= substr($value,0, 3); 
				}

				$latitud = $identidadDatos['latitud'];
				$longitud = $identidadDatos['longitud'];
				$locationGoogleMaps = "https://maps.google.com/?q={$latitud},{$longitud}";
				$datos = array(
					'nombre' => $identidadDatos['nombre'],
					'apellido_paterno' => $identidadDatos['apellido_paterno'],
					'apellido_materno' => $identidadDatos['apellido_materno'],
					'fecha_nacimiento' => $identidadDatos['fecha_nacimiento'],
					'estado' => estadoNombre($identidadDatos['id_estado']),
					'municipio' => municipioNombre($identidadDatos['id_municipio']),
					'localidad' => localidadNombre($identidadDatos['id_localidad']),
					'locationGoogleMaps' => $locationGoogleMaps,
					'coordenadas' => $coordenadas= array('lng' => $longitud,'lat' => $latitud ),
					'usuario' => $correo_sugerido,
					'password' => $correo_sugerido,
					'status' => 'success',
				);
				echo json_encode($datos, JSON_FORCE_OBJECT);
			}else{
				$datos = array( 
					'status' => 'error',
				);
				echo json_encode($datos, JSON_FORCE_OBJECT);
			}
		}
	}
?>
