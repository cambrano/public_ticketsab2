<?php
		function tipos_ciudadanos($id=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT id,nombre FROM tipos_ciudadanos WHERE 1 = 1 ;";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}


		function tipos_ciudadanosDatos($id=null){
			include 'db.php';
			$sql="SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function tipo_ciudadanoDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function tipo_ciudadanoNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function tipo_ciudadanoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_ciudadanos WHERE 1 = 1 ");
			if($clave!=""){
				$sql.=" AND clave='{$clave}' ";
			}
			if($id!=""){
				$sql.=" AND id !='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['id']; 
			return $datos;
		}


?>