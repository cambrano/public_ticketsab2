<?php
	function usuario_moduloDatos($id,$id_modulo=null,$id_empleado=null){
		include 'db.php';
		$sql="SELECT * FROM usuarios_modulos WHERE codigo_plataforma='{$codigo_plataforma}'";
		if($id!=''){
			$sql.=" AND id='{$id}' ";
		}
		if($id_modulo!=''){
			$sql.=" AND id_modulo='{$id_modulo}' ";
		}
		if($id_empleado!=''){
			$sql.=" AND id_empleado='{$id_empleado}' ";
		}
		$resultado = $conexion->query($sql); 
		$row=$resultado->fetch_array();
		foreach($row as $key => $value){
			if(is_numeric($key)) unset($row[$key]);
		}
		$datos=$row;
		$conexion->close();
		return $datos;
	}
?>