<?php
	function secciones_ine_giras($id=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql="SELECT 
		id,nombre,tipo
		FROM secciones_ine_giras WHERE 1 = 1 ORDER BY clave ASC";
	
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre'].' - '.strtoupper($row['tipo'])."</option> ";
		} 
		$conexion->close();
		return $return;
	}


	function seccion_ine_giraDatos($id_seccion_ine_gira=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_giras WHERE id='$id_seccion_ine_gira'   ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function seccion_ine_giraClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM secciones_ine_giras WHERE 1 = 1 ");
			if($clave!=""){
				$sql.=" AND clave='{$clave}' ";
			}
			if($id!=""){
				$sql.=" AND id !='{$id}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['id']; 
			return $datos;
		}


	function secciones_ine_girasDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT
				sia.id,
				sia.clave,
				sia.folio,
				sia.tipo,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_giras sicg WHERE sicg.id_seccion_ine_gira = sia.id) participantes,
				sia.nombre,
				sia.fecha,
				sia.hora,
				sia.observaciones,
				sia.fecha_hora,
				m.municipio,
				(SELECT l.localidad FROM localidades l WHERE l.id = sia.id_localidad ) localidad,
				sia.calle,
				sia.colonia,
				sia.codigo_postal,
				si.numero seccion,
				dl.numero distrito_local,
				df.numero distrito_federal,
				sia.latitud,
				sia.longitud
				
			FROM secciones_ine_giras sia
			LEFT JOIN secciones_ine si on sia.id_seccion_ine = si.id
			LEFT JOIN distritos_locales dl on si.id_distrito_local = dl.id
			LEFT JOIN distritos_federales df on si.id_distrito_federal = df.id
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
						$sql.= " AND  si.{$key} LIKE '%{$value}%' ";
					}elseif($key=="id_distrito_federal"){
						$sql.= " AND  si.{$key} LIKE '%{$value}%' ";
					}else{
						if($key == "tipo" || $key == "id_seccion_ine_gira" || $key == "id_seccion_ine" || $key == "id_municipio" || $key == "id_localidad"){
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
		if( $fecha_1 != '' && $fecha_2 == ''){ 
			$sql.=" AND sia.fecha <= '{$fecha_1}' ";
		}

		if( $fecha_1 == '' && $fecha_2 != ''){ 
			$sql.=" AND sia.fecha >= '{$fecha_2}' ";
		}
		if( $fecha_1 != '' && $fecha_2 != ''){ 
			$sql.=" AND sia.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
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