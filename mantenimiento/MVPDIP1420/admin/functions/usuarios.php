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
				u.id_perfil_usuario,
				IF(
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) IS NULL,
					u.usuario,
					(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) 
				)
				 nombreCompledo 

				FROM usuarios u WHERE u.id='$id' AND (u.codigo_plataforma='{$codigo_plataforma}' OR u.codigo_plataforma='x')  ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row;
		}

		function usuariosCiudadanos($id=null,$id_seccion_ine_ciudadano=null,$id_seccion_ine=null,$status=null,$permisos=null,$sin_seleccione=null){
			include 'db.php';  
			if($sin_seleccione==""){
				$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			}
			$sql = " SELECT 
					u.id,
					u.usuario,
					sic.id AS id_seccion_ine_ciudadano,
					sic.nombre_completo,
					s.numero AS seccion,
					sic.id_seccion_ine,
					sicp.casilla,
					sicp.entrega,
					sicp.recibe,
					sicp.evaluacion
					FROM usuarios u
					LEFT JOIN secciones_ine_ciudadanos sic
					ON u.id_seccion_ine_ciudadano = sic.id
					LEFT JOIN secciones_ine s
					ON sic.id_seccion_ine = s.id
					LEFT JOIN secciones_ine_ciudadanos_permisos sicp
					ON sic.id = sicp.id_seccion_ine_ciudadano
					WHERE u.tabla = 'secciones_ine_ciudadanos'
			";
			if($id !=""){
				$sql.= " AND u.id = {$id} ";
			}
			if($id_seccion_ine_ciudadano !=""){
				$sql.= " AND u.id_seccion_ine_ciudadano = {$id_seccion_ine_ciudadano} ";
			}
			if($status !=""){
				$sql.= " AND u.status = {$status} ";
			}
			if($id_seccion_ine !=""){
				$sql.= " AND sic.id_seccion_ine = '{$id_seccion_ine}' ";
			}

			foreach ($permisos as $key => $value) {
				$sql.= " AND sicp.{$key} = {$value} ";
			}
			$sql.=" LIMIT 10 ";
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre_completo'].' - '.$row['usuario'].' - '.$row['seccion']."</option> ";
			} 
			$conexion->close();
			return $return;
		}


		function usuarioDatos($id=null,$id_empleado=null,$id_seccion_ine_ciudadano=null) {
			include 'db.php'; 
			$sql="
				SELECT 
				*,
				(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) nombre_usuario 
				FROM usuarios u WHERE 1 ";
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

				FROM usuarios u WHERE  (u.codigo_plataforma='{$codigo_plataforma}' {$sqlSub} ) AND u.id_seccion_ine_ciudadano IS NULL";
			 

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
			$sql.="SELECT * FROM usuarios WHERE usuario = '{$usuario}'   ";
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
				(SELECT * FROM usuarios u WHERE u.id_perfil_usuario IN (2,3)  ) ";
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