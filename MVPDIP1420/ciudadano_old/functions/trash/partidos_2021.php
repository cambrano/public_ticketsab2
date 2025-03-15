<?php
		function partidos_2021($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM partidos_2021 WHERE 1 AND codigo_plataforma='{$codigo_plataforma}'";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_array()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".str_replace("_"," - ",$row['nombre_corto'])."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function partido_2021Datos($id=null,$id_partido_2021=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2021 WHERE codigo_plataforma='{$codigo_plataforma}' ");
			if($id_partido_2021!=""){
				$sql.=" AND id_partido_2021='{$id_partido_2021}' ";
			}
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

		function partido_2021Nombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2021 WHERE codigo_plataforma='{$codigo_plataforma}' ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
			$datos=$row['nombre']; 
			return $datos;
		}

		function partido_2021ClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2021 WHERE codigo_plataforma='{$codigo_plataforma}' ");
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

		function partido_2021PrincipalDatos(){
			include 'db.php';
			$sql=("SELECT * FROM partidos_2021 WHERE codigo_plataforma='{$codigo_plataforma}' AND principal=1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
			$datos=$row; 
			return $datos;
		}

		function partidos_2021Datos($id=null,$orden=null){
			include 'db.php'; 
			$sql=("SELECT * FROM partidos_2021 WHERE codigo_plataforma='{$codigo_plataforma}' ");
			if($id!=""){
				$sql.=" AND id={$id} ";
			}
			if($orden!=""){
				$sql.=" ORDER BY {$orden} ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_array()){
				foreach($row as $key => $value){
					if(is_numeric($key)) unset($row[$key]);
				}
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