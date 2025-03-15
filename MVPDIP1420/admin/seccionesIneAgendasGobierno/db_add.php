<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_agendas_gobierno.php";
	include __DIR__."/../functions/claves_2.php";
	include __DIR__."/../functions/usuario_permisos.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_agendas_gobierno',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['insert'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	if(!empty($_POST)){
		$conexion->autocommit(FALSE);
		foreach($_POST["seccion_ine_agenda_gobierno"][0] as $keyPrincipal => $atributo) {
			//$_POST["seccion_ine_agenda_gobierno"][0][$keyPrincipal] = mysqli_real_escape_string($conexion, $atributo);
		}
	
		$seccion_ine_agenda_gobiernoClaveVerificacion = seccion_ine_agenda_gobiernoClaveVerificacion($_POST["seccion_ine_agenda_gobierno"][0]['clave'], '', 1);
		if($seccion_ine_agenda_gobiernoClaveVerificacion) {
			$claveF = clave2('secciones_ine_agendas_gobierno');
			if (empty($claveF['input'])) {
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			} else {
				$_POST["seccion_ine_agenda_gobierno"][0]['clave'] = $claveF['clave'];
			}
		}
	
		$success = true;
		$_POST["seccion_ine_agenda_gobierno"][0]['fechaR'] = $fechaH;
		$_POST["seccion_ine_agenda_gobierno"][0]['codigo_plataforma'] = $codigo_plataforma;
	
		// Preparar campos
		$fields = array_keys($_POST["seccion_ine_agenda_gobierno"][0]);
		$placeholders = implode(",", array_fill(0, count($fields), "?"));
		$fields_pdo = "`" . implode("`,`", $fields) . "`";

		// Preparar la primera consulta para insertar en secciones_ine_agendas_gobierno
		$stmt = $conexion->prepare("INSERT INTO secciones_ine_agendas_gobierno ($fields_pdo) VALUES ($placeholders)");

		if (!$stmt) {
			$success = false;
			echo "ERROR al preparar consulta en secciones_ine_agendas_gobierno.";
			var_dump($conexion->error);
			$stmt->close();
			die;
		}
		// Asocia cada parámetro con el tipo correspondiente (por ejemplo, 's' para string, 'i' para integer)
		$paramTypes = str_repeat('s', count($_POST["seccion_ine_agenda_gobierno"][0])); // Suponiendo que todos los campos son strings
		$params = array_values($_POST["seccion_ine_agenda_gobierno"][0]);

		// Usa bind_param para asociar los parámetros
		$stmt->bind_param($paramTypes, ...$params);

		// Ejecutar la consulta
		if (!$stmt->execute()) {
			echo "ERROR al ejecutar consulta en secciones_ine_agendas_gobierno.";
			$success = false;
			var_dump($stmt->error);
			$stmt->close();
			die;
		}
		$id = $conexion->insert_id;
		$_POST["seccion_ine_agenda_gobierno"][0]['id_seccion_ine_agenda_gobierno'] = $id;
		$stmt->close(); // Cerrar la sentencia preparada
		
		$stmt = $conexion->prepare("INSERT INTO secciones_ine_agendas_gobierno_historicos ($fields_pdo) VALUES ($placeholders)");
		if (!$stmt) {
			$success = false;
			echo "ERROR al preparar consulta en secciones_ine_agendas_gobierno_historicos.";
			var_dump($conexion->error);
			$stmt->close();
			die;
		}
		// Usa bind_param para asociar los parámetros nuevamente
		$stmt->bind_param($paramTypes, ...$params);
		// Ejecutar la consulta para la tabla de históricos
		if (!$stmt->execute()) {
			$success = false;
			echo "ERROR al ejecutar consulta en secciones_ine_agendas_gobierno_historicos.";
			var_dump($stmt->error);
			$stmt->close(); // Cerrar la sentencia preparada
			die;
		}
		

		// Procesar ids_dependencias
		$ids_dependencias = explode(",", $_POST["seccion_ine_agenda_gobierno"][0]['ids_dependencias']);
		if (isset($ids_dependencias[0]) && $ids_dependencias[0] === "") {
			$ids_dependencias = [];
		}

		// Procesar ids_dependencias
		foreach ($ids_dependencias as $value) {
			$dep = [
				'id_seccion_ine_agenda_gobierno' => $id,
				'id_dependencia' => $value,
				'fechaR' => $fechaH,
				'codigo_plataforma' => $codigo_plataforma
			];

			// Construcción dinámica de campos y placeholders
			$fields = implode(",", array_keys($dep));
			$placeholders = implode(",", array_fill(0, count($dep), "?"));

			// Insertar en la tabla secciones_ine_agendas_gobierno_dependencias
			$stmt = $conexion->prepare("INSERT INTO secciones_ine_agendas_gobierno_dependencias ($fields) VALUES ($placeholders)");

			if (!$stmt) {
				$success = false;
				echo "ERROR al preparar consulta en secciones_ine_agendas_gobierno_dependencias.";
				var_dump($conexion->error);
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}

			// Asumiendo que todos los campos son de tipo string
			$paramTypes = str_repeat('s', count($dep)); // Suponiendo que todos son strings
			$stmt->bind_param($paramTypes, ...array_values($dep));

			if (!$stmt->execute()) {
				$success = false;
				echo "ERROR al ejecutar consulta en secciones_ine_agendas_gobierno_dependencias.";
				var_dump($stmt->error);
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}

			$stmt->close(); // Cerrar la sentencia preparada si se completó con éxito
		}

		//Validamos si las secciones son igual o son diferentes
		$ids_dependencias_generales = explode(",", $_POST["seccion_ine_agenda_gobierno"][0]['ids_dependencias']);
		$ids_dependencias_generales[] = $_POST["seccion_ine_agenda_gobierno"][0]['id_dependencia'];
		// Usar array_map y trim para eliminar los espacios en blanco de cada elemento
		$ids_dependencias_generales = array_map('trim', $ids_dependencias_generales);
		// Eliminar valores repetidos
		$ids_dependencias_generales = array_unique($ids_dependencias_generales);
		// Usar array_filter para eliminar los elementos vacíos o solo espacios
		$ids_dependencias_generales = array_filter($ids_dependencias_generales, function($value) {
			return trim($value) !== "";
		});
		
	
		
		foreach ($ids_dependencias_generales as $value) {
			$entra = true;
		
			// Definir los valores
			$dep = [
				'id_seccion_ine_agenda_gobierno' => $id,
				'id_dependencia' => $value,
				'fechaR' => $fechaH,
				'codigo_plataforma' => $codigo_plataforma
			];
		
			// Crear la lista de campos y placeholders
			$fields_pdo = implode(", ", array_keys($dep)); // Sin comillas inversas
			$placeholders = implode(", ", array_fill(0, count($dep), '?'));
		
			$insert_secciones_ine_agendas_gobierno_dependencia = 
				"INSERT INTO secciones_ine_agendas_gobierno_dependencias_generales ($fields_pdo) VALUES ($placeholders)";
		
			// Preparar la consulta
			$insert_stmt = $conexion->prepare($insert_secciones_ine_agendas_gobierno_dependencia);
		
			// Comprobar errores en la preparación
			if ($insert_stmt === false) {
				echo "ERROR al preparar la consulta de dependencias generales: " . mysqli_error($conexion);
				$success = false;
				$stmt->close();
				break;
			}
		
			// Recopilar valores para bind_param
			$bindTypes = str_repeat("s", count($dep)); // Todos los valores son strings
			$bindValues = array_values($dep);
		
			// Bindear los parámetros de manera dinámica
			$insert_stmt->bind_param($bindTypes, ...$bindValues);
		
			// Ejecutar la consulta
			if (!$insert_stmt->execute()) {
				$success = false;
				echo "ERROR al ejecutar la consulta de dependencias generales: " . mysqli_error($conexion);
				$stmt->close();
				break;
			}
		}
		
	
		// Procesar sub_eventos
		foreach ($_POST['sub_eventos'] as $value) {
			$value['id_seccion_ine_agenda_gobierno'] = $id;
			$value['fecha_hora'] = $value['fecha'] . " " . $value['hora'];
			$value['fechaR'] = $fechaH;
			$value['codigo_plataforma'] = $codigo_plataforma;
		
			// Obtener datos adicionales
			$seccion_ineDatos = seccion_ineDatos($value['id_seccion_ine']);
			if (!$seccion_ineDatos) {
				$success = false;
				echo "ERROR al obtener datos de seccion_ine.";
				$stmt->close();
				break;
			}
		
			$value['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
			$value['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];
		
			// Construcción dinámica de campos y placeholders
			$fields = implode(",", array_keys($value));
			$placeholders = implode(",", array_fill(0, count($value), "?"));
		
			// Inserción en secciones_ine_agendas_gobierno_locaciones
			$stmt = $conexion->prepare("INSERT INTO secciones_ine_agendas_gobierno_locaciones ($fields) VALUES ($placeholders)");
		
			if (!$stmt) {
				$success = false;
				echo "ERROR al preparar consulta en secciones_ine_agendas_gobierno_locaciones.";
				var_dump($conexion->error);
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
		
			$paramTypes = str_repeat('s', count($value));
			$stmt->bind_param($paramTypes, ...array_values($value));
		
			if (!$stmt->execute()) {
				$success = false;
				echo "ERROR al ejecutar consulta en secciones_ine_agendas_gobierno_locaciones.";
				var_dump($stmt->error);
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
			$stmt->close();
		
			$value['id_seccion_ine_agenda_gobierno_locacion'] = $conexion->insert_id;
			// Construcción dinámica de campos y placeholders
			$fields = implode(",", array_keys($value));
			$placeholders = implode(",", array_fill(0, count($value), "?"));
			// Inserción en secciones_ine_agendas_gobierno_locaciones
			$stmt = $conexion->prepare("INSERT INTO secciones_ine_agendas_gobierno_locaciones_historicos ($fields) VALUES ($placeholders)");
		
			if (!$stmt) {
				$success = false;
				echo "ERROR al preparar consulta en secciones_ine_agendas_gobierno_locaciones_historicos.";
				var_dump($conexion->error);
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
		
			$paramTypes = str_repeat('s', count($value));
			$stmt->bind_param($paramTypes, ...array_values($value));
		
			if (!$stmt->execute()) {
				$success = false;
				echo "ERROR al ejecutar consulta en secciones_ine_agendas_gobierno_locaciones_historicos.";
				var_dump($stmt->error);
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
		}
		$stmt->close(); // Cerrar la sentencia preparada
		
		if($success){
			$log = logUsuario($_COOKIE["id_usuario"], 'secciones_ine_agendas_gobierno', $id, 'Insert', '', $fechaH);
			if($log == true){
				echo "SI";
				$conexion->commit();
				$conexion->close();
			} else {
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}
		} else {
			echo "NO";
			$conexion->rollback();
			$conexion->close();
		} 
	}
	
