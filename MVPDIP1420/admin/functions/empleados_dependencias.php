<?php
		function empleados_dependenciasDatos($id=null,$id_empleado=null,$id_dependencia=null){
			include 'db.php';
			$sql="SELECT * FROM empleados_dependencias WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_empleado!=""){
				$sql.=" AND id_empleado='{$id_empleado}' ";
			}
			if($id_dependencia!=""){
				$sql.=" AND id_dependencia='{$id_dependencia}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function empleado_dependenciaDatos($id=null,$id_empleado=null,$id_dependencia=null){
			include 'db.php';
			$sql=("SELECT * FROM empleados_dependencias WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_empleado!=""){
				$sql.=" AND id_empleado='{$id_empleado}' ";
			}
			if($id_dependencia!=""){
				$sql.=" AND id_dependencia='{$id_dependencia}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function empleado_dependenciaIdsDependenciasDatos($id=null,$id_empleado=null,$id_dependencia=null){
			include 'db.php';
			$sql=("SELECT GROUP_CONCAT(id_dependencia) ids_dependencias FROM empleados_dependencias WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_empleado!=""){
				$sql.=" AND id_empleado='{$id_empleado}' ";
			}
			if($id_dependencia!=""){
				$sql.=" AND id_dependencia='{$id_dependencia}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function empleado_dependenciaValidadorUnico($id=null,$id_empleado=null,$id_dependencia=null){
			include 'db.php';
			$sql=("SELECT * FROM empleados_dependencias WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_empleado!=""){
				$sql.=" AND id_empleado='{$id_empleado}' ";
			}
			if($id_dependencia!=""){
				$sql.=" AND id_dependencia='{$id_dependencia}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			if(empty($row)){
				$datos = false;
			}else{
				$datos = true;
			}
			return $datos;
		}

		


?>