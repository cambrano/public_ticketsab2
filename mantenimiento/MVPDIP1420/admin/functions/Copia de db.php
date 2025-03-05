<?php
		/*
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
		*/
		/*
		include 'admin/keySistema/key.php';
		include '../admin/keySistema/key.php';
		include '../keySistema/key.php';
		include '../../keySistema/key.php';
		*/

		//include dirname(__DIR__)."/keySistema/key.php";
		include '../admin/keySistema/dir.php';
		include '../../admin/keySistema/dir.php';
		include 'admin/keySistema/dir.php';

		include dirname(__DIR__)."/keySistema/key.php";
		include dirname(__DIR__)."/keySistema/dir.php";

		include $_SERVER['DOCUMENT_ROOT'].'/'.$dir_base.'/'.$dir_produccion."/admin/keySistema/key.php";

		//$codigo_plataforma="H7wyje1541437845eWBT1F1541437845";
		$tipoServidor=2;
		if($tipoServidor==2){
			/*
			include 'admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php';
			include '../admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php';
			include '../keySistema/nf4WUJ1540838393iaHbsU1540838393.php';
			include '../../keySistema/nf4WUJ1540838393iaHbsU1540838393.php';
			*/
			include dirname(__DIR__)."/keySistema/nf4WUJ1540838393iaHbsU1540838393.php";
			include $_SERVER['DOCUMENT_ROOT'].'/'.$dir_base.'/'.$dir_produccion.'/admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php';

		}
		$conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
		mysqli_set_charset($conexion, "utf8mb4"); 
		if ($conexion->connect_error){
			echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
		}

		$conexionUsuario = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
		mysqli_set_charset($conexionUsuario, "utf8mb4"); 
		if ($conexionUsuario->connect_error){
			echo "Ha ocurrido un error: " . $conexionUsuario->connect_error . "Número del error: " . $conexionUsuario->connect_errno;
		}
		$conexionReset = new mysqli($dbhost, $dbusuario_user, $dbpassword_user, $db, $dbport);
		mysqli_set_charset($conexionReset, "utf8mb4"); 
		if ($conexionReset->connect_error){
			echo "Ha ocurrido un error: " . $conexionReset->connect_error . "Número del error: " . $conexionReset->connect_errno;
		}
		$sqlKIjU21534330577Y0iPs61534330577=("SELECT count(*) contador FROM usuarios WHERE tipo='1'  ");
		$resultadoKIjU21534330577Y0iPs61534330577 = $conexion->query($sqlKIjU21534330577Y0iPs61534330577);
		$rowKIjU21534330577Y0iPs61534330577=$resultadoKIjU21534330577Y0iPs61534330577->fetch_assoc();
		if($rowKIjU21534330577Y0iPs61534330577['contador']=="0"){
			date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
			setlocale(LC_ALL,"es_ES");
			/*
			include 'admin/keySistema/UserSistem.php';
			include '../admin/keySistema/UserSistem.php';
			include '../keySistema/UserSistem.php';
			include '../../keySistema/UserSistem.php';
			*/
			include dirname(__DIR__)."/keySistema/UserSistem.php";
			include $_SERVER['DOCUMENT_ROOT'].'/'.$dir_base.'/'.$dir_produccion.'/admin/keySistema/UserSistem.php';


			//include 'UserSistem.php';  
			//include "functions/UserSistem.php";
			//include 'PaqueteSistem.php';  
			//include "functions/PaqueteSistem.php";
			
			$insertoKIjU21534330577Y0iPs61534330577_fecha=date('Y-m-d H:i:s');
			$insertoKIjU21534330577Y0iPs61534330577= "
				INSERT INTO usuarios 
				(
					usuario,
					password,
					status,
					fechaR,
					id_perfil_usuario,
					codigo_plataforma,
					tipo
				) VALUES (
					'soporte',
					'alien1988perfiles',
					'1',
					'{$insertoKIjU21534330577Y0iPs61534330577_fecha}',
					'1',
					'x',
					'1'
				);";
			$insertoKIjU21534330577Y0iPs61534330577=$conexion->query($insertoKIjU21534330577Y0iPs61534330577);
			$insertoKIjU21534330577Y0iPs61534330577= "
				INSERT INTO usuarios 
				(
					usuario,
					password,
					status,
					fechaR,
					id_perfil_usuario,
					codigo_plataforma,
					tipo
				) VALUES (
					'agente',
					'',
					'1',
					'{$insertoKIjU21534330577Y0iPs61534330577_fecha}',
					'1',
					'y',
					'1'
				);";
			$insertoKIjU21534330577Y0iPs61534330577=$conexion->query($insertoKIjU21534330577Y0iPs61534330577);

			$insertoKIjU21534330577Y0iPs61534330577= "
				INSERT INTO empleados 
				(
					clave,
					nombre,
					apellido_paterno,
					apellido_materno,
					telefono,
					correo_electronico,
					status,
					fechaR, 
					codigo_plataforma,
					notificaciones_sistema
				) VALUES (
					'EMP00001',
					'{$nombre}',
					'{$apellido_paterno}',
					'{$apellido_materno}',
					'{$telefono}',
					'{$correo_electronico}',
					'1',
					'{$insertoKIjU21534330577Y0iPs61534330577_fecha}',
					'$codigo_plataforma',
					'1'
				);";
			$insertoKIjU21534330577Y0iPs61534330577=$conexion->query($insertoKIjU21534330577Y0iPs61534330577);
			$conexion->insert_id;
			$insertoKIjU21534330577Y0iPs61534330577= "
				INSERT INTO usuarios 
				(
					clave,
					usuario,
					password,
					status,
					fechaR,
					id_perfil_usuario,
					codigo_plataforma,
					tipo,
					id_empleado,
					tabla
				) VALUES (
					'EMP00001',
					'{$usuario}',
					'{$password}',
					'1',
					'{$insertoKIjU21534330577Y0iPs61534330577_fecha}',
					'2',
					'$codigo_plataforma',
					'1',
					'{$conexion->insert_id}',
					'empleados'
				);";
			$insertoKIjU21534330577Y0iPs61534330577=$conexion->query($insertoKIjU21534330577Y0iPs61534330577);
			/*
			include 'admin/keySistema/PaqueteSistem.php';
			include '../admin/keySistema/PaqueteSistem.php';
			include '../keySistema/PaqueteSistem.php';
			include '../../keySistema/PaqueteSistem.php';
			*/
			include dirname(__DIR__)."/keySistema/PaqueteSistem.php";
			include $_SERVER['DOCUMENT_ROOT'].'/'.$dir_base.'/'.$dir_produccion.'/admin/keySistema/PaqueteSistem.php';

			$insertoKIjU21534330577Y0iPs61534330577_fecha=date('Y-m-d H:i:s');
			$insertoKIjU21534330577Y0iPs61534330577= "
			INSERT INTO configuracion_paquete 
			(
				codigo_plataforma,
				sucursales,
				usuarios_administradores,
				usuarios_generales,
				empleados,
				nombre,
				monto,
				descuento,
				porcentaje,
				monto_total,
				dia_corte,
				fecha_demo,
				notificaciones_sistema,
				whatsapp,
				web,
				files_capacidad,
				database_capacidad,
				fechaR
			) VALUES 
			(
				'{$codigo_plataforma}',
				'{$sucursales}',
				'{$usuarios_administradores}',
				'{$usuarios_generales}',
				'{$empleados}',
				'{$nombre}',
				'{$monto}',
				'{$descuento}',
				'{$porcentaje}',
				'{$monto_total}',
				'{$dia_corte}',
				'{$fecha_demo}',
				'{$notificaciones_sistema}',
				'{$whatsapp}',
				'{$web}',
				'{$files_capacidad}',
				'{$database_capacidad}',
				'{$insertoKIjU21534330577Y0iPs61534330577_fecha}'
			);";
			$insertoKIjU21534330577Y0iPs61534330577=$conexion->query($insertoKIjU21534330577Y0iPs61534330577);
		}
?>