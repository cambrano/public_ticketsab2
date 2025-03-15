<?php
		function secciones_ine_ciudadanos_programas_apoyos($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM secciones_ine_ciudadanos_programas_apoyos WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function secciones_ine_ciudadanos_programas_apoyosDatos($id=null){
			include 'db.php';
			$sql="SELECT * ,(SELECT nombre FROM programas_apoyos pa WHERE pa.id = sicpa.id_programa_apoyo) nombre_programa_apoyo
						FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND sicpa.id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row; 
			} 
			$conexion->close();
			return $datos;
		}

		function seccion_ine_ciudadano_programa_apoyoDatos($id=null,$id_seccion_ine_ciudadano=null){
			include 'db.php';
			$sql="SELECT * ,(SELECT nombre FROM programas_apoyos pa WHERE pa.id = sicpa.id_programa_apoyo) nombre_programa_apoyo
						FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE 1 = 1 ";
			if($id!=""){
				$sql.=" AND sicpa.id='{$id}' ";
			}
			$sql.=";";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}


		function seccion_ine_ciudadano_programa_apoyoClaveVerificacion($clave=null,$id=null,$tipo=null){
			include 'db.php';
			$sql=("SELECT * FROM secciones_ine_ciudadanos_programas_apoyos WHERE 1 = 1 ");
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