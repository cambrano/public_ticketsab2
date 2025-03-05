<?php
		setcookie("id_usuario","",false,"/",false);
		setcookie("UserManager","",false,"/",false);
		foreach ($_COOKIE as $nombre => $valor) {
			// Establece la cookie con un tiempo de expiración pasado para eliminarla
			setcookie($nombre, '', time() - 3600, '/');
			// También puedes unset($_COOKIE[$nombre]); para eliminarla de la variable $_COOKIE
		}
		session_start();
		session_destroy();
		echo '<SCRIPT LANGUAGE="javascript">
			  location.href = "../../login.php";
			  </SCRIPT>';