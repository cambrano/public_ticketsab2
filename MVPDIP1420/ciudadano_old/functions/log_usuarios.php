<?php

		function logUsuario($id_usuario=null,$tabla=null,$id_columna=null,$operacion=null,$detalle=null,$fecha=null,$tipo=null){
			include 'db.php';

			$sql="
				SELECT 
					table_schema 'base_datos', 
					SUM(data_length + index_length )-72925184  'file_size',
					(SELECT database_capacidad FROM configuracion_paquete LIMIT 1) capacidad
				FROM 
					information_schema.TABLES 
				WHERE 
					table_schema = '{$db}' 
				GROUP BY 
					table_schema; ";

			$sql;
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			if($row['capacidad'] <= $row['file_size'] && $operacion=="Insert" ){
				echo "NO TIENE CAPACIDAD EN BASE DE DATOS,";
				$success=false;
				return $success;
				$conexion->close();
			}

			$sql="SELECT u.id,u.usuario,u.id_empleado,(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) nombreCompledo FROM usuarios u WHERE u.id='$id_usuario' ";
			$resultado = $conexionUsuario->query($sql);
			$row=$resultado->fetch_assoc();
			if($row['nombreCompledo']==""){
				$nombre_usuario=$row['usuario'];
			}else{
				$nombre_usuario=$row['nombreCompledo'];
			}
			if($fecha==""){
				$fecha=$fechaH;
			}
			$logUsuario['fechaR']=$fecha;
			$logUsuario['status']=1;
			$logUsuario['id_usuario']=$id_usuario; 
			$logUsuario['nombre_usuario']=$nombre_usuario; 
			$logUsuario['tabla']=$tabla;
			$logUsuario['id_columna']=$id_columna;
			$logUsuario['operacion']=$operacion;
			$logUsuario['detalle']=$detalle;
			$logUsuario['codigo_plataforma']=$codigo_plataforma;
			$logUsuario['tipo']=$tipo;

			
			$success=true;
			$fields_pdo = "`".implode('`,`', array_keys($logUsuario))."`";
			$values_pdo = "'".implode("','", $logUsuario)."'";
			$inset_log_usuarios= "INSERT INTO log_usuarios ($fields_pdo) VALUES ($values_pdo);";
			$inset_log_usuarios=$conexionUsuario->query($inset_log_usuarios);
			$num=$conexionUsuario->affected_rows;
			if(!$inset_log_usuarios || $num=0){
				$success=false;
				echo "<br>";
				echo "ERROR inset_log_usuarios"; 
				var_dump($conexionUsuario->error); 
			}
			if($success){ 
				$conexionUsuario->commit();
				$conexionUsuario->close();
			}else{ 
				$conexionUsuario->rollback();
				$conexionUsuario->close(); 
			}
			return $success;
		}

		function logUsuarioArray($arrayLog,$fecha=null,$id_usuario=null){
			include 'db.php';
			$conexionUsuario->autocommit(FALSE);
			foreach ($arrayLog as $key => $value) {
				$id_columna=$value['id'];
				$tabla=$value['tabla'];
				$operacion=$value['tipo']; 
				$detalle=$value['detalle']; 
				$sql="SELECT u.id,u.usuario,u.id_empleado,(SELECT CONCAT_WS(' ',e.nombre,e.apellido_paterno,e.apellido_materno)  FROM empleados e WHERE e.id=u.id_empleado) nombreCompledo FROM usuarios u WHERE u.id='$id_usuario' ";
				$resultado = $conexionUsuario->query($sql);
				$row=$resultado->fetch_assoc();
				if($row['nombreCompledo']==""){
					$nombre_usuario=$row['usuario'];
				}else{
					$nombre_usuario=$row['nombreCompledo'];
				}
				if($fecha==""){
					$fecha=$fechaH;
				}
				$logUsuario['fechaR']=$fecha;
				$logUsuario['status']=1;
				$logUsuario['id_usuario']=$id_usuario; 
				$logUsuario['nombre_usuario']=$nombre_usuario; 
				$logUsuario['tabla']=$tabla;
				$logUsuario['id_columna']=$id_columna;
				$logUsuario['operacion']=$operacion;
				$logUsuario['detalle']=$detalle;
				$logUsuario['codigo_plataforma']=$codigo_plataforma;

				$success=true;
				$fields_pdo = "`".implode('`,`', array_keys($logUsuario))."`";
				$values_pdo = "'".implode("','", $logUsuario)."'";
				$inset_log_usuarios= "INSERT INTO log_usuarios ($fields_pdo) VALUES ($values_pdo);";
				$inset_log_usuarios=$conexionUsuario->query($inset_log_usuarios);
				$num=$conexionUsuario->affected_rows;
				if(!$inset_log_usuarios || $num=0){
					$success=false;
					echo "<br>";
					echo "ERROR inset_log_usuarios"; 
					var_dump($conexionUsuario->error); 
				}
			}
			if($success){ 
				$conexionUsuario->commit();
				$conexionUsuario->close();
			}else{ 
				$conexionUsuario->rollback();
				$conexionUsuario->close(); 
			}
			return $success;
		}

		function tipoOperacion($operacion){
			if($operacion=="Update"){
				$tipo="Edición";
			}
			if($operacion=="Delete"){
				$tipo="Eliminación";
			}
			if($operacion=="Reset"){
				$tipo="Reset";
			}
			if($operacion=="Insert"){
				$tipo="Inserción";
			}
			return $tipo;

		}

		function operaciones($operacion=null) {
			$return .="<option  value='' >Seleccione</option> ";
			$return .="<option  value='Insert' >Inserción</option> ";
			$return .="<option  value='Update' >Edición</option> ";
			$return .="<option  value='Delete' >Eliminación</option> ";
			$return .="<option  value='Reset' >Reset</option> ";
			return $return;
		}


		function moduloNombreLog($tabla){
			include '../functions/db.php';  
			include 'functions/db.php'; 
			$sql="SELECT * FROM log_usuarios lu WHERE lu.tabla ='{$tabla}' ";
			$resultado = $conexionUsuario->query($sql);
			$row=$resultado->fetch_assoc();
			if($row['tabla']=="log_importaciones"){
				$row['tabla']="Importación";
			}
			$nombre=str_replace("_"," ",$row['tabla']); 
			$conexionUsuario->close();
			return $nombre;
		}

		function modulosLog() {
			include '../functions/db.php';  
			include 'functions/db.php';  
			$id;
			$select[$tabla]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			echo $sql="SELECT * FROM log_usuarios WHERE tabla NOT IN ('status_facturaciones')  GROUP BY tabla";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$sel=$row['tabla'];
				
				$nombre=str_replace("_"," ",$row['tabla']); 
				 
				$return .="<option ".$select[$sel]." value='".$row['tabla']."' >".ucwords($nombre)."</option> ";
			}
			$conexion->close();
			return $return;
		}

		function logo_usuarioDatos($id=null){
			include 'db.php';
			$sql="SELECT * FROM log_usuarios lu WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$conexion->close();
			return $row;
		}

?>