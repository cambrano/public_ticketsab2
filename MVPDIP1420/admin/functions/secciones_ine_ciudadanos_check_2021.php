<?php
	function secciones_ine_ciudadanosCheck2021DatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			e.id,
			e.clave,
			e.whatsapp,
			e.telefono,
			e.celular,
			e.calle,
			e.colonia,
			e.codigo_postal,
			e.fecha_nacimiento,
			e.latitud,
			e.longitud,
			e.distancia_km,
			e.nombre_completo,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
			(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = e.id_tipo_ciudadano ) tipo_ciudadano,
			(SELECT tm.municipio FROM municipios tm WHERE tm.id = e.id_municipio ) municipio,
			(SELECT sicc2021.check_out FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  LIMIT 1) check_out,
			(SELECT sicc2021.check_out_hora FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id LIMIT 1 ) check_out_hora,

			(SELECT sim.nombre_completo FROM secciones_ine_ciudadanos sim WHERE sim.id = e.id_seccion_ine_ciudadano_compartido LIMIT 1) relacionado
			FROM secciones_ine_ciudadanos e
			WHERE 1 = 1 
			";

		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			if($value !=""){
				if($key!="fecha_1" && $key!="fecha_2"){
					if($key=="clave" || $key=="sexo" || $key=="id_seccion_ine_ciudadano_compartido" ){
						$sql.= " AND  e.{$key} = '{$value}' ";
					}elseif($key=="nombre_completo"){
						$sql.=" AND e.nombre_completo LIKE '%{$value}%' ";
					}elseif($key=="checks"){
						$porciones = explode(",", $value);
						$cont=1;
						$sqlx;
						$tipo_check = false;
						foreach ($porciones as $keyT => $valueT) {
							if($valueT==1){
								$tipo_check = true;
								$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND check_in = 1 )";
							}
							if($valueT==2){
								$tipo_check = true;
								if(count($porciones)>1){
									$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
								}else{
									$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
								}
							}
					
							if($valueT==3){
								$tipo_check = true;
								if(count($porciones)>1){
									$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND sicc2021.check_out = 1 AND sicc2021.check_in = 1  )";
								}else{
									$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id AND sicc2021.check_out = 1 AND sicc2021.check_in = 1  )";
								}
							}
					
							if($valueT==4){
								$tipo_check = true;
								if(count($porciones)>1){
									$sqlx.=" OR NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  )";
								}else{
									$sqlx.=" AND NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2021 sicc2021 WHERE sicc2021.id_seccion_ine_ciudadano = e.id  )";
								}
							}
						}
						if($tipo_check==true && count($porciones) <4 ){
							$sql.= $sqlx;
						}
					}else{
						$sql.= " AND e.{$key} IN ($value) ";
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
	
	function seccion_ine_ciudadano_check_2021Datos($id=null,$id_seccion_ine=null,$id_municipioL=null,$id_casilla_voto_2021=null,$id_usuario=null,$id_empleado=null,$id_seccion_ine_ciudadano=null,$id_seccion_ine_ciudadano_relacionado=null){ 
		include 'db.php'; 
		$sql=" SELECT *	FROM secciones_ine_ciudadanos_check_2021 WHERE 1 = 1  ";

		if($id!=""){
			$sql.= " AND id = '{$id}' ";
		}

		if($id_seccion_ine!=""){
			$sql.= " AND id_seccion_ine = '{$id_seccion_ine}' ";
		}

		if($id_municipioL!=""){
			$sql.= " AND id_municipio = '{$id_municipioL}' ";
		}

		if($id_casilla_voto_2021!=""){
			$sql.= " AND id_casilla_voto_2021 = '{$id_casilla_voto_2021}' ";
		}

		if($id_usuario!=""){
			$sql.= " AND id_usuario = '{$id_usuario}' ";
		}

		if($id_empleado!=""){
			$sql.= " AND id_empleado = '{$id_empleado}' ";
		}

		if($id_seccion_ine_ciudadano!=""){
			$sql.= " AND id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' ";
		}

		if($id_seccion_ine_ciudadano_relacionado!=""){
			$sql.= " AND id_seccion_ine_ciudadano_relacionado = '{$id_seccion_ine_ciudadano_relacionado}' ";
		}

		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}