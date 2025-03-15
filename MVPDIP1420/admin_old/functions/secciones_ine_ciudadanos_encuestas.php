<?php
	function seccion_ine_ciudadano_encuestaDatos($id=null,$id_encuesta=null,$id_seccion_ine_ciudadano=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_ciudadanos_encuestas WHERE 1=1  ";
		if($id !=''){
			$sql .= " AND id = '{$id}' ";
		}
		if($id_encuesta !=''){
			$sql .= " AND id_encuesta = '{$id_encuesta}' ";
		}
		if($id_seccion_ine_ciudadano !=''){
			$sql .= "AND id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}'";
		}

		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}

	function seccion_ine_ciudadano_encuestaClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT id FROM secciones_ine_ciudadanos_encuestas WHERE 1 = 1 ");
		if($clave!=""){
			$sql.=" AND clave='{$clave}' ";
		}
		if($id!=""){
			$sql.=" AND id !='{$id}' ";
		}
		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row['id']; 
		return $datos;
	}