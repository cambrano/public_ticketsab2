<?php
		function localidades($id_localidad=null,$id_municipio=null,$id_estado=null) {
			include 'db.php';    
			$id;
			$select[$id_localidad]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			if(!empty($id_municipio)){

				if(!empty($id_estado)){
					$sql="SELECT * FROM localidades WHERE 1 AND id_estado={$id_estado} AND id_municipio={$id_municipio} ";
					$result = $conexion->query($sql);  
					 
					while($row=$result->fetch_array()){
						$sel=$row['id'];
						$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['localidad']."</option> ";
					} 
				}
			}
			$conexion->close();
			return $return;
		}
		
		function localidadNombre($localidad=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 ");
			if($localidad!=""){
				$sql.= " AND id='{$localidad}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			$conexion->close();
			return $row['localidad'];
		}
		
		function localidadCoordenadas($localidad=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 ");
			if($localidad!=""){
				$sql.= " AND id='{$localidad}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			$longitud=$row['longitud'];
			$latitud=$row['latitud'];
			$coordenadas= array('lng' => $longitud,'lat' => $latitud );
			$conexion->close();
			return $coordenadas;
		}

		function localidadId($localidad=null,$id_estado=null,$id_municipio=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 ");
			if($localidad!=""){
				$sql.= " AND localidad LIKE '%{$localidad}%' ";
			}
			if($id_estado!=""){
				$sql.= " AND id_estado='{$id_estado}'";
			}
			if($id_municipio!=""){
				$sql.= " AND id_municipio='{$id_municipio}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			$conexion->close();
			return $row['id'];
		}
		function localidadCorrecto($localidad=null,$id_estado=null,$id_municipio=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 ");
			if($localidad!=""){
				$sql.= " AND localidad LIKE '%{$localidad}%' ";
			}
			if($id_estado!=""){
				$sql.= " AND id_estado='{$id_estado}'";
			}
			if($id_municipio!=""){
				$sql.= " AND id_municipio='{$id_municipio}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			$conexion->close();
			return $row['localidad'];
		}
?>