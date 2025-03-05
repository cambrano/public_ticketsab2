<?php
// Cargamos la imagen a mostrar
//$_SERVER['DOCUMENT_ROOT'];
//$_GET['id_img']="Y6GWP1550733552tID10446611262976.png";
$linkMain = 'https://'.$_SERVER['HTTP_HOST'];
//include __DIR__."/../MVPDIP1420/admin/keySistema/dir.php";
$file=$_GET['id_img'];
if($file!=""){

	$archivo = "verificacion_email.png";
	if (file_exists($archivo)){
 
		$mime=mime_content_type($archivo);
		header("Content-type: {$mime}");
		header("Content-length: ".filesize($archivo));
		header("Content-Disposition: inline; filename=$file");
		header('Content-Transfer-Encoding: binary');
		readfile($archivo);
 
		/*
		$mycontent = file_get_contents($archivo);
		$file_info = new finfo(FILEINFO_MIME_TYPE);
		$mime_type = $file_info->buffer(file_get_contents($archivo));
		header("Content-type: {$mime_type}");
		header("Content-length: ".filesize($archivo));
		header("Content-Disposition: attachment; name={$file}; filename={$file} ");
		header('Content-Transfer-Encoding: binary');
		echo $mycontent;
		*/
		$codigo_ciudadano = $_GET['cot'];
		$identificador = $_GET['cot1'];
		$close = $_GET['cot2'];
		if(empty($codigo_ciudadano) || empty($identificador) ){
			die;
		}
		if($close=='xAse2sq'){
			die;
		}
		include '../ops/db_Es1Ss_3S.php';


		


		//update
		/*
		$update_correo_mailing ='
		UPDATE secciones_ine_ciudadanos_campanas_mailing_programadas 
			SET status = "3" 
		WHERE ( id <>0 AND codigo_seccion_ine_ciudadano ="'.$codigo_ciudadano.'" AND identificador ="'.$identificador.'" );';
		*/
		$logCombinacion['status'] = 3;
		$logCombinacion['fecha_leido'] = $fechaSF;
		$logCombinacion['hora_leido'] = $fechaSH;
		$logCombinacion['fecha_hora_leido'] = $fechaH;
		$logCombinacion['tipo_leido'] = 'imagen';
		foreach($logCombinacion as $keyPrincipal => $atributos) {
			if($keyPrincipal !='id'){
				$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
			}else{
				$id=$atributos;
			}
		}
		$update_correo_mailing = "UPDATE secciones_ine_ciudadanos_campanas_mailing_programadas SET ". join(",",$valueSets) . " WHERE ( id <>0 AND codigo_seccion_ine_ciudadano = '{$codigo_ciudadano}'  AND identificador = '{$identificador}'  );";
		$update_correo_mailing=$conexion->query($update_correo_mailing);
		if(!$update_correo_mailing || $num=0){
			$success=false;
			echo "ERROR update_correo_mailing"; 
			var_dump($conexion->error);
		}

	 


	} else {
		$archivo= $_SERVER['DOCUMENT_ROOT']."/{$dir_base}/admin/ftpFiles/file_roto.gif";
		$mime=mime_content_type($archivo);
		header("Content-type: {$mime}");
		header("Content-length: ".filesize($archivo));
		header("Content-Disposition: inline; filename=$file");
		readfile($archivo);
	}
}else{
	$archivo= $_SERVER['DOCUMENT_ROOT']."/{$dir_base}/admin/ftpFiles/file_roto.gif";
	$mime=mime_content_type($archivo);
	header("Content-type: {$mime}");
	header("Content-length: ".filesize($archivo));
	header("Content-Disposition: inline; filename=$file");
	readfile($archivo);
}


//    http://localhost/vw/apapachoviajes/admin/ftpFiles/files/icono.gif