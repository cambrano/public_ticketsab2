<?php

	function distritos_federales($id=null,$sin_seleccione=null) {
		include 'db.php'; 
		$id;
		$ids = explode(",", $id);
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT * FROM distritos_federales WHERE 1 = 1 ";
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

	/*
	function filtrosSelect($columna=null) {
		if($columna==''){
			die;
		}
		include 'db.php';  
		$id;
		$select[$columna]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql = "SELECT {$columna} columna FROM distritos_federales WHERE 1 = 1 ";
		$sql .= " GROUP BY {$columna} ";
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['columna']."' >".$row['columna']."</option> ";
		}
		$conexion->close();
		return $return;
	}
	*/


	function distrito_federalDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM distritos_federales WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}


	function distritos_federalesDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*
			FROM distritos_federales
			WHERE 1 = 1 
			";

		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
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

	function distrito_federalClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT * FROM distritos_federales WHERE 1 = 1 ");
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

	function distritos_federalesDatosMapa($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*,
			(SELECT COUNT(*) FROM lista_nominal l WHERE l.id_distrito_federal = s.id) lista_nominal,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos l WHERE l.id_distrito_federal = s.id) ciudadanos
			FROM distritos_federales s
			WHERE 1 = 1 
			";

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
