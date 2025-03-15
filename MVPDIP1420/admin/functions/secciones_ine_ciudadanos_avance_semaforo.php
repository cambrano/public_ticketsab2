<?php
		function secciones_ine_ciudadanos_avance_semaforoDatos(){
			include 'db.php'; 
			$sql=("SELECT * FROM secciones_ine_ciudadanos_avance_semaforo WHERE 1 = 1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc(); 
			$arrayConfiguracion=$row; 
			$conexion->close();
			return $arrayConfiguracion;
		}

		function secciones_ine_ciudadanos_avance_semaforoPermisos($permiso=null){
			include 'db.php'; 
			$sql=("SELECT * FROM secciones_ine_ciudadanos_avance_semaforo WHERE 1 = 1 ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc(); 
			$data=$row; 
			$conexion->close();
			return $data; 
		}
?>