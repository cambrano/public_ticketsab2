<?php
		function municipios($id_municipio=null,$id_estado=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id_municipio]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			if(!empty($id_estado)){
				$sql="SELECT * FROM municipios WHERE id_estado={$id_estado} ";
				$result = $conexion->query($sql);  
				 
				while($row=$result->fetch_assoc()){
					$sel=$row['id'];
					$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['municipio']."</option> ";
				} 
			}
			$conexion->close();
			return $return;
		}
		

		function municipioNombre($id_municipio=null){
			include 'db.php'; 
			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");
			if($id_municipio!=""){
				$sql.= " AND id='{$id_municipio}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['municipio'];
		}

		function municipioCoordenadas($id_municipio=null){
			include 'db.php'; 
			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");
			if($id_municipio!=""){
				$sql.= " AND id='{$id_municipio}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$longitud=$row['longitud'];
			$latitud=$row['latitud'];
			$coordenadas= array('lng' => $longitud,'lat' => $latitud );
			$conexion->close();
			return $coordenadas;
		}

		function municipioId($municipio=null,$id_estadoL=null){
			include 'db.php'; 

			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");

			if($municipio!=""){
				$sql.= " AND municipio = '{$municipio}' ";
			}
			if($id_estadoL!=""){
				$sql.= " AND id_estado='{$id_estadoL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}

		function municipioCorrecto($municipio=null,$id_estado=null){
			include '../functions/db.php'; 
			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");
			if($municipio!=""){
				$sql.= " AND municipio LIKE '%{$municipio}%' ";
			}
			if($id_estado!=""){
				$sql.= " AND id_estado='{$id_estado}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['municipio'];
		}
?>