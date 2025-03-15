<?php
if(!empty($_POST)){
    @session_start();
		//setcookie("Paguinasub",encrypt_ab_checkSin($urlink), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_2", 1, array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
}

?>