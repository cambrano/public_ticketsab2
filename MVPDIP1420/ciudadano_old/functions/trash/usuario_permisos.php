<?php
	function seccionPermiso($seccion=null,$id_usuario=null){
		include 'db.php';
		//verificamos si es super usuario o es usuario normal
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND (codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x') ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		if($row['id'] != ""){
			return true;
		}else{
			$sql="
			SELECT * 
			FROM usuarios_permisos up 
			WHERE up.id_usuario='{$id_usuario}' AND  up.seccion='{$seccion}' AND up.status='1' 
			";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			$conexion->close();
			if($row['id']){
				return true;
			}else{
				return false;
			}
		}
		
	}

	function moduloPermiso($modulo=null,$seccion=null,$id_usuario=null){
		include 'db.php';
		//verificamos si es super usuario o es usuario normal
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND (codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x') ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		if($row['id'] != ""){
			return true;
		}else{
			$sql="SELECT * FROM usuarios_permisos up WHERE up.id_usuario='{$id_usuario}' AND  up.seccion='{$seccion}' AND up.modulo='$modulo' AND up.status='1' ";
		$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			if($row['id'] != ""){
				return true;
			}else{
				return false;
			}
		}  
	}

	function moduloAccion($seccion=null,$modulo=null,$id_usuario=null,$permiso=null){
		include 'db.php';
		//verificamos si es super usuario o es usuario normal
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND (codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x') ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_array();
		if($row['id'] != ""){
			return true;
		}else{
			$sql="SELECT * FROM usuarios_permisos up WHERE up.id_usuario='{$id_usuario}' AND  up.seccion='{$seccion}' AND up.modulo='$modulo' AND up.status='1' AND up.permiso='{$permiso}' ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_array();
			if($row['id'] != ""){
				return true;
			}else{
				return false;
			}
		}
		
	}
?>