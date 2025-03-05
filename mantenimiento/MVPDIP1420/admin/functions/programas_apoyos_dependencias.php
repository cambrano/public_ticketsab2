<?php
		function programas_apoyos_dependencias($id=null,$id_programa_apoyo=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT 
					pad.id,
					(SELECT d.nombre FROM dependencias d WHERE d.id = pad.id_dependencia) dependencia
					FROM programas_apoyos_dependencias pad WHERE 1 = 1 AND pad.id_programa_apoyo = '{$id_programa_apoyo}' ";
			$sql;
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['dependencia']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function programas_apoyos_dependenciasDatos($id=null,$id_programa_apoyo=null){
			include 'db.php';
			$sql=("SELECT * FROM programas_apoyos_dependencias WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_programa_apoyo!=""){
				$sql.=" AND id_programa_apoyo='{$id_programa_apoyo}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row;
			} 
			$conexion->close(); 
			return $datos;
		}

		function programa_apoyo_dependenciaIdDatos($id=null,$id_programa_apoyo=null){
			include 'db.php';
			$sql=("SELECT * FROM programas_apoyos_dependencias WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_programa_apoyo!=""){
				$sql.=" AND id_programa_apoyo='{$id_programa_apoyo}' ";
			}
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}

		function programas_apoyos_dependenciasIdDatos($id=null,$id_programa_apoyo=null){
			include 'db.php';
			$sql=("SELECT * FROM programas_apoyos_dependencias WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_programa_apoyo!=""){
				$sql.=" AND id_programa_apoyo='{$id_programa_apoyo}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[$row['id_dependencia']]=$row;
			} 
			$conexion->close(); 
			return $datos;
		}
?>