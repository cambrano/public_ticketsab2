<?php
	function manzana_ineDatos($id=null){
		include 'db.php';
		$sql=("SELECT * FROM manzanas_ine WHERE 1 = 1 ");
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}

	function manzanas_ineDatosMapa($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos l WHERE l.id_seccion_ine = s.id_seccion_ine AND l.manzana = s.numero  ) ciudadanos
			FROM manzanas_ine s
			WHERE 1 = 1 
			";

		foreach ($registros as $key => $value) {
			if($value !=""){
				$sql.= " AND  s.{$key} = '{$value}' ";
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
			$datos[$row['id']]=$row;
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	}

	function manzanas_ineDatosMapaForm($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos l WHERE l.id_seccion_ine = s.id_seccion_ine AND l.manzana = s.numero  ) ciudadanos
			FROM manzanas_ine s
			WHERE 1 = 1 
			";

		foreach ($registros as $key => $value) {
			if($value !=""){
				$sql.= " AND  s.{$key} = '{$value}' ";
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
			$datos[$row['id']]=$row;
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	}
	function manzanas_ineDatosMapaSinInfo($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*
			FROM manzanas_ine s
			WHERE 1 = 1 
			";

		foreach ($registros as $key => $value) {
			foreach ($registros as $key => $value) {
				if (is_array($value)) {
					$inClause = "'".implode("','", $value)."'";
					$sql .= " AND s.{$key} IN ({$inClause})";
				} elseif ($value !== '') {
					$sql .= " AND s.{$key} = '{$value}'";
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
			$datos[$row['id']]=$row;
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	}
