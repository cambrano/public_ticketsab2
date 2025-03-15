<?php

		function manzanas_ine_parametrosDatosMapa($id=null,$id_manzana_ine=null,$id_seccion_ine=null,$id_municipioL=null,$id_distrito_localL=null,$id_distrito_federalL=null,$orden=null,$limit=null) {
			include 'db.php'; 
			$sql="SELECT mip.id_manzana_ine,mip.tipo,mip.orden,mip.latitud,mip.longitud FROM manzanas_ine_parametros mip WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND mip.id = '{$id}' ";
			}
			if(!empty($id_seccion_ine)){
				if (is_array($id_seccion_ine)) {
					$inClause = "'".implode("','", $id_seccion_ine)."'";
					$sql .= " AND mip.id_seccion_ine IN ({$inClause})";
				} elseif ($value !== '') {
					$sql.=" AND mip.id_seccion_ine = '{$id_seccion_ine}' ";
				}
			}
			if($id_manzana_ine!=""){
				$sql.=" AND mip.id_manzana_ine = '{$id_manzana_ine}' ";
			}
			if($id_municipioL!=""){
				$sql.=" AND EXISTS( SELECT mi.id FROM manzanas_ine mi WHERE mi.id_municipio='{$id_municipioL}' AND mi.id = mip.id_manzana_ine ) ";
			}

			if($id_distrito_localL!=""){
				$sql.=" AND EXISTS( SELECT mi.id FROM manzanas_ine mi WHERE mi.id_distrito_local='{$id_distrito_localL}' AND mi.id = mip.id_manzana_ine ) ";
			}

			if($id_distrito_federalL!=""){
				$sql.=" AND EXISTS( SELECT si.id FROM manzanas_ine si WHERE si.id_distrito_federal='{$id_distrito_federalL}' AND si.id = mip.id_manzana_ine ) ";
			}

			if($orden!=""){
				$sql.= " ORDER BY mip.{$orden}";
			}
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_manzana_ine']][$row['tipo']][$row['orden']]=$row;
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