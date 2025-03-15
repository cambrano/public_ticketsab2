<?php
		function usuarios($id=null) {
			include 'db.php'; 
			if($id==""){
				$id=$_COOKIE["id_usuario"];
			}

			$sql="
				SELECT 
				u.id,
				u.usuario,
				u.id_empleado,

				IF(
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) IS NULL,
					u.usuario,
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) 
				)
				 nombreCompledo 

				FROM usuarios u WHERE u.id='$id' ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row;
		}
		function usuarioDatos($id=null,$id_empleado=null,$id_seccion_ine_ciudadano=null) {
			include 'db.php'; 
			$sql="
				SELECT 
				*,
				(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) nombre_usuario ,
				(SELECT e.id_seccion_ine  FROM secciones_ine_ciudadanos e WHERE e.id=u.id_seccion_ine_ciudadano) id_seccion_ine 
				FROM usuarios u WHERE  1 ";
			if($id!=""){
				$sql.=" AND u.id={$id} ";
			}
			if($id_empleado!=""){
				$sql.=" AND u.id_empleado={$id_empleado} ";
			}
			if($id_seccion_ine_ciudadano!=""){
				$sql.=" AND u.id_seccion_ine_ciudadano={$id_seccion_ine_ciudadano} ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row;
			$conexion->close();
			return $datos;
		}
		function usuariosSelect($id_usuario=null,$tipo=null,$web_site=null,$cliente=null) {
			include 'db.php';  
			if($id_usuario==""){
				$id_usuario=$_COOKIE["id_usuario"];
			}
			if($tipo==2 || $tipo==3){
				$id_usuario="";
			}
			$select[$id_usuario]='selected="selected"';
			$return ="<option value='' >Seleccione</option> ";

			$sqlSub="";
			if($_COOKIE["id_usuario"]=="1" || $tipo==3 || $tipo==1 || $tipo==2 ){
				$sqlSub=" OR u.codigo_plataforma='x' ";
				if($web_site!=""){
					$sqlSub .=" OR u.codigo_plataforma='y' ";
				}
				if($cliente!=""){
					$sqlSub .=" OR u.codigo_plataforma='z'";
				}

			}

			$sql="SELECT 
				u.id,
				u.usuario,
				u.id_empleado,

				IF(
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) IS NULL,
					CONCAT(UCASE(LEFT(u.usuario, 1)), SUBSTRING(u.usuario, 2)),
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) 
				)
				 nombreCompledo 

				FROM usuarios u WHERE  (u.codigo_plataforma='{$codigo_plataforma}' {$sqlSub} ) ";
			 

			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombreCompledo']."</option> ";
			} 
			$conexion->close();
			return $return;
		}
		
		function usuarioValidadorSistema($usuario=null,$tipo=null,$id_empleado=null,$id_seccion_ine_ciudadano=null){
			include 'db.php'; 
			$sql.="SELECT * FROM usuarios WHERE usuario = '{$usuario}'  ";
			if($tipo==2){
				$sql.=" AND id_empleado != $id_empleado";
			}
			if($tipo==3){
				$sql.=" AND id_seccion_ine_ciudadano != $id_seccion_ine_ciudadano";
			}
			$sql;
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			if($row['id']==""){
				$existe=false;
			}else{
				$existe=true;
			}
			$sql;
			$conexion->close();
			return $existe; 
		}

		function usuarioId($usuario=null){
			include 'db.php'; 
			$sql.="SELECT * FROM usuarios WHERE usuario = '{$usuario}'  ";
			$sql;
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			$sql;
			$conexion->close();
			return $row['id']; 
		}

		function usuariosNotificaciones($id_usuario=null,$tipo=null) {
			include 'db.php';  
			$sql=" 
				SELECT * 
				FROM empleados e 
				WHERE e.notificaciones_sistema =1
				AND 
				e.codigo_plataforma='{$codigo_plataforma}'
				AND 
				EXISTS
				(SELECT * FROM usuarios u WHERE u.id_perfil_usuario IN (2,3)  ";
			$result = $conexion->query($sql);
			$num=0;
			while($row=$result->fetch_assoc()){
				$datos[$num]=$row;
				$num=$num+1;
			} 
			$conexion->close();
			return $datos;
		}


?>