<?php
// Cargamos la imagen a mostrar
//$_SERVER['DOCUMENT_ROOT'];
//$_GET['id_img']="Y6GWP1550733552tID10446611262976.png";
$linkMain = 'https://'.$_SERVER['HTTP_HOST'];
//include __DIR__."/../MVPDIP1420/admin/keySistema/dir.php";

$file=$_GET['id_img'];
if($file!=""){
	include 'db.php';
	$sql='SELECT nombre,slogan,url_base FROM configuracion WHERE 1 = 1 LIMIT 1';
	$resultado = $conexion->query($sql);
	$configuracionDatos=$resultado->fetch_assoc();
	$remoteImage = $configuracionDatos['url_base'].'ops/logo_partido.php?id_img='.$file;
	$imginfo = getimagesize($remoteImage);
	header("Content-type: {$imginfo['mime']}");
	header("Content-Disposition: inline; filename={$file}");
	readfile($remoteImage);

}else{
	include 'db.php';
	$sql='SELECT nombre,slogan,url_base FROM configuracion WHERE 1 = 1 LIMIT 1';
	$resultado = $conexion->query($sql);
	$configuracionDatos=$resultado->fetch_assoc();
	$remoteImage=$configuracionDatos['url_base'].'ops/imagen.php?id_img=file_roto.gif';
	$imginfo = getimagesize($remoteImage);
	header("Content-type: {$imginfo['mime']}");
	header("Content-Disposition: inline; filename={$file}");
	readfile($remoteImage);
	die;
}


//    http://localhost/vw/apapachoviajes/admin/ftpFiles/files/icono.gif