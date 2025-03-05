<?php
	function seccionPermiso($seccion=null,$id_usuario=null){
		include 'db.php';
		//verificamos si es super usuario o es usuario normal
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND 1 /*(codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x')*/ ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		if($row['id'] != ""){
			return true;
		}else{
			$sql="
			SELECT * 
			FROM usuarios_permisos up 
			WHERE up.id_usuario='{$id_usuario}' AND  up.seccion='{$seccion}' AND up.status='1' 
			";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
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
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND 1 /*(codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x') */";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		if($row['id'] != ""){
			return true;
		}else{
			$sql="SELECT * FROM usuarios_permisos up WHERE up.id_usuario='{$id_usuario}' AND  up.seccion='{$seccion}' AND up.modulo='$modulo' AND up.status='1' ";
		$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
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
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND 1 /*(codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x')*/ ";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		if($row['id'] != ""){
			return true;
		}else{
			$sql="SELECT * FROM usuarios_permisos up WHERE up.id_usuario='{$id_usuario}' AND  up.seccion='{$seccion}' AND up.modulo='$modulo' AND up.status='1' AND up.permiso='{$permiso}' ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			if($row['id'] != ""){
				return true;
			}else{
				return false;
			}
		}
	}
	function moduloAccionPermisos($seccion=null,$modulo=null,$id_usuario=null){
		include 'db.php';
		//verificamos si es super usuario o es usuario normal
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND 1 /*(codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x')*/;";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		if($row['id'] != ""){
			$data["all"]=true;
		}else{
			$sql="SELECT * FROM usuarios_permisos up WHERE up.id_usuario='{$id_usuario}' AND  up.seccion='{$seccion}' AND up.modulo='$modulo' AND up.status='1';";
			$sql;
			$result = $conexion->query($sql); 
			while($row=$result->fetch_assoc()){
				$data[$row['permiso']]=true;
			}
		}
		return $data;
		$conexion->close();
	}
	function modulosPermiso($seccion=null,$modulo=null,$id_usuario=null){
		include 'db.php';
		//verificamos si es super usuario o es usuario normal
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND 1 /* (codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x') */";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$data  = array();
		if($row['id'] != ""){
			$data["all"]=true;
		}else{
			$sql = "SELECT modulo FROM usuarios_permisos WHERE status=1  ";
			if($seccion!=""){
				$sql.= " AND seccion = '{$seccion}' ";
			}
			if($modulo!=""){
				$sql.= " AND modulo = '{$modulo}' ";
			}
			if($id_usuario!=""){
				$sql.= " AND id_usuario = '{$id_usuario}' ";
			}
			$sql .= " GROUP BY modulo; ";
			$sql;
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$data[$row['modulo']]=true;
			} 
		}
		return $data;
	}

	function seccionesPermiso($id_usuario=null){
		include 'db.php';
		//verificamos si es super usuario o es usuario normal
		$sql="SELECT * FROM usuarios WHERE id='{$id_usuario}' AND id_perfil_usuario IN(1,2) AND 1 /*(codigo_plataforma='{$codigo_plataforma}' OR codigo_plataforma='x') */";
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$data  = array();
		if($row['id'] != ""){
			$data["all"]=true;
		}else{
			$sql = "SELECT seccion FROM usuarios_permisos WHERE status=1  ";
			if($id_usuario!=""){
				$sql.= " AND id_usuario = '{$id_usuario}' ";
			}
			//$sql .= " GROUP BY id_seccion; ";
			$sql;
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$data[$row['seccion']]=true;
			} 
		}
		return $data;
	}
?>