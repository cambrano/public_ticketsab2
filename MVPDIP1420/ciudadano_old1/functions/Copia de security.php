<?php
		//header('Cache-Control: max-age=84600');
		@session_start();
		include 'db.php';  
		include "functions/db.php"; 
		//$host= $_SERVER["HTTP_HOST"];
		//$url= $_SERVER["REQUEST_URI"]; 
		//echo "http://" . $host . $url;
		//$urlFunction= $host."/checador/admin/functions/";
		//include "timemex.php"; 
		//include $urlFunction."db.php";
		//include "genid.php"; 
		error_reporting(0);
		// Notificar solamente errores de ejecución
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		// Notificar E_NOTICE también puede ser bueno (para informar de variables
		// no inicializadas o capturar errores en nombres de variables ...)
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
		// Notificar todos los errores excepto E_NOTICE
		// Este es el valor predeterminado establecido en php.ini
		error_reporting(E_ALL ^ E_NOTICE);
		// Notificar todos los errores de PHP (ver el registro de cambios)
		error_reporting(E_ALL);
		// Notificar todos los errores de PHP
		error_reporting(-1);
		// Lo mismo que error_reporting(E_ALL);
		ini_set('error_reporting', E_ALL);
		error_reporting(E_ALL);
		error_reporting(E_ERROR | E_PARSE);
		ini_set("display_errors", false);

		$_COOKIE["UserManager"];
		if ($_COOKIE["UserManager"] == "UserCiudadano"){
			$_COOKIE["id_usuario"];
			$SQLUser= " SELECT *  FROM usuarios  WHERE id='{$_COOKIE["id_usuario"]}' AND id_perfil_usuario = 4  ";
			$ResultSQL = $conexion->query($SQLUser);
			$RowUser = $ResultSQL->fetch_assoc();
			$status=$RowUser['status'];
			//checamos si tiene id vigente o status vigente
			if(!empty($RowUser['id']) || $status ==0 ){
				echo '<SCRIPT LANGUAGE="javascript">
				location.href = "../ciudadano/"; 
				</SCRIPT>';
			}
			die;
		}
		if ($_COOKIE["UserManager"] == "UserAdmin"){
			$_COOKIE["id_usuario"];
			$SQLUser= " SELECT *  FROM usuarios  WHERE id='{$_COOKIE["id_usuario"]}' AND id_perfil_usuario IN (1,2,3)  ";
			$ResultSQL = $conexion->query($SQLUser);
			$RowUser = $ResultSQL->fetch_assoc();
			$status=$RowUser['status'];
			//checamos si tiene id vigente o status vigente
			if(empty($RowUser['id']) || $status ==0 ){
				echo '<SCRIPT LANGUAGE="javascript">
				location.href = "cerrar.php"; 
				</SCRIPT>';
			}
		}
		
		if($_COOKIE["UserManager"] == NULL ){
			echo '<SCRIPT LANGUAGE="javascript">
			location.href = "../../../login.php"; 
			</SCRIPT>';
		}
?>