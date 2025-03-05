<?php
		function casillas_votos_partidos_2018Datos($id=null,$id_casilla_voto_2018=null,$id_seccion_ine=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_partidos_2018 WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_casilla_voto_2018!=""){
				$sql.=" AND id_casilla_voto_2018 = '{$id_casilla_voto_2018}' ";
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

		function casillas_votos_partidos_2018_partidosDatos($id=null,$id_casilla_voto_2018=null,$id_seccion_ine=null,$orden=null,$tipo=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_partidos_2018 cv
					LEFT JOIN partidos_2018 p
					ON p.id = cv.id_partido_2018
					WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND cv.id = '{$id}' ";
			}
			if($id_casilla_voto_2018!=""){
				$sql.=" AND cv.id_casilla_voto_2018 = '{$id_casilla_voto_2018}' ";
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