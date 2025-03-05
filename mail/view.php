<?php 
	setcookie("HnMbcrK17A",'pQmsd8r8ns',time()+(60*60*24*650),"/",false);
	setcookie("AUTckmyE9n",'Be25ZcE43C',time()+(60*60*24*650),"/",false);
	setcookie("uHUxAcsGnp",'qCJB0UDPYn',time()+(60*60*24*650),"/",false);
	//header('Cache-Control: max-age=84600');
	$codigo_ciudadano = $_GET['cot'];
	$identificador = $_GET['cot1'];

	if(empty($codigo_ciudadano) || empty($identificador) ){
		header("Location: ../index.php");
		die;
	}
	include '../ops/db_Es1Ss_3S.php';

	$codigo_ciudadano = mysqli_real_escape_string($conexion,$codigo_ciudadano);
	$identificador = mysqli_real_escape_string($conexion,$identificador);

	$cuerpo ='Datos Ciudadano<br>
		-[*__Tipo_Ciudadano__*]<br>
		-[*__Nombre_Completo__*]<br>
		-[*__Nombre__*]<br>
		-[*__Apellido_Paterno__*]<br>
		-[*__Apellido_Materno__*]<br>
		-[*__Fecha_Nacimiento__*]<br>
		-[*__Fecha_Nacimiento_Texto__*]<br>
		-[*__Edad__*]<br>
		-[*__Sexo__*]<br>
		-[*__Relacionado__*]<br>
		-[*__Whatsapp__*]<br>
		-[*__Telefono__*]<br>
		-[*__Celular__*]<br>
		-[*__Correo_Electronico__*]<br><br>
		<hr>
		Datos Ciudadano Usuario<br>
		-[*__Usuario__*]<br>
		-[*__Password__*]<br>
		-[*__Status__*]<br><br>
		<hr>
		Datos Ciudadano Cartografia<br>
		-[*__Estado__*]<br>
		-[*__Municipio__*]<br>
		-[*__Localidad__*]<br>
		-[*__Distrito_Local__*]<br>
		-[*__Distrito_Federal__*]<br><br>
		<hr>
		Datos Fecha Hora<br>
		-[*__Fecha__*]<br>
		-[*__Fecha_WDDMMAAA__*]<br>
		-[*__Hora__*]<br>
		-[*__Hora_AMPM__*]<br>
		-[*__Hora_ampm__*]<br><br>
		<hr>
		Datos del Correo<br>
		-[*__Correo_Electronico_Repuesta__*]<br>
		-[*__Correo_Electronico_Envio__*]<br>
		-[*__URL__*]<br>
		-[*__Nombre_Sistema__*]<br>
		-[*__Slogan_Sistema__*]<br>
		-[*__Logo_Sistema__*]<br>
		-[*__Correo_Vista_Web__*]<br><br>';

	$logCombinacion['status'] = 3;
	$logCombinacion['fecha_leido'] = $fechaSF;
	$logCombinacion['hora_leido'] = $fechaSH;
	$logCombinacion['fecha_hora_leido'] = $fechaH;
	$logCombinacion['tipo_leido'] = 'navegador';
	foreach($logCombinacion as $keyPrincipal => $atributos) {
		if($keyPrincipal !='id'){
			$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
		}else{
			$id=$atributos;
		}
	}

	//verificar si esta activo el envio de correos
	$scriptSQL=" SELECT * FROM api_mailing LIMIT 1";
	$resultado = $conexion->query($scriptSQL);
	$row=$resultado->fetch_assoc();
	$url_mailing = $row['url_mailing'];
	// 30 * 30
	if($row['status'] == 0){
		echo "offline :(";
		die;
	} 

	


	$sql_seach = " SELECT id,id_seccion_ine_ciudadano,id_campana_mailing FROM secciones_ine_ciudadanos_campanas_mailing_programadas WHERE  codigo_seccion_ine_ciudadano = '{$codigo_ciudadano}'  AND identificador = '{$identificador}'";
	$resultado = $conexion->query($sql_seach);
	$row=$resultado->fetch_assoc();
	$id = $row['id'];

	if($id==''){
		header("Location: ../index.php");
		die;
	}

	////parido_principal
	$scriptPartido=" SELECT icono,logo FROM partidos_2021 WHERE principal=1 LIMIT 1";
	$resultadoPartido = $conexion->query($scriptPartido);
	$rowPartido=$resultadoPartido->fetch_assoc();
	$partido_icono = $rowPartido['icono'];
	$partido_logo = $rowPartido['logo'];

	$scriptCandidato=" SELECT * FROM candidato";
	$resultadoCandidato = $conexion->query($scriptCandidato);
	$candidatoDatos=$resultadoCandidato->fetch_assoc();

	$candidatoDatos['tipo_cartografia'];
	$sqlSeccionesWhere ='';
	if($candidatoDatos['tipo_cartografia']!='0' || $candidatoDatos['tipo_cartografia']!=''){
		if($candidatoDatos['tipo_cartografia']=='municipios'){
			$sqlSeccionesWhere = ' AND si.id_municipio = "'.$candidatoDatos['id_tipo_cartografia'].'"';
		}elseif($candidatoDatos['tipo_cartografia']=='distritos_locales'){
			$sqlSeccionesWhere = ' AND si.id_distrito_local = "'.$candidatoDatos['id_tipo_cartografia'].'"';
		}elseif($candidatoDatos['tipo_cartografia']=='distritos_federales'){
			$sqlSeccionesWhere = ' AND si.id_distrito_federal = "'.$candidatoDatos['id_tipo_cartografia'].'"';
		}else{
			$sqlSeccionesWhere = ' AND si.id = "'.$candidatoDatos['id_tipo_cartografia'].'"';
		}
	}

	//$secciones_ineDatosMapa = secciones_ineDatosMapa();
	$sqlSecciones = "SELECT * FROM secciones_ine si WHERE 1 ".$sqlSeccionesWhere;
	$resultSecciones = $conexion->query($sqlSecciones); 
	while($rowSecciones=$resultSecciones->fetch_assoc()){
		$secciones_ineDatosMapa[$rowSecciones['id']]=$rowSecciones;
	}

	if(!empty($sqlSeccionesWhere)){
		$sqlSeccionesParametrosWhere = 'AND EXISTS ('.$sqlSecciones.' AND si.id = sip.id_seccion_ine )';
	}

	//$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
	$sqlSeccionesParametros="SELECT sip.id_seccion_ine,sip.tipo,sip.orden,sip.latitud,sip.longitud FROM secciones_ine_parametros sip WHERE 1 ".$sqlSeccionesParametrosWhere;
	$resultSeccionesParametros = $conexion->query($sqlSeccionesParametros); 
	while($rowSeccionesParametros=$resultSeccionesParametros->fetch_assoc()){
		$secciones_ine_parametrosDatosMapa[$rowSeccionesParametros['id_seccion_ine']][$rowSeccionesParametros['tipo']][$rowSeccionesParametros['orden']]=$rowSeccionesParametros;
	}

	$id_seccion_ine_ciudadano = $row['id_seccion_ine_ciudadano'];
	$id_campana_mailing = $row['id_campana_mailing'];
	/////// esto es para recavar informacion con solo ip
	$logCombinacion['status'] = 3;
	$logCombinacion['fecha_leido'] = $fechaSF;
	$logCombinacion['hora_leido'] = $fechaSH;
	$logCombinacion['fecha_hora_leido'] = $fechaH;
	$logCombinacion['tipo_leido'] = 'navegador';
	foreach($logCombinacion as $keyPrincipal => $atributos) {
		if($keyPrincipal !='id'){
			$valueSets[] = $keyPrincipal . " = '" . $atributos . "'";
		}else{
			$id=$atributos;
		}
	}
	$update_correo_mailing = "UPDATE secciones_ine_ciudadanos_campanas_mailing_programadas SET ". join(",",$valueSets) . " WHERE ( id = '{$id}' );";
	$update_correo_mailing=$conexion->query($update_correo_mailing);
	if(!$update_correo_mailing || $num=0){
		$success=false;
		echo "ERROR update_correo_mailing"; 
		var_dump($conexion->error);
	}


	$sql_mailing="
	SELECT
		siccmp.id,
		siccmp.id_seccion_ine_ciudadano,

		cmc.cuerpo,
		cmc.asunto,

		sic.nombre_completo,
		sic.nombre,
		sic.apellido_paterno,
		sic.apellido_materno,
		sic.fecha_nacimiento,
		sic.edad,
		sic.sexo,
		(SELECT sicr.nombre_completo FROM secciones_ine_ciudadanos sicr WHERE sicr.id = sic.id_seccion_ine_ciudadano_compartido ) relacionado,
		sic.whatsapp,
		sic.telefono,
		sic.celular,
		sic.correo_electronico,
		(SELECT tc.nombre FROM tipos_ciudadanos tc WHERE tc.id = sic.id_tipo_ciudadano ) tipo_ciudadano,

		(SELECT u.usuario FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) usuario,
		(SELECT u.password FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) password,
		(SELECT u.status FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) status,
		(SELECT e.estado FROM estados e WHERE e.id=sic.id_estado) estado,
		(SELECT m.municipio FROM municipios m WHERE m.id=sic.id_estado) municipio,
		(SELECT l.localidad FROM localidades l WHERE l.id=sic.id_localidad) localidad,
		(SELECT dl.numero FROM distritos_locales dl WHERE dl.id=sic.id_distrito_local) distrito_local,
		(SELECT df.numero FROM distritos_federales df WHERE df.id=sic.id_distrito_federal) distrito_federal,
		(SELECT s.numero FROM secciones_ine s WHERE s.id=sic.id_seccion_ine) seccion,

		cmr.id d_id,
		cmr.servidor d_servidor,
		cmr.puerto d_puerto,
		cmr.cifrado d_cifrado,
		cmr.usuario d_usuario,
		cmr.password d_password,
		cmr.de d_de,
		cmr.correo_electronico u_correo_electronico,

		siccmp.identificador u_identificador,
		sic.codigo_seccion_ine_ciudadano u_codigo_unico


	FROM secciones_ine_ciudadanos_campanas_mailing_programadas siccmp
	LEFT JOIN secciones_ine_ciudadanos sic
	ON sic.id = siccmp.id_seccion_ine_ciudadano
	LEFT JOIN campanas_mailing cm
	ON cm.id = siccmp.id_campana_mailing
	LEFT JOIN correos_mailing cmr
	ON cmr.id = cm.id_correo_mailing
	LEFT JOIN campanas_mailing_cuerpos cmc
	ON cmc.id_campana_mailing = cm.id

	WHERE  siccmp.tipo IN (1,2,3)  AND cm.status=1 AND cmr.status=1 AND cmc.id_campana_mailing ='{$id_campana_mailing}' AND sic.id = '{$id_seccion_ine_ciudadano}'
	LIMIT 1;
	";
	$resultado = $conexion->query($sql_mailing);
	$rowMail=$resultado->fetch_assoc();

	$sql='SELECT nombre,slogan,url_base FROM configuracion WHERE 1 = 1 LIMIT 1';
	$resultado = $conexion->query($sql);
	$configuracionDatos=$resultado->fetch_assoc();
	$img_logo='<img src="'.$configuracionDatos['url_base'].'ops/imagen.php?id_img=logo_principal.png" height="90px" >';


 

	$fecha_hora = array(
		"[*__Fecha__*]" => $fechaSF,
		"[*__Fecha_WDDMMAAA__*]" => fechaNormalSimpleWDDMMAA_ES($fechaSF),
		"[*__Hora__*]" => $fechaSH,
		"[*__Hora_AMPM__*]" => convertidorAMPM($fechaSH,'','mayuscula'),
		"[*__Hora_ampm__*]" => convertidorAMPM($fechaSH,'',''),
	);

	$correo_electronico = array(
		"[*__Correo_Electronico_Repuesta__*]" => $rowMail['correo_electronico'],
		"[*__Correo_Electronico_Envio__*]" => $rowMail['usuario'],
		"[*__URL__*]" => $configuracionDatos['url_base'],
		"[*__Nombre_Sistema__*]" => $configuracionDatos['nombre'],
		"[*__Slogan_Sistema__*]" => $configuracionDatos['slogan'],
		"[*__Logo_Sistema__*]" => $img_logo,
		"[*__Correo_Vista_Web__*]" => 'demo',
	);

	$datos_ciudadano = array(
		"[*__Tipo_Ciudadano__*]" => $rowMail['tipo_ciudadano'],
		"[*__Nombre_Completo__*]" => $rowMail['nombre_completo'],
		"[*__Nombre__*]" => $rowMail['nombre'],
		"[*__Apellido_Paterno__*]" => $rowMail['apellido_paterno'],
		"[*__Apellido_Materno__*]" => $rowMail['apellido_materno'],
		"[*__Fecha_Nacimiento__*]" => $rowMail['fecha_nacimiento'],
		"[*__Fecha_Nacimiento_Texto__*]" => fechaNormalSimpleWDDMMAA_ES($rowMail['fecha_nacimiento']),
		"[*__Edad__*]" => $rowMail['edad'],
		"[*__Sexo__*]" => $rowMail['sexo'],
		"[*__Relacionado__*]" => $rowMail['nombre_completo']==''?'No tiene':$rowMail['nombre_completo'],
		"[*__Whatsapp__*]" => $rowMail['whatsapp'],
		"[*__Telefono__*]" => $rowMail['telefono'],
		"[*__Celular__*]" => $rowMail['celular'],
		"[*__Correo_Electronico__*]" => $rowMail['correo_electronico'],
	);

	$datos_ciudadano_usuario = array(
		"[*__Usuario__*]" => $rowMail['usuario'],
		"[*__Password__*]" => $rowMail['password'],
		"[*__Status__*]" => $rowMail['status']=='1'?'Online':'Offline',
	);

	$datos_ciudadano_cartografia = array(
		"[*__Estado__*]" => $rowMail['estado'],
		"[*__Municipio__*]" => $rowMail['municipio'],
		"[*__Localidad__*]" => $rowMail['localidad'],
		"[*__Distrito_Local__*]" => $rowMail['distrito_local'],
		"[*__Distrito_Federal__*]" => $rowMail['distrito_federal'],
		"[*__Seccion__*]" => $rowMail['seccion'],
	);


	$cuerpo = $rowMail['cuerpo'];
	$asunto = $rowMail['asunto'];
	$bodyHTML = strtr($cuerpo, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
	$asuntoHTML = strtr($asunto, array_merge($fecha_hora,$correo_electronico,$datos_ciudadano,$datos_ciudadano_usuario,$datos_ciudadano_cartografia));
	$logox = 'https://'.$_SERVER['HTTP_HOST'].'/ops/imagen.php?id_img=logo_principal.png';
?>
	<!doctype html>
	<html class="no-js" lang="zxx">
		<head>
			<!-- meta character set -->
			<meta name="google-site-verification" content="+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34="/>
			<meta charset="utf-8">
			<!-- Always force latest IE rendering engine or request Chrome Frame -->
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<title><?= $asuntoHTML; ?></title>
			<meta name="description" content="">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<meta name="google" content="notranslate" />
			<!-- Meta Description -->
			<meta name="description" content="Vista Correo Electrónico">
			<meta name="keywords" content="Vista Correo Electrónico">
			<meta name="author" content="A1XCZ">

			<!-- Iconos -->
			<link rel="apple-touch-icon" sizes="57x57" href="<?= $logox ?>" />
			<link rel="apple-touch-icon" sizes="114x114" href="<?= $logox ?>" />
			<link rel="apple-touch-icon" sizes="72x72" href="<?= $logox ?>" />
			<link rel="apple-touch-icon" sizes="144x144" href="<?= $logox ?>" />
			<link rel="apple-touch-icon" sizes="60x60" href="<?= $logox ?>" />
			<link rel="apple-touch-icon" sizes="120x120" href="<?= $logox ?>" />
			<link rel="apple-touch-icon" sizes="76x76" href="<?= $logox ?>" />
			<link rel="apple-touch-icon" sizes="152x152" href="<?= $logox ?>" />
			<link rel="icon" type="image/png" sizes="196x196" href="<?= $logox ?>" />
			<link rel="icon" type="image/png" sizes="96x96" href="<?= $logox ?>" />
			<link rel="icon" type="image/png" sizes="32x32" href="<?= $logox ?>" />
			<link rel="icon" type="image/png" sizes="16x16" href="<?= $logox ?>" />
			<link rel="icon" type="image/png" sizes="128x128" href="<?= $logox ?>" />
			<meta name="application-name" content="&nbsp;"/>
			<meta name="msapplication-TileColor" content="#FFFFFF" />
			<meta name="msapplication-TileImage" content="<?= $logox ?>" />
			<meta name="msapplication-square70x70logo" content="<?= $logox ?>" />
			<meta name="msapplication-square150x150logo" content="<?= $logox ?>" />
			<meta name="msapplication-wide310x150logo" content="<?= $logox ?>" />
			<meta name="msapplication-square310x310logo" content="<?= $logox ?>" />


			<meta property="og:url" content="<?= $urlout ?>" />
			<meta property="og:locale:alternate" content="es_ES" />
			<meta property="og:type" content="website" />
			<meta property="og:title" content="Perfiles - Net" />
			<meta property="og:description" content="Vista Correo Electrónico">
			<meta property="og:image" content="<?= $logox ?>" />
			
			<!-- Mobile Specific Meta -->
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<!-- CSS here -->
			<link rel="stylesheet" href="assets/css/bootstrap.min.css">
			<link rel="stylesheet" href="assets/css/owl.carousel.min.css">
			<link rel="stylesheet" href="assets/css/flaticon.css">
			<link rel="stylesheet" href="assets/css/slicknav.css">
			<link rel="stylesheet" href="assets/css/animate.min.css">
			<link rel="stylesheet" href="assets/css/magnific-popup.css">
			<link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
			<link rel="stylesheet" href="assets/css/themify-icons.css">
			<link rel="stylesheet" href="assets/css/slick.css">
			<link rel="stylesheet" href="assets/css/nice-select.css">
			<link rel="stylesheet" href="assets/css/style.css">

			<script type="text/javascript">
				console.log(
					"%cDetente Amig@!","color:red;font-family:system-ui;font-size:4rem;-webkit-text-stroke: 1px black;font-weight:bold"
				);
				console.log(
					"%cEsta Función es para desarrolladores, si tienes dudas o te interesa el sistema comunícate con nosotros. Pero si quieres o intentas entrar al sistema, si lo logras comunícate y te invitamos la cena :P","color:gray;font-family:system-ui;font-size:1.4rem;-webkit-text-stroke: 1px black;font-weight:bold;padding:10px"
				);
			</script>

		</head>

		<style type="text/css">
			.preloader .preloader-circle {
				border-top-color:black;
			}
		</style>
		<body>
			<!-- Preloader Start -->
			<div id="preloader-active">
				<div class="preloader d-flex align-items-center justify-content-center">
					<div class="preloader-inner position-relative">
						<div class="preloader-circle"></div>
						<div class="preloader-img pere-text">
							<img src="https://<?= $_SERVER['HTTP_HOST'] ?>/ops/logo_partido.php?id_img=<?= $partido_logo ?>" alt="load">
						</div>
					</div>
				</div>
			</div>
			<!-- Preloader Start -->
			 


			<style type="text/css">
				/* The Modal (background) */
				.modal {
				  display: none; /* Hidden by default */
				  position: fixed; /* Stay in place */
				  z-index: 1; /* Sit on top */
				  left: 0;
				  top: 0;
				  width: 100%; /* Full width */
				  height: 100%; /* Full height */
				  overflow: auto; /* Enable scroll if needed */
				  background-color: rgb(0,0,0); /* Fallback color */
				  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
				}

				/* Modal Content/Box */
				.modal-content {
				  background-color: #fefefe;
				  margin: 10% auto; /* 15% from the top and centered */
				  padding: 20px;
				  border: 1px solid #888;
				  width: 80%; /* Could be more or less, depending on screen size */
				}

				/* The Close Button */
				.close {
				  color: #aaa;
				  float: right;
				  font-size: 28px;
				  font-weight: bold;
				}

				.close:hover,
				.close:focus {
				  color: black;
				  text-decoration: none;
				  cursor: pointer;
				}
			</style>
			<script type="text/javascript">
				function cerrarModalConUbicacion(){
					var modal = document.getElementById("modalConUbicacion");
					modal.style.display = "none";
				}
				function abrirModal(){
					<?php
					if(empty($_COOKIE["uHUxAcsGnp"])){
						?>
						var modal = document.getElementById("modalConUbicacion");
						modal.style.display = "block";
						<?php
					}
					?>
				}

				function cerrarModalSinUbicacion(){
					var modal = document.getElementById("modalSinUbicacion");
					modal.style.display = "none";
				}
				function abrirModalSinUbicación(){
					<?php
					if(empty($_COOKIE["uHUxAcsGnp"])){
						?>
						cerrarModalConUbicacion();
						var modal = document.getElementById("modalSinUbicacion");
						modal.style.display = "block";
						<?php
					}
					?>
					var divInstrucciones = document.getElementById("divInstrucciones");
					divInstrucciones.style.display = "block";
				}

				function obtenerUbicacion(){
					location.reload();
				}

				
			</script>
			<!-- The Modal -->
			<div id="modalConUbicacion" class="modal" onclick="cerrarModalConUbicacion();">
				<!-- Modal content -->
				<div class="modal-content" onclick="cerrarModalConUbicacion()" >
					<span class="close" onclick="cerrarModalConUbicacion();">&times;</span>
					<p onclick="cerrarModalConUbicacion()">
						<center>
							<img src="<?= $logox ?>" style="width: 220px"><br>
						</center>
						Gracias por compartir tu ubicación con el navegador para ubicar la sección desde donde nos visitas, y saber donde nos puedes encontrar.
						<br><br>
						<div style="padding: 0px;color: black;">
							<font style="font-size:15px;color: black;">Candidato</font>
							<br>
							<font style="font-size:18px;color: black;font-weight:bold">
								<b><?= $candidatoDatos['nombre_completo'] ?></b>
								<br><br>
							</font>
							<font style="font-size: 12px;color: black; font-weight:bold">Teléfono:</font>
							<font style="font-size: 14x;">
							<?= $candidatoDatos['telefono'] ?>
							</font>
							<br>
							<font style="font-size: 12px;color: black; font-weight:bold">Whatsapp:</font>
							<font style="font-size: 14x;">
							<?= $candidatoDatos['whatsapp'] ?>
							</font>
							<br>
							<font style="font-size: 12px;color: black; font-weight:bold">Dirección:</font>
							<font style="font-size: 14x;">
							<?= $candidatoDatos['direccion_completa'] ?>
							</font>
							<br><br> 
						</div>
					</p>
				</div>
			</div>

			<div id="modalSinUbicacion" class="modal" onclick="cerrarModalSinUbicacion();">
				<!-- Modal content -->
				<div class="modal-content" onclick="cerrarModalSinUbicacion()" >
					<span class="close" onclick="cerrarModalSinUbicacion();">&times;</span>
					<p onclick="cerrarModalSinUbicacion()">
						<center>
							<img src="<?= $logox ?>" style="width: 220px"><br>
						</center>
						<b>Hola!</b> Si deseas saber tu sección solo tienes que compartir tu ubicación con el navegador es necesario refrescar o presionar el candado que aparece en la parte donde ingresas la direcciñon de la página gracias.
						<br><br>
						<div style="padding: 0px;color: black;">
							<font style="font-size:15px;color: black;">Candidato</font>
							<br>
							<font style="font-size:18px;color: black;font-weight:bold">
								<b><?= $candidatoDatos['nombre_completo'] ?></b>
								<br><br>
							</font>
							<font style="font-size: 12px;color: black; font-weight:bold">Teléfono:</font>
							<font style="font-size: 14x;">
							<?= $candidatoDatos['telefono'] ?>
							</font>
							<br>
							<font style="font-size: 12px;color: black; font-weight:bold">Whatsapp:</font>
							<font style="font-size: 14x;">
							<?= $candidatoDatos['whatsapp'] ?>
							</font>
							<br>
							<font style="font-size: 12px;color: black; font-weight:bold">Dirección:</font>
							<font style="font-size: 14x;">
							<?= $candidatoDatos['direccion_completa'] ?>
							</font>
							<br><br> 
						</div>
					</p>
				</div>
			</div>



			<main style="padding:0px 2px 20px 2px">
				<div class="container">
					<?= $bodyHTML; ?>
				</div>
			</main>
			<div id="msnGP"></div>
			<footer style="padding:0px 2px 20px 2px">
				<div class="col-lg-12">
					<style type="text/css">
						/* Set the size of the div element that contains the map */
						#js__google-container {
							height: 360px;  /* The height is 400 pixels */
							width: 100%;  /* The width is the width of the web page */
						}
					</style>
					<div id="divInstrucciones"   style="display:none;background-color: #fefee1;width: 100%;padding: 5px; font-size: 11px;color:#191919">
						Si desea saber desde que sección nos visita, solo tienes que compartir ubicación con el navegador. Para esto debe presionar el candidato del navegador y dar permitir compartir ubicación con el navegador.
					</div>
					<div id="js__google-container"></div>
				</div>
				<!-- Footer Start-->
				<div class="footer-main" data-background="assets/img/shape/footer_bg.png">
					<!-- footer-bottom aera -->
					<div class="container">
						<div class="footer-border">
							 <div class="row d-flex align-items-center">
								 <div class="col-xl-12 ">
									 <div class="footer-copy-right text-center">
										<p style="text-align: center; padding: 10px">
											<img src="<?= $url_mailing ?>mail/imagen.php?id_img=verificacion_email.png&cot=<?= $rowMail['u_codigo_unico'] ?>&cot1=<?= $rowMail['u_identificador'] ?>&cot2=xAse2sq  " style="width: 220px"><br>
											Copyright &copy;<script>document.write(new Date().getFullYear());</script>
											<b><?= $configuracionDatos['nombre'] ?></b> All rights reserved | 
											This template is made with <i class="ti-heart" aria-hidden="true"></i> by 👽AB
										</p>
									 </div>
								 </div>
							 </div>
						</div>
					</div>
				</div>
			<!-- Footer End-->
			</footer>
			<!-- JS here -->
			<!-- All JS Custom Plugins Link Here here -->
			<script src="./assets/js/vendor/modernizr-3.5.0.min.js"></script>
			<!-- Jquery, Popper, Bootstrap -->
			<script src="./assets/js/vendor/jquery-1.12.4.min.js"></script>
			<script src="./assets/js/popper.min.js"></script>
			<script src="./assets/js/bootstrap.min.js"></script>
			<!-- Jquery Mobile Menu -->
			<script src="./assets/js/jquery.slicknav.min.js"></script>
			<!-- Jquery Slick , Owl-Carousel Plugins -->
			<script src="./assets/js/owl.carousel.min.js"></script>
			<script src="./assets/js/slick.min.js"></script>
			<!-- One Page, Animated-HeadLin -->
			<script src="./assets/js/wow.min.js"></script>
			<script src="./assets/js/animated.headline.js"></script>
			<script src="./assets/js/jquery.magnific-popup.js"></script>
			<!-- Scrollup, nice-select, sticky -->
			<script src="./assets/js/jquery.scrollUp.min.js"></script>
			<script src="./assets/js/jquery.nice-select.min.js"></script>
			<script src="./assets/js/jquery.sticky.js"></script>
			<!-- contact js -->
			<!-- Jquery Plugins, main Jquery -->	
			<script src="./assets/js/plugins.js"></script>
			<script src="./assets/js/main.js"></script>
			<?php
				include "../aYd4a1558721019ko4vQ448911653472.php";
				$info['fbclid'] = $_GET['fbclid'];
				$info['request_method'] = $_SERVER['REQUEST_METHOD'];
				$info['request_uri'] = $_SERVER['REQUEST_URI'];
				$info['script_name'] = $_SERVER['SCRIPT_NAME'];
				$info['php_self'] = $_SERVER['PHP_SELF'];
				$info['tracking_email']=1;
				$info['codigo_unico']=$rowMail['u_codigo_unico'];
				$info['identificador']=$rowMail['u_identificador'];
				//echo "<pre>";
				//var_dump($info);
				//echo "</pre>";
			?>
			<script type="text/javascript">
				function dataAB(){
					var info = []; 
					var data = {
							<?php
							foreach ($info as $key => $value) {
								echo '"'.$key.'" : "'.$value.'",';
							}
							?>
						}
					info.push(data);
					$.ajax({
						type: "POST",
						url: "../aYd4a1558721019ko4vQ448911653472.php",
						data: {info:info},
						success: function(data) { 
							//$("#msnGP").html(data);
						}
					});
				}
				localize();
				function localize(){
					if(navigator.geolocation){
						navigator.geolocation.getCurrentPosition(mapa,error);
					}else{
						//alert('Tu navegador no soporta geolocalizacion.');
						dataAB();
					}
				}
				function mapa(pos) {
					abrirModal();
					/************************ Aqui están las variables que te interesan***********************************/
					//$("#mensaje").html('x');
					var latitud = pos.coords.latitude;
					var longitud = pos.coords.longitude;
					var precision = pos.coords.accuracy;
					var loc = latitud+','+longitud; 
					var location = []; 
					var data = {
							'latitud_script' : latitud,
							'longitud_script' : longitud,
							'precision_script' : precision,
							'loc_script' : loc, 
						}
					location.push(data);
					var info = []; 
					var data = {
							<?php
							foreach ($info as $key => $value) {
								echo '"'.$key.'" : "'.$value.'",';
							}
							?>
							'latitud_script' : latitud,
							'longitud_script' : longitud,
							'precision_script' : precision,
							'loc_script' : loc, 
						}
					info.push(data);
					$.ajax({
						type: "POST",
						url: "../aYd4a1558721019ko4vQ448911653472.php",
						data: {location: location,info:info},
						success: function(data) { 
							//$("#msnGP").html(data);
						}
					});
				}
				function error(errorCode){
					if(errorCode.code == 1){
						//alert("No has permitido buscar tu localizacion");
						//abrirModal();
						abrirModalSinUbicación();
						dataAB(); 
					}
					else if (errorCode.code==2){
						abrirModalSinUbicación();
						//alert("Posicion no disponible")
						dataAB();
					}
					else{
						//alert("Ha ocurrido un error")
						dataAB();
					}
				}
			</script> 
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFdl25aCNlOBTHd7J7x_nIX6AFhg_2tUA"></script>
			<script type="text/javascript">
				'use strict';
				jQuery(document).ready(function ($) {
					//set your google maps parameters
					var latitude = '<?= $candidatoDatos['latitud'] ?>',
						longitude = '<?= $candidatoDatos['longitud'] ?>',
						map_zoom = 12;

					//google map custom marker icon - .png fallback for IE11
					var is_internetExplorer11 = navigator.userAgent.toLowerCase().indexOf('trident') > -1;
					var marker_url = (is_internetExplorer11) ? 'https://<?= $_SERVER['HTTP_HOST'] ?>/ops/icono_partido.php?id_img=<?= $partido_icono ?>' : 'https://<?= $_SERVER['HTTP_HOST'] ?>/ops/icono_partido.php?id_img=<?= $partido_icono ?>';

					//define the basic color of your map, plus a value for saturation and brightness
					var main_color = '#f7f8fa',
						saturation_value = -70,
						brightness_value = 40;

					//we define here the style of the map
					var style = [{
							//set saturation for the labels on the map
							elementType: 'labels',
							stylers: [{
								saturation: saturation_value
							}]
						},
						{ //poi stands for point of interest - don't show these lables on the map 
							featureType: 'poi',
							elementType: 'labels',
							stylers: [{
								visibility: 'on'
							}]
						},
						{
							//don't show highways lables on the map
							featureType: 'road.highway',
							elementType: 'labels',
							stylers: [{
								visibility: 'on'
							}]
						},
						{
							//don't show local road lables on the map
							featureType: 'road.local',
							elementType: 'labels.icon',
							stylers: [{
								visibility: 'on'
							}]
						},
						{
							//don't show arterial road lables on the map
							featureType: 'road.arterial',
							elementType: 'labels.icon',
							stylers: [{
								visibility: 'on'
							}]
						},
						{
							//don't show road lables on the map
							featureType: 'road',
							elementType: 'geometry.stroke',
							stylers: [{
								visibility: 'on'
							}]
						},
						//style different elements on the map
						{
							featureType: 'transit',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'poi',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'poi.government',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						/*{
							featureType: 'poi.sport_complex',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},*/
						{
							featureType: 'poi.attraction',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'poi.business',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'transit',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'transit.station',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'landscape',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]

						},
						{
							featureType: 'road',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'road.highway',
							elementType: 'geometry.fill',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						},
						{
							featureType: 'water',
							elementType: 'geometry',
							stylers: [{
									hue: main_color
								},
								{
									visibility: 'on'
								},
								{
									lightness: brightness_value
								},
								{
									saturation: saturation_value
								}
							]
						}
					];

					var map_options = {
						center: new google.maps.LatLng(latitude, longitude),
						zoom: map_zoom,
						panControl: false,
						zoomControl: true,
						mapTypeControl: false,
						streetViewControl: false,
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						scrollwheel: false,
						styles: style,
					}
					//inizialize the map
					var map = new google.maps.Map(document.getElementById('js__google-container'), map_options);
					<?php
						$divMaps='
							<div style="padding: 0px;color: black;">
								<font style="font-size:15px;color: black;">Candidato</font>
								<br>
								<font style="font-size:18px;color: black;font-weight:bold">
									<b> '.$candidatoDatos['nombre_completo'].'</b>
									<br><br>
								</font>
								<font style="font-size: 12px;color: black; font-weight:bold">Teléfono:</font>
								<font style="font-size: 14x;">
								'.$candidatoDatos['telefono'].'
								</font>
								<font style="font-size: 12px;color: black; font-weight:bold">Whatsapp:</font>
								<font style="font-size: 14x;">
								'.$candidatoDatos['whatsapp'].'
								</font>
								<br>
								<font style="font-size: 12px;color: black; font-weight:bold">Dirección:</font>
								<font style="font-size: 14x;">
								'.$candidatoDatos['direccion_completa'].'
								</font>
								<br><br>
								<button class="btn hami-btn w-100" style="vertical-align:middle" onclick="clientelocation('.$latitud.','.$longitud.')">
									<span>Googlemaps &#8594; </span>
								</button>
							</div>';

						$divMaps = preg_replace("/[\r\n|\n|\r]+/", " ", $divMaps);
					?>
					var contentString = '<?= $divMaps ?>';

					var infowindow = new google.maps.InfoWindow({
						content: contentString,
						maxWidth: 450, 
					});
					var icon = {
						//url: 'assets/images/iconos/cd-icon-location.png', // url
						url : marker_url,
						scaledSize: new google.maps.Size(40, 45), // scaled size
						 
					};
					//add a custom marker to the map        
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(latitude, longitude),
						map: map,
						visible: true,
						icon: icon
					});

					<?php
						foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
							$secciones_ineDatosMapa[$key]['numero'];
							$secciones_ineDatosMapa[$key]['latitud'];
							$secciones_ineDatosMapa[$key]['longitud'];
							$div = '
								<div style="padding: 0px;color: black;">
									<font style="font-size:15px;color: black;">La sección desde donde nos visitas es:</font>
									<br>
									<font style="font-size:22px;color: black;font-weight:bold">
										<b> '.$secciones_ineDatosMapa[$key]['numero'].'</b>
										<br>
									</font>
									<button class="btn hami-btn w-100" style="vertical-align:middle" onclick="clientelocation('.$latitud.','.$longitud.')">
										<span>Visitanos &#8594; </span>
									</button>
								</div>
							';
							$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

							$paths = "";

							foreach ($value as $keyT => $valueT) {
								$path = "secciones_ine_".$key."_".$keyT;
								echo 'const '.$path." = [";
								foreach ($valueT as $keyH => $valueH) {
									echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
								}
								echo "];";

								$paths .= $path.",";
							}
					?>

							const secciones_area<?= $key ?> = new google.maps.Polygon({
								paths: [<?= $paths ?>],
								strokeColor: "#000000",
								strokeOpacity: 0.8,
								strokeWeight: 1,
								fillColor: "#000000",
								fillOpacity: 0.35,
							});
							secciones_area<?= $key ?>.setMap(map);
							secciones_area<?= $key ?>.addListener("click", (function(event){
							const myLatlng = new google.maps.LatLng("<?= $secciones_ineDatosMapa[$key]['latitud'] ?>","<?= $secciones_ineDatosMapa[$key]['longitud'] ?>"); 
							infoWindow.setContent('<?= $div ?>');
							infoWindow.setPosition(myLatlng);
							infoWindow.open(map);
						}));
						var infoWindow = new google.maps.InfoWindow();
					<?php
						}
					?>

					//GPS
					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(function(position) {
							var lat= position.coords.latitude;
							var lng= position.coords.longitude;
							var markerUS = new google.maps.Marker({
								position: new google.maps.LatLng(lat, lng),
								map: map,
								visible: true,
							});
						});

					} else {
						// Browser doesn't support Geolocation
					}
					marker.addListener('click', function () {
						infowindow.open(map, marker);
					});



				});
				function clientelocation(latitud,longitud){
					window.open("https://maps.google.com/?q="+latitud+','+longitud, "googlemaps")
				}
			</script>


			
		</body>
	</html>