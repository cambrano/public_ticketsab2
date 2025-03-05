<?php
		function usuario_ciudadanosDatos($id=null,$id_seccion_ine_ciudadano=null) {
			include 'db.php'; 
			$sql="
				SELECT 
				*,
				(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM secciones_ine_ciudadanos e WHERE e.id=u.id_seccion_ine_ciudadano) nombre_usuario 
				FROM usuarios u WHERE  (u.codigo_plataforma='{$codigo_plataforma}' ) 
			";
			if($id!=""){
				$sql.=" AND u.id={$id} ";
			}
			if($id_seccion_ine_ciudadano!=""){
				$sql.=" AND u.id_seccion_ine_ciudadano={$id_seccion_ine_ciudadano} ";
			}	 

			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			if($row['nombre_usuario']==null){
				$row['nombre_usuario']='soporte';
			}
			foreach($row as $key => $value){
				if(is_numeric($key)) unset($row[$key]);
			}
			$datos=$row;
			$conexion->close();
			return $datos;
		}
?>