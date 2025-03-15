<?php
		function secciones_ine_agendas_gobierno_dependencias_generalesDatos($id=null,$id_seccion_ine_agenda_gobierno=null,$id_dependencia=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM secciones_ine_agendas_gobierno_dependencias_generales WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_agenda_gobierno!=""){
				$sql.=" AND id_seccion_ine_agenda_gobierno = '{$id_seccion_ine_agenda_gobierno}' ";
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
		function secciones_ine_agendas_gobierno_dependencias_generalesIdsDatos($id=null,$id_seccion_ine_agenda_gobierno=null,$id_dependencia=null,$orden=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM secciones_ine_agendas_gobierno_dependencias_generales WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine_agenda_gobierno!=""){
				$sql.=" AND id_seccion_ine_agenda_gobierno = '{$id_seccion_ine_agenda_gobierno}' ";
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