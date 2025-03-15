<?php
		function estados($id_estado=null,$tipo=null,$sin_seleccione=null) {
			include 'db.php';
			$id;
			$select[$id_estado]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM estados WHERE 1 = 1 ";

			if($tipo==1){
				$sql .= " AND id='{$id_estado}'";
			}
			 

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['estado']."</option> ";
			} 
			$conexion->close();
			return $return;
		}
			

		function estadoNombre($id_estado=null){  
			include 'db.php';
			$sql=("SELECT * FROM estados WHERE 1 = 1 ");
			if($id_estado!=""){
				$sql.= " AND id='{$id_estado}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['estado'];
		}

		function estadoCoordenadas($id_estado=null){ 
			include 'db.php';
			$sql=("SELECT * FROM estados WHERE 1 = 1 ");
			if($id_estado!=""){
				$sql.= " AND id='{$id_estado}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$longitud=$row['longitud'];
			$latitud=$row['latitud'];
			$coordenadas= array('lng' => $longitud,'lat' => $latitud );
			$conexion->close();
			return $coordenadas;
		}

		function estadoId($estado=null,$tipo=null){ 
			include 'db.php';
			$sql=("SELECT * FROM estados WHERE 1 = 1 ");
			if($estado!=""){
				if($tipo){
					$sql.= " AND estado = '{$estado}' ";
				}else{
					$sql.= " AND estado LIKE '%{$estado}%' ";
				}
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}

		function estadoCorrecto($estado=null){ 
			include 'db.php';
			$sql=("SELECT * FROM estados WHERE 1 = 1 ");
			if($estado!=""){
				$sql.= " AND estado LIKE '%{$estado}%' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['estado'];
		}

?>