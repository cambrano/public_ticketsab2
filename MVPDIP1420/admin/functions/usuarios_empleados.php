<?php
		function usuario_empleadosDatos($id=null,$id_empleado=null) {
			include 'db.php'; 
			$sql="
				SELECT 
				*,
				(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) nombre_usuario 
				FROM usuarios u WHERE 1 /*(u.codigo_plataforma='{$codigo_plataforma}' or u.codigo_plataforma='x')*/ ";
			if($id!=""){
				$sql.=" AND u.id={$id} ";
			}
			if($id_empleado!=""){
				$sql.=" AND u.id_empleado={$id_empleado} ";
			}

			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			if($row['nombre_usuario']==null){
				$row['nombre_usuario']='soporte';
			}
			
			$datos=$row;
			$conexion->close();
			return $datos;
		}
?>