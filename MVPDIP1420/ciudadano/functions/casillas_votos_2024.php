<?php
		function casillas_votos_2024($id=null,$id_seccion_ine=null,$tipo=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM casillas_votos_2024 WHERE 1 = 1 ";
			if($id_seccion_ine!=""){
				$sql.= " AND id_seccion_ine = '{$id_seccion_ine}' ";
			}
			if($tipo!=""){
				if($tipo=='x'){
					$tipo = 0;
				}
				$sql.= " AND tipo = '{$tipo}' ";
			}
			$sql;
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['codigo']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function casilla_voto_2024Datos($id=null,$id_casilla_voto_2024=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2024 WHERE 1 = 1 ");
			if($id_casilla_voto_2024!=""){
				$sql.=" AND id_casilla_voto_2024='{$id_casilla_voto_2024}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($tipo!=""){
				if($tipo=='x'){
					$tipo=0;
				}
				$sql.=" AND tipo='{$tipo}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function casillas_votos_2024Datos($id=null,$id_seccion_ine=null,$orden=null){
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

		function casillas_votos_2024DatosMapa($id=null,$id_seccion_ine=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT
						cv2024.id, 
						cv2024.id_seccion_ine, 
						cv2024.id_tipo_casilla, 
						cv2024.clave, 
						cv2024.codigo, 
						cv2024.votos_nulos, 
						cv2024.votos_can_nreg, 
						cv2024.lista_nominal, 
						cv2024.status, 
						cv2024.codigo_plataforma, 
						cv2024.referencia_importacion, 
						cv2024.fechaR, 
						cv2024.id_distrito_local, 
						cv2024.id_distrito_federal, 
						cv2024.calle, 
						cv2024.colonia, 
						cv2024.id_localidad, 
						cv2024.id_estado, 
						cv2024.id_municipio, 
						cv2024.id_pais, 
						cv2024.codigo_postal, 
						cv2024.longitud, 
						cv2024.latitud,
						m.municipio,
						l.localidad
					FROM casillas_votos_2024 cv2024
					LEFT JOIN municipios m ON cv2024.id_municipio = m.id
					LEFT JOIN localidades l ON cv2024.id_localidad = l.id
					WHERE 1 
			";
			if($id!=""){
				$sql.=" AND cv2024.id = '{$id}' ";
			}
			if($id_seccion_ine!=""){
				$sql.=" AND cv2024.id_seccion_ine = '{$id_seccion_ine}' ";
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