<?php
		function tipos_estructuras_ciudadanos($id=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$ids = explode(",", $id);
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT id,nombre FROM tipos_estructuras_ciudadanos WHERE 1 = 1 ;";
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


		function tipos_estructuras_ciudadanosDatos($id=null){
			include 'db.php';
			$sql="SELECT * FROM tipos_estructuras_ciudadanos WHERE 1 = 1 ";
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

		function tipo_estructura_ciudadanoDatos($id=null){
			include 'db.php';
			$sql=("SELECT * FROM tipos_estructuras_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

?>