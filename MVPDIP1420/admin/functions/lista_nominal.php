<?php
	function lista_nominalId($clave_elector=null){
		include 'db.php';
		$sql = "SELECT id FROM lista_nominal WHERE 1 = 1 AND clave_elector='{$clave_elector}' ";
		$resultado = $conexion->query($sql);
		$row = $resultado->fetch_assoc();
		return $row['id'];
	}

	function lista_nominalDatos($id=null){
		include 'db.php';
		$sql="SELECT * FROM lista_nominal WHERE 1 = 1 ";
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}
	function lista_nominalDatosArray($registros=null,$orderby=null,$limit=null,$tipo_perfil_usuario=null,$id_usuario=null) {
		include 'db.php';
		include 'plataformas.php';
		$ano = date("Y");
		if($tipo_uso_plataforma=='municipio'){
			//$registros['id_municipio'] = $id_municipio;
			//$registros['codigo_plataforma'] = $codigo_plataforma;
		}elseif($tipo_uso_plataforma=='distrito_local'){
			//$registros['distrito_local'] = $distrito_local;
			//$registros['codigo_plataforma'] = $codigo_plataforma;
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			//$registros['distrito_federal'] = $distrito_federal;
			//$registros['codigo_plataforma'] = $codigo_plataforma;
		}
		$fecha_actual = date('Y-m-d');
		$sql = "SELECT 
			e.id,
			e.curp,
			e.clave_elector,
			e.sexo,
			e.fecha_nacimiento,
			CONCAT_WS(', ',e.calle,e.colonia ) direccion,
			e.num_ext,
			e.num_int,
			e.calle,
			e.colonia,
			(SELECT m.municipio FROM municipios m WHERE m.id = e.id_municipio) municipio,
			(SELECT l.localidad FROM localidades l WHERE l.id = e.id_localidad) localidad,
			e.longitud,
			e.latitud, 
			e.codigo_postal,
			e.nombre_completo,
			(SELECT IF(s.tipo=1,'Urbano','Rural') FROM secciones_ine s WHERE s.id = e.id_seccion_ine) tipo_seccion,
			e.id_seccion_ine,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
			e.manzana,
			(SELECT si.numero FROM distritos_locales si WHERE si.id = e.id_distrito_local) distrito_local,
			(SELECT si.numero FROM distritos_federales si WHERE si.id = e.id_distrito_federal) distrito_federal,
			e.militante_partido,
			e.militante_registro
		FROM lista_nominal e
		WHERE 1 ";
		if($validar_codigo_plataforma == false){
			//$sql.=" AND e.codigo_plataforma = '{$codigo_plataforma}' ";
		}
		if($tipo_perfil_usuario=='3'){
			//$sql .= ' AND EXISTS (SELECT * FROM log_usuarios lg WHERE lg.id_columna = e.id AND lg.tabla="lista_nominal" AND lg.operacion="Insert" AND lg.id_usuario= "'.$id_usuario.'"  )';
		}
		
		foreach ($registros as $key => $value) {
			/*
			echo $key;
			echo "-";
			echo $value;
			echo "<br>";
			*/
			if($value !=""){
				if($key=="sexo" ){
					$sql.= " AND  {$key} = '{$value}' ";
				}elseif($key=="nombre"){
					$sql.=" AND e.nombre LIKE '%{$value}%' ";
				}elseif($key=="apellido_paterno"){
					$sql.=" AND e.apellido_paterno LIKE '%{$value}%' ";
				}elseif($key=="apellido_materno"){
					$sql.=" AND e.apellido_materno LIKE '%{$value}%' ";
				}elseif($key=="clave_elector"){
					$sql.=" AND e.clave_elector LIKE '%{$value}%' ";
				}elseif($key=="curp"){
					$sql.=" AND e.curp LIKE '%{$value}%' ";
				}elseif($key=="manzana"){
					$sql.=" AND e.manzana LIKE '%{$value}%' ";
				}elseif($key=="militante_partido"){
					$sql.=" AND e.militante_partido LIKE '%{$value}%' ";
				}elseif($key=="id_distrito_local"){
					$sql.=" AND e.id_distrito_local = '{$value}' ";
				}elseif($key=="id_distrito_federal"){
					$sql.=" AND e.id_distrito_federal = '{$value}' ";
				}elseif($key=="padrones_especificos"){
					if($value == 1){
						$sql.= " AND EXISTS ( SELECT p.id_lista_nominal FROM padron_bienestar_65_03_a_04_2023 p WHERE p.id_lista_nominal = e.id)";
					}
					if($value == 2){
						$sql.= " AND EXISTS ( SELECT p.id_lista_nominal FROM padron_bienestar_B_J_01_a_02_2023 p WHERE p.id_lista_nominal = e.id)";
					}
				}elseif($key=="tipo_ciudadano"){
					$estado_abrev = str_pad($id_estado, 2, "0", STR_PAD_LEFT);
					if($value == 1){
						$sql.=" AND SUBSTRING(e.clave_elector, 13, 2) = '{$estado_abrev}' ";
					}
					if($value == 2){
						$sql.=" AND SUBSTRING(e.clave_elector, 13, 2) != '{$estado_abrev}' ";
					}
				}else{
					$sql.= " AND {$key} IN ($value) ";
				}
			}
		}


		if($orderby!=""){
			$sql.=" {$orderby} ";
		}

		if($limit!=""){
			$sql.=" {$limit} ";
		}
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
		/*
		foreach ($datos as $key => $value) {
			echo $value['nombre_completo'];
			echo "<br>";
		}*/
		return $datos;
	} 
?>