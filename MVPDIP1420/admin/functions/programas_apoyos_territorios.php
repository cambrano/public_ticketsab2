<?php
		function programas_apoyos_territorios($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM programas_apoyos_territorios WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function programas_apoyos_territoriosDatos($id=null,$id_programa_apoyo=null){
			include 'db.php';
			$sql=("SELECT * FROM programas_apoyos_territorios WHERE 1 = 1 ");
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

		function programas_apoyos_territoriosIdDatos($id=null,$id_programa_apoyo=null){
			include 'db.php';
			$sql=("SELECT * FROM programas_apoyos_territorios WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_programa_apoyo!=""){
				$sql.=" AND id_programa_apoyo='{$id_programa_apoyo}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[$row['id_tipo_territorio']]=$row;
			} 
			$conexion->close(); 
			return $datos;
		}
?>