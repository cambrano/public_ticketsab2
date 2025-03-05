<?php
		function municipios_parametrosDatosMapa($id_municipio=null,$id_estado=null) {
			include 'db.php'; 
			$sql="SELECT * FROM municipios_parametros WHERE 1 = 1 ";
			//$resultado = $conexion->query($sql);
			if($id_estado!=""){
				$sql.= " AND id_estado = '{$id_estado}' ";
			}
			if($id_municipio!=""){
				$sql.= " AND id_municipio = '{$id_municipio}' ";
			}
			$sql .= " ORDER BY id_municipio,orden DESC	";
			$sql;
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$row['id_municipio']][$row['tipo']][$row['orden']]=$row;
				//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}
?>