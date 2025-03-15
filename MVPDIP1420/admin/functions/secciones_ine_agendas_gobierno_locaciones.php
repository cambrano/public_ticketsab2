<?php
		function secciones_ine_agendas_gobierno_locacionesDatos($id=null,$id_seccion_ine_agenda_gobierno=null,$id_seccion_ineL=null,$id_distrito_localL=null,$id_distrito_federalL=null,$id_municipioL=null,$id_localidadL=null,$orden=null,$limit=null){
			include 'db.php'; 
			$sql="SELECT 
					*,
					(SELECT s.numero FROM secciones_ine s WHERE s.id = siagl.id_seccion_ine) seccion_ine,
					(SELECT l.localidad FROM localidades l WHERE l.id = siagl.id_localidad) localidad,
					(SELECT m.municipio FROM municipios m WHERE m.id = siagl.id_municipio) municipio
					FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND siagl.id = '{$id}' ";
			}
			if($id_seccion_ine_agenda_gobierno!=""){
				$sql.=" AND siagl.id_seccion_ine_agenda_gobierno = '{$id_seccion_ine_agenda_gobierno}' ";
			}
			if($id_seccion_ineL!=""){
				$sql.=" AND siagl.id_seccion_ine = '{$id_seccion_ineL}' ";
			}
			if($id_municipioL!=""){
				$sql.=" AND siagl.id_municipio = '{$id_municipioL}' ";
			}
			if($id_localidadL!=""){
				$sql.=" AND siagl.id_localidad = '{$id_localidadL}' ";
			}
			if($id_distrito_localL!=""){
				$sql.=" AND siagl.id_distrito_local = '{$id_distrito_localL}' ";
			}
			if($id_distrito_federalL!=""){
				$sql.=" AND siagl.id_distrito_federal = '{$id_distrito_federalL}' ";
			}
			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}
			if($limit!=""){
				$sql.=" {$limit} ";
			}
			//echo "<pre>";
			//var_dump($sql);
			//echo "</pre>";die;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function secciones_ine_agendas_gobierno_locacionesDatosArray($registros=null,$orderby=null,$limit=null) {
			include 'db.php'; 
			$sql="
				SELECT 
					*,
					(SELECT s.numero FROM secciones_ine s WHERE s.id = siagl.id_seccion_ine) seccion_ine,
					(SELECT l.localidad FROM localidades l WHERE l.id = siagl.id_localidad) localidad,
					(SELECT m.municipio FROM municipios m WHERE m.id = siagl.id_municipio) municipio
				FROM secciones_ine_agendas_gobierno_locaciones siagl 
				WHERE 1 = 1 
				";
				foreach ($registros as $key => $value) {
					//echo $key;
					//echo "-";
					//echo $value;
					//echo "<br>";
					if($value !=""){
						if($key!="fecha_1" && $key!="fecha_2"){
							if($key == "id_seccion_ine" || $key == "id_municipio" || $key == "id_localidad" || $key == "id_distrito_local" || $key == "id_distrito_federal"){
								$sql.= " AND  siagl.{$key} IN ({$value}) ";
							}else{
								$sql.= " AND  siagl.{$key} = '{$value}' ";
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
					$sql.=" AND siagl.fecha <= '{$fecha_1}' ";
				}
		
				if( $fecha_1 == '' && $fecha_2 != ''){ 
					$sql.=" AND siagl.fecha >= '{$fecha_2}' ";
				}
				if( $fecha_1 != '' && $fecha_2 != ''){ 
					$sql.=" AND siagl.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
				}
		
				if($orderby!=""){
					$sql.=" ORDER BY {$orderby} ";
				}
		
				if($limit!=""){
					$sql.=" {$limit} ";
				}
				//echo "<pre>";
				//echo $sql;
				//echo "</pre>";
				//die;
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

		
?>