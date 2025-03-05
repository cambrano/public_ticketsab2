<?php
	$dbhost="database-1-instance-1-us-east-2b.cywmkfwca0fn.us-east-2.rds.amazonaws.com";
	$dbport="3306";
	//$dbusuario_user = $dbusuario="cambrano_perMVP";
	//$dbpassword_user = $dbpassword="Z225a3wwZeYd";
	$db="irapuato";
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab1', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab2', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab3', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab4', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab5', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab6', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab7', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab8', 'password' => 'JxKjHCdO6vRX', );
	$datauser_random = array_rand($database_users_12X12, 1);
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario']="admin";
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password']="m3rm3l4d4;";


 
	$tipo_uso_plataforma = 'municipio'; // estatal,municipio, distrito_local distrito_federal all
	if($tipo_uso_plataforma == 'municipio'){
		/* Irapuato */
		/*
		$id_estado = 11;
		$id_municipio = 342;
		$latitud="20.6786652";
		$longitud="-101.3544964";
		$estado_nombre = "Gto.";
		$extranjeros_mode=false;
		*/

		/* Silao */

		$id_estado = 11;
		$id_municipio = 362;
		$latitud="20.952141";
		$longitud="-101.4282369";
		$estado_nombre = "Gto.";
		$extranjeros_mode=false;

	}elseif($tipo_uso_plataforma=='distrito_local'){

		$id_distrito_local = 13;
		$latitud="20.897797315902803";
		$longitud="-101.50962451743504";
		$estado_nombre = "Gto.";
		$extranjeros_mode=false;

	}elseif($tipo_uso_plataforma=='distrito_federal'){

		$id_distrito_federal = 9;
		$latitud="20.854080657075848";
		$longitud="-101.39461559908166";
		$estado_nombre = "Gto.";
		$extranjeros_mode=false;

	}else{

		$id_estado = 11;
		$latitud="21.0190145";
		$longitud="-101.2573586";
		$estado_nombre = "Gto.";
		$extranjeros_mode=false;

	}


	///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
	
	$dbhost = 'localhost'; 
	$db="cambrano_gto";
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario']="root";
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password']="root";
?>