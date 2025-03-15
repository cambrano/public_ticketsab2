<?php
	function seccion_ine_ciudadano_seccion_avance_semaforoDatos($id=null,$id_seccion_ine=null){ 
		include 'db.php';
		$sql="SELECT * FROM secciones_ine_ciudadanos_secciones_avance_semaforo WHERE 1";
		if($id != null){
			$sql.= " AND id='$id' ";
		}
		if($id_seccion_ine != null){
			$sql.= " AND id_seccion_ine='$id_seccion_ine' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row;
		return $datos;
	}

	function secciones_ine_ciudadanos_secciones_avance_semaforoDatos($id=null,$id_seccion_ine=null){
		include 'db.php';
		$sql="SELECT * FROM secciones_ine_ciudadanos_secciones_avance_semaforo WHERE 1";
		if($id != null){
			$sql.= " AND id='$id' ";
		}
		if($id_seccion_ine != null){
			$sql.= " AND id_seccion_ine='$id_seccion_ine' ";
		}
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$datos[$row['id_seccion_ine']] = $row;
		} 
		return $datos;
	}