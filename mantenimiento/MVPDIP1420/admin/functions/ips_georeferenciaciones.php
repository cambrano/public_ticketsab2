<?php
	function ip_georeferenciacionDatos($ip=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM ips_georeferenciaciones WHERE ip='$ip' LIMIT 1  ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row;
		return $datos;
	}