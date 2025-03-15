<?php
	function secciones_ine_colonias($id=null,$id_seccion_ineL=null,$id_municipioL=null,$id_distrito_localL=null,$id_distrito_federalL=null,$sin_seleccione=null){
		include 'db.php'; 
		$id;
		$ids = explode(",", $id);
		if($sin_seleccione==""){
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		}
		$sql="SELECT nombre,group_concat(id_seccion_ine) id_seciones_ine FROM secciones_ine_colonias WHERE 1 = 1 ";
		if($id_seccion_ineL!=""){
			$sql.= " AND id_seccion_ine = '{$id_seccion_ineL}' ";
		}
		if($id_municipioL!=""){
			$sql.= " AND id_municipio = '{$id_municipioL}' ";
		}
		if($id_distrito_localL!=""){
			$sql.= " AND id_distrito_local = '{$id_distrito_localL}' ";
		}
		if($id_distrito_federalL!=""){
			$sql.= " AND id_distrito_federal = '{$id_distrito_federalL}' ";
		}
		$sql.= " GROUP BY nombre ";
		$result = $conexion->query($sql); 
		while($row=$result->fetch_assoc()){
			if (in_array($row['id'], $ids) && $id !='' ) {
				$return .="<option selected value='".$row['id_seciones_ine']."' >".$row['nombre']."</option> ";
			}else{
				$return .="<option value='".$row['id_seciones_ine']."' >".$row['nombre']."</option> ";
			}
		} 
		$conexion->close();
		return $return;
	}