<?php
	function registrosComparaOld($tabla=null,$registro=null,$tipo=null) {
		include 'db.php'; 

		//foreach($registro as $keyPrincipal => $atributo) {
		//	$registro[$keyPrincipal]= mysqli_real_escape_string($conexion,$atributo);
		//}

		if($registro['id']!=""){
			if($tipo==1){
				if($registro['status']=="x"){
					$registro['status']=0;
				}
				foreach($registro as $key => $value) {
					if($key !='id'){
						//$valueSetsx[] = $key . " = BINARY '" . $value . "'";
						if($value==""){
							$valueSetsx[] = " (".$key . " IS NULL  OR ".$key . " =  '' )";
						}else{
							$valueSetsx[] = $key . " = BINARY '" . $value . "'";
						}

					}else{
						$id=$value;
					}
				}
				//"<pre>";
				$search = "SELECT * FROM {$tabla} WHERE ". join(" AND ",$valueSetsx) . " AND id=".$id;
				$search;
				//"</pre>";
				$resultSearch = $conexion->query($search);
				if($conexion->error!=""){
					var_dump($conexion->error);
				}
				$rowSearch=$resultSearch->fetch_assoc();
				$id=$rowSearch['id'];
				if(empty($id)){
					$return= true;
				}else{
					$return= false;
				}
			}
		}
		$conexion->close();
		return $return;
	}
	


	function registrosCompara1($tabla=null, $registro=null, $tipo=null) {
		include 'db.php'; 
	
		// Verifica si el ID está presente
		if ($registro['id'] != "") {
			if ($tipo == 1) {
				// Verificar si 'status' existe antes de usarlo
				if (isset($registro['status']) && $registro['status'] == "x") {
					$registro['status'] = 0;
				} elseif (!isset($registro['status'])) {
					// Si no existe 'status', no hacemos nada o podemos asignar un valor por defecto
					//$registro['status'] = 0;  // O cualquier valor que tenga sentido
				}
	
				// Preparamos los demás parámetros de la consulta
				$valueSetsx = [];
				$params = [];
				$types = ""; // Para definir la cadena de tipos de los parámetros
	
				// Preparar la parte del SET de la consulta con los parámetros seguros
				foreach ($registro as $key => $value) {
					if ($key != 'id') {
						if ($value == "") {
							// Tratamos los campos vacíos como NULL o vacío
							$valueSetsx[] = "$key IS NULL OR $key = ''";
						} else {
							$valueSetsx[] = "$key = ?";
							$params[] = $value;
							$types .= "s"; // "s" significa string
						}
					} else {
						$id = $value; // Guardamos el valor de id para usarlo en el WHERE
					}
				}
	
				// Añadimos el "id" en el WHERE con el tipo 'i' (int)
				$search = "SELECT * FROM {$tabla} WHERE " . join(" AND ", $valueSetsx) . " AND id = ?";
				$params[] = $id; // Agregar el id al array de parámetros
				$types .= "i"; // El tipo de dato para 'id' es un entero, por eso 'i'
	
				// Preparar la declaración de la consulta
				if ($stmt = $conexion->prepare($search)) {
	
					// Vinculamos los parámetros de forma segura
					// El primer parámetro es la cadena de tipos de los parámetros, el resto son los valores
					$stmt->bind_param($types, ...$params);
	
					// Ejecutamos la consulta
					$stmt->execute();
	
					// Obtenemos los resultados
					$resultSearch = $stmt->get_result();
	
					// Si la consulta tiene errores
					if ($stmt->error) {
						die('Error al ejecutar la consulta: ' . $stmt->error);
					}
	
					// Comprobar si se obtuvo un resultado
					$rowSearch = $resultSearch->fetch_assoc();
	
					// Comprobar si 'rowSearch' contiene el índice 'id'
					if (isset($rowSearch['id'])) {
						$id = $rowSearch['id'];
						if (empty($id)) {
							$return = true; // No existe, se puede agregar
						} else {
							$return = false; // Existe, no se puede agregar
						}
					} else {
						// Si no se encontró el índice 'id', manejarlo de manera adecuada
						$return = true; // O alguna otra lógica en caso de error, como permitir agregar
					}
	
					// Cerrar la declaración
					$stmt->close();
				} else {
					// Si no se puede preparar la declaración
					die('Error al preparar la consulta SQL: ' . $conexion->error);
				}
			}
		}
	
		// Cerrar la conexión a la base de datos
		$conexion->close();
		$stmt->close();
		return $return;
	}
	function registrosCompara($tabla = null, $registro = null, $tipo = null) {
    include 'db.php'; 
    
    $return = false; // Valor predeterminado
    
    // Verifica si el ID está presente
    if ($registro['id'] != "") {
        if ($tipo == 1) {
            // Verificar si 'status' existe antes de usarlo
            if (isset($registro['status']) && $registro['status'] == "x") {
                $registro['status'] = 0;
            }
    
            // Preparamos los demás parámetros de la consulta
            $valueSetsx = [];
            $params = [];
            $types = ""; // Para definir la cadena de tipos de los parámetros
    
            // Preparar la parte del SET de la consulta con los parámetros seguros
            foreach ($registro as $key => $value) {
                if ($key != 'id') {
                    if ($value == "") {
                        // Tratamos los campos vacíos como NULL o vacío
                        $valueSetsx[] = "$key IS NULL OR $key = ''";
                    } else {
                        $valueSetsx[] = "$key = ?";
                        $params[] = $value;
                        $types .= "s"; // "s" significa string
                    }
                } else {
                    $id = $value; // Guardamos el valor de id para usarlo en el WHERE
                }
            }
    
            // Añadimos el "id" en el WHERE con el tipo 'i' (int)
            $search = "SELECT * FROM {$tabla} WHERE " . join(" AND ", $valueSetsx) . " AND id = ?";
            $params[] = $id; // Agregar el id al array de parámetros
            $types .= "i"; // El tipo de dato para 'id' es un entero, por eso 'i'
    
            // Preparar la declaración de la consulta
            if ($stmt = $conexion->prepare($search)) {
                // Vinculamos los parámetros de forma segura
                $stmt->bind_param($types, ...$params);
    
                // Ejecutamos la consulta
                $stmt->execute();
    
                // Almacenamos el resultado
                $stmt->store_result();
    
                // Vinculamos los resultados dinámicamente
                $rowSearch = [];
                $stmt->bind_result($rowSearch);
    
                // Verificar si hay filas en el resultado
                if ($stmt->num_rows > 0) {
                    $stmt->fetch();
                    if (!empty($rowSearch['id'])) {
                        $return = false; // Existe, no se puede agregar
                    } else {
                        $return = true; // No existe, se puede agregar
                    }
                } else {
                    $return = true; // Si no hay filas, permitir agregar
                }
    
                // Cerrar la declaración
                $stmt->close();
            } else {
                // Si no se puede preparar la declaración
                die('Error al preparar la consulta SQL: ' . $conexion->error);
            }
        }
    }
    
    // Cerrar la conexión a la base de datos
    $conexion->close();
    return $return;
}

	
	
	