<?php
	function localidades_seccionesDatosSeccion($id_estadoL=null,$id_municipioL=null,$seccion=null){ 
		include 'db.php';
		$sql=("SELECT ls.id,
					ls.clave,
					ls.localidad,
					(SELECT m.municipio FROM municipios m WHERE m.id = ls.id_municipio) municipio 
				FROM localidades_secciones ls WHERE 1 = 1 ");
		if($id_estadoL!=""){
			$sql.= " AND ls.id_estado = '{$id_estadoL}' ";
		}
		if($id_municipioL!=""){
			$sql.= " AND ls.id_municipio = '{$id_municipioL}' ";
		}
		if($id_municipioL!=""){
			$sql.= " AND ls.id_municipio = '{$id_municipioL}' ";
		}
		if($seccion!=""){
			$sql.= " AND ls.seccion = '{$seccion}' ";
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