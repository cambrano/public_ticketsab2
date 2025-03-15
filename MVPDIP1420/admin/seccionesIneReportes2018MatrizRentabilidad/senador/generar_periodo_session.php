<?php
	@session_start();
	if(!empty($_POST)){
		echo "<pre>";
		var_dump($_POST['genPeriodo']);
		echo "</pre>";
		$fecha_inicial = $_POST['genPeriodo'][0]['fecha_inicial'];
		$fecha_final = $_POST['genPeriodo'][0]['fecha_final'];
		$tipo = $_POST['genPeriodo'][0]['tipo'];
		$ano = $_POST['genPeriodo'][0]['ano'];

		setcookie("fecha_inicial", $fecha_inicial, array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("fecha_final", $fecha_final, array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));


	}
?>