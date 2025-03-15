<?php
		function switch_operacionesDatos(){
			include 'db.php'; 
			$sql=("SELECT * FROM switch_operaciones WHERE codigo_plataforma='{$codigo_plataforma}'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array(); 
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
			$denominacion=$row['nombre']; 
			$logo=$row['logo'];
			$arrayConfiguracion=$row;
			if($denominacion==""){
				$denominacion="Ingrese el Nombre";
			}
			$arrayConfiguracion['nombre']=$denominacion;
			$arrayConfiguracion['logo']=$logo;
			$arrayConfiguracion['id']=$row['id'];
			$conexion->close();
			return $arrayConfiguracion;
		}

		function switch_operacionesPermisos($permiso=null){
			include 'db.php'; 
			$sql=("SELECT * FROM switch_operaciones WHERE codigo_plataforma='{$codigo_plataforma}'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array(); 
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
			$data=$row; 
			$conexion->close();
			return $data; 
		}
?>