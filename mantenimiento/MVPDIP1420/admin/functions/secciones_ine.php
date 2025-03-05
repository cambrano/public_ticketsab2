<?php
	function secciones_ine($id=null,$id_municipioL=null,$id_distrito_localL=null,$id_distrito_federalL=null,$sin_seleccione=null){
		include 'db.php'; 
		$id;
		$ids = explode(",", $id);
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT * FROM secciones_ine WHERE 1 = 1 ";

		if($id_municipioL!=""){
			$sql.= " AND id_municipio = '{$id_municipioL}' ";
		}

		if($id_distrito_localL!=""){
			$sql.= " AND id_distrito_local = '{$id_distrito_localL}' ";
		}

		if($id_distrito_federalL!=""){
			$sql.= " AND id_distrito_federal = '{$id_distrito_federalL}' ";
		}
		$result = $conexion->query($sql); 
		while($row=$result->fetch_assoc()){
			if (in_array($row['id'], $ids)) {
				$return .="<option selected value='".$row['id']."' >".$row['numero']."</option> ";
			}else{
				$return .="<option value='".$row['id']."' >".$row['numero']."</option> ";
			}
		} 
		$conexion->close();
		return $return;
	}
	function secciones_ineIn($idIn=null,$id_municipioL=null,$id_distrito_localL=null,$id_distrito_federalL=null,$sin_seleccione=null){
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT * FROM secciones_ine WHERE 1 = 1 ";

		if($id_municipioL!=""){
			$sql.= " AND id_municipio = '{$id_municipioL}' ";
		}

		if($id_distrito_localL!=""){
			$sql.= " AND id_distrito_local = '{$id_distrito_localL}' ";
		}

		if($id_distrito_federalL!=""){
			$sql.= " AND id_distrito_federal = '{$id_distrito_federalL}' ";
		}
		if($idIn!=""){
			$sql.= " AND id IN ({$idIn}) ";
		}
		$result = $conexion->query($sql); 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['numero']."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function filtrosSelectSeccionIne($columna=null) {
		if($columna==''){
			die;
		}
		include 'db.php';  
		$id;
		$select[$columna]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql = "SELECT {$columna} columna FROM secciones_ine WHERE 1 = 1 ";
		$sql .= " GROUP BY {$columna} ";
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['columna']."' >".$row['columna']."</option> ";
		}
		$conexion->close();
		return $return;
	}


	function seccion_ineDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM secciones_ine WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}


	function secciones_ineDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*
			FROM secciones_ine
			WHERE 1 = 1 
			";

		foreach ($registros as $key => $value) {
			if($value !=""){
				$sql.= " AND  {$key} like '%{$value}%' ";
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
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	}
	function secciones_ineDatosMapaCheck2021($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			(SELECT SUM(lista_nominal) FROM manzanas_ine l WHERE l.id_seccion_ine = s.id) lista_nominal,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos l WHERE l.id_seccion_ine = s.id) ciudadanos,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_check_2021 l WHERE l.id_seccion_ine = s.id) ciudadanos_check
			FROM secciones_ine s
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
	function secciones_ineDatosMapaCheck2024($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			(SELECT SUM(lista_nominal) FROM manzanas_ine l WHERE l.id_seccion_ine = s.id) lista_nominal,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos l WHERE l.id_seccion_ine = s.id) ciudadanos,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_check_2024 l WHERE l.id_seccion_ine = s.id) ciudadanos_check
			FROM secciones_ine s
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

	function secciones_ineDatosMapa($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			(SELECT SUM(lista_nominal) FROM manzanas_ine l WHERE l.id_seccion_ine = s.id) lista_nominal,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos l WHERE l.id_seccion_ine = s.id) ciudadanos,
			(SELECT COUNT(*) FROM manzanas_ine l WHERE l.id_seccion_ine = s.id) manzanas
			FROM secciones_ine s
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

	function secciones_ineIdDatosMapa($idL=null,$registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			(SELECT SUM(lista_nominal) FROM manzanas_ine l WHERE l.id_seccion_ine = s.id) lista_nominal,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos l WHERE l.id_seccion_ine = s.id) ciudadanos
			FROM secciones_ine s
			WHERE 1 = 1 
			";
		if($idL !=""){
			$sql.= " AND  s.id = '{$idL}' ";
		}
		foreach ($registros as $key => $value) {
			if($value !=""){
				$sql.= " AND  s.{$key} like '%{$value}%' ";
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

	function secciones_ineDatosForm($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*
			FROM secciones_ine s
			WHERE 1
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

	function seccion_ineClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT * FROM secciones_ine WHERE 1 = 1 ");
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
