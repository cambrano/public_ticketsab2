<?php
		function dependencias($id=null,$sin_seleccione=null) {
			include 'db.php';
			
			//validamos las dependencias si estan con el usuario
			$sql=("SELECT 
					u.id_perfil_usuario,
					u.id,
					u.id_empleado,
					(SELECT GROUP_CONCAT(ed.id_dependencia) ids_dependencias FROM empleados_dependencias ed WHERE ed.id_empleado = u.id_empleado ) ids_dependencias,
					(SELECT COUNT(*) FROM usuarios_permisos up WHERE up.id_usuario = u.id AND up.id_permiso = 8) todas_dependencias
					FROM usuarios u 
					WHERE u.id= '{$_COOKIE['id_usuario']}' ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();


			$select[$id]='selected="selected"';
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql="SELECT * FROM dependencias WHERE 1 = 1 ";
			if($row['id_perfil_usuario'] == 3){
				if($row['todas_dependencias'] != 1){
					$sql.= " AND id IN (".$row['ids_dependencias'].") ";
				}
			}
			
			
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function dependenciasDatos($id=null){
			include 'db.php';
			$sql="SELECT * FROM dependencias WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function dependenciaDatos($id=null,$id_dependencia=null){
			include 'db.php';
			$sql=("SELECT * FROM dependencias WHERE 1 = 1 ");
			if($id_dependencia!=""){
				$sql.=" AND id_dependencia='{$id_dependencia}' ";
			}
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function dependenciaNombre($id=null){
			include 'db.php';
			$sql=("SELECT nombre FROM dependencias WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row['nombre']; 
			return $datos;
		}


		function dependenciaClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM dependencias WHERE 1 = 1 ");
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


?>