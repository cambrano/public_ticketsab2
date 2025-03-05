<?php
	function tipo_ciudadano_seccion_avance_semaforoDatos($id=null,$id_seccion_ine=null,$id_tipo_ciudadano=null){ 
		include 'db.php';
		$sql="SELECT * FROM tipos_ciudadanos_secciones_avance_semaforo WHERE 1";
		if($id != null){
			$sql.= " AND id='$id' ";
		}
		if($id_seccion_ine != null){
			$sql.= " AND id_seccion_ine='$id_seccion_ine' ";
		}
		if($id_tipo_ciudadano != null){
			$sql.= " AND id_tipo_ciudadano='$id_tipo_ciudadano' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row;
		return $datos;
	}

	function tipos_ciudadanos_secciones_avance_semaforoDatos($id=null,$id_seccion_ine=null,$id_tipo_ciudadano=null){
		include 'db.php';
		$sql="SELECT * FROM tipos_ciudadanos_secciones_avance_semaforo WHERE 1";
		if($id != null){
			$sql.= " AND id='$id' ";
		}
		if($id_seccion_ine != null){
			$sql.= " AND id_seccion_ine='$id_seccion_ine' ";
		}
		if($id_tipo_ciudadano != null){
			$sql.= " AND id_tipo_ciudadano='$id_tipo_ciudadano' ";
		}
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$datos[$row['id_seccion_ine']][$row['id_tipo_ciudadano']] = $row;
		} 
		return $datos;
	}