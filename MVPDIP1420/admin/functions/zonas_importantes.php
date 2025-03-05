<?php
	function zona_importanteDatos($id_zona_importante=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM zonas_importantes WHERE id='$id_zona_importante'   ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function zonas_importantesDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT
				sia.id,
				sia.nombre,
				case sia.tipo
					when 0 then 'Amigo'
					when 1 then 'Hostil'
					when 2 then 'Neutro'
					when 3 then 'Interés'
				end AS tipo,
				(SELECT l.localidad FROM localidades l WHERE l.id = sia.id_localidad ) localidad,
				m.municipio,
				sia.latitud,
				sia.longitud,
				sia.observaciones,
				sia.calle,
				sia.num_int,
				sia.num_ext,
				sia.colonia,
				sia.codigo_postal
			FROM zonas_importantes sia
			LEFT JOIN municipios m on sia.id_municipio = m.id 
			WHERE 1 = 1 
			";

		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			if($value !=""){
				if($key!="fecha_1" && $key!="fecha_2"){
					if($key=="clave"){
						$sql.= " AND  sia.{$key} LIKE '%{$value}%' ";
					}elseif($key=="folio"){
						$sql.= " AND  sia.{$key} LIKE '%{$value}%' ";
					}elseif($key=="nombre"){
						$sql.= " AND  sia.{$key} LIKE '%{$value}%' ";
					}elseif($key=="id_distrito_local"){
						$sql.= " AND  si.{$key} IN ({$value}) ";
					}elseif($key=="id_distrito_federal"){
						$sql.= " AND  si.{$key} IN ({$value}) ";
					}else{
						if($key == $key == "id_municipio" || $key == "id_localidad" ){
							$sql.= " AND  sia.{$key} IN ({$value}) ";
						}else{
							$sql.= " AND  sia.{$key} = '{$value}' ";
						}
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

		if($orderby!=""){
			$sql.=" {$orderby} ";
		}

		if($limit!=""){
			$sql.=" {$limit} ";
		}
		//echo "<pre>";
		//echo $sql;
		//echo "</pre>";
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