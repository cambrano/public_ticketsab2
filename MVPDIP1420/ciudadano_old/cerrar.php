<?php
		setcookie("id_usuario","",false,"/",false);
		setcookie("UserManager","",false,"/",false);
		session_start();
		session_destroy();
		echo '<SCRIPT LANGUAGE="javascript">
			  location.href = "../../login.php";
			  </SCRIPT>';