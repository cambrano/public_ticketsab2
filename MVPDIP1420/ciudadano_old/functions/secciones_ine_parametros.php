<?php
		function secciones_ine_parametrosDatos($id=null,$id_seccion_ine=null,$orden=null,$limit=null){
			include 'db.php'; 
			$sql="SELECT 
					*
					FROM secciones_ine_parametros WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id = '{$id}' ";
			}
			if($id_seccion_ine!=""){
				$sql.=" AND id_seccion_ine = '{$id_seccion_ine}' ";
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

		function secciones_ine_parametrosDatosMapa($id=null,$id_seccion_ine=null,$id_municipio=null,$id_distrito_local=null,$id_distrito_federal=null,$orden=null,$limit=null) {
			include 'db.php'; 
			$sql="SELECT id_seccion_ine,tipo,orden,latitud,longitud FROM secciones_ine_parametros sip WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND sip.id = '{$id}' ";
			}
			if($id_seccion_ine!=""){
				$sql.=" AND sip.id_seccion_ine = '{$id_seccion_ine}' ";
			}
			if($id_municipio!=""){
				$sql.=" AND EXISTS( SELECT si.id FROM secciones_ine si WHERE si.id_municipio='{$id_municipio}' AND si.id = sip.id_seccion_ine ) ";
			}

			if($id_distrito_local!=""){
				$sql.=" AND EXISTS( SELECT si.id FROM secciones_ine si WHERE si.id_distrito_local='{$id_distrito_local}' AND si.id = sip.id_seccion_ine ) ";
			}

			if($id_distrito_federal!=""){
				$sql.=" AND EXISTS( SELECT si.id FROM secciones_ine si WHERE si.id_distrito_federal='{$id_distrito_federal}' AND si.id = sip.id_seccion_ine ) ";
			}

			if($orden!=""){
				$sql.= " ORDER BY sip.{$orden}";
			}
			$sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_seccion_ine']][$row['tipo']][$row['orden']]=$row;
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