<?php
	function seccion_ine_ciudadano_permisosDatos($id=null,$id_seccion_ine_ciudadano=null,$id_usuario=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_ciudadanos_permisos WHERE codigo_plataforma='{$codigo_plataforma}' ";

		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}

		if($id_seccion_ine_ciudadano!=""){
			$sql.=" AND id_seccion_ine_ciudadano='{$id_seccion_ine_ciudadano}' ";
		}

		if($id_usuario!=""){
			$sql.=" AND id_usuario='{$id_usuario}' ";
		}
		$sql;

		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		foreach($row as $key => $value){
			if(is_numeric($key)) unset($row[$key]);
		}
		$datos=$row; 
		$conexion->close();
		return $datos;
	}