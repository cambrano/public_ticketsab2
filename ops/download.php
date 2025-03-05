<?php
// Cargamos la imagen a mostrar
//$_SERVER['DOCUMENT_ROOT'];
//$_GET['id_img']="Y6GWP1550733552tID10446611262976.png";
@session_start();

$file=$_GET['file'];
$pageService=$_GET['cot2'];
if($_SESSION['pageService']==$pageService){
	$linkMain = 'https://'.$_SERVER['HTTP_HOST'];
	include __DIR__."/../admin/keySistema/dir.php";
	if($file!="" ){
		$archivo= $_SERVER['DOCUMENT_ROOT']."/{$dir_base}/{$dir_produccion}/admin/ftpFiles/filesOthers/{$file}";
		if (file_exists($archivo)){
			include __DIR__."/../admin/functions/clientes_archivos.php";
			$clientes_archivosName=clientes_archivosName($file);
			$filePath=$clientes_archivosName['nombre'].'.'.$clientes_archivosName['extension'];;
			$type=$clientes_archivosName['type'];

			// Define headers
			header("Pragma: public");
			header("Expires: 0"); 
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			//header("Content-Disposition: attachment;filename=data.png ");
			//header("Content-Transfer-Encoding: binary ");

			//header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=$filePath");
			header("Content-Type: $type");
			header("Content-Transfer-Encoding: binary");
			// Read the file
			readfile($archivo);
			echo '<script type="text/javascript">window.close();</script>';
			exit;


		} else {
			echo 'Archivo No Existe';
		}
	}else{
		echo 'Archivo No Existe';
	}
}else{
	echo "Error de seguridad";
	exit;
}


//    http://localhost/vw/apapachoviajes/admin/ftpFiles/files/icono.gif