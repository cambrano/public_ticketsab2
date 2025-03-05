<?php
		function campanas_whatsapp_cuerpos($id=null) {
			include 'db.php'; 
			$id;
			$select[$id]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM campanas_whatsapp_cuerpos WHERE 1 = 1 ";
		
			$result = $conexion->query($sql);  
			 
			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['nombre']."</option> ";
			} 
			$conexion->close();
			return $return;
		}

		function campana_whatsapp_cuerpoDatos($id=null,$id_campana_whatsapp=null){
			include 'db.php';
			$sql=("SELECT * FROM campanas_whatsapp_cuerpos WHERE 1 = 1 ");
			if($id!=""){
				$sql.=" AND id='{$id}' ";
			}
			if($id_campana_whatsapp!=""){
				$sql.=" AND id_campana_whatsapp='{$id_campana_whatsapp}' ";
			}
			$sql;
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}
?>