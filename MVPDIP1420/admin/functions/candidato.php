<?php
		function candidatoDatos($id=null){
			include 'db.php';
			$sql="SELECT * FROM candidato WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

?>