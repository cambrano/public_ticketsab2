<?php
		function casillas_votos_2022_revocacion_mandato($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM casillas_votos_2022_revocacion_mandato WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function casilla_voto_2022_revocacion_mandatoDatos($id=null,$id_casilla_voto_2022_revocacion_mandato=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2022_revocacion_mandato WHERE 1 = 1 ");
			if($id_casilla_voto_2022_revocacion_mandato!=""){
				$sql.=" AND id_casilla_voto_2022_revocacion_mandato='{$id_casilla_voto_2022_revocacion_mandato}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function casillas_votos_2022_revocacion_mandatoDatos($id=null,$id_seccion_ine=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_2022_revocacion_mandato WHERE 1 = 1 ";
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

		function casilla_voto_2022_revocacion_mandatoNombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2022_revocacion_mandato WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function casilla_voto_2022_revocacion_mandatoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2022_revocacion_mandato WHERE 1 = 1 ");
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