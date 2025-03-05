<?php
	function lista_nominalId($clave_elector=null){
		include 'db.php';
		$sql = "SELECT id FROM lista_nominal WHERE 1 = 1 AND clave_elector='{$clave_elector}' ";
		$resultado = $conexion->query($sql);
		$row = $resultado->fetch_assoc();
		return $row['id'];
	}
?>