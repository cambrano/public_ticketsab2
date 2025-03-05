<?php
 
		function seccion_ine_ciudadano_campana_sms_programadaDatos($id=null){
			include 'db.php';
			$sql=("SELECT 
					siccmp.fechaR,
					siccmp.id,
					(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) nombre_completo,
					(SELECT sic.celular FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) celular,
					(SELECT cm.nombre FROM campanas_sms cm WHERE cm.id= siccmp.id_campana_sms) nombre,
					(SELECT cmp.asunto FROM campanas_sms_cuerpos cmp WHERE cmp.id_campana_sms= siccmp.id_campana_sms) asunto,
					(SELECT cmp.cuerpo FROM campanas_sms_cuerpos cmp WHERE cmp.id_campana_sms= siccmp.id_campana_sms) cuerpo,
					siccmp.fecha_hora_envio,
					(SELECT s.numero FROM secciones_ine s WHERE s.id =siccmp.id_seccion_ine) seccion,
					(SELECT dl.numero FROM distritos_locales dl WHERE dl.id =siccmp.id_distrito_local) distrito_local,
					(SELECT df.numero FROM distritos_federales df WHERE df.id =siccmp.id_distrito_federal) distrito_federal,
					(SELECT m.municipio FROM municipios m WHERE m.id =siccmp.id_municipio) municipio,
					siccmp.status
				FROM secciones_ine_ciudadanos_campanas_sms_programadas siccmp WHERE 1=1 ");
			if($id!=""){
				$sql.=" AND siccmp.id='{$id}' ";
			} 
			$sql;
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$datos=$row; 
			return $datos;
		}


		function secciones_ine_ciudadanos_campanas_sms_programadasDatosArray($registros=null,$orderby=null,$limit=null) {
		include 'db.php'; 
		$sql="
			SELECT
				*,
				siccmp.fechaR,
				siccmp.id,
				CASE
					WHEN siccmp.tipo = 1 THEN 'bienvenida'
					WHEN siccmp.tipo = 3 THEN 'encuesta'
					ELSE 'programada'
				END tipo,

				(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) nombre_completo,
				(SELECT sic.celular FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) celular,
				(SELECT cm.nombre FROM campanas_sms cm WHERE cm.id= siccmp.id_campana_sms) nombre,
				siccmp.fecha_hora_envio,
				(SELECT s.numero FROM secciones_ine s WHERE s.id =siccmp.id_seccion_ine) seccion,
				(SELECT dl.numero FROM distritos_locales dl WHERE dl.id =siccmp.id_distrito_local) distrito_local,
				(SELECT df.numero FROM distritos_federales df WHERE df.id =siccmp.id_distrito_federal) distrito_federal,
				(SELECT m.municipio FROM municipios m WHERE m.id =siccmp.id_municipio) municipio,
				siccmp.status
			FROM secciones_ine_ciudadanos_campanas_sms_programadas siccmp WHERE 1=1 
		";
		foreach ($registros as $key => $value) {
			//echo $key;
			//echo "-";
			//echo $value;
			//echo "<br>";
			if($value !=""){
				if($key!="siccmp.fecha_1" && $key!="siccmp.fecha_2"){
					if($key=="id_seccion_ine"){
						$sql.= " AND  siccmp.id_seccion_ine IN ({$value}) ";
					}else{
						$sql.= " AND  siccmp.{$key} = '{$value}' ";
					}
				}
				if($key=="siccmp.fecha_1"){
					$fecha_1 = $value;
				}
				if($key=="siccmp.fecha_2"){
					$fecha_2 = $value;
				}
			}
		}
		if( $fecha_1 != '' && $fecha_2 == ''){ 
			$sql.=" AND siccmp.fechaR <= '{$fecha_1} 23:59:59' ";
		}
		if( $fecha_1 == '' && $fecha_2 != ''){ 
			$sql.=" AND siccmp.fechaR >= '{$fecha_2} 00:00:00' ";
		}
		if( $fecha_1 != '' && $fecha_2 != ''){ 
			$sql.=" AND siccmp.fechaR BETWEEN '{$fecha_1} 00:00:00' AND '{$fecha_2} 23:59:59' ";
		}
		if($orderby!=""){
			$sql.=" {$orderby} ";
		}
		if($limit!=""){
			$sql.=" {$limit} ";
		}
		//echo "<pre>";
		//echo $sql;
		//echo "</pre>";
		//$resultado = $conexion->query($sql);
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			$datos[$num]=$row;
			//$datos[$num]['nombre_ciudadano']=$row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno'];
			$num=$num+1;
		}
		if($num==0){
			$datos=null;
		}
		$conexion->close();
		return $datos;
	} 
?>