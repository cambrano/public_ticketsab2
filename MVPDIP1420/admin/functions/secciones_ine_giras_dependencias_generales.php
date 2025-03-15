<?php
		function secciones_ine_giras_dependencias_generalesDatos($id=null,$id_seccion_ine_gira=null,$id_dependencia=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM secciones_ine_giras_dependencias_generales WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_gira!=""){
				$sql.=" AND id_seccion_ine_gira = '{$id_seccion_ine_gira}' ";
			}
			if($id_dependencia!=""){
				$sql.=" AND id_dependencia = '{$id_dependencia}' ";
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
		function secciones_ine_giras_dependencias_generalesIdsDatos($id=null,$id_seccion_ine_gira=null,$id_dependencia=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM secciones_ine_giras_dependencias_generales WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_gira!=""){
				$sql.=" AND id_seccion_ine_gira = '{$id_seccion_ine_gira}' ";
			}
			if($id_dependencia!=""){
				$sql.=" AND id_dependencia = '{$id_dependencia}' ";
			}
			if($orden!=""){
				$sql.= " ORDER BY {$orden}";
			}

			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_dependencia']]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}
?>