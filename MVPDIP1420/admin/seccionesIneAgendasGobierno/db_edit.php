<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/secciones_ine_agendas_gobierno.php";
	include __DIR__."/../functions/claves_2.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_agendas_gobierno_locaciones.php";
	include __DIR__."/../functions/secciones_ine_agendas_gobierno_dependencias.php";
	include __DIR__."/../functions/secciones_ine_agendas_gobierno_dependencias_generales.php";
	include __DIR__."/../functions/usuario_permisos.php";

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios',"secciones_ine_agendas_gobierno",$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['update'] == false && $moduloAccionPermisos['all'] == false ){
		echo "No tiene permiso.";
		die;
	}
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	if(!empty($_POST)){
		$id_seccion_ine_agenda_gobierno = $_POST['seccion_ine_agenda_gobierno'][0]['id'];
		$seccion_ine_agenda_gobiernoClaveVerificacion = seccion_ine_agenda_gobiernoClaveVerificacion($_POST["seccion_ine_agenda_gobierno"][0]["clave"], $_POST["seccion_ine_agenda_gobierno"][0]['id'], 1);

		if ($seccion_ine_agenda_gobiernoClaveVerificacion) {
			$claveF = clave2("secciones_ine_agendas_gobierno");
			if (empty($claveF['input'])) {
				echo "Favor de Ingresar una Clave Válida o que no exista en sistema.";
				die;
			} else {
				$_POST["seccion_ine_agenda_gobierno"][0]["clave"] = $claveF["clave"];
			}
		}

		$success = true;
		$entra = false;
		$conexion->autocommit(FALSE);

		// Validar existencia y formato correcto de POST
		if (registrosCompara("secciones_ine_agendas_gobierno", $_POST['seccion_ine_agenda_gobierno'][0], 1)) {
			if (!empty($_POST)) {
				$entra = true;

				// Obtener datos actuales de la sección para actualizar
				$seccion_ine_agenda_gobiernoDatos = seccion_ine_agenda_gobiernoDatos($_POST['seccion_ine_agenda_gobierno'][0]['id']);

				// Actualizar variables
				$_POST["seccion_ine_agenda_gobierno"][0]['fechaR'] = $fechaH;
				$_POST["seccion_ine_agenda_gobierno"][0]['codigo_plataforma'] = $codigo_plataforma;
				$_POST["seccion_ine_agenda_gobierno"][0]["referencia_importacion"] = $seccion_ine_agenda_gobiernoDatos['referencia_importacion'];

				// Construcción de la query de actualización
				$update_secciones_ine_agendas_gobierno = "UPDATE secciones_ine_agendas_gobierno SET ";
				$valueSets = [];
				$id = null;

				foreach ($_POST['seccion_ine_agenda_gobierno'][0] as $key => $value) {
					if ($key != 'id') {
						$valueSets[] = "$key = ?";
					} else {
						$id = $value;
					}
				}

				if ($id === null) {
					echo "ERROR: El ID está vacío.";
					exit();
				}

				$update_secciones_ine_agendas_gobierno .= implode(", ", $valueSets) . " WHERE id = ?";
				// Preparar sentencia
				$stmt = $conexion->prepare($update_secciones_ine_agendas_gobierno);
				if ($stmt === false) {
					echo "ERROR preparando la consulta de actualización: " . mysqli_error($conexion);
					$success = false;
					$stmt->close(); // Cerrar la sentencia preparada
					die;
				}

				// Bindear parámetros
				$bindTypes = '';
				$bindValues = [];
				foreach ($_POST['seccion_ine_agenda_gobierno'][0] as $key => $value) {
					if ($key != 'id') {
						$bindTypes .= 's'; // Ajusta el tipo según tus campos
						$bindValues[] = $value;
					}
				}

				$bindTypes .= 'i'; // ID siempre será un entero
				$bindValues[] = $id;

				// Mostrar query depurada
				//echo "QUERY UPDATE: " . $update_secciones_ine_agendas_gobierno . "<br>";
				//var_dump($bindValues);

				$stmt->bind_param($bindTypes, ...$bindValues);

				// Ejecutar UPDATE
				if (!$stmt->execute()) {
					echo "ERROR ejecutando el UPDATE: " . $stmt->error;
					$success = false;
					$stmt->close(); // Cerrar la sentencia preparada
					die;
				} else {
					//echo "UPDATE exitoso. Filas afectadas: " . $stmt->affected_rows . "<br>";
				}
				$stmt->close(); // Cerrar la sentencia preparada


				unset($_POST["seccion_ine_agenda_gobierno"][0]['id']);
				$_POST["seccion_ine_agenda_gobierno"][0]['id_seccion_ine_agenda_gobierno'] = $id;

				// Inserción en la tabla histórica
				$columns = array_keys($_POST["seccion_ine_agenda_gobierno"][0]);
				$placeholders = array_fill(0, count($columns), "?");

				$insert_secciones_ine_agendas_gobierno_historicos = "INSERT INTO secciones_ine_agendas_gobierno_historicos (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeholders) . ")";
				$insert_stmt = $conexion->prepare($insert_secciones_ine_agendas_gobierno_historicos);

				if ($insert_stmt === false) {
					echo "ERROR preparando la consulta de inserción: " . mysqli_error($conexion);
					$success = false;
					$insert_stmt->close(); // Cerrar la sentencia preparada
					die;
				}

				$bindTypes = str_repeat('s', count($columns));
				$bindValues = array_values($_POST["seccion_ine_agenda_gobierno"][0]);

				$insert_stmt->bind_param($bindTypes, ...$bindValues);

				if (!$insert_stmt->execute()) {
					echo "ERROR insertando en históricos: " . $insert_stmt->error;
					$success = false;
					$insert_stmt->close(); // Cerrar la sentencia preparada
					die;
				} else {
					//echo "Inserción en tabla histórica exitosa.<br>";
				}
				$insert_stmt->close(); // Cerrar la sentencia preparada
			}
		}

		// Validar dependencias
		$ids_dependencias = array_map('trim', explode(",", $_POST["seccion_ine_agenda_gobierno"][0]['ids_dependencias']));
		$ids_dependencias = array_filter($ids_dependencias, function($value) {
			return trim($value) !== "";
		});
		$ids_dependencias = array_values($ids_dependencias);

		if (!empty($ids_dependencias)) {
			$dependencias_array = array_combine($ids_dependencias, $ids_dependencias);
		}

		$secciones_ine_agendas_gobierno_dependenciasIdsDatos = secciones_ine_agendas_gobierno_dependenciasIdsDatos('', $_POST['seccion_ine_agenda_gobierno'][0]['id']);
		$delete_dependencias = [];
		$add_dependencias = [];

		foreach ($secciones_ine_agendas_gobierno_dependenciasIdsDatos as $key => $value) {
			if (empty($dependencias_array[$key])) {
				$delete_dependencias[] = $value['id'];
			}
		}
		foreach ($ids_dependencias as $value) {
			if (empty($secciones_ine_agendas_gobierno_dependenciasIdsDatos[$value])) {
				$add_dependencias[] = $value;
			}
		}
		// Insertar dependencias
		
		foreach ($add_dependencias as $value) {
			$entra = true;
		
			// Definir los valores
			$dep = [
				'id_seccion_ine_agenda_gobierno' => $id_seccion_ine_agenda_gobierno,
				'id_dependencia' => $value,
				'fechaR' => $fechaH,
				'codigo_plataforma' => $codigo_plataforma
			];
		
			// Crear la lista de campos y placeholders
			$fields_pdo = implode(", ", array_keys($dep)); // Sin comillas inversas
			$placeholders = implode(", ", array_fill(0, count($dep), '?'));
		
			$insert_secciones_ine_agendas_gobierno_dependencia = 
				"INSERT INTO secciones_ine_agendas_gobierno_dependencias ($fields_pdo) VALUES ($placeholders)";
		
			// Preparar la consulta
			$insert_stmt = $conexion->prepare($insert_secciones_ine_agendas_gobierno_dependencia);
		
			// Comprobar errores en la preparación
			if ($insert_stmt === false) {
				echo "ERROR al preparar la consulta de dependencias: " . mysqli_error($conexion);
				$success = false;
				$insert_stmt->close(); // Cerrar la sentencia preparada
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
				echo "ERROR al ejecutar la consulta de dependencias: " . mysqli_error($conexion);
				$insert_stmt->close(); // Cerrar la sentencia preparada
				break;
			}
			$insert_stmt->close(); // Cerrar la sentencia preparada
		}
		// Eliminar dependencias
		foreach ($delete_dependencias as $id) {
			$entra = true;
			// Prepara la consulta de eliminación
			$sql_delete = "DELETE FROM secciones_ine_agendas_gobierno_dependencias WHERE id = ?";
			$stmt = $conexion->prepare($sql_delete);
	
			if ($stmt === false) {
				echo "ERROR al preparar la consulta de eliminación: " . mysqli_error($conexion);
				$success = false;
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
	
			// Asocia el parámetro 'i' (integer) y ejecuta la consulta
			$stmt->bind_param('i', $id);
	
			if (!$stmt->execute()) {
				echo "ERROR al ejecutar eliminación: " . mysqli_error($conexion);
				$success = false;
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
	
			$stmt->close();
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
		// Reindexar el array para tener claves continuas
		$ids_dependencias_generales = array_values($ids_dependencias_generales);
		if (!empty($ids_dependencias_generales)) {
			$dependencias_generales_array = array_combine($ids_dependencias_generales, $ids_dependencias_generales);
			//var_dump($dependencias_generales_array);
		}
	
		$secciones_ine_agendas_gobierno_dependencias_generalesIdsDatos = secciones_ine_agendas_gobierno_dependencias_generalesIdsDatos('',$_POST['seccion_ine_agenda_gobierno'][0]['id']);
	
		foreach ($secciones_ine_agendas_gobierno_dependencias_generalesIdsDatos as $key => $value) {
			if(empty($dependencias_generales_array[$key])){
				$delete_dependencias_generales[] = $value['id'];
			}
		}
		foreach ($ids_dependencias_generales as $key => $value) {
			if(empty($secciones_ine_agendas_gobierno_dependencias_generalesIdsDatos[$value])){
				$add_dependencias_generales[] = $value;
			}
		}
		foreach ($add_dependencias_generales as $value) {
			$entra = true;
		
			// Definir los valores
			$dep = [
				'id_seccion_ine_agenda_gobierno' => $id_seccion_ine_agenda_gobierno,
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
				$insert_stmt->close(); // Cerrar la sentencia preparada
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
				$insert_stmt->close(); // Cerrar la sentencia preparada
				break;
			}
			$insert_stmt->close(); // Cerrar la sentencia preparada
		}

		foreach ($delete_dependencias_generales as $id) {
			$entra = true;
			// Prepara la consulta de eliminación
			$sql_delete = "DELETE FROM secciones_ine_agendas_gobierno_dependencias_generales WHERE id = ?";
			$stmt = $conexion->prepare($sql_delete);
	
			if ($stmt === false) {
				echo "ERROR al preparar la consulta de eliminación: " . mysqli_error($conexion);
				$success = false;
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
	
			// Asocia el parámetro 'i' (integer) y ejecuta la consulta
			$stmt->bind_param('i', $id);
	
			if (!$stmt->execute()) {
				echo "ERROR al ejecutar eliminación: " . mysqli_error($conexion);
				$success = false;
				$stmt->close(); // Cerrar la sentencia preparada
				break;
			}
	
			$stmt->close();
		}
		

		//locaciones
		$secciones_ine_agendas_gobierno_locacionesDatos = secciones_ine_agendas_gobierno_locacionesDatos('', $id_seccion_ine_agenda_gobierno);
		$delete_locaciones = [];
		$update_locaciones = [];

		foreach ($_POST['sub_eventos_nuevos'] as $value) {
			$entra = true;
			$value['id_seccion_ine_agenda_gobierno'] = $id_seccion_ine_agenda_gobierno;
			$value['fecha_hora'] = $value['fecha'] . " " . $value['hora'];
			$value['fechaR'] = $fechaH;
			$value['codigo_plataforma'] = $codigo_plataforma;
			$seccion_ineDatos = seccion_ineDatos($value['id_seccion_ine']);
			$value['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
			$value['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];

			$fields = array_keys($value);
			$placeholders = implode(",", array_fill(0, count($fields), "?"));
			$fields_pdo = "`" . implode("`,`", $fields) . "`";

			// Insertar en `secciones_ine_agendas_gobierno_locaciones`
			$stmt = $conexion->prepare("INSERT INTO secciones_ine_agendas_gobierno_locaciones ($fields_pdo) VALUES ($placeholders)");
			
			$paramTypes = str_repeat('s', count($value)); // Asumiendo que todos los campos son strings
			$params = array_values($value);
			
			$stmt->bind_param($paramTypes, ...$params);
			if (!$stmt->execute()) {
				$success = false;
				echo "ERROR: No se pudo insertar en 'secciones_ine_agendas_gobierno_locaciones'<br>";
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

		// Deshabilitar autocommit para iniciar una transacción
		// Extraemos los IDs de ambos arrays
		$ids_arra1 = array_column($secciones_ine_agendas_gobierno_locacionesDatos, 'id');
		$ids_arra2 = array_column($_POST['sub_eventos_registrados'], 'id');

		// Obtenemos los IDs que están en $ids_arra1 pero no en $ids_arra2
		$sub_eventos_delete = array_diff($ids_arra1, $ids_arra2);

		$query = "DELETE FROM secciones_ine_agendas_gobierno_locaciones WHERE id = ?";
		$stmt = $conexion->prepare($query);

		if (!$stmt) {
			// Si no se puede preparar la consulta, deshacer transacciones y salir
			die("Error al preparar la consulta de eliminación: " . $conexion->error);
		}

		// Iterar sobre el array y ejecutar la consulta para cada elemento
		foreach ($sub_eventos_delete as $value) {
			$entra == true;
			// Vincular el parámetro
			$stmt->bind_param("i", $value);
			if (!$stmt->execute()) {
				$success = false;
				echo "ERROR: No se pudo eliminar en 'secciones_ine_agendas_gobierno_locaciones'<br>";
				break; // Sale del bucle si hay un error
			}

			$num = $stmt->affected_rows;
			if ($num == 0) {
				//echo "No se encontraron registros para eliminar con id: $value<br>";
			}
		}

		foreach ($_POST['sub_eventos_registrados'] as $key => $value) {
			// Agregamos datos adicionales al registro
			$value['id_seccion_ine_agenda_gobierno'] = $id_seccion_ine_agenda_gobierno;
			$value['fecha_hora'] = $value['fecha'] . " " . $value['hora'];
			$value['codigo_plataforma'] = $codigo_plataforma;

			$seccion_ineDatos = seccion_ineDatos($value['id_seccion_ine']);
			$value['id_distrito_local'] = $seccion_ineDatos['id_distrito_local'];
			$value['id_distrito_federal'] = $seccion_ineDatos['id_distrito_federal'];

			if (registrosCompara("secciones_ine_agendas_gobierno_locaciones", $value, 1)) {
				$entra = true;
				$value['fechaR'] = $fechaH;

				// Generar los campos y valores dinámicamente para la actualización
				$fields = array_keys($value);
				$placeholders = implode(",", array_fill(0, count($fields), "?"));
				$fields_update = implode(" = ?, ", $fields) . " = ?";

				// UPDATE en `secciones_ine_agendas_gobierno_locaciones`
				$updateQuery = "UPDATE secciones_ine_agendas_gobierno_locaciones SET $fields_update WHERE id = ?";
				$stmt = $conexion->prepare($updateQuery);

				if (!$stmt) {
					echo "Error al preparar el UPDATE: " . $conexion->error . "<br>";
					$success = false;
					break;
				}

				$paramTypes = str_repeat('s', count($value)) . 'i'; // Asumiendo 'id' como entero
				$params = array_merge(array_values($value), [$value['id']]);
				$stmt->bind_param($paramTypes, ...$params);

				if (!$stmt->execute()) {
					echo "Error al ejecutar el UPDATE: " . $stmt->error . "<br>";
					$success = false;
					break;
				}

				$value['id_seccion_ine_agenda_gobierno_locacion'] = $value['id'];
				unset($value['id']);
				$fields = array_keys($value);
				$placeholders = implode(",", array_fill(0, count($fields), "?"));
				// INSERT en `secciones_ine_agendas_gobierno_locaciones_historicos`
				$insertQuery = "INSERT INTO secciones_ine_agendas_gobierno_locaciones_historicos (" . implode(",", $fields) . ") VALUES ($placeholders)";
				$stmt = $conexion->prepare($insertQuery);

				if (!$stmt) {
					echo "Error al preparar el INSERT: " . $conexion->error . "<br>";
					$success = false;
					break;
				}

				$stmt->bind_param(str_repeat('s', count($value)), ...array_values($value));

				if (!$stmt->execute()) {
					echo "Error al ejecutar el INSERT: " . $stmt->error . "<br>";
					$success = false;
					break;
				}
			}
		}
		// Confirmación y commit
		if($entra == true){
			if($success){
				$log= logUsuario($_COOKIE["id_usuario"],'secciones_ine_agendas_gobierno',$id_seccion_ine_agenda_gobierno,'Update','',$fechaH);
				if($log==true){
					echo "SI";
					$conexion->commit();
					$conexion->close();
				}else{
					echo "NO";
					$conexion->rollback();
					$conexion->close();
				}
			}else{
				echo "NO";
				$conexion->rollback();
				$conexion->close();
			}
		}

		$conexion->close();



	}
	