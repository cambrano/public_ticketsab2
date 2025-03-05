<?php
		function cuarteles($id=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$ids = explode(",", $id);
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM cuarteles WHERE 1 = 1 ";
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

		function cuartelDatos($id=null,$id_cuartel=null){
			include 'db.php';
			$sql=("SELECT * FROM cuarteles WHERE 1 = 1 ");
			if($id_cuartel!=""){
				$sql.=" AND id_cuartel='{$id_cuartel}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function cuartelNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM cuarteles WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function cuartelClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM cuarteles WHERE 1 = 1 ");
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


?>