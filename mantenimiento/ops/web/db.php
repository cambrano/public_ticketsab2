<?php

	//include __DIR__."/../MVPDIP1420/admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php";
	$dbhost="mysql1005.mochahost.com";
	$dbport="3306";
	//$dbusuario_user = $dbusuario="cambrano_perMVP";
	//$dbpassword_user = $dbpassword="Z225a3wwZeYd";

	$db="cambrano_perMVP";
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP1', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP2', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP3', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP4', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP5', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP6', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP7', 'password' => 'Z225a3wwZeYd', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perMVP8', 'password' => 'Z225a3wwZeYd', );
	$datauser_random = array_rand($database_users_12X12, 1);
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario'];
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password'];

	/* 
	$db="cambrano_perTab";
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
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario'];
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password'];
	

	 
	$dbhost="localhost";
	//$dbhost='yucatan.cywmkfwca0fn.us-east-2.rds.amazonaws.com';
	$db="cambrano_perMVP";
	$dbusuario = $dbusuario_user="root";
	$dbpassword_user = $dbpassword="root";
	*/
	 
	
	
	$id_estado = 31;
	$latitud="20.804850619727194";
	$longitud="-88.9397908318924";
	$estado_nombre = "Yuc.";
/*
	$id_estado = 27;
	$latitud="17.936412456387718";
	$longitud="-92.8633196777344";
	$estado_nombre = "Tab.";*/

	$extranjeros_mode=false;
	$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
	mysqli_set_charset($conexion, "utf8mb4"); 
	if ($conexion->connect_error){
		echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
	}

	date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
	setlocale(LC_ALL,"es_ES");
	$fechaH=date('Y-m-d H:i:s');
	$fechaSH=date('H:i:s');
	$fechaSF=date('Y-m-d');
	$nombreSemana= array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
	$numeroSemanaActual=date('w');
	$diaSemanaActual=$nombreSemana[$numeroSemanaActual];
	$numeroMesActual=date('n');
	$nombreMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	$mesNombreAcutal=$nombreMes[$numeroMesActual];

	function fechaNormalSimpleWDDMMAA_ES($fecha){
		$nombreSemana= array('Dom','Lun','Mar','Mie','Jue','Vie','Sab');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Ene','Feb','Mar','Abr','Ma','Jun','Jul','Ago','Sep','Oct','Nov','Dic'); 

		$return=$nombreSemana[$diaSemana]." ".$dia." ".$nombreMes[$mes]." ".$ano;
		return $return;
	}

	function convertidorAMPM($hora=null,$segundos=null,$tipo_letra=null){
		if($tipo_letra=="mayuscula"){
			if($segundos==""){
				$return=date("g:i A",strtotime($hora));
			}else{
				$return=date("g:i:s A",strtotime($hora));
			}
		}else{
			if($segundos==""){
				$return=date("g:i a",strtotime($hora));
			}else{
				$return=date("g:i:s a",strtotime($hora));
			}
		}
		return $return; 
	}