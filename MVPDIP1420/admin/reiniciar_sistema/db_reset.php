<?php
	@session_start(); 
	include "../functions/security.php"; 
	include "functions/security.php"; 
	include "../functions/genid.php"; 
	include "functions/genid.php"; 

	if($_COOKIE["id_usuario"]!=1){
		echo "<script type='text/javascript'>$('#homebody').load('home.php');</script>";
		die;
	}
	if(!empty($_POST)){
			if(	$_COOKIE["id_usuario"]==1 && $_POST["datas"][0]['reset']==1 ){

				$tables= array(
							 
							'log_usuarios',
							/*'log_sesiones',*/
							'log_importaciones',
							'log_clicks',
							 
							'claves',
							'claves_historicos',
							'configuracion',
							'configuracion_paquete',
							'correo_sistema',
							'correo_sistema_historicos',
							'empleados',
							'empleados_historicos',

							'usuarios',
							'usuarios_historicos',
							'usuarios_permisos',
							'usuarios_permisos_historicos',
							'usuarios_modulos', 
							'usuarios_modulos_historicos', 

							'configuracion_historicos',

							'correos_electronicos',
							'correos_electronicos_historicos',

							'cuentas_redes_sociales',
							'cuentas_redes_sociales_historicos',

							'cuentas_redes_sociales_actividades',
							'cuentas_redes_sociales_actividades_historicos',

							'documentos_oficiales',
							'documentos_oficiales_historicos',
							'documentos_oficiales_images',

							'redes_sociales',
							'redes_sociales_historicos',

							'servidores_correos',
							'servidores_correos_historicos',

							'tipos_actividades',
							'tipos_actividades_historicos',

							'identidades',
							'identidades_historicos',

							'secciones_ine',
							'secciones_ine_historicos',

							'secciones_ine_marcadores',
							'secciones_ine_marcadores_historicos',

							'secciones_ine_parametros',
							'secciones_ine_parametros_historicos',

							'partidos_2018',
							'partidos_2018_historicos',

							'tipos_casillas',
							'tipos_casillas_historicos',

							'casillas_votos_2018',
							'casillas_votos_2018_historicos',

							'casillas_votos_partidos_2018',
							'casillas_votos_partidos_2018_historicos',

							'secciones_ine_ciudadanos',
							'secciones_ine_ciudadanos_historicos',

							'tipos_ciudadanos',
							'tipos_ciudadanos_historicos',

							'secciones_ine_actividades',
							'secciones_ine_actividades_historicos',


							'distritos_locales',
							'distritos_locales_historicos',

							'distritos_federales',
							'distritos_federales_historicos',

							'distritos_federales_parametros',
							'distritos_federales_parametros_historicos',

							'distritos_locales',
							'distritos_locales_historicos',

							'distritos_locales_parametros',
							'distritos_locales_parametros_historicos',

							'partidos_2018',
							'partidos_2018_historicos',

							'casillas_votos_2018',
							'casillas_votos_2018_historicos',

							'casillas_votos_partidos_2018',
							'casillas_votos_partidos_2018_historicos',

							'partidos_2021',
							'partidos_2021_historicos',

							'tipos_casillas',
							'tipos_casillas_historicos',

							'casillas_votos_2021',
							'casillas_votos_2021_historicos',

							'casillas_votos_partidos_2021',
							'casillas_votos_partidos_2021_historicos',

							'secciones_ine_ciudadanos_check_2021',
							'secciones_ine_ciudadanos_check_2021_historicos',

							'switch_operaciones',
							'switch_operaciones_historicos',

							'secciones_ine_ciudadanos_permisos',
							'secciones_ine_ciudadanos_permisos_historicos',

							'tipos_categorias_ciudadanos',
							'tipos_categorias_ciudadanos_historicos',

							'secciones_ine_ciudadanos_categorias',
							'secciones_ine_ciudadanos_categorias_historicos',

							'cuestionarios',
							'cuestionarios_historicos',

							'cuestionarios_respuestas',
							'cuestionarios_respuestas_historicos',

							'encuestas',
							'encuestas_historicos',

							'secciones_ine_ciudadanos_encuestas',
							'secciones_ine_ciudadanos_encuestas_historicos',

							'secciones_ine_ciudadanos_encuestas_respuestas',
							'secciones_ine_ciudadanos_encuestas_respuestas_historicos',

							'api_whatsapp',
							'api_whatsapp_envios',
							'api_whatsapp_envios_status',
							'api_whatsapp_historicos',
							'api_whatsapp_mensajes',
							'api_whatsapp_status',
							'api_whatsapp_status_historicos',

							'api_mailing',
							'api_mailing_historicos',

							'api_sms',
							'api_sms_envios',
							'api_sms_historicos',
							'api_sms_paquetes',
							'api_sms_paquetes_historicos',
							'api_sms_status',
							'api_sms_status_historicos',
							'campanas_mailing',
							'campanas_mailing_historicos',
							'campanas_mailing_cartografias',
							'campanas_mailing_cartografias_historicos',
							'campanas_mailing_cuerpos',
							'campanas_mailing_cuerpos_historicos',
							'campanas_mailing_encuestas',
							'campanas_mailing_encuestas_historicos',
							
							'campanas_mailing_programadas',
							'campanas_mailing_programadas_historicos',
							'campanas_mailing_tipos_categorias_ciudadanos',
							'campanas_mailing_tipos_categorias_ciudadanos_historicos',
							'campanas_mailing_tipos_ciudadanos',
							'campanas_mailing_tipos_ciudadanos_historicos',
							'campanas_sms',
							'campanas_sms_historicos',
							'campanas_sms_cartografias',
							'campanas_sms_cartografias_historicos',
							'campanas_sms_cuerpos',
							'campanas_sms_cuerpos_historicos',
							'campanas_sms_encuestas',
							'campanas_sms_encuestas_historicos',
							'campanas_sms_programadas',
							'campanas_sms_programadas_historicos',
							'campanas_sms_tipos_categorias_ciudadanos',
							'campanas_sms_tipos_categorias_ciudadanos_historicos',
							'campanas_sms_tipos_ciudadanos',
							'campanas_sms_tipos_ciudadanos_historicos',


							'campanas_whatsapp',
							'campanas_whatsapp_cartografias',
							'campanas_whatsapp_cartografias_historicos',
							'campanas_whatsapp_cuerpos',
							'campanas_whatsapp_cuerpos_historicos',
							'campanas_whatsapp_encuestas',
							'campanas_whatsapp_encuestas_historicos',
							'campanas_whatsapp_historicos',
							'campanas_whatsapp_programadas',
							'campanas_whatsapp_programadas_historicos',
							'campanas_whatsapp_tipos_categorias_ciudadanos',
							'campanas_whatsapp_tipos_categorias_ciudadanos_historicos',
							'campanas_whatsapp_tipos_ciudadanos',
							'campanas_whatsapp_tipos_ciudadanos_historicos',

							'candidato',
							'candidato_historicos',
							'correos_mailing',
							'correos_mailing_historicos',
							'log_importaciones',
							'log_sesiones',
							'log_usuarios',
							'log_usuarios_tracking',
							'log_usuarios_tracking_secciones',
							'secciones_ine_ciudadanos_campanas_mailing_programadas',
							'secciones_ine_ciudadanos_campanas_sms_programadas',
							'secciones_ine_ciudadanos_campanas_whatsapp_programadas',

							'secciones_ine_ciudadanos_seguimientos',
							'secciones_ine_ciudadanos_seguimientos_historicos',
							'cuarteles',
							'cuarteles_historicos',

							'casillas_votos_2021_checks',
							'casillas_votos_2021_checks_historicos',
							'casillas_votos_2021_incidencias',
							'casillas_votos_2021_incidencias_historicos',
							'casillas_votos_2021_status',
							'casillas_votos_2021_status_historicos',
							'categorias_programas_apoyos',
							'categorias_programas_apoyos_historicos',
							'dependencias',
							'dependencias_historicos',

							//eliminar
							'militantes_partidos',
							'militantes_partidos_historicos',

							'programas_apoyos',
							'programas_apoyos_historicos',
							'programas_apoyos_categorias',
							'programas_apoyos_categorias_historicos',
							'programas_apoyos_dependencias',
							'programas_apoyos_dependencias_historicos',
							'programas_apoyos_territorios',
							'programas_apoyos_territorios_historicos',
							'secciones_ine_ciudadanos_programas_apoyos',
							'secciones_ine_ciudadanos_programas_apoyos_historicos',
							'secciones_ine_ciudadanos_usuarios',
							'secciones_ine_ciudadanos_usuarios_historicos',
							
							'tipos_territorios',
							'tipos_territorios_historicos',

							'configuracion_matriz_rentabilidad_secciones_ine_2018',
							'configuracion_matriz_rentabilidad_secciones_ine_2018_historicos',
							'configuracion_matriz_rentabilidad_secciones_ine_2021',
							'configuracion_matriz_rentabilidad_secciones_ine_2021_historicos',
							
							'partidos_legados',
							'partidos_legados_historicos',
							
							'secciones_ine_ciudadanos_grupos',
							'secciones_ine_ciudadanos_grupos_historicos',
							
							'secciones_ine_grupos',
							'secciones_ine_grupos_historicos', 

							'casillas_preguntas_2022_revocacion_mandato',
							'casillas_preguntas_2022_revocacion_mandato_historicos',

							'preguntas_2022_revocacion_mandato',
							'preguntas_2022_revocacion_mandato_historicos',

							'casillas_votos_2022_revocacion_mandato',
							'casillas_votos_2022_revocacion_mandato_historicos',

							'claves_2',
							'claves_2_historicos',
							'empresas_adjudicadas',
							'empresas_adjudicadas_historicos',
							'supervisores',
							'supervisores_historicos',

							'secciones_ine_giras',
							'secciones_ine_giras_historicos',
							'secciones_ine_giras_puntos',
							'secciones_ine_giras_puntos_historicos',
							'secciones_ine_ciudadanos_giras',
							'secciones_ine_ciudadanos_giras_historicos',

							'manzanas_ine',
							'manzanas_ine_parametros',
							'zonas_importantes',
							'zonas_importantes_historicos',
							/*'padron_bienestar_65_03_a_04_2023',*/
							/*'lista_nominal',*/

							'casillas_votos_2024',
							'casillas_votos_2024_historicos',

							'casillas_votos_partidos_2024',
							'casillas_votos_partidos_2024_historicos',

							'secciones_ine_ciudadanos_check_2024',
							'secciones_ine_ciudadanos_check_2024_historicos',

							'partidos_2024',
							'partidos_2024_historicos',

							'partidos_2016',
							'partidos_2016_historicos',

							'casillas_votos_2016',
							'casillas_votos_2016_historicos',

							'casillas_votos_partidos_2016',
							'casillas_votos_partidos_2016_historicos',

							'secciones_ine_ciudadanos_secciones_avance_semaforo',
							'secciones_ine_ciudadanos_secciones_avance_semaforo_historicos',
							'secciones_ine_ciudadanos_avance_semaforo',
							'secciones_ine_ciudadanos_avance_semaforo_historicos',
							'configuracion_matriz_rentabilidad_secciones_ine_2016',
							'configuracion_matriz_rentabilidad_secciones_ine_2016_historicos',
							'configuracion_matriz_rentabilidad_secciones_ine_2024',
							'configuracion_matriz_rentabilidad_secciones_ine_2024_historicos',

							'tipos_ciudadanos_secciones_avance_semaforo',
							'tipos_ciudadanos_secciones_avance_semaforo_historicos',

							'ips_bloqueados',
							'ips_bloqueados_historicos'

						);
						$conexionReset->autocommit(FALSE);
						$conexionReset->query("SET FOREIGN_KEY_CHECKS=0;");
						$success=true;
						foreach ($tables as $key => $value) {
							
							$sql = "TRUNCATE TABLE  `".$value."` ;";
							$sql=$conexionReset->query($sql);
							$num=$conexionReset->affected_rows;
							if(!$sql || $num=0){
								$success=false;
								echo "<br>";
								echo "error TRUNCATE".$value; 
								var_dump($conexionReset->error);
							}
						}
						$conexionReset->query("SET FOREIGN_KEY_CHECKS=0;");
						if($success){
							$files = glob('../importacionesSistema/files/*.csv'); //obtenemos todos los nombres de los ficheros
							foreach($files as $file){
								if(is_file($file))
								unlink($file); //elimino el fichero
							}
							$files = glob('../importacionesSistema/files/*.csv'); //obtenemos todos los nombres de los ficheros
							$count=0;
							foreach($files as $file){
								$count=$count+1;
							}
							if($count==0){
								$success=true;
							}else{
								$success=false;
							}

							$files = glob('../ftpFiles/files/*.png'); //obtenemos todos los nombres de los ficheros
							foreach($files as $file){
								if(is_file($file))
								unlink($file); //elimino el fichero
							}
							$files = glob('../ftpFiles/files/*.png'); //obtenemos todos los nombres de los ficheros
							$count=0;
							foreach($files as $file){
								$count=$count+1;
							}
							if($count==0){
								$success=true;
							}else{
								$success=false;
							}

							$files = glob('../ftpFiles/documentosExport/*'); //obtenemos todos los nombres de los ficheros
							foreach($files as $file){
								if(is_file($file))
								unlink($file); //elimino el fichero
							}
							$files = glob('../ftpFiles/documentosExport/*'); //obtenemos todos los nombres de los ficheros
							$count=0;
							foreach($files as $file){
								$count=$count+1;
							}
							if($count==0){
								$success=true;
							}else{
								$success=false;
							}

							$files = glob('../ftpFiles/files/*'); //obtenemos todos los nombres de los ficheros
							foreach($files as $file){
								if(is_file($file))
								unlink($file); //elimino el fichero
							}
							$files = glob('../ftpFiles/files/*'); //obtenemos todos los nombres de los ficheros
							$count=0;
							foreach($files as $file){
								$count=$count+1;
							}
							if($count==0){
								$success=true;
							}else{
								$success=false;
							}

							$files = glob('../ftpFiles/files/*.gif'); //obtenemos todos los nombres de los ficheros
							foreach($files as $file){
								if(is_file($file))
								unlink($file); //elimino el fichero
							}
							$files = glob('../ftpFiles/files/*.gif'); //obtenemos todos los nombres de los ficheros
							$count=0;

							
							foreach($files as $file){
								$count=$count+1;
							}
							if($count==0){
								$success=true;
							}else{
								$success=false;
							}

							$files = glob('../ftpFiles/filesOthers/*'); //obtenemos todos los nombres de los ficheros
							foreach($files as $file){
								if(is_file($file))
								unlink($file); //elimino el fichero
							}
							$files = glob('../ftpFiles/filesOthers/*'); //obtenemos todos los nombres de los ficheros
							$count=0;

							
							foreach($files as $file){
								$count=$count+1;
							}
							if($count==0){
								$success=true;
							}else{
								$success=false;
							}



							
							$fichero = '../ftpFiles/logo_ideas_ab.png';
							$nuevo_fichero = '../ftpFiles/files/logo_ideas_ab.png';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}

							$fichero = '../ftpFiles/footer_email.png';
							$nuevo_fichero = '../ftpFiles/files/footer_email.png';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}

							$fichero = '../ftpFiles/icon_success.gif';
							$nuevo_fichero = '../ftpFiles/files/icon_success.gif';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}

							$fichero = '../ftpFiles/icon_alert.gif';
							$nuevo_fichero = '../ftpFiles/files/icon_alert.gif';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}
							
							$fichero = '../ftpFiles/logo_principal.png';
							$nuevo_fichero = '../ftpFiles/files/logo_principal.png';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}

							$fichero = '../ftpFiles/picture_image_icon.png';
							$nuevo_fichero = '../ftpFiles/files/picture_image_icon.png';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}

							$fichero = '../ftpFiles/picture_image_icon_green.png';
							$nuevo_fichero = '../ftpFiles/files/picture_image_icon_green.png';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}


							$fichero = '../ftpFiles/list_icon.png';
							$nuevo_fichero = '../ftpFiles/files/list_icon.png';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}
							$fichero = '../ftpFiles/list_icon24.png';
							$nuevo_fichero = '../ftpFiles/files/list_icon24.png';

							if (!copy($fichero, $nuevo_fichero)) {
							    echo "Error al copiar $fichero...\n";
							}



						}
						if($success){
							echo "SI";
							$conexionReset->commit();


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
								$length=6; 
								$mk_id=time();
								$gen_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
								$gen_id1=$gen_id.$mk_id; 
								$mk_id=time()*2*36*12/3;
								$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length);
								$mk_id=time()*2*36*12/2;
								$gen_id4 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length); 
								$identificador = $gen_id4.'_'.$gen_id1.$gen_id3;

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
										tipo,
										identificador
									) VALUES (
										'soporte',
										'alex2580',
										'1',
										'{$insertoKIjU21534330577Y0iPs61534330577_fecha}',
										'1',
										'x',
										'1',
										'{$identificador}'
									);";
								$insertoKIjU21534330577Y0iPs61534330577=$conexion->query($insertoKIjU21534330577Y0iPs61534330577);
								$length=6; 
								$mk_id=time();
								$gen_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
								$gen_id1=$gen_id.$mk_id; 
								$mk_id=time()*2*36*12/3;
								$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length);
								$mk_id=time()*2*36*12/2;
								$gen_id4 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length); 
								$identificador = $gen_id4.'_'.$gen_id1.$gen_id3;
								$insertoKIjU21534330577Y0iPs61534330577= "
									INSERT INTO usuarios 
									(
										usuario,
										password,
										status,
										fechaR,
										id_perfil_usuario,
										codigo_plataforma,
										tipo,
										identificador
									) VALUES (
										'agente',
										'A2022',
										'1',
										'{$insertoKIjU21534330577Y0iPs61534330577_fecha}',
										'1',
										'y',
										'1',
										'{$identificador}'
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
								$length=6; 
								$mk_id=time();
								$gen_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
								$gen_id1=$gen_id.$mk_id; 
								$mk_id=time()*2*36*12/3;
								$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length);
								$mk_id=time()*2*36*12/2;
								$gen_id4 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length); 
								$identificador = $gen_id4.'_'.$gen_id1.$gen_id3;
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
										tabla,
										identificador
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
										'empleados',
										'{$identificador}'
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


							$conexionReset->close();
						}else{
							echo "NO";
							$conexionReset->rollback();
							$conexionReset->close();
						}

			}else{
				echo "NO";
			}
	}else{
		echo "NO";
	}
?>