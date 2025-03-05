<?php
	function secciones_ine_ciudadanos($id=null,$id_seccion_ine=null,$id_distrito_local=null,$id_distrito_federal=null,$id_municipio=null,$sin_seleccione=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT 
		id,
		nombre_completo,
		(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = sim.id_tipo_ciudadano ) tipo_ciudadano
		FROM secciones_ine_ciudadanos sim WHERE 1 ORDER BY nombre_completo ASC";
	
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre_completo']." - ".$row['tipo_ciudadano']."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function secciones_ine_ciudadanos_relacionados($id=null,$id_seccion_ine=null,$id_distrito_local=null,$id_distrito_federal=null,$id_municipio=null,$sin_seleccione=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT 
		sim.id,
		sim.nombre_completo,
		(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = sim.id_tipo_ciudadano ) tipo_ciudadano
		FROM secciones_ine_ciudadanos sim WHERE 1 = 1 

		AND  EXISTS (SELECT sa.id_seccion_ine_ciudadano_compartido FROM secciones_ine_ciudadanos sa WHERE sa.id_seccion_ine_ciudadano_compartido = sim.id )

		ORDER BY sim.nombre_completo ASC";
	
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre_completo']." - ".$row['tipo_ciudadano']."</option> ";
		} 
		$conexion->close();
		return $return;
	}


	function seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano=null){ 
		include 'db.php'; 
		$sql="
			SELECT * ,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = sim.id_seccion_ine) seccion,
			(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = sim.id_tipo_ciudadano ) tipo_ciudadano,
			(SELECT tm.municipio FROM municipios tm WHERE tm.id = sim.id_municipio ) municipio
		FROM secciones_ine_ciudadanos sim WHERE sim.id='$id_seccion_ine_ciudadano'  ";
		//$sql.=" AND sim.codigo_plataforma = '{$codigo_plataforma}' ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function seccion_ine_ciudadanoPrimeroDatos($id_seccion_ine_ciudadano=null){ 
		include 'db.php'; 
		$sql="
			SELECT * ,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = sim.id_seccion_ine) seccion,
			(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = sim.id_tipo_ciudadano ) tipo_ciudadano
		FROM secciones_ine_ciudadanos sim WHERE 1 ";
		if($id_seccion_ine_ciudadano!=""){
			$sql.=" AND id ='{$id_seccion_ine_ciudadano}' ";
		}
		$sql .= ' LIMIT 1 ;';
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function seccion_ine_ciudadanoClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT id FROM secciones_ine_ciudadanos WHERE 1 = 1 ");
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

	function seccion_ine_ciudadanoClaveElectorVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT id FROM secciones_ine_ciudadanos WHERE 1 = 1 ");
		if($clave!=""){
			$sql.=" AND clave_elector='{$clave}' ";
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


	function secciones_ine_ciudadanosDatosArray($registros=null,$orderby=null,$limit=null,$tipo_perfil_usuario=null,$id_usuario=null) {
		include 'db.php';
		include 'plataformas.php';
		$ano = date("Y");
		if($tipo_uso_plataforma=='municipio'){
			$registros['id_municipio'] = $id_municipio;
			//$registros['codigo_plataforma'] = $codigo_plataforma;
		}elseif($tipo_uso_plataforma=='distrito_local'){
			$registros['distrito_local'] = $distrito_local;
			//$registros['codigo_plataforma'] = $codigo_plataforma;
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			$registros['distrito_federal'] = $distrito_federal;
			//$registros['codigo_plataforma'] = $codigo_plataforma;
		}
		$fecha_actual = date('Y-m-d');
		$sql = "SELECT 
			e.id,
			e.clave,
			(SELECT p.nombre FROM plataformas p WHERE p.plataforma = e.codigo_plataforma) plataforma,
			e.folio,
			e.curp,
			e.clave_elector,
			e.distancia_km,
			e.sexo,
			e.fecha_nacimiento,
			e.whatsapp,
			e.celular,
			e.telefono,
			e.observaciones,
			CONCAT_WS(', ',e.calle,e.colonia ) direccion,
			e.calle,
			e.num_ext,
			e.num_int,
			e.colonia,
			(SELECT m.municipio FROM municipios m WHERE m.id = e.id_municipio) municipio,
			(SELECT l.localidad FROM localidades l WHERE l.id = e.id_localidad) localidad,
			e.longitud,
			e.latitud, 
			e.correo_electronico,
			CASE
				WHEN e.status_verificacion = '1' THEN 'Encontrado'
				WHEN e.status_verificacion = '2' THEN 'Verificado'
				WHEN e.status_verificacion = '3' THEN 'Por Validar'
				ELSE 'No Encontrado'
			END as status_verificacion,
			(SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) seguimientos,
			
			e.distancia_m, 
			e.codigo_postal,

			e.nombre_completo,
			(SELECT IF(s.tipo=1,'Urbano','Rural') FROM secciones_ine s WHERE s.id = e.id_seccion_ine) tipo_seccion,
			(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = e.id_tipo_ciudadano) tipo_ciudadano,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
			e.manzana,
			(SELECT si.numero FROM distritos_locales si WHERE si.id = e.id_distrito_local) distrito_local,
			(SELECT si.numero FROM distritos_federales si WHERE si.id = e.id_distrito_federal) distrito_federal,
			(SELECT sim.nombre_completo FROM secciones_ine_ciudadanos sim WHERE sim.id = e.id_seccion_ine_ciudadano_compartido) relacionado,
			(
				IF(
					(SELECT sicc.id FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id LIMIT 1 ) IS NULL,
					'Sin Categorías',
					(
						SELECT GROUP_CONCAT(tcc.nombre) categoriast
						FROM secciones_ine_ciudadanos_categorias sicc
						LEFT JOIN tipos_categorias_ciudadanos tcc
						ON tcc.id = sicc.id_tipo_categoria_ciudadano
						WHERE sicc.id_seccion_ine_ciudadano = e.id
						GROUP BY sicc.id_seccion_ine_ciudadano
					)
				)
			) categorias,
			CASE 
				WHEN e.medio_registro = 1 THEN 'CIUDADANO'
				WHEN e.medio_registro = 2 THEN 'SISTEMA'
				ELSE 'IMPORTACION'
			END AS medio_registro,
			IF(e.distancia_alert=0,'NO TIENE','DISTANCIA') distancia_alert,

			(SELECT count(do.id) FROM documentos_oficiales do WHERE do.id_seccion_ine_ciudadano = e.id) documentos_oficiales,
			(SELECT count(do.id) FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id) programas_apoyos,
			(SELECT GROUP_CONCAT(DISTINCT cpa.nombre) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN programas_apoyos_categorias pac ON sicpa.id_programa_apoyo = pac.id_programa_apoyo LEFT JOIN categorias_programas_apoyos cpa ON cpa.id = pac.id_categoria_programa_apoyo WHERE sicpa.id_seccion_ine_ciudadano = e.id) programas_apoyos_categorias,
			(SELECT pl.nombre_corto FROM militantes_partidos mp LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) militantes_partidos
		FROM secciones_ine_ciudadanos e
		WHERE 1 = 1 "; 
		if(validar_codigo_plataforma($codigo_plataforma) == false){
			$sql.=" AND e.codigo_plataforma = '{$codigo_plataforma}' ";
			unset($registros['plataforma']);
		}
		if($tipo_perfil_usuario=='3'){
			//$sql .= ' AND EXISTS (SELECT * FROM log_usuarios lg WHERE lg.id_columna = e.id AND lg.tabla="secciones_ine_ciudadanos" AND lg.operacion="Insert" AND lg.id_usuario= "'.$id_usuario.'"  )';
		}

		foreach ($registros as $key => $value) {
			/*
			echo $key;
			echo "-";
			echo $value;
			echo "<br>";
			*/
			if($value !=""){
				if($key!="fecha_nacimiento_1" && $key!="fecha_nacimiento_2"){
					if($key=="sexo" || $key=="id_seccion_ine_ciudadano_compartido" || $key=="id_seccion_ine_ciudadano_compartido" || $key=="status_verificacion"){
						$sql.= " AND  {$key} = '{$value}' ";
					}elseif($key=="nombre_completo"){
						$sql.=" AND e.nombre_completo LIKE '%{$value}%' ";
					}elseif($key=="nombre"){
						$sql.=" AND e.nombre LIKE '%{$value}%' ";
					}elseif($key=="apellido_paterno"){
						$sql.=" AND e.apellido_paterno LIKE '%{$value}%' ";
					}elseif($key=="apellido_paterno"){
						$sql.=" AND e.apellido_paterno LIKE '%{$value}%' ";
					}elseif($key=="plataforma"){
						$sql.=" AND e.codigo_plataforma = '{$value}' ";
					}elseif($key=="fecha_nacimiento_dia"){
						$sql.=" AND DAY(e.fecha_nacimiento) = '{$value}' ";
					}elseif($key=="fecha_nacimiento_mes"){
						$sql.=" AND MONTH(e.fecha_nacimiento) = '{$value}' ";
					}elseif($key=="fecha_nacimiento_edad"){
						$sql.=" AND TIMESTAMPDIFF(YEAR,e.fecha_nacimiento,CURDATE()) = '{$value}' ";
					}elseif($key=="info_vigente"){
						$ano = date("Y");
						if($value==1){
							$sql.=" AND e.vigencia < '{$ano}' ";
						}else{
							$sql.=" AND e.vigencia >= '{$ano}' ";
						}
					}elseif($key=="clave"){
						$sql.=" AND e.clave LIKE '%{$value}%' ";
					}elseif($key=="clave_elector"){
						$sql.=" AND e.clave_elector LIKE '%{$value}%' ";
					}elseif($key=="curp"){
						$sql.=" AND e.curp LIKE '%{$value}%' ";
					}elseif($key=="id_programa_apoyo"){
						$sql.="  AND EXISTS (SELECT do.id FROM secciones_ine_ciudadanos_programas_apoyos do WHERE do.id_seccion_ine_ciudadano = e.id AND do.id_programa_apoyo IN ({$value}))  ";
					}elseif($key=="tipo_seccion"){
						$sql.="  AND EXISTS (SELECT do.id FROM secciones_ine do WHERE do.id = e.id_seccion_ine AND do.tipo IN ({$value}))  ";
					}elseif($key=="folio"){
						$sql.=" AND e.folio LIKE '%{$value}%' ";
					}elseif($key=="id_seccion_ine_grupo"){
						$sql.=" AND (SELECT pl.id FROM secciones_ine_ciudadanos_grupos mp LEFT JOIN secciones_ine_grupos pl ON mp.id_seccion_ine_grupo = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$value}) ";
					}elseif($key=="relacion"){
						if($value==1){
							$sql.=" AND e.id_seccion_ine_ciudadano_compartido > 0 ";
						}else{
							$sql.=" AND (e.id_seccion_ine_ciudadano_compartido IS NULL OR  e.id_seccion_ine_ciudadano_compartido =0 ) ";
						}
					}elseif($key=="solo_padre"){
						if($value==1){
							$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos sicc WHERE sicc.id_seccion_ine_ciudadano_compartido = e.id ) > 0 ";
						}else{
							$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos sicc WHERE sicc.id_seccion_ine_ciudadano_compartido = e.id ) = 0 ";
						}
					}elseif($key=="documentos_oficiales"){
						if($value==1){
							$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
						}else{
							$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
						}
					}elseif($key=="vigencia_documentos_oficiales"){
						if($value==1){
							$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.fecha_vigencia < '{$fecha_actual}' ) > 0 ";
						}else{
							$sql.=" AND (SELECT COUNT(*) FROM documentos_oficiales sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.fecha_vigencia < '{$fecha_actual}' ) = 0 ";
						}
					}elseif($key=="programas_apoyos"){
						if($value==1){
							$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) > 0 ";
						}else{
							$sql.=" AND (SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) = 0 ";
						}
					}elseif($key=="id_partido_legado"){
						$sql.=" AND (SELECT pl.id FROM militantes_partidos mp LEFT JOIN partidos_legados pl ON mp.id_partido_legado = pl.id WHERE mp.id_seccion_ine_ciudadano = e.id AND mp.status = 1 ORDER BY pl.id DESC LIMIT 1 ) IN ({$value}) ";
					}elseif($key=="num_seguimiento"){
						$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_seguimientos sics WHERE sics.id_seccion_ine_ciudadano = e.id ) = ".$value;
					}elseif($key=="id_tipo_categoria_ciudadano"){
						$porciones = explode(",", $value);
						if (in_array("0", $porciones)) {
							if(count($porciones)==1){
								//solo muestra 0
								$sql .= " AND NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id ) ";
							}else{
								/// muestra mas 
								$sql.= " AND ( EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($value)) OR NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id )) ";
							}
						}else{
							$sql.= " AND EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = e.id AND sicc.id_tipo_categoria_ciudadano IN ($value) )";
						}
					}else{
						$sql.= " AND {$key} IN ($value) ";
					}
				}
				if($key=="fecha_nacimiento_1"){
					$fecha_1 = $value;
				}
				if($key=="fecha_nacimiento_2"){
					$fecha_2 = $value;
				}
			}
		}

		if( $fecha_1 != '' && $fecha_2 == ''){ 
			$sql.=" AND e.fecha_nacimiento <= '{$fecha_1}' ";
		}

		if( $fecha_1 == '' && $fecha_2 != ''){ 
			$sql.=" AND e.fecha_nacimiento >= '{$fecha_2}' ";
		}
		if( $fecha_1 != '' && $fecha_2 != ''){ 
			$sql.=" AND e.fecha_nacimiento BETWEEN '{$fecha_1}' AND '{$fecha_2}' ";
		}

		if($orderby!=""){
			$sql.=" {$orderby} ";
		}

		if($limit!=""){
			$sql.=" {$limit} ";
		}
		//echo "<pre>";
		//var_dump($sql);
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