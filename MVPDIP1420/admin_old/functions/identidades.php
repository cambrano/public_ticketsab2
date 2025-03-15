<?php
	function identidades($id=null,$sin_seleccione=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT 
		id,
		CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno ) nombre_completo
		FROM identidades WHERE 1 = 1 ORDER BY nombre_completo ASC";
	
		$result = $conexion->query($sql);  
		 
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre_completo']."</option> ";
		} 
		$conexion->close();
		return $return;
	}
	function log_clicksDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT 
			*
			FROM log_clicks
			WHERE 1 = 1 
			";
		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			if($value !=""){
				$sql.= " AND  {$key} = '{$value}' ";
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

	function identidadDatos($id_identidad=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM identidades WHERE id=$id_identidad   ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$datos['nombre_completo']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
		$conexion->close();
		return $datos;
	}

	function identidadClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM identidades WHERE 1 = 1 ");
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