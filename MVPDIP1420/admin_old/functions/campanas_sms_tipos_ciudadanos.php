<?php
		function campanas_sms_tipos_ciudadanos($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM campanas_sms_tipos_ciudadanos WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function campanas_sms_tipos_ciudadanosDatos($id=null,$id_campana_sms=null){
			include 'db.php';
			$sql=("SELECT * FROM campanas_sms_tipos_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_campana_sms!=""){
				$sql.=" AND id_campana_sms='{$id_campana_sms}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[]=$row;
			} 
			$conexion->close(); 
			return $datos;
		}

		function campanas_sms_tipos_ciudadanosIdDatos($id=null,$id_campana_sms=null){
			include 'db.php';
			$sql=("SELECT * FROM campanas_sms_tipos_ciudadanos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_campana_sms!=""){
				$sql.=" AND id_campana_sms='{$id_campana_sms}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			while($row=$resultado->fetch_assoc()){
				$datos[$row['id_tipo_ciudadano']]=$row;
			} 
			$conexion->close(); 
			return $datos;
		}
?>