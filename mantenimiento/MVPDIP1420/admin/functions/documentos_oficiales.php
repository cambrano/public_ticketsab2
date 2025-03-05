<?php
	function documento_oficialDatos($id=null){
		include 'db.php';
		$sql=("SELECT * FROM documentos_oficiales WHERE 1 = 1 ");
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}

	function documento_oficialCiudadanoDatos($id=null,$id_seccion_ine_ciudadano=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT * FROM documentos_oficiales WHERE 1 = 1 ");
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		if($id_seccion_ine_ciudadano!=""){
			$sql.=" AND id_seccion_ine_ciudadano='{$id_seccion_ine_ciudadano}' ";
		}
		if($tipo!=""){
			$sql.=" AND tipo='{$tipo}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}

	