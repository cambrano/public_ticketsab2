<?php
		function tipos_categorias_ciudadanos($id=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$ids = explode(",", $id);
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT id,nombre FROM tipos_categorias_ciudadanos WHERE 1 = 1 ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				if (in_array($row['id'], $ids)) {
					$return .="<option selected value='".$row['id']."' >".$row['nombre']."</option> ";
				}else{
					$return .="<option value='".$row['id']."' >".$row['nombre']."</option> ";
				}
			} 
			$conexion->close();
			return $return;
		}

		function tipo_categoria_ciudadanoDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_categorias_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function tipo_categoria_ciudadanoNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_categorias_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function tipo_categoria_ciudadanoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_categorias_ciudadanos WHERE 1 = 1 ");
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

		function tipos_categorias_ciudadanosDatos($id=null,$orden=null){
			include 'db.php'; 
			$sql=("SELECT * FROM tipos_categorias_ciudadanos WHERE 1 = 1 ");
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