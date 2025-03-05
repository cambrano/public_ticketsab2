<?php
		function municipios($id_municipioL=null,$id_estado=null,$sin_seleccione=null) {
			include 'db.php'; 
			$id;
			$select[$id_municipioL]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			if(!empty($id_estado)){
				$sql="SELECT * FROM municipios WHERE id_estado={$id_estado} ";
				$result = $conexion->query($sql);  
				 
				while($row=$result->fetch_assoc()){
					$sel=$row['id'];
					$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['clave']." - ".$row['municipio']."</option> ";
				} 
			}
			$conexion->close();
			return $return;
		}
		

		function municipioNombre($id_municipioL=null){
			include 'db.php'; 
			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");
			if($id_municipioL!=""){
				$sql.= " AND id='{$id_municipioL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['municipio'];
		}

		function municipioCoordenadas($id_municipioL=null){
			include 'db.php'; 
			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");
			if($id_municipioL!=""){
				$sql.= " AND id='{$id_municipioL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$longitud=$row['longitud'];
			$latitud=$row['latitud'];
			$coordenadas= array('lng' => $longitud,'lat' => $latitud );
			$conexion->close();
			return $coordenadas;
		}

		function municipioId($municipioL=null,$id_estadoL=null){
			include 'db.php'; 

			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");

			if($municipioL!=""){
				$sql.= " AND municipio = '{$municipioL}' ";
			}
			if($id_estadoL!=""){
				$sql.= " AND id_estado='{$id_estadoL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}

		function municipioIdClave($municipioL=null,$id_estadoL=null){
			include 'db.php'; 

			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");

			if($municipioL!=""){
				$municipioL = str_pad($municipioL,  4, "0",STR_PAD_LEFT); 
				$sql.= " AND clave = '{$municipioL}' ";
			}
			if($id_estadoL!=""){
				$sql.= " AND id_estado='{$id_estadoL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}

		function municipioCorrecto($municipioL=null,$id_estadoL=null){
			include '../functions/db.php'; 
			$sql=("SELECT * FROM municipios WHERE 1 = 1 ");
			if($municipioL!=""){
				$sql.= " AND municipioL LIKE '%{$municipioL}%' ";
			}
			if($id_estadoL!=""){
				$sql.= " AND id_estadoL='{$id_estadoL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['municipio'];
		}
?>