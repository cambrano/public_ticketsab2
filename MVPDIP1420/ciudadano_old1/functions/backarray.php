<?php
		@session_start();
		include '../functions/tool_xhpzab.php';
		$urlink=$_POST['urlink'];
		//setcookie("Paguinasub",encrypt_ab_checkSin($urlink), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("Paguinasub", encrypt_ab_checkSin($urlink), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
?>