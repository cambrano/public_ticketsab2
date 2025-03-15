<?php

	function secciones_ine($id=null,$id_municipio=null,$id_distrito_local=null,$id_distrito_federal=null){
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql="SELECT * FROM secciones_ine WHERE 1 AND codigo_plataforma='{$codigo_plataforma}'";

		if($id_municipio!=""){
			$sql.= " AND id_municipio = '{$id_municipio}' ";
		}

		if($id_distrito_local!=""){
			$sql.= " AND id_distrito_local = '{$id_distrito_local}' ";
		}

		if($id_distrito_federal!=""){
			$sql.= " AND id_distrito_federal = '{$id_distrito_federal}' ";
		}

		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_array()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['numero']."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function filtrosSelect($columna=null) {
		if($columna==''){
			die;
		}
		include 'db.php';  
		$id;
		$select[$columna]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql = "SELECT {$columna} columna FROM secciones_ine WHERE 1  ";
		$sql .= " GROUP BY {$columna} ";
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_array()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['columna']."' >".$row['columna']."</option> ";
		}
		$conexion->close();
		return $return;
	}


	function seccion_ineDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM secciones_ine WHERE codigo_plataforma='{$codigo_plataforma}' ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
			$datos=$row; 
			return $datos;
		}


	function secciones_ineDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*
			FROM secciones_ine
			WHERE 1 AND codigo_plataforma='{$codigo_plataforma}'
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
		//$resultado = $conexion->query($sql);
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_array()){
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
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


	function secciones_ineDatosMapa($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*
			FROM secciones_ine
			WHERE 1 AND codigo_plataforma='{$codigo_plataforma}'
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
		//$resultado = $conexion->query($sql);
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_array()){
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
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
		$sql=("SELECT * FROM secciones_ine WHERE codigo_plataforma='{$codigo_plataforma}' ");
		if($clave!=""){
			$sql.=" AND clave='{$clave}' ";
		}
		if($id!=""){
			$sql.=" AND id !='{$id}' ";
		}
		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		foreach($row as $key => $value){
			if(is_numeric($key)) unset($row[$key]);
		}
		$datos=$row['id']; 
		return $datos;
	}
