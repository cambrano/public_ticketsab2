<?php
$dbhost = '74.208.77.67'; 
//$dbhost = '192.168.1.99';
$db="radarDb";
$dbport="3306";
$database_users_12X12[] = array('usuario' => 'cambrano_yucaradar', 'password' => 'ZuXKCvDbDSZNxpxu9euxsDU', );
$database_users_12X12[] = array('usuario' => 'cambrano_yucaradar1', 'password' => 'ZuXKCvDbDSZNxpxu9euxsDU', );
$database_users_12X12[] = array('usuario' => 'cambrano_yucaradar2', 'password' => 'ZuXKCvDbDSZNxpxu9euxsDU', );
$database_users_12X12[] = array('usuario' => 'cambrano_yucaradar3', 'password' => 'ZuXKCvDbDSZNxpxu9euxsDU', );
$database_users_12X12[] = array('usuario' => 'cambrano_yucaradar4', 'password' => 'ZuXKCvDbDSZNxpxu9euxsDU', );
$datauser_random = array_rand($database_users_12X12, 1);
$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario'];
$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password'];














$tipo_uso_plataforma="all";
$id_estado="23";










$tipo_uso_plataforma="municipio";
$id_estado="23";
$cur_abreviacion_estado="QR";
$id_municipio="1824";
$latitud="20.9376025";
$longitud="-87.129838";
$estado_nombre="Q. Roo";
$extranjeros_mode="false";

?>
