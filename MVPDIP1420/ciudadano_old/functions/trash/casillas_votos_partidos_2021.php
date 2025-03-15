<?php
		function casillas_votos_partidos_2021Datos($id=null,$id_casilla_voto_2021=null,$id_seccion_ine=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_partidos_2021 WHERE codigo_plataforma='{$codigo_plataforma}' ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_casilla_voto_2021!=""){
				$sql.=" AND id_casilla_voto_2021 = '{$id_casilla_voto_2021}' ";
			}

			if($id_seccion_ine!=""){
				$sql.=" AND id_seccion_ine = '{$id_seccion_ine}' ";
			}

			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}
			$sql;
			$resultado = $conexion->query($sql);
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_array()){
				foreach($row as $key => $value){
					if(is_numeric($key)) unset($row[$key]);
				}
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}

		function casillas_votos_partidos_2021_partidosDatos($id=null,$id_casilla_voto_2021=null,$id_seccion_ine=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM casillas_votos_partidos_2021 cv
					LEFT JOIN partidos_2021 p
					ON p.id = cv.id_partido_2021
					WHERE cv.codigo_plataforma='{$codigo_plataforma}' ";
			if($id!=""){
				$sql.=" AND cv.id = '{$id}' ";
			}
			if($id_casilla_voto_2021!=""){
				$sql.=" AND cv.id_casilla_voto_2021 = '{$id_casilla_voto_2021}' ";
			}

			if($id_seccion_ine!=""){
				$sql.=" AND cv.id_seccion_ine = '{$id_seccion_ine}' ";
			}

			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}
			$sql;
			$resultado = $conexion->query($sql);
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_array()){
				foreach($row as $key => $value){
					if(is_numeric($key)) unset($row[$key]);
				}
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