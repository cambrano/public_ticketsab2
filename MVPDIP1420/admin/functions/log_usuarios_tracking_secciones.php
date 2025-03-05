<?php
	function filtrosSelect($columna=null) {
		if($columna==''){
			die;
		}
		include 'db.php';  
		$id;
		$select[$columna]='selected="selected"';
		$return ="<option ".$select[$columna]." value='' >Seleccione</option> ";
		$sql = "SELECT {$columna} columna FROM log_usuarios_tracking_secciones WHERE 1 = 1 ";
		$sql .= " GROUP BY {$columna} ";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['columna']."' >".$row['columna']."</option> ";
		}
		$conexion->close();
		return $return;
	}

	function filtrosSelectUsuarios($columna=null) {
		if($columna==''){
			die;
		}
		include 'db.php';  
		$return ="<option value='' >Seleccione</option> ";
		$sql = "SELECT 
				    lut.id_usuario,
				    COALESCE(u.usuario, lut.id_usuario) AS usuario,
				    CASE 
				        WHEN u.tabla = 'empleados' THEN 'empleado'
				    END AS tabla_tipo,
				    CASE 
				        WHEN u.tabla = 'empleados' THEN
				            CONCAT(e.nombre, ' ', e.apellido_paterno, ' ', e.apellido_materno)
				    END AS nombre_usuario,
				    u.usuario
				FROM log_usuarios_tracking_secciones lut 
				LEFT JOIN usuarios u ON lut.id_usuario = u.id
				LEFT JOIN empleados e ON u.id_empleado = e.id AND u.tabla = 'empleados'
				WHERE lut.id_usuario <> 1
				GROUP BY lut.id_usuario;
";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option  value='".$row['id_usuario']."' >".$row['nombre_usuario'].' - '.$row['usuario'].' - '.$row['tabla_tipo']."</option> ";
		}
		$conexion->close();
		return $return;
	}


	function log_usuarios_tracking_seccionesDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
				id,
				usuario,
				fechaR,
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
				tipo_usuario,
				nombre_completo,
				alerta_m,
				Paguinasub,
				paguinaId,
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
				alerta_m,
				alerta_seccion,
				punto_latitud,
				punto_longitud
			FROM log_usuarios_tracking_secciones
			WHERE 1 = 1 AND id_usuario !=1 
			";

		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			if($value !=""){
				if($key!="fecha_1" && $key!="fecha_2"){
					if($key=="alerta"){
						if($value=="x"){
							$sql.= " AND  {$key} IS NULL ";
						}else{
							$sql.= " AND  {$key} = '{$value}' ";
						}
					}else{
						$sql.= " AND  {$key} = '{$value}' ";
					}
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
		$sql;
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