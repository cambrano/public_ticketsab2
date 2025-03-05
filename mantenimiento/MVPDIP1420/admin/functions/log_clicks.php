<?php
	function filtrosSelect($columna=null) {
		if($columna==''){
			die;
		}
		include 'db.php';  
		$id;
		$select[$columna]='selected="selected"';
		$return ="<option ".$select[$columna]." value='' >Seleccione</option> ";
		$sql = "SELECT {$columna} columna FROM log_clicks WHERE 1 = 1 ";
		$sql .= " GROUP BY {$columna} ";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['columna']."' >".$row['columna']."</option> ";
		}
		$conexion->close();
		return $return;
	}


	function log_clicksDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
				fechaR,
				script_name,
				server_name,
				browser,
				os,
				ip,
				hostname,
				ip_type,
				isp,
				org,
				asname,
				loc,
				loc_script,
				city,
				region,
				country,
				direccion_calle,
				direccion_numero,
				direccion_colonia,
				direccion_completa,
				zip_code,
				alerta_m,
				type,
				domain,
				user_agent,
				latitud_script,
				longitud_script,
				latitud,
				longitud,
				case alerta 
					when 0 then 'Amigo'  
					when 1 then 'Hostil'  
					when 2 then 'Neutro'  
					when 3 then 'Interés'  
				end AS alerta,
				alerta_m
			FROM log_clicks
			WHERE 1 = 1
			";

		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			if($value !=""){
				if($key!="fecha_1" && $key!="fecha_2"){
					$sql.= " AND  {$key} = '{$value}' ";
				}
				if($key=="fecha_1"){
					$fecha_1 = $value;
				}
				if($key=="fecha_2"){
					$fecha_2 = $value;
				}
			}
		}

		if( $fecha_1 != '' && $fecha_2 == ''){ 
			$sql.=" AND fechaR <= '{$fecha_1} 23:59:59' ";
		}

		if( $fecha_1 == '' && $fecha_2 != ''){ 
			$sql.=" AND fechaR >= '{$fecha_2} 00:00:00' ";
		}

		if( $fecha_1 != '' && $fecha_2 != ''){ 
			$sql.=" AND fechaR BETWEEN '{$fecha_1} 00:00:00' AND '{$fecha_2} 23:59:59' ";
		}



		if($orderby!=""){
			$sql.=" {$orderby} ";
		}

		if($limit!=""){
			$sql.=" {$limit} ";
		}
		//$resultado = $conexion->query($sql);
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			$datos[$num]=$row;
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	}

	function log_clicksConteo($maximo = null, $ip = null, $tiempo_seg = null, $fecha_hora = null){
		if( $ip == '::1'){
			$ip = '187.147.187.148';
		}
		include 'db.php'; 
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
        $sql = "SELECT COUNT(*) as conteo FROM log_clicks WHERE 1";
        if ($ip != '') {
            $sql .= " AND ip='{$ip}'";
        }
        if ($fecha_hora != '') {
            $sql .= " AND fechaR BETWEEN '$fecha_hora_atras' AND '$fecha_hora'";
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