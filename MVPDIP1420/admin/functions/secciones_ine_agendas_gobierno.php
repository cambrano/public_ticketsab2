<?php
	function secciones_ine_agendas_gobierno($id=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql="SELECT 
		id,nombre,tipo
		FROM secciones_ine_agendas_gobierno WHERE 1 = 1 ORDER BY clave ASC";
	
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre'].' - '.strtoupper($row['tipo'])."</option> ";
		} 
		$conexion->close();
		return $return;
	}


	function seccion_ine_agenda_gobiernoDatos($id_seccion_ine_agenda_gobierno=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_agendas_gobierno WHERE id='$id_seccion_ine_agenda_gobierno'   ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function seccion_ine_agenda_gobiernoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM secciones_ine_agendas_gobierno WHERE 1 = 1 ");
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


	function secciones_ine_agendas_gobiernoDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		include 'usuarios.php';
		include 'empleados_dependencias.php';
		include 'secciones_ine_agendas_gobierno_locaciones.php';
		$sql="
			SELECT
				sia.id,
				sia.clave,
				sia.folio,
				(SELECT d.nombre FROM tipos_giras d WHERE d.id = sia.id_tipo_gira) tipo_gira,
				(SELECT d.nombre FROM dependencias d WHERE d.id = sia.id_dependencia) dependencia_coordinadora,
				sia.nombre,
				sia.observaciones,
				(
					SELECT GROUP_CONCAT(d.nombre ORDER BY d.id SEPARATOR ',<br><br> ') 
					FROM dependencias d 
					WHERE FIND_IN_SET(d.id, sia.ids_dependencias) > 0
				) AS dependencias_colaborativas,
			(SELECT e.nombre FROM ejes_gobierno e WHERE e.id = sia.id_eje_gobierno ) eje_gobierno,
			num_beneficiarios,
			num_asistentes,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.colonia ORDER BY loc.colonia SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS colonia,
			(
				SELECT GROUP_CONCAT(DISTINCT m.municipio ORDER BY m.municipio SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN municipios m
				ON loc.id_municipio = m.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS municipio,
            (
				SELECT GROUP_CONCAT(DISTINCT l.localidad ORDER BY l.localidad SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN localidades l
				ON loc.id_localidad = l.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS localidad,
            (
				SELECT GROUP_CONCAT(DISTINCT loc.fecha_hora ORDER BY loc.fecha_hora SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS fecha_hora,
            (
				SELECT GROUP_CONCAT(DISTINCT s.numero ORDER BY s.numero SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN secciones_ine s
				ON loc.id_seccion_ine = s.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS seccion,
			(SELECT loc.latitud FROM secciones_ine_agendas_gobierno_locaciones loc WHERE loc.id_seccion_ine_agenda_gobierno = sia.id LIMIT 1) latitud,
			(SELECT loc.longitud FROM secciones_ine_agendas_gobierno_locaciones loc WHERE loc.id_seccion_ine_agenda_gobierno = sia.id LIMIT 1) longitud,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.id_distrito_local ORDER BY loc.id_distrito_local SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS distrito_local,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.id_distrito_federal ORDER BY loc.id_distrito_federal SEPARATOR ', ') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS distrito_federal
			FROM secciones_ine_agendas_gobierno sia
			WHERE 1 = 1 
			";

		$usuarioDatos = usuarioDatos($_COOKIE['id_usuario']);	
		$id_empleado = $usuarioDatos['id_empleado'];
		if($id_empleado != ""){
			$empleado_dependenciaIdsDependenciasDatos = empleado_dependenciaIdsDependenciasDatos('',$id_empleado);
			if(!empty($empleado_dependenciaIdsDependenciasDatos['ids_dependencias'])){
				$sql.= " AND sia.id_dependencia IN ({$empleado_dependenciaIdsDependenciasDatos['ids_dependencias']}) ";
			}
		}

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
						$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_local IN ({$value})  )";
					}elseif($key=="id_distrito_federal"){
						$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_federal IN ({$value})  )";
					}elseif($key=="id_dependencias"){
						$sql.= " AND  sia.id_dependencia IN ({$value}) ";
					}elseif($key=="id_dependencias_colaborativas"){
						$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_dependencias sigd WHERE sigd.id_dependencia IN ({$value}) AND sigd.id_seccion_ine_agenda_gobierno = sia.id )";
					}elseif($key=="id_dependencias_general"){
						$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_dependencias_generales sigd WHERE sigd.id_dependencia IN ({$value}) AND sigd.id_seccion_ine_agenda_gobierno = sia.id )";
					}elseif($key=="id_seccion_ine"){
						$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_seccion_ine IN ({$value})  )";
					}elseif($key=="id_municipio"){
						$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_municipio IN ({$value})  )";
					}elseif($key=="id_localidad"){
						$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_localidad IN ({$value})  )";
					}else{
						if($key == "id_tipo_gira" || $key == "id_seccion_ine_agenda_gobierno"){
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
			//$sql.=" AND sia.fecha <= '{$fecha_1}' ";
			$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha <= '{$fecha_1}' )";
		}

		if( $fecha_1 == '' && $fecha_2 != ''){ 
			//$sql.=" AND sia.fecha >= '{$fecha_2}' ";
			$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha >= '{$fecha_2}' )";
		}
		if( $fecha_1 != '' && $fecha_2 != ''){ 
			//$sql.=" AND sia.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
			$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' )";
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
		if (!$result) {
			// Si la consulta falla, mostrar el error de MySQL
			//echo "Error en la consulta: " .date("H.i:s"). $conexion->error;
		} else {
			// Si la consulta fue exitosa, puedes proceder con el resto del código
			//echo "Consulta ejecutada correctamente.".date("H.i:s");
		}
		//die;
		$num=0; 
		while($row=$result->fetch_assoc()){
			$datos[$num] = $row;
			$registros['id_seccion_ine_agenda_gobierno'] = $row['id'];
			unset($registros['clave']);
			unset($registros['folio']);
			unset($registros['nombre']);
			unset($registros['id_tipo_gira']);
			unset($registros['id_dependencias']);
			unset($registros['id_dependencias_colaborativas']);
			unset($registros['id_dependencias_general']);
			unset($registros['id_eje_gobierno']);
			//$secciones_ine_agendas_gobierno_locacionesDatosArray = secciones_ine_agendas_gobierno_locacionesDatosArray('',$row['id'],'','','','','','siagl.fecha_hora ASC','');
			$secciones_ine_agendas_gobierno_locacionesDatosArray = secciones_ine_agendas_gobierno_locacionesDatosArray($registros,'siagl.fecha_hora ASC','');
			$datos[$num]['locaciones'] = $secciones_ine_agendas_gobierno_locacionesDatosArray;
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	} 