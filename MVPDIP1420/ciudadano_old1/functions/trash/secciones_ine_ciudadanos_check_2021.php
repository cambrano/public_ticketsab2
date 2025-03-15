<?php
	function seccion_ine_ciudadano_check_2021Datos($id=null,$id_seccion_ine=null,$id_municipio=null,$id_casilla_voto_2021=null,$id_usuario=null,$id_empleado=null,$id_seccion_ine_ciudadano=null,$id_seccion_ine_ciudadano_relacionado=null){ 
		include 'db.php'; 
		$sql=" SELECT *	FROM secciones_ine_ciudadanos_check_2021 WHERE codigo_plataforma='{$codigo_plataforma}'  ";

		if($id!=""){
			$sql.= " AND id = '{$id}' ";
		}

		if($id_seccion_ine!=""){
			$sql.= " AND id_seccion_ine = '{$id_seccion_ine}' ";
		}

		if($id_municipio!=""){
			$sql.= " AND id_municipio = '{$id_municipio}' ";
		}

		if($id_casilla_voto_2021!=""){
			$sql.= " AND id_casilla_voto_2021 = '{$id_casilla_voto_2021}' ";
		}

		if($id_usuario!=""){
			$sql.= " AND id_usuario = '{$id_usuario}' ";
		}

		if($id_empleado!=""){
			$sql.= " AND id_empleado = '{$id_empleado}' ";
		}

		if($id_seccion_ine_ciudadano!=""){
			$sql.= " AND id_seccion_ine_ciudadano = '{$id_seccion_ine_ciudadano}' ";
		}

		if($id_seccion_ine_ciudadano_relacionado!=""){
			$sql.= " AND id_seccion_ine_ciudadano_relacionado = '{$id_seccion_ine_ciudadano_relacionado}' ";
		}

		$sql;
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		$datos=$row; 
		$conexion->close();
		return $datos;
	}