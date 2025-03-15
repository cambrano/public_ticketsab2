<?php

	function cuenta_red_social_actividadDatos($id=null){
		include 'db.php';
		$sql=("SELECT * FROM cuentas_redes_sociales_actividades WHERE 1 = 1 ");
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}

	function cuenta_red_social_actividadClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT * FROM cuentas_redes_sociales_actividades WHERE 1 = 1 ");
		if($clave!=""){
			$sql.=" AND clave='{$clave}' ";
		}
		if($id!=""){
			$sql.=" AND id !='{$id}' ";
		}
		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row['id']; 
		return $datos;
	}