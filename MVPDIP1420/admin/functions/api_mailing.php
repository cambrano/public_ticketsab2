<?php
		function api_mailingDatos($id=null){
			include 'db.php';
			$sql="SELECT * FROM api_mailing WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

?>