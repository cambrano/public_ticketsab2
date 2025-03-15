<?php
		function localidades($id_localidadL=null,$id_municipioL=null,$id_estado=null,$sin_seleccione=null) {
			include 'db.php';    
			$id;
			$select[$id_localidadL]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			if(!empty($id_municipioL)){

				if(!empty($id_estado)){
					$sql="SELECT * FROM localidades WHERE id_estado={$id_estado} AND id_municipio={$id_municipioL} ";
					$result = $conexion->query($sql);  
					 
					while($row=$result->fetch_assoc()){
						$sel=$row['id'];
						$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['clave']." - ".$row['localidad']."</option> ";
					} 
				}
			}
			$conexion->close();
			return $return;
		}

		function localidades_municipio_array($id_localidad=null,$id_municipio=null,$id_estado=null,$sin_seleccione=null) {
			include 'db.php';    
			$id;
			$select[$id_localidad]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			if(!empty($id_municipio)){
				if(!empty($id_estado)){
					$sql="
							SELECT 
								* ,
								(SELECT m.municipio FROM municipios m WHERE m.id = l.id_municipio) municipio
							FROM localidades l 
							WHERE l.id_estado={$id_estado} AND l.id_municipio IN ({$id_municipio}) ";
					$result = $conexion->query($sql);
					while($row=$result->fetch_assoc()){
						$sel=$row['id'];
						$return .="<option ".$select[$sel]." value='".$row['id']."' > ".$row['municipio']." _-_ ".$row['clave']." - ".$row['localidad']."</option> ";
					}
				}
			}
			$conexion->close();
			return $return;
		}

		function localidadesFiltro($id_localidad=null,$id_municipio=null,$id_estado=null,$sin_seleccione=null) {
			include 'db.php';    
			$id;
			$select[$id_localidad]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM localidades WHERE 1 ";
			if($id_estado!=''){
				$sql.=" AND id_estado='{$id_estado}'";
			}
			if($id_municipio!=''){
				$sql.="AND id_municipio='{$id_municipio}'";
			}
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['clave']." - ".$row['localidad']."</option> ";
			}
			$conexion->close();
			return $return;
		}
		
		function localidadNombre($localidad=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 = 1 ");
			if($localidad!=""){
				$sql.= " AND id='{$localidad}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['localidad'];
		}
		
		function localidadCoordenadas($localidad=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 = 1 ");
			if($localidad!=""){
				$sql.= " AND id='{$localidad}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$longitud=$row['longitud'];
			$latitud=$row['latitud'];
			$coordenadas= array('lng' => $longitud,'lat' => $latitud );
			$conexion->close();
			return $coordenadas;
		}

		function localidadId($localidad=null,$id_estadoL=null,$id_municipioL=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 = 1 ");
			if($localidad!=""){
				$sql.= " AND localidad LIKE '%{$localidad}%' ";
			}
			if($id_estadoL!=""){
				$sql.= " AND id_estado='{$id_estadoL}'";
			}
			if($id_municipioL!=""){
				$sql.= " AND id_municipio='{$id_municipioL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}

		function localidadIdClave($localidad=null,$id_estadoL=null,$id_municipioL=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 = 1 ");
			if($localidad!=""){
				$localidad = str_pad($localidad,  4, "0",STR_PAD_LEFT); 
				$sql.= " AND clave = '{$localidad}' ";
			}
			if($id_estadoL!=""){
				$sql.= " AND id_estado='{$id_estadoL}'";
			}
			if($id_municipioL!=""){
				$sql.= " AND id_municipio='{$id_municipioL}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}

		function localidadIgualId($localidad=null,$id_estadoL=null,$id_municipioL=null){
			include 'db.php';  
			$sql="SELECT id FROM localidades WHERE 1 = 1 ";
			if($localidad!=""){
				$sql.= " AND localidad = '{$localidad}' ";
			}
			if($id_estadoL!=""){
				$sql.= " AND id_estado='{$id_estadoL}'";
			}
			if($id_municipioL!=""){
				$sql.= " AND id_municipio='{$id_municipioL}' ";
			}

			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['id'];
		}

		function localidadCorrecto($localidad=null,$id_estado=null,$id_municipio=null){
			include 'db.php';  
			$sql=("SELECT * FROM localidades WHERE 1 = 1 ");
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
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row['localidad'];
		}
?>