<?php
		function partidos_2018($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM partidos_2018 WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".str_replace("_"," - ",$row['nombre_corto'])."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function partido_2018Datos($id=null,$id_partido_2018=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
			if($id_partido_2018!=""){
				$sql.=" AND id_partido_2018='{$id_partido_2018}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function partido_2018Nombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}

		function partido_2018ClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
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

		function partidos_2018Datos($id=null,$orden=null){
			include 'db.php'; 
			$sql=("SELECT * FROM partidos_2018 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id={$id} ";
			}
			if($orden!=""){
				$sql.=" ORDER BY {$orden} ";
			}
			$sql;
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
			return $datos;
		}

?>