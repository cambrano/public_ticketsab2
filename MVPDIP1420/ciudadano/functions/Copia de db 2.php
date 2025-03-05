<?php
		header('Cache-Control: max-age=84600');
		ini_set('memory_limit', '5048M');
		ini_set('max_execution_time', 1200);

		include '../admin/keySistema/dir.php';
		include '../../admin/keySistema/dir.php';
		include 'admin/keySistema/dir.php';
		include dirname(__DIR__).'/keySistema/key.php';
		include dirname(__DIR__).'/keySistema/dir.php';
		include $_SERVER['DOCUMENT_ROOT'].'/'.$dir_base.'/'.$dir_produccion.'/admin/keySistema/key.php';
		//$codigo_plataforma='H7wyje1541437845eWBT1F1541437845';
		include dirname(__DIR__).'/keySistema/nf4WUJ1540838393iaHbsU1540838393.php';
		include $_SERVER['DOCUMENT_ROOT'].'/'.$dir_base.'/'.$dir_produccion.'/admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php';
		
		$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
		mysqli_set_charset($conexion, 'utf8mb4'); 
		if ($conexion->connect_error){
			echo 'Ha ocurrido un error: ' . $conexion->connect_error . 'Número del error: ' . $conexion->connect_errno;
		}

		$conexionUsuario = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
		mysqli_set_charset($conexionUsuario, 'utf8mb4'); 
		if ($conexionUsuario->connect_error){
			echo 'Ha ocurrido un error: ' . $conexionUsuario->connect_error . 'Número del error: ' . $conexionUsuario->connect_errno;
		}
		$conexionReset = new mysqli($dbhost, $dbusuario_user, $dbpassword_user, $db, $dbport);
		mysqli_set_charset($conexionReset, 'utf8mb4'); 
		if ($conexionReset->connect_error){
			echo 'Ha ocurrido un error: ' . $conexionReset->connect_error . 'Número del error: ' . $conexionReset->connect_errno;
		}
?>