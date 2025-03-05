<?php
		function elecciones() {
			include 'db.php'; 
			$sql="SELECT * FROM elecciones WHERE 1 = 1 ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$data[$row['modulo']] = $row;
			} 
			return $data;
		}

		function eleccionesDatos() {
			include 'db.php'; 
			$sql="SELECT * FROM elecciones WHERE 1 = 1 ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$data[$row['id']] = $row;
			} 
			return $data;
		}

		function eleccionesModulo($modulo=null,$tipo_eleccion=null){
			include 'db.php';
			if($tipo_eleccion==''){
				$tipo_eleccion = '*';
			}
			$sql=("SELECT {$tipo_eleccion} FROM elecciones WHERE 1 = 1 ");
			if($modulo!=""){
				$sql.=" AND modulo='{$modulo}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}
?>