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
			(SELECT tm.nombre FROM tipos_ciudadanos tm WHERE tm.id = sim.id_tipo_ciudadano ) tipo_ciudadano
		FROM secciones_ine_ciudadanos sim WHERE sim.id='$id_seccion_ine_ciudadano'  ";
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

	function secciones_ine_ciudadanosDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql = "SELECT 
			e.id,
			e.clave,
			e.distancia_km,
			e.sexo,
			e.fecha_nacimiento,
			e.whatsapp,
			e.celular,
			e.telefono,
			e.observaciones,
			CONCAT_WS(', ',e.calle,e.colonia ) direccion,
			e.calle,
			e.colonia,
			(SELECT m.municipio FROM municipios m WHERE m.id = e.id_municipio) municipio,
			(SELECT l.localidad FROM localidades l WHERE l.id = e.id_localidad) localidad,

			e.longitud,
			e.latitud, 
			e.correo_electronico,
			
			e.distancia_m, 
			e.codigo_postal,

			e.nombre_completo,
			(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = e.id_tipo_ciudadano) tipo_ciudadano,
			(SELECT si.numero FROM secciones_ine si WHERE si.id = e.id_seccion_ine) seccion,
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
			IF(e.distancia_alert=0,'NO TIENE','DISTANCIA') distancia_alert
		FROM secciones_ine_ciudadanos e
		WHERE 1 = 1   "; 

		foreach ($registros as $key => $value) {
			/*
			echo $key;
			echo "-";
			echo $value;
			echo "<br>";
			*/
			if($value !=""){
				if($key!="fecha_1" && $key!="fecha_2"){
					if($key=="clave" || $key=="sexo" || $key=="id_seccion_ine_ciudadano_compartido"){
						$sql.= " AND  {$key} = '{$value}' ";
					}elseif($key=="nombre_completo"){
						$sql.=" AND e.nombre_completo LIKE '%{$value}%' ";
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