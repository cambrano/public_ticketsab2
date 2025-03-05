<?php
		function secciones_ine_giras_puntosDatos($id=null,$id_seccion_ine_gira=null,$id_seccion_ineL=null,$id_municipioL=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM secciones_ine_giras_puntos WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_gira!=""){
				$sql.=" AND id_seccion_ine_gira = '{$id_seccion_ine_gira}' ";
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

		function secciones_ine_giras_puntosDatosMapa($id=null,$id_seccion_ine_gira=null,$id_seccion_ine_giras=null,$id_seccion_ineL=null,$id_municipioL=null,$orden=null) {
			include 'db.php'; 
			$sql="SELECT * FROM secciones_ine_giras_puntos sip WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_gira!=""){
				$sql.=" AND id_seccion_ine_gira = '{$id_seccion_ine_gira}' ";
			}
			if($id_seccion_ine_giras!=""){
				$id_seccion_ine_giras_in = implode(',',$id_seccion_ine_giras);
				$sql.=" AND id_seccion_ine_gira IN ({$id_seccion_ine_giras_in}) ";
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
				$datos[$row['id_seccion_ine_gira']][$row['orden']]=$row;
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