<?php
		function tipos_casillas($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM tipos_casillas WHERE 1 AND codigo_plataforma='{$codigo_plataforma}'";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_array()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function tipo_casillaDatos($id=null,$id_tipo_casilla=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_casillas WHERE codigo_plataforma='{$codigo_plataforma}' ");
			if($id_tipo_casilla!=""){
				$sql.=" AND id_tipo_casilla='{$id_tipo_casilla}' ";
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

		function tipo_casillaNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_casillas WHERE codigo_plataforma='{$codigo_plataforma}' ");
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


		function tipo_casillaClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_casillas WHERE codigo_plataforma='{$codigo_plataforma}' ");
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


?>