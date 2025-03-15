<?php
	function seccion_ine_ciudadano_seguimientoDatos($id=null,$id_seccion_ine_ciudadano=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_ciudadanos_seguimientos WHERE 1 = 1 ";

		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}

		if($id_seccion_ine_ciudadano!=""){
			$sql.=" AND id_seccion_ine_ciudadano='{$id_seccion_ine_ciudadano}' ";
		}

		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}


	 