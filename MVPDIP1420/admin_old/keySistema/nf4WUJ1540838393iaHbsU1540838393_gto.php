<?php
	//$dbhost="database-1-instance-1-us-east-2b.cywmkfwca0fn.us-east-2.rds.amazonaws.com";
	$dbhost="mysql1005.mochahost.com";
	$dbport="3306";
	//$dbusuario_user = $dbusuario="cambrano_perMVP";
	//$dbpassword_user = $dbpassword="Z225a3wwZeYd";
	$db="cambrano_gto";
	$database_users_12X12[] = array('usuario' => 'cambrano_gto', 'password' => 'gto1111', );
	$database_users_12X12[] = array('usuario' => 'cambrano_gto1', 'password' => 'gto1111', );
	$database_users_12X12[] = array('usuario' => 'cambrano_gto2', 'password' => 'gto1111', );
	$database_users_12X12[] = array('usuario' => 'cambrano_gto3', 'password' => 'gto1111', );
	$datauser_random = array_rand($database_users_12X12, 1);
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario'];
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password'];


 
	$tipo_uso_plataforma = 'all'; // estatal,municipio, distrito_local distrito_federal all
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
		$id_municipio = 342;
		$latitud="20.952141";
		$longitud="-101.4282369";
		$estado_nombre = "Gto.";
		$extranjeros_mode=false;

		$mun_array_020202asd['326'] = array ('municipio' => 'Abasolo', 'longitud' => '-101.5269589', 'latitud' => '20.4536351', 'id_estado' => '11');
		$mun_array_020202asd['327'] = array ('municipio' => 'Acámbaro', 'longitud' => '-100.718241', 'latitud' => '20.025105', 'id_estado' => '11');
		$mun_array_020202asd['328'] = array ('municipio' => 'San Miguel de Allende', 'longitud' => '-100.745235', 'latitud' => '20.9144491', 'id_estado' => '11');
		$mun_array_020202asd['329'] = array ('municipio' => 'Apaseo el Alto', 'longitud' => '-100.6224706', 'latitud' => '20.4602715', 'id_estado' => '11');
		$mun_array_020202asd['330'] = array ('municipio' => 'Apaseo el Grande', 'longitud' => '-100.6844284', 'latitud' => '20.5446948', 'id_estado' => '11');
		$mun_array_020202asd['331'] = array ('municipio' => 'Atarjea', 'longitud' => '-99.7187387', 'latitud' => '21.2679767', 'id_estado' => '11');
		$mun_array_020202asd['332'] = array ('municipio' => 'Celaya', 'longitud' => '-100.8112885', 'latitud' => '20.5279612', 'id_estado' => '11');
		$mun_array_020202asd['333'] = array ('municipio' => 'Manuel Doblado', 'longitud' => '-101.9512777', 'latitud' => '20.7281285', 'id_estado' => '11');
		$mun_array_020202asd['334'] = array ('municipio' => 'Comonfort', 'longitud' => '-100.7596892', 'latitud' => '20.7197856', 'id_estado' => '11');
		$mun_array_020202asd['335'] = array ('municipio' => 'Coroneo', 'longitud' => '-100.3669879', 'latitud' => '20.1990484', 'id_estado' => '11');
		$mun_array_020202asd['336'] = array ('municipio' => 'Cortazar', 'longitud' => '-100.962716', 'latitud' => '20.4829841', 'id_estado' => '11');
		$mun_array_020202asd['337'] = array ('municipio' => 'Cuerámaro', 'longitud' => '-101.6717765', 'latitud' => '20.6258385', 'id_estado' => '11');
		$mun_array_020202asd['338'] = array ('municipio' => 'Doctor Mora', 'longitud' => '-100.3185428', 'latitud' => '21.1430821', 'id_estado' => '11');
		$mun_array_020202asd['339'] = array ('municipio' => 'Dolores Hidalgo Cuna de la Independencia Nacional', 'longitud' => '-100.8994862', 'latitud' => '21.168635', 'id_estado' => '11');
		$mun_array_020202asd['340'] = array ('municipio' => 'Guanajuato', 'longitud' => '-101.2573586', 'latitud' => '21.0190145', 'id_estado' => '11');
		$mun_array_020202asd['341'] = array ('municipio' => 'Huanímaro', 'longitud' => '-101.4967068', 'latitud' => '20.3670262', 'id_estado' => '11');
		$mun_array_020202asd['342'] = array ('municipio' => 'Irapuato', 'longitud' => '-101.3544964', 'latitud' => '20.6786652', 'id_estado' => '11');
		$mun_array_020202asd['343'] = array ('municipio' => 'Jaral del Progreso', 'longitud' => '-101.0683472', 'latitud' => '20.3735465', 'id_estado' => '11');
		$mun_array_020202asd['344'] = array ('municipio' => 'Jerécuaro', 'longitud' => '-100.5102219', 'latitud' => '20.1496667', 'id_estado' => '11');
		$mun_array_020202asd['345'] = array ('municipio' => 'León', 'longitud' => '-101.6859605', 'latitud' => '21.1250077', 'id_estado' => '11');
		$mun_array_020202asd['346'] = array ('municipio' => 'Moroleón', 'longitud' => '-101.1933423', 'latitud' => '20.1264558', 'id_estado' => '11');
		$mun_array_020202asd['347'] = array ('municipio' => 'Ocampo', 'longitud' => '-101.4782799', 'latitud' => '21.6481453', 'id_estado' => '11');
		$mun_array_020202asd['348'] = array ('municipio' => 'Pénjamo', 'longitud' => '-101.724058', 'latitud' => '20.4313532', 'id_estado' => '11');
		$mun_array_020202asd['349'] = array ('municipio' => 'Pueblo Nuevo', 'longitud' => '-101.3720989', 'latitud' => '20.5244759', 'id_estado' => '11');
		$mun_array_020202asd['350'] = array ('municipio' => 'Purísima del Rincón', 'longitud' => '-101.9119871', 'latitud' => '20.8894632', 'id_estado' => '11');
		$mun_array_020202asd['351'] = array ('municipio' => 'Romita', 'longitud' => '-101.5168556', 'latitud' => '20.8708929', 'id_estado' => '11');
		$mun_array_020202asd['352'] = array ('municipio' => 'Salamanca', 'longitud' => '-101.1957172', 'latitud' => '20.5739314', 'id_estado' => '11');
		$mun_array_020202asd['353'] = array ('municipio' => 'Salvatierra', 'longitud' => '-100.8818401', 'latitud' => '20.2090023', 'id_estado' => '11');
		$mun_array_020202asd['354'] = array ('municipio' => 'San Diego de la Unión', 'longitud' => '-100.8705484', 'latitud' => '21.4683688', 'id_estado' => '11');
		$mun_array_020202asd['355'] = array ('municipio' => 'San Felipe', 'longitud' => '-101.21588', 'latitud' => '21.4788756', 'id_estado' => '11');
		$mun_array_020202asd['356'] = array ('municipio' => 'San Francisco del Rincón', 'longitud' => '-101.8492577', 'latitud' => '21.0170828', 'id_estado' => '11');
		$mun_array_020202asd['357'] = array ('municipio' => 'San José Iturbide', 'longitud' => '-100.386287', 'latitud' => '20.998668', 'id_estado' => '11');
		$mun_array_020202asd['358'] = array ('municipio' => 'San Luis de la Paz', 'longitud' => '-100.5239867', 'latitud' => '21.293404', 'id_estado' => '11');
		$mun_array_020202asd['359'] = array ('municipio' => 'Santa Catarina', 'longitud' => '-100.0696919', 'latitud' => '21.1409653', 'id_estado' => '11');
		$mun_array_020202asd['360'] = array ('municipio' => 'Santa Cruz de Juventino Rosas', 'longitud' => '-100.9910004', 'latitud' => '20.6420743', 'id_estado' => '11');
		$mun_array_020202asd['361'] = array ('municipio' => 'Santiago Maravatío', 'longitud' => '-100.9919246', 'latitud' => '20.1735132', 'id_estado' => '11');
		$mun_array_020202asd['362'] = array ('municipio' => 'Silao de la Victoria', 'longitud' => '-101.4282369', 'latitud' => '20.952141', 'id_estado' => '11');
		$mun_array_020202asd['363'] = array ('municipio' => 'Tarandacuao', 'longitud' => '-100.5166329', 'latitud' => '20.0024426', 'id_estado' => '11');
		$mun_array_020202asd['364'] = array ('municipio' => 'Tarimoro', 'longitud' => '-100.7577044', 'latitud' => '20.2872523', 'id_estado' => '11');
		$mun_array_020202asd['365'] = array ('municipio' => 'Tierra Blanca', 'longitud' => '-100.1606537', 'latitud' => '21.0982059', 'id_estado' => '11');
		$mun_array_020202asd['366'] = array ('municipio' => 'Uriangato', 'longitud' => '-101.178725', 'latitud' => '20.1462015', 'id_estado' => '11');
		$mun_array_020202asd['367'] = array ('municipio' => 'Valle de Santiago', 'longitud' => '-101.1900528', 'latitud' => '20.392169', 'id_estado' => '11');
		$mun_array_020202asd['368'] = array ('municipio' => 'Victoria', 'longitud' => '-100.2160835', 'latitud' => '21.2114596', 'id_estado' => '11');
		$mun_array_020202asd['369'] = array ('municipio' => 'Villagrán', 'longitud' => '-100.9948315', 'latitud' => '20.5134143', 'id_estado' => '11');
		$mun_array_020202asd['370'] = array ('municipio' => 'Xichú', 'longitud' => '-100.0565095', 'latitud' => '21.2992144', 'id_estado' => '11');
		$mun_array_020202asd['371'] = array ('municipio' => 'Yuriria', 'longitud' => '-101.1265968', 'latitud' => '20.2125571', 'id_estado' => '11');

		
		$valor_seguridad_key_nasdajsd = '342';
		$id_estado = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['id_estado'];
		$id_municipio = $valor_seguridad_key_nasdajsd;
		$latitud = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['latitud'];
		$longitud = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['longitud'];
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