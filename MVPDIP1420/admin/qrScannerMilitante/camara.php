<?php
	if(!empty($_POST)){
		@session_start();
		$camara = $_POST['camara'][0]['id_camara'];
		//setcookie("Paguinasub",encrypt_ab_checkSin($urlink), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("camdv", $camara, array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		die;
	}
?>