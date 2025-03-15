<?php
	function secciones_ine_grupos($id=null,$id_seccion_ine=null,$id_distrito_localL=null,$id_distrito_federalL=null,$id_municipioL=null,$sin_seleccione=null) {
		include 'db.php'; 
		$id;
		$ids = explode(",", $id);
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql = "SELECT sig.id,sig.nombre,sig.clave,(SELECT tg.nombre FROM tipos_secciones_ine_grupos tg WHERE tg.id=sig.id_tipo_seccion_ine_grupo) tipo_grupo FROM secciones_ine_grupos sig WHERE 1 ";
		if($id_seccion_ine!=''){
			$sql.=" AND sig.id_seccion_ine = '{$id_seccion_ine}'  ";
		}
		if($id_distrito_localL!=''){
			$sql.= " AND EXISTS (SELECT * FROM secciones_ine si WHERE si.id_distrito_local = '{$id_distrito_localL}' AND si.id = sig.id_seccion_ine) ";
		}
		if($id_distrito_federalL!=''){
			$sql.= " AND EXISTS (SELECT * FROM secciones_ine si WHERE si.id_distrito_federal = '{$id_distrito_federalL}' AND si.id = sig.id_seccion_ine) ";
		}
		if($id_municipioL!=''){
			$sql.=" AND sig.id_municipio = '{$id_municipioL}'  ";
		}
		$sql.= " ORDER BY sig.clave ASC ";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			if (in_array($row['id'], $ids)) {
				$return .="<option selected value='".$row['id']."' >".$row['tipo_grupo']." - ".$row['nombre']."</option> ";
			}else{
				$return .="<option value='".$row['id']."' >".$row['tipo_grupo']." - ".$row['nombre']."</option> ";
			}
		}
		$conexion->close();
		return $return;
	}


	function seccion_ine_grupoDatos($id_seccion_ine_grupo=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_grupos WHERE id='$id_seccion_ine_grupo'   ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function seccion_ine_grupoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM secciones_ine_grupos WHERE 1 = 1 ");
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


	function secciones_ine_gruposDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT
				sia.id,
				sia.clave,
				sia.folio,
				sia.fecha_hora,
				sia.nombre,
				(SELECT tg.nombre FROM tipos_secciones_ine_grupos tg WHERE tg.id=sia.id_tipo_seccion_ine_grupo) tipo_grupo,
				(SELECT tg.nombre FROM tipos_intereses tg WHERE tg.id=sia.id_tipo_interes) tipo_interes,
				(SELECT tg.nombre FROM tipos_relaciones tg WHERE tg.id=sia.id_tipo_relacion) tipo_relacion,
				(SELECT tg.nombre_corto FROM partidos_legados tg WHERE tg.id=sia.id_partido_legado) partido,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_grupos sicg WHERE sicg.status = 1 AND sicg.id_seccion_ine_grupo = sia.id) miembros,
				m.municipio,
				(SELECT l.localidad FROM localidades l WHERE l.id = sia.id_localidad ) localidad,
				sia.colonia,
				si.numero seccion,
				dl.numero distrito_local,
				df.numero distrito_federal,
				sia.latitud,
				sia.longitud,
				sia.observaciones,
				sia.fecha,
				sia.hora,
				sia.calle,
				sia.codigo_postal
			FROM secciones_ine_grupos sia
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
						if($key == "id_tipo_seccion_ine_grupo" || $key == "id_tipo_interes" || $key == "id_tipo_relacion" || $key == "id_partido_legado" || $key == "id_seccion_ine" || $key == "id_municipio" || $key == "id_localidad" ){
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
		$sql;
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