<?php
	if(!empty($_POST)){
		include __DIR__.'/../functions/security.php'; 
		@session_start();
		$clave_elector = trim($_POST['search']);
		$clave_elector = mysqli_real_escape_string($conexion,$clave_elector);
 
		if($clave_elector!=""){
			$data = array();
			$sql="
				SELECT 
					e.id,
					e.nombre_completo,
					(SELECT s.numero FROM secciones_ine s WHERE s.id = e.id_seccion_ine) seccion,
					(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = e.id_tipo_ciudadano ) tipo_ciudadano,
					e.clave_elector
				FROM secciones_ine_ciudadanos e 
				WHERE e.clave_elector LIKE '%{$clave_elector}%'
				ORDER BY e.nombre_completo DESC LIMIT 0,50;
				";
			$result = $conexion->query($sql); 
			$num=0;
			$response[] = array("id"=>"", "text"=>"SIN RELACION");
			while($row=$result->fetch_assoc()){
				//$html .= '<div><a class="suggest-element" data="'.$row['nombre_completo'].'" id="'.$row['id'].'">'.$row['nombre_completo'].'</a></div>';
				//$data[$num]['nombre_completo'] = $row["nombre_completo"];
				//$data[$num]['id'] = $row["id"];
				$response[] = array("id"=>$row['id'], "text"=>$row['nombre_completo']." - Sección:".$row['seccion']." - Tipo:".$row['tipo_ciudadano']." - Clave Elector:".$row['clave_elector']);
			}
			echo json_encode($response);
		}else{
			$data = array();
			$sql="
				SELECT 
					e.id,
					e.nombre_completo,
					(SELECT s.numero FROM secciones_ine s WHERE s.id = e.id_seccion_ine) seccion,
					(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = e.id_tipo_ciudadano ) tipo_ciudadano,
					e.clave_elector
				FROM secciones_ine_ciudadanos e 
				ORDER BY e.nombre_completo DESC LIMIT 0,5;
				";
			$result = $conexion->query($sql); 
			$num=0;
			$response[] = array("id"=>"", "text"=>"SIN RELACION");
			while($row=$result->fetch_assoc()){
				//$html .= '<div><a class="suggest-element" data="'.$row['nombre_completo'].'" id="'.$row['id'].'">'.$row['nombre_completo'].'</a></div>';
				//$data[$num]['nombre_completo'] = $row["nombre_completo"];
				//$data[$num]['id'] = $row["id"];
				#$response[] = array("id"=>$row['id'], "text"=>$row['clave_elector']." - ".$row['nombre_completo']." - Sección:".$row['seccion']);
				$response[] = array("id"=>$row['id'], "text"=>$row['nombre_completo']." - Sección:".$row['seccion']." - Tipo:".$row['tipo_ciudadano']." - Clave Elector:".$row['clave_elector']);
			}
			echo json_encode($response);
		}
	}else{
		
	}
?>