<?php
    function municipios($conexion, $id_municipioL = null, $id_estadoL = null, $sin_seleccione = null){
        $id;
        //$id_municipioL = 1814;
        $select[$id_municipioL] = 'selected="selected"';
        if ($sin_seleccione == "") {
            $return = "<option " . $select[$id_municipioL] . " value='' >Seleccione</option> ";
        }
        if (!empty($id_estadoL)) {
            $sql = "SELECT * FROM municipios WHERE id_estado={$id_estadoL} ";
            $result = $conexion->query($sql);
            while ($row = $result->fetch_assoc()) {
                $sel = $row['id'];
                $return .= "<option " . $select[$sel] . " value='" . $row['id'] . "' >" . $row['clave'] . " - " . $row['municipio'] . "</option> ";
            }
        }
        return $return;
    }
    function localidades($conexion, $id_municipioL = null, $id_estadoL = null, $sin_seleccione = null){
        $id;
        //$id_municipioL = 1814;
        //$select[$id_municipioL] = 'selected="selected"';
        if ($sin_seleccione == "") {
            $return = "<option " . $select[$id_municipioL] . " value='' >Seleccione</option> ";
        }
        if (!empty($id_municipioL)) {
            $sql = "SELECT * FROM localidades WHERE id_municipio={$id_municipioL} ";
            $result = $conexion->query($sql);
            while ($row = $result->fetch_assoc()) {
                $sel = $row['id'];
                $return .= "<option " . $select[$sel] . " value='" . $row['id'] . "' >" . $row['clave'] . " - " . $row['localidad'] . "</option> ";
            }
        }
        return $return;
    }
    function validarClaveElector($clave) {
        // Expresión regular para validar la clave de elector
        $regex = '/^[A-Z]{6}\d{8}[A-Z]\d{3}$/';
        return preg_match($regex, $clave) === 1;
    }
    function validarFecha($fechaString, $formato = 'Y-m-d') {
        $fecha = DateTime::createFromFormat($formato, $fechaString);
        if ($fecha === false) {
            return false; // No se pudo crear el objeto DateTime
        }
        $errores = DateTime::getLastErrors();
        // Verificar si hay errores en la creación de la fecha
        if ($errores['warning_count'] + $errores['error_count'] > 0) {
            return false; // Fecha no válida
        }
        return true; // Fecha válida
    }
    function visitas($conexion = null, $data = null){
        // Iniciar una transacción
        $conexion->begin_transaction();
        try {
            // Consulta preparada para la inserción segura
            $query = "INSERT INTO visitas (
                `hash_user`, 
                `http_user_agent`, 
                `http_sec_ch_ua_platform`, 
                `http_origin`, 
                `server_name`, 
                `script_name`, 
                `ip`, 
                `fecha`, 
                `hora`, 
                `fecha_hora`,
                `tipo`
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Preparar la consulta
            $stmt = $conexion->prepare($query);
            if ($stmt === false) {
                throw new Exception("Error en la consulta: " . $conexion->error);
            }

            // Vincular parámetros
            $stmt->bind_param(
                "sssssssssss",
                $data["hash_user"],
                $data["http_user_agent"],
                $data["http_sec_ch_ua_platform"],
                $data["http_origin"],
                $data["server_name"],
                $data["script_name"],
                $data["ip"],
                $data["fecha"],
                $data["hora"],
                $data["fecha_hora"],
                $data["tipo"]
            );

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Confirmar los cambios
                $conexion->commit();
                // Cerrar la consulta
                $stmt->close();
                // Cierra la conexión
                //$conexion->close();
                return array('success' => true);
            } else {
                // Revertir los cambios en caso de error
                $conexion->rollback();
                // Cerrar la consulta
                $stmt->close();
                // Cierra la conexión
                //$conexion->close();
                throw new Exception("Error al insertar el registro: " . $stmt->error);
            }
        } catch (Exception $e) {
            // Revertir los cambios en caso de excepción
            $conexion->rollback();
            // Cerrar la consulta
            $stmt->close();
            // Cierra la conexión
            //$conexion->close();
            return array('success' => false, 'error' => $e->getMessage());
        }
    }
    function bloquearIP($conexion = null, $tiempo_abreviacion = null, $data = null){
        // Verificar si las variables esenciales están definidas
        if (!$conexion || !$tiempo_abreviacion || !$data) {
            return array('success' => false, 'error' => 'Faltan parámetros esenciales.');
        }

        // Descomponer la abreviación del tiempo
        list($tiempo, $tiempo_tipo) = explode('-', $tiempo_abreviacion);

        // Verificar si el tiempo es un número positivo
        if (!is_numeric($tiempo) || $tiempo <= 0) {
            return array('success' => false, 'error' => 'El tiempo debe ser un número positivo.');
        }

        // Verificar si el tipo de tiempo es válido
        $fecha_abreviacion = array(
            'Y' => array('years', 'year'),
            'M' => array('months', 'month'),
            'D' => array('days', 'day'),
            'H' => array('hours', 'hour'),
            'MM' => array('minutes', 'minute'),
            'S' => array('seconds', 'second'),
        );

        if (!isset($fecha_abreviacion[$tiempo_tipo])) {
            return array('success' => false, 'error' => 'Tipo de tiempo no válido.');
        }

        $tiempo_formato = ($tiempo > 1) ? $fecha_abreviacion[$tiempo_tipo][0] : $fecha_abreviacion[$tiempo_tipo][1];

        // Guardar el tiempo de bloqueo
        $data['tiempo'] = $tiempo_abreviacion;

        // Calcular la fecha y hora final
        $fecha_hora = $data['fecha_hora'];
        $date_time = new DateTime($fecha_hora);
        $date_time->modify('+' . $tiempo . ' ' . $tiempo_formato);
        $data['fecha_hora_final'] = $date_time->format('Y-m-d H:i:s');
        $data['status'] = 1;

        // Iniciar una transacción
        $conexion->begin_transaction();

        try {
            // Construir la consulta preparada
            unset($data['tipo']);
            $columnas = "`" . implode("`,`", array_keys($data)) . "`";
            $signos = implode(",", array_fill(0, count($data), '?'));
            $query = "INSERT INTO ips_bloqueados ($columnas) VALUES ($signos)";

            // Preparar la consulta
            $stmt = $conexion->prepare($query);
            if (!$stmt) {
                //echo "Error en la consulta: " . $conexion->error;
                //$stmt->close();
                return array('success' => false, 'error' => $conexion->error);
                //throw new Exception("Error en la consulta: " . $conexion->error);
            }

            // Vincular parámetros
            $parametros = array_values($data);
            $tipos = str_repeat('s', count($parametros));
            array_unshift($parametros, $tipos); // Agregar tipos al principio
            call_user_func_array([$stmt, 'bind_param'], $parametros);

            // Ejecutar la consulta
            if (!$stmt->execute()) {
                throw new Exception("Error al insertar el registro: " . $stmt->error);
                //return array('success' => false, 'error' => $stmt->error);
            }
            // Confirmar los cambios
            $conexion->commit();
            // Cerrar la consulta
            $stmt->close();
            return array('success' => true);
        } catch (Exception $e) {
            // Revertir los cambios en caso de excepción
            $conexion->rollback();
            // Cerrar la consulta
            if (isset($stmt)) {
                $stmt->close();
            }
            return array('success' => false, 'error' => $e->getMessage());
        }

    }
    function ip_bloqueado($conexion = null, $id = null,$ip = null, $status = null, $fecha_hora){
        $sql = " SELECT ip FROM ips_bloqueados WHERE 1 ";
        if($id != ''){
            $sql.= " AND id = '{$id}' ";
        }
        if($ip != ''){
            $sql.= " AND ip = '{$ip}' ";
        }
        if($status != ''){
            $sql.= " AND status = '{$status}' ";
        }
        // Execute the SQL query
        $resultado = $conexion->query($sql);
        // Fetch the result as an associative array
        $row = $resultado->fetch_assoc();
        // Store the result in the $datos variable
        $datos = $row;

        return $datos;
    }
    function ips_bloqueados($conexion = null, $id = null,$ip = null, $status = null, $fecha_hora){
        $sql = " SELECT ip FROM ips_bloqueados WHERE 1 ";
        if($id != ''){
            $sql.= " AND id = '{$id}' ";
        }
        if($ip != ''){
            $sql.= " AND ip = '{$ip}' ";
        }
        if($status != ''){
            $sql.= " AND status = '{$status}' ";
        }
        $resultado = $conexion->query($sql);
		while($row=$resultado->fetch_assoc()){
			$datos[]=$row;
		} 
		$conexion->close(); 
		return $datos;
    }
    function ips_bloqueadosHtaccess($conexion = null, $id = null,$ip = null, $status = null, $fecha_hora){
        $sql = " SELECT ip FROM ips_bloqueados WHERE 1 ";
        if($id != ''){
            $sql.= " AND id = '{$id}' ";
        }
        if($ip != ''){
            $sql.= " AND ip = '{$ip}' ";
        }
        if($status != ''){
            $sql.= " AND status = '{$status}' ";
        }
        $resultado = $conexion->query($sql);
		while($row=$resultado->fetch_assoc()){
			$datos[]=$row;
		}
        // Ruta del archivo .htaccess
        $nombre_archivo = '.htaccess';
        // Intenta abrir el archivo en modo lectura
        $archivo_lectura = fopen($nombre_archivo, 'r');
        // Verifica si se pudo abrir el archivo de lectura
        if ($archivo_lectura === false) {
            $mensaje['success'] = false;
            $mensaje['mensaje'] = 'No se pudo abrir el archivo de lectura.';
            return $mensaje;
        } else {
            // Lee las primeras 80 líneas del archivo
            $lineas_originales = array();
            $numero_linea = 1;
            while (!feof($archivo_lectura) && $numero_linea <= 80) {
                $lineas_originales[] = fgets($archivo_lectura);
                $numero_linea++;
            }
            // Cierra el archivo de lectura
            fclose($archivo_lectura);
            // Abre el archivo en modo escritura, creándolo si no existe
            $archivo_escritura = fopen($nombre_archivo, 'w');
            // Verifica si se pudo abrir el archivo de escritura
            if ($archivo_escritura === false) {
                $mensaje['success'] = false;
                $mensaje['mensaje'] = 'No se pudo abrir el archivo de escritura.';
                return $mensaje;
            } else {
                // Escribe las líneas originales en el nuevo archivo
                foreach ($lineas_originales as $linea) {
                    fwrite($archivo_escritura, $linea);
                }
                // Escribe las nuevas reglas en el archivo
                fwrite($archivo_escritura, "\n");
                foreach ($datos as $value) {
                    $linea = "Deny from " . $value['ip'] . "\n";
                    fwrite($archivo_escritura, $linea);
                    fwrite($archivo_escritura, "\n");
                }
                fwrite($archivo_escritura, "\n");
                // Cierra el archivo de escritura después de escribir
                fclose($archivo_escritura);
                $mensaje['success'] = true;
                $mensaje['mensaje'] = 'Se han añadido las reglas al archivo .htaccess correctamente.';
                return $mensaje;
            }
        }
    }
    function visitasConteo($conexion = null, $maximo = null, $ip = null, $tipo = null, $tiempo_seg = null, $fecha_hora = null){
        // Check if $maximo is set to 'random'; if so, generate a random number between 10 and 20
        if ($maximo == 'random') {
            $maximo = rand(10, 15);
        }
        // Create a DateTime object based on the provided $fecha_hora
        $date_time = new DateTime($fecha_hora);
        // Subtract $tiempo_seg seconds from the DateTime object
        $date_time->modify('-' . $tiempo_seg . ' seconds');
        // Get the new date and time as a formatted string
        $fecha_hora_atras = $date_time->format('Y-m-d H:i:s');

        // Construct the SQL query to count records in the 'visitas' table based on specified conditions
        $sql = "SELECT COUNT(*) as conteo FROM visitas WHERE 1";
        if ($ip != '') {
            $sql .= " AND ip='{$ip}'";
        }
        if ($tipo != '') {
            $sql .= " AND tipo='{$tipo}'";
        }
        if ($fecha_hora != '') {
            $sql .= " AND fecha_hora BETWEEN '$fecha_hora_atras' AND '$fecha_hora'";
        }

        // Execute the SQL query
        $resultado = $conexion->query($sql);
        // Fetch the result as an associative array
        $row = $resultado->fetch_assoc();
        // Store the result in the $datos variable
        $datos = $row;

        if($datos['conteo'] > $maximo ){
            $datos['bloqueo'] = 1;
        }else{
            $datos['bloqueo'] = 0;
        }

        // Return the result
        return $datos;
    }

