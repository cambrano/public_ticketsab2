<?php
	function filtrosSelect($columna=null) {
		if($columna==''){
			die;
		}
		include 'db.php';  
		$id;
		$select[$columna]='selected="selected"';
		$return ="<option ".$select[$columna]." value='' >Seleccione</option> ";
		$sql = "SELECT {$columna} columna FROM log_sesiones WHERE 1 = 1 ";
		$sql .= " GROUP BY {$columna} ";
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['columna']."' >".$row['columna']."</option> ";
		}
		$conexion->close();
		return $return;
	}


	function log_sesionesDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
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
			FROM log_sesiones
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