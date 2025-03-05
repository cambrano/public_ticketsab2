<?php
		function casillas_votos_2021($id=null,$id_seccion_ine=null,$tipo=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM casillas_votos_2021 WHERE 1 = 1 ";
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

		function casilla_voto_2021Datos($id=null,$id_casilla_voto_2021=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2021 WHERE 1 = 1 ");
			if($id_casilla_voto_2021!=""){
				$sql.=" AND id_casilla_voto_2021='{$id_casilla_voto_2021}' ";
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

		function casillas_votos_2021Datos($id=null,$id_seccion_ine=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_2021 WHERE 1 = 1 ";
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

		function casillas_votos_2021DatosMapa($id=null,$id_seccion_ine=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT
						cv2021.id, 
						cv2021.id_seccion_ine, 
						cv2021.id_tipo_casilla, 
						cv2021.clave, 
						cv2021.codigo, 
						cv2021.votos_nulos, 
						cv2021.votos_can_nreg, 
						cv2021.lista_nominal, 
						cv2021.status, 
						cv2021.codigo_plataforma, 
						cv2021.referencia_importacion, 
						cv2021.fechaR, 
						cv2021.id_distrito_local, 
						cv2021.id_distrito_federal, 
						cv2021.calle, 
						cv2021.colonia, 
						cv2021.id_localidad, 
						cv2021.id_estado, 
						cv2021.id_municipio, 
						cv2021.id_pais, 
						cv2021.codigo_postal, 
						cv2021.longitud, 
						cv2021.latitud,
						m.municipio,
						l.localidad
					FROM casillas_votos_2021 cv2021
					LEFT JOIN municipios m ON cv2021.id_municipio = m.id
					LEFT JOIN localidades l ON cv2021.id_localidad = l.id
					WHERE 1 
			";
			if($id!=""){
				$sql.=" AND cv2021.id = '{$id}' ";
			}
			if($id_seccion_ine!=""){
				$sql.=" AND cv2021.id_seccion_ine = '{$id_seccion_ine}' ";
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

		function casilla_voto_2021Nombre($id=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2021 WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function casilla_voto_2021ClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM casillas_votos_2021 WHERE 1 = 1 ");
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