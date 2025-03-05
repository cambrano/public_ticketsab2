<?php
		function casillas_votos_2024($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM casillas_votos_2024 WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function casilla_voto_2024Datos($id=null,$id_casilla_voto_2024=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2024 WHERE 1 = 1 ");
			if($id_casilla_voto_2024!=""){
				$sql.=" AND id_casilla_voto_2024='{$id_casilla_voto_2024}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function casillas_votos_2024Datos($id=null,$id_seccion_ine=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_2024 WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine!=""){
				$sql.=" AND id_seccion_ine = '{$id_seccion_ine}' ";
			}
			if($tipo!=""){
				$sql.=" AND tipo = '{$tipo}' ";
			}

			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}
			$sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function casillas_votos_2024DatosMapa($id=null,$id_seccion_ine=null,$id_municipioL=null,$id_distrito_localL=null,$id_distrito_federalL=null,$orden=null,$tipo=null,$groupby=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_2024 WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine!=""){
				$sql.=" AND id_seccion_ine = '{$id_seccion_ine}' ";
			}if($id_municipioL!=""){
				$sql.=" AND id_municipio = '{$id_municipioL}' ";
			}
			if($id_distrito_localL!=""){
				$sql.=" AND id_distrito_localL = '{$id_distrito_localL}' ";
			}
			if($id_distrito_federalL!=""){
				$sql.=" AND id_distrito_federal = '{$id_distrito_federalL}' ";
			}
			if($tipo!=""){
				$sql.=" AND tipo = '{$tipo}' ";
			}
			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}

			$result = $conexion->query($sql); 
			$num=0; 
			if($groupby!=""){
				while($row=$result->fetch_assoc()){
					$datos[$row[$groupby]]=$row;
					$num=$num+1;
				}	
			}else{
				while($row=$result->fetch_assoc()){
					$datos[$num]=$row;
					$num=$num+1;
				}	
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function casilla_voto_2024Nombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2024 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function casilla_voto_2024ClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2024 WHERE 1 = 1 ");
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