<?php
		function programas_apoyos($id=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$ids = explode(",", $id);
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT id,nombre FROM programas_apoyos WHERE 1 = 1 ";
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

		function programa_apoyoDatos($id=null,$id_programa_apoyo=null){
			include 'db.php';
			$sql=("SELECT * FROM programas_apoyos WHERE 1 = 1 ");
			if($id_programa_apoyo!=""){
				$sql.=" AND id_programa_apoyo='{$id_programa_apoyo}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function programa_apoyoNombre($id=null){
			include 'db.php';
			$sql=("SELECT nombre FROM programas_apoyos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function programa_apoyoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM programas_apoyos WHERE 1 = 1 ");
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