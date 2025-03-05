<?php
	ini_set("display_errors", false); 
	$_COOKIE["UserManager"];
	if ($_COOKIE["UserManager"] == "UserAdmin") {
		//setcookie('Paguinasub', '', false, '/MVPDIP1420/ciudadano/');
		//echo "Entra";
		//die;
		echo '<SCRIPT LANGUAGE="javascript">
				location.href = "MVPDIP1420/admin/index.php"; 
			</SCRIPT>';
	}
	if ($_COOKIE["UserManager"] == "UserCiudadano") {
		//setcookie('Paguinasub', '', time() + (86400 * 30), '/MVPDIP1420/ciudadano/');
		//echo "Entra";
		//die;
		echo '<SCRIPT LANGUAGE="javascript">
				location.href = "MVPDIP1420/ciudadano/index.php"; 
			</SCRIPT>';
	}
	else{
		//echo "SALE";
		//die;
		echo '<SCRIPT LANGUAGE="javascript">
				location.href = "login.php"; 
			</SCRIPT>';
	}
	die;