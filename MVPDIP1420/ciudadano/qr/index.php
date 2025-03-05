<?php
require 'librerias/phpqrcode/qrlib.php';

$size = 4;
$marge = 1;
$data = $enlace_actual;
$level = QR_ECLEVEL_Q;

ob_start();
QRcode::png($data, null, $level, $size, $marge);

$image_data = ob_get_clean();
echo "<br><br>";
// Luego, puedes mostrar el código QR en una etiqueta <img>
echo '<img src="data:image/png;base64,' . base64_encode($image_data) . '" alt="QR Code" width="50%">';
?>