<?php
		function secciones_ine_actividades_puntosDatos($id=null,$id_seccion_ine_actividad=null,$id_seccion_ineL=null,$id_municipioL=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM secciones_ine_actividades_puntos WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_actividad!=""){
				$sql.=" AND id_seccion_ine_actividad = '{$id_seccion_ine_actividad}' ";
			}
			if($id_seccion_ineL!=""){
				$sql.=" AND id_seccion_ine = '{$id_seccion_ineL}' ";
			}
			if($id_municipioL!=""){
				$sql.=" AND id_municipio = '{$id_municipioL}' ";
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

		function secciones_ine_actividades_puntosDatosMapa($id=null,$id_seccion_ine_actividad=null,$id_seccion_ine_actividades=null,$id_seccion_ineL=null,$id_municipioL=null,$orden=null) {
			include 'db.php'; 
			$sql="SELECT * FROM secciones_ine_actividades_puntos sip WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_actividad!=""){
				$sql.=" AND id_seccion_ine_actividad = '{$id_seccion_ine_actividad}' ";
			}
			if($id_seccion_ine_actividades!=""){
				$id_seccion_ine_actividades_in = implode(',',$id_seccion_ine_actividades);
				$sql.=" AND id_seccion_ine_actividad IN ({$id_seccion_ine_actividades_in}) ";
			}
			if($id_seccion_ineL!=""){
				$id_seccion_ineL = implode(',',$id_seccion_ineL);
				$sql.=" AND id_seccion_ine IN ('{$id_seccion_ineL}') ";
			}
			if($id_municipioL!=""){
				$sql.=" AND id_municipio = '{$id_municipioL}' ";
			}
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_seccion_ine_actividad']][$row['orden']]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}

			$sql;
			$conexion->close();
			return $datos;
		}
?>