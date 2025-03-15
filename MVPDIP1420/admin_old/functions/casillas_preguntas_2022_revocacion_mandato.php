<?php
		function casillas_preguntas_2022_revocacion_mandatoDatos($id=null,$id_casilla_voto_2022_revocacion_mandato=null,$id_seccion_ine=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_preguntas_2022_revocacion_mandato WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_casilla_voto_2022_revocacion_mandato!=""){
				$sql.=" AND id_casilla_voto_2022_revocacion_mandato = '{$id_casilla_voto_2022_revocacion_mandato}' ";
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

		function casillas_preguntas_2022_revocacion_mandato_partidosDatos($id=null,$id_casilla_voto_2022_revocacion_mandato=null,$id_seccion_ine=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_preguntas_2022_revocacion_mandato cv
					LEFT JOIN partidos_2018 p
					ON p.id = cv.id_partido_2018
					WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND cv.id = '{$id}' ";
			}
			if($id_casilla_voto_2022_revocacion_mandato!=""){
				$sql.=" AND cv.id_casilla_voto_2022_revocacion_mandato = '{$id_casilla_voto_2022_revocacion_mandato}' ";
			}
			if($tipo!=""){
				$sql.=" AND cv.tipo = '{$tipo}' ";
			}

			if($id_seccion_ine!=""){
				$sql.=" AND cv.id_seccion_ine = '{$id_seccion_ine}' ";
			}

			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}
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
?>