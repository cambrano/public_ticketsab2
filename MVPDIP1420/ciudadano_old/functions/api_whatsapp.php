<?php
	function apiWhatsApp($numero=null,$texto=null){
		include 'db.php'; 
		$sql=("SELECT whatsapp FROM configuracion_paquete WHERE 1 = 1 ");
		$resultado = $conexion->query($sql);
		$row=$resultado->fetch_assoc();
		$paquetesSistema=$row['whatsapp'];
		if($paquetesSistema=='SI'){
			$sql=("SELECT * FROM api_whatsapp WHERE status='1' ");
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
?>