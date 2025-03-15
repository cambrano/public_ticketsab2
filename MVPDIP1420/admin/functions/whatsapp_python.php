<?php
	function whatsappPython($numero=null,$texto=null){
		include 'db.php'; 
		$sql=("SELECT whatsapp FROM configuracion_paquete WHERE 1 = 1 ");
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$paquetesSistema=$row['whatsapp'];
		if($paquetesSistema=='SI'){
			$sql=("SELECT * FROM whatsapp_python WHERE status='1' ");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$link=str_replace("[_numero_]","".$numero,$row['code']);
			if($texto==""){
				$link=str_replace("?text=[_texto_]","",$link);
			}else{
				$link=str_replace("[_texto_]",$texto,$link);
			}
			$conexion->close();
			return $link;
		}else{
			$conexion->close();
			return '';
		}
	}

	function whatsapp_python($id=null) {
		include 'db.php'; 
		$id;
		$select[$id]='selected="selected"';
		$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
		$sql="SELECT * FROM whatsapp_python WHERE 1 = 1 ";
	
		$result = $conexion->query($sql);  
		while($row=$result->fetch_assoc()){
			$sel=$row['id'];
			$return .="<option ".$select[$sel]." value='".$row['id']."' >".$row['mobile']."</option> ";
		} 
		$conexion->close();
		return $return;
	}

	function whatsapp_pythonDatos($id=null){
		include 'db.php';
		$sql="SELECT * FROM whatsapp_python WHERE 1 = 1 ";
		if($id!=""){
			$sql.=" AND id='{$id}' ";
		}
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$datos=$row; 
		return $datos;
	}
?>