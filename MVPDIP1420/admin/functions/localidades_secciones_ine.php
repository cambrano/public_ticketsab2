<?php
	function localidades_secciones_ineIdSecciones($id=null,$id_estadoL=null,$id_municipioL=null,$seccion=null,$sin_seleccione=null){ 
		include 'db.php';
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql=("SELECT 
				l.id,
				l.clave,
				l.localidad,
				l.id
				FROM localidades l  WHERE 1 = 1 ");
		if($id_estadoL!=""){
			$sql.= " AND l.id_estado = '{$id_estadoL}' ";
		}
		if($id_municipioL!=""){
			$sql.= " AND l.id_municipio = '{$id_municipioL}' ";
		}
		if($id!=""){
			$sql.= " AND l.id = '{$id}' ";
		}
		if($seccion!=""){
			$sql.= " AND ls.seccion = '{$seccion}' ";
		}
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			unset($id_secciones_ine);
			$sql="SELECT id_seccion_ine FROM localidades_secciones_ine WHERE id_localidad = '{$row['id']}' ";
			$resultLS = $conexion->query($sql);
			while($rowLS=$resultLS->fetch_assoc()){
				$id_secciones_ine[] = $rowLS['id_seccion_ine'];
			}

			$id_secciones_ine_string = implode(",", $id_secciones_ine);
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$id_secciones_ine_string."' >".$row['clave']." - ".$row['localidad']."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function localidades_secciones_ineIdSeccionesSelect($id=null,$id_estadoL=null,$id_municipioL=null,$id_distrito_localL=null,$id_distrito_federalL=null,$seccion=null,$sin_seleccione=null){ 
		include 'db.php';
		$id;
		$select[$id]='selected="selected"';
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql=("
				SELECT 
					clave_localidad,
					localidad,
					id_localidad ,
					group_concat(id_seccion_ine) id_seccion_ine
				FROM localidades_secciones_ine l  
				WHERE 1 = 1 ");
		if($id_estadoL!=""){
			//$sql.= " AND l.id_estado = '{$id_estadoL}' ";
		}
		if($id_municipioL!=""){
			$sql.= " AND l.id_municipio = '{$id_municipioL}' ";
		}
		if($id_distrito_localL!=""){
			$sql.= " AND EXISTS (
					SELECT * FROM secciones_ine s WHERE s.id_distrito_local = {$id_distrito_localL} AND s.numero = l.seccion
					) ";
		}
		if($id_distrito_federalL!=""){
			$sql.= " AND EXISTS (
				SELECT * FROM secciones_ine s WHERE s.id_distrito_federal = {$id_distrito_federalL} AND s.numero = l.seccion
				) ";
		}
		if($id!=""){
			$sql.= " AND l.id = '{$id}' ";
		}
		if($seccion!=""){
			$sql.= " AND ls.seccion = '{$seccion}' ";
		}
		$sql .= " GROUP BY l.localidad,id_localidad,clave_localidad ;";
		echo $sql;
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id_localidad'];
			$return .="<option ".$select[$sel]." value='".$row['id_seccion_ine']."' >".$row['clave_localidad']." - ".$row['localidad']."</option> ";
		} 
		$conexion->close();
		return $return;
	}