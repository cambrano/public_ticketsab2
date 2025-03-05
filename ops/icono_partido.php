<?php
// Cargamos la imagen a mostrar
//$_SERVER['DOCUMENT_ROOT'];
//$_GET['id_img']="Y6GWP1550733552tID10446611262976.png";
$linkMain = 'https://'.$_SERVER['HTTP_HOST'];
include __DIR__."/../MVPDIP1420/admin/keySistema/dir.php";
$file=$_GET['id_img'];
if($file!=""){

	$archivo= $_SERVER['DOCUMENT_ROOT']."/{$dir_base}/admin/images/iconos_partidos/{$file}";
	if (file_exists($archivo)){
		//$mime=mime_content_type($archivo);
		/*
		header("Content-type: {$mime}");
		header("Content-length: ".filesize($archivo));
		header("Content-Disposition: inline; filename=$file");
		readfile($archivo);
		*/

		$mycontent = file_get_contents($archivo);
		$file_info = new finfo(FILEINFO_MIME_TYPE);
		$mime_type = $file_info->buffer(file_get_contents($archivo));
		header("Content-type: {$mime_type}");
		header("Content-length: ".filesize($archivo));
		header("Content-Disposition: attachment; name={$file}; filename={$file} ");
		header('Content-Transfer-Encoding: binary');
		echo $mycontent;



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