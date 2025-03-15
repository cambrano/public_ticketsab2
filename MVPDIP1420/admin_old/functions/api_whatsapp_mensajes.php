<?php
	function api_whatsapp_mensajesWhatsappDatos($whatsapp=null,$orderby=null){
		include 'db.php';
		$sql=("SELECT * FROM api_whatsapp_mensajes WHERE 1 = 1 ");
		if($whatsapp!=""){
			$sql.=" AND whatsapp='{$whatsapp}' ";
		}
		if($orderby !='' ){
			$sql.=" ORDER BY ".$orderby;
		}
		$resultado = $conexion->query($sql);
		while($row=$resultado->fetch_assoc()){
			$datos[]=$row;
		} 
		$conexion->close(); 
		return $datos;
	}

	function api_whatsapp_mensajesIdSeccionCiudadanoDatos($id_seccion_ine_ciudadano=null,$orderby=null){
		include 'db.php';
		$sql=("SELECT * FROM api_whatsapp_mensajes WHERE 1 = 1 AND id_seccion_ine_ciudadano='{$id_seccion_ine_ciudadano}' ");
		if($orderby !='' ){
			$sql.=" ORDER BY ".$orderby;
		}
		$resultado = $conexion->query($sql);
		while($row=$resultado->fetch_assoc()){
			$datos[]=$row;
		} 
		$conexion->close(); 
		return $datos;
	}

	
?>