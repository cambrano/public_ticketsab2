<?php
		function switch_operacionesDatos(){
			include 'db.php'; 
			$sql=("SELECT * FROM switch_operaciones WHERE 1 = 1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc(); 
			$arrayConfiguracion=$row; 
			$conexion->close();
			return $arrayConfiguracion;
		}

		function switch_operacionesPermisos($permiso=null){
			include 'db.php'; 
			$sql=("SELECT * FROM switch_operaciones WHERE 1 = 1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc(); 
			$data=$row; 
			$conexion->close();
			return $data; 
		}
?>