<?php
	function seccion_ine_ciudadano_encuesta_respuestasDatos($id=null,$id_seccion_ine_ciudadano_encuesta=null,$id_seccion_ine_ciudadano=null,$id_encuesta=null,$id_cuestionario=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_ciudadanos_encuestas_respuestas WHERE 1=1  ";
		if($id !=''){
			$sql .= " AND id = '{$id}' ";
		}
		if($id_seccion_ine_ciudadano_encuesta !=''){
			$sql .= " AND id_seccion_ine_ciudadano_encuesta = '{$id_seccion_ine_ciudadano_encuesta}' ";
		}
		if($id_seccion_ine_ciudadano !=''){
			$sql .= "AND id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}'";
		}
		if($id_encuesta !=''){
			$sql .= " AND id_encuesta = '{$id_encuesta}' ";
		}
		if($id_cuestionario !=''){
			$sql .= " AND id_cuestionario = '{$id_cuestionario}' ";
		}

		$sql;
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			$datos[$num]=$row;
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	}

	function seccion_ine_ciudadano_encuesta_respuestasIdDatos($id=null,$id_seccion_ine_ciudadano_encuesta=null,$id_seccion_ine_ciudadano=null,$id_encuesta=null,$id_cuestionario=null){ 
		include 'db.php'; 
		$sql="SELECT * FROM secciones_ine_ciudadanos_encuestas_respuestas WHERE 1=1  ";
		if($id !=''){
			$sql .= " AND id = '{$id}' ";
		}
		if($id_seccion_ine_ciudadano_encuesta !=''){
			$sql .= " AND id_seccion_ine_ciudadano_encuesta = '{$id_seccion_ine_ciudadano_encuesta}' ";
		}
		if($id_seccion_ine_ciudadano !=''){
			$sql .= "AND id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}'";
		}
		if($id_encuesta !=''){
			$sql .= " AND id_encuesta = '{$id_encuesta}' ";
		}
		if($id_cuestionario !=''){
			$sql .= " AND id_cuestionario = '{$id_cuestionario}' ";
		}

		$sql;
		$result = $conexion->query($sql); 
		$num=0;
	 
		while($row=$result->fetch_assoc()){
			if($row['id_cuestionario_respuesta']==""){
				$id_cuestionario_respuesta = "x";
			}else{
				$id_cuestionario_respuesta = $row['id_cuestionario_respuesta'];
			}
			$datos[$row['id_cuestionario']][$id_cuestionario_respuesta]=$row;
		}
		$conexion->close();
		return $datos;
	}

	function seccion_ine_ciudadano_encuesta_respuestaClaveVerificacion($clave=null,$id=null,$tipo=null){
		include 'db.php';
		$sql=("SELECT id FROM secciones_ine_ciudadanos_encuestas_respuestas WHERE 1 = 1 ");
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