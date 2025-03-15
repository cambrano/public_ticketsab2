<?php
require 'librerias/phpqrcode/qrlib.php';
$_SERVER['HTTP_REFERER'];
$enlace_actual = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$enlace_actual = $_SERVER['HTTP_REFERER'];
$enlace_actual = str_replace("qr/index.php", "", $enlace_actual);
//$file = 'qr/jr-qrcode.png';
$file = "ftpFiles/files/jr-qrcode.png";
$size = 4;
$marge = 1;
$data = $enlace_actual;
$level = QR_ECLEVEL_Q;
//$logo="configuracion/img/logo.png";
QRcode::png($data, $file, $level, $size,$marge);
?>
<br><br>
<img src="../../ops/imagen.php?id_img=jr-qrcode.png" width="100px">
 