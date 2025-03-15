<?php
	function tablasRelacionadas($tabla=null,$id=null){
		include 'db.php';
		if($tabla==null){
			return false;
			die;
		}
		$sql="SELECT
				a.TABLE_NAME tabla,
				(SELECT b.TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES b WHERE table_schema='{$db}' AND b.TABLE_NAME = a.TABLE_NAME ) comentario,
				a.COLUMN_NAME columna
			FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE a
			WHERE 1
				AND a.REFERENCED_TABLE_NAME = '{$tabla}'
				AND a.CONSTRAINT_SCHEMA = '{$db}';";
		$resultado = $conexion->query($sql);
		while($row=$resultado->fetch_assoc()){
			if($id!=''){
				$sql_contador = "SELECT count(*) contador FROM {$row['tabla']} WHERE {$row['columna']} = '{$id}'; ";
				$resultadoTablas = $conexion->query($sql_contador);
				$rowTablas=$resultadoTablas->fetch_assoc();
				$row['registros']=$rowTablas['contador']; 
			}
			$datos['tablas'][]=$row;
		}
		$conexion->close();
		return $datos;
	}
?>