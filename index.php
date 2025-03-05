<?php
	//header("Location: login.php");
	//die;
	header('Cache-Control: max-age=84600');
	$icono = $logox = 'assets/iconos/logo.png';
	$titulo_page = 'Playa del sabor';
	$latitud = '20.669365969640292';
	$longitud = '-87.05745923033206';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic -->
		<meta charset="utf-8"/>
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title><?= $titulo_page ?> - Inicio</title>
		<!--Optiones-->
		<meta property="og:url" content="index.php">
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?= $titulo_page ?> - Inicio" />
		<meta property="og:image" content="<?= $icono ?>" />
		<meta property="og:image:alt" content="<?= $titulo_page ?>." />
		<meta property="og:description" content="<?= $titulo_page ?>">
		<meta property="og:locale:alternate" content="es_MX" />
		<meta property="og:site_name" content="<?= $titulo_page ?>" />
		<meta property="name" content="<?= $titulo_page ?>" />
		<meta itemprop="name" content="<?= $titulo_page ?> - Inicio" />
		<meta itemprop="description" content="<?= $titulo_page ?>">
		<link rel="shortcut icon" href="<?= $icono ?>" type="image/x-icon">
		<!-- Iconos -->
		<link rel="apple-touch-icon" sizes="57x57" href="<?= $icono ?>" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?= $icono ?>" />
		<link rel="apple-touch-icon" sizes="72x72" href="<?= $icono ?>" />
		<link rel="apple-touch-icon" sizes="144x144" href="<?= $icono ?>" />
		<link rel="apple-touch-icon" sizes="60x60" href="<?= $icono ?>" />
		<link rel="apple-touch-icon" sizes="120x120" href="<?= $icono ?>" />
		<link rel="apple-touch-icon" sizes="76x76" href="<?= $icono ?>" />
		<link rel="apple-touch-icon" sizes="152x152" href="<?= $icono ?>" />
		<link rel="icon" type="image/png" sizes="196x196" href="<?= $icono ?>" />
		<link rel="icon" type="image/png" sizes="96x96" href="<?= $icono ?>" />
		<link rel="icon" type="image/png" sizes="32x32" href="<?= $icono ?>" />
		<link rel="icon" type="image/png" sizes="16x16" href="<?= $icono ?>" />
		<link rel="icon" type="image/png" sizes="128x128" href="<?= $icono ?>" />
		<meta name="application-name" content="Ideas y Soluciones AB"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="<?= $icono ?>" />
		<meta name="msapplication-square70x70logos" content="<?= $icono ?>" />
		<meta name="msapplication-square150x150logos" content="<?= $icono ?>" />
		<meta name="msapplication-wide310x150logos" content="<?= $icono ?>" />
		<meta name="msapplication-square310x310logos" content="<?= $icono ?>" />
		<meta name="google" content="notranslate" />
		<meta name="title" content="<?= $titulo_page ?>" />
		<meta name="description" content="<?= $titulo_page ?>">
		<meta name="keywords" content="<?= $titulo_page ?>">
		<meta name="author" content="Ideas">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!--====== Bootstrap css ======-->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<!--====== Line Icons css ======-->
		<link rel="stylesheet" href="assets/css/LineIcons.css">
		<!--====== Magnific Popup css ======-->
		<link rel="stylesheet" href="assets/css/magnific-popup.css">
		<!--====== Default css ======-->
		<link rel="stylesheet" href="assets/css/default.css">
		<!--====== Style css ======-->
		<link rel="stylesheet" href="assets/css/style.css">
		<!--====== FontAwesome css ======-->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
		<script type="text/javascript">
			console.log(
				"%cDetente Amig@!","color:red;font-family:system-ui;font-size:4rem;-webkit-text-stroke: 1px black;font-weight:bold"
			);
			console.log(
				"%cEsta Función es para desarrolladores, si tienes dudas o te interesa el sistema comunícate con nosotros. Pero si quieres o intentas entrar al sistema, si lo logras comunícate y te invitamos la cena :P","color:gray;font-family:system-ui;font-size:1.4rem;-webkit-text-stroke: 1px black;font-weight:bold;padding:10px"
			);
		</script>
		<style>
			.boton_privacidad{
				align-items: center;
				background-color: #FFFFFF;
				border: 1px solid rgba(0, 0, 0, 0.1);
				border-radius: 0.25rem;
				box-shadow: rgba(0, 0, 0, 0.02) 0 1px 3px 0;
				box-sizing: border-box;
				color: rgba(0, 0, 0, 0.85);
				cursor: pointer;
				font-size: 15px;
				padding: 5px;
			}
			.boton_privacidad:hover{
				background-color: #808080;
				color:white;
			}
			.boton_privacidad:active{
				background-color: #5a5a5a;
				color:white;
			}
		</style>
	</head>
	<body>
		<!-- Modal para pedir permiso -->
		<div class="modal" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="permissionModalLabel">Permiso necesario de ubicación</h5>
					</div>
					<div class="modal-body">
						<b>Tu privacidad es importante:</b>
						<br>
						<p style="font-size:12px;line-height: normal;">
							Nos tomamos en serio tu privacidad y seguridad. Tu ubicación solo se utilizará para mejorar tu experiencia en nuestro sitio web, y nunca compartiremos esta información con terceros sin tu consentimiento.	
							Para mostrar tu ubicación, necesitamos acceso a tu geolocalización. Por favor, otorga el permiso para continuar.
						</p>
						<br>
						<b>Por que lo pedimos:</b>
						<br>
						<p style="font-size:12px;line-height: normal;">
							Con tu ubicación, podemos mostrar en el mapa todas las atracciones, restaurantes, playas y otros lugares de interés que están más cerca de ti. Esto te ayudará a planificar tus actividades de manera más eficiente y a encontrar lo que buscas con facilidad.
							<br>
						</p>
						<br>
						<b>Ofrecer recomendaciones personalizadas:</b>
						<br>
						
					
						<b style="padding:0px;color:red" >Como dar permisos en su dispositivo:</b>
						<img style="padding:0px;" src="assets/images/android/android_1.png">
						<img style="padding:0px;" src="assets/images/android/android_2.png">
						<img style="padding:0px;" src="assets/images/android/android_3.png">
						<img style="padding:0px;" src="assets/images/android/android_4.png">
						<br><br>
						<b style="padding:0px;color:red" >Como dar permisos en su navegador de escritorio:</b>
						<img style="padding:0px;" src="assets/images/desktop/desktop_1.png">
						<img style="padding:0px;" src="assets/images/desktop/desktop_2.png">
						<img style="padding:0px;" src="assets/images/desktop/desktop_3.png">
					</div>
				</div>
			</div>
		</div>
		<!--====== HEADER PART START ======-->
		<header class="header-area">
			<div class="navgition navgition-transparent">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<nav class="navbar navbar-expand-lg">
								<a class="navbar-brand" href="#">
								<img style="width: 80px " src="assets/iconos/logo.png" alt="Logo">
								</a>
								<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarOne" aria-controls="navbarOne" aria-expanded="false" aria-label="Toggle navigation">
								<span class="toggler-icon"></span>
								<span class="toggler-icon"></span>
								<span class="toggler-icon"></span>
								</button>
								<div class="collapse navbar-collapse sub-menu-bar" id="navbarOne">
									<ul class="navbar-nav m-auto">
										<li class="nav-item active">
											<a class="page-scroll" href="#home">Inicio</a>
										</li>
										<li class="nav-item">
											<a class="page-scroll" href="#que_es_descripcion">¿Que ofrecemos?</a>
										</li>
									</ul>
								</div>
							</nav>
							<!-- navbar -->
						</div>
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- navgition -->
			<div id="home" class="header-hero bg_cover" style="background-image: url(assets/images/header-bg.jpg);">
				<div class="container" >
					<div class="row justify-content-center">
						<div class="col-xl-8 col-lg-8">
							<div class="header-content text-center">
								<h3 class="header-title" style="text-transform: uppercase;color: red"><?= $titulo_page ?></h3>
								<p class="text" style="color:#df6124;">
									Deléitate con exquisitos mariscos frescos en nuestro restaurante frente al mar con vistas panorámicas impresionantes.<br><br>
									<div style="background-color:rgba(0, 0, 0, 0.0);padding:12px 12px 12px 12px">
										<b style="color: white">Además, ofrecemos nuestros productos frescos del mar a otros restaurantes en busca de calidad y sabor excepcionales.</b>
									</div>
								</p>
								<ul class="header-btn">
									<li><a class="main-btn btn-one" onclick="areaGo('registro_cliente')">¡Soy restaurantero!</a></li>
									<li><a class="main-btn btn-one" href="login.php">¡Ya soy cliente!</a></li>
								</ul>
							</div>
							<!-- header content -->
						</div>
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- header content -->
		</header>
		<!--====== HEADER PART ENDS ======-->
		<!--====== SERVICES PART START ======-->
		<section id="que_es_descripcion" class="services-area">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
						<div class="section-title pb-10">
							<h4 class="title">Mariscos Frescos Sostenibles</h4>
							<p class="text">Explora nuestro surtido de productos del mar, capturados de forma sostenible, que incluye pescados, mariscos y delicias frescas. Nuestra calidad se refleja en cada bocado.</p>
						</div>
						<!-- section title -->
					</div>
				</div>
				<!-- row -->
				<div class="row">
					<div class="col-lg-8">
						<div class="row">
							<div class="col-md-6">
								<div class="services-content mt-40 d-sm-flex">
									<div class="services-icon">
										<i class="fas fa-check"></i>
									</div>
									<div class="services-content media-body">
										<h4 class="services-title">Verifica la Sostenibilidad</h4>
										<p class="text">Nos comprometemos a ofrecer mariscos que cumplen con estándares ambientales para preservar nuestros océanos y recursos.</p>
									</div>
								</div>
								<!-- services content -->
							</div>
							<div class="col-md-6">
								<div class="services-content mt-40 d-sm-flex">
									<div class="services-icon">
										<i class="fas fa-utensils"></i>
									</div>
									<div class="services-content media-body">
										<h4 class="services-title">Consume Variedad</h4>
										<p class="text">Ofrecemos una amplia gama de mariscos frescos y deliciosos, satisfaciendo gustos diversos y preferencias culinarias.</p>
									</div>
								</div>
								<!-- services content -->
							</div>
							<div class="col-md-12">
								<div class="services-content mt-40 d-sm-flex">
									<div class="services-icon">
										<i class="fas fa-handshake"></i>
									</div>
									<div class="services-content media-body">
										<h4 class="services-title">Apoya a Pescadores Locales</h4>
										<p class="text">Colaboramos estrechamente con pescadores locales, impulsando la economía de la comunidad y promoviendo la pesca sostenible.</p>
									</div>
								</div>
								<!-- services content -->
							</div>
						</div>
						<!-- row -->
					</div>
					<!-- row -->
				</div>
				<!-- row -->
			</div>
			<!-- conteiner -->
			<div class="services-image d-lg-flex align-items-center">
				<div class="image">
					<img src="assets/images/playa_sabor.png" alt="Services">
				</div>
			</div>
			<!-- services image -->
		</section>
		<!--====== SERVICES PART ENDS ======-->
		<!--====== PRICING PART START ======-->
		<!--====== SERVICES PART START ======-->
		<section id="registro_cliente" class="pricing-area">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title pb-10">
							<h4 class="title" style="color:red;">Registrate como distribuidor o como nuestro cliente</h4>
							<p class="text"></p>
							<p class="text">Ofrecer nuestros productos marinos es una oportunidad única. Frescura, calidad y variedad garantizadas para destacar como distribuidor o vendedor..</p>
						</div>
					</div>
					<div class="col-lg-12">
						<div id="spam" style="display:none" >
							<label>Dejar esto en blanco</label>
							<input type="text" id="xcode1" name="xcode1" />
							<label>No cambiar esto</label>
							<input type="text" id="xcode2" value="https://" name="xcode2" />
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label for="nombre">Nombre:</label>
							<input type="text" class="form-control" id="nombre" name="nombre" required>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label for="telefono">Teléfono:</label>
							<input type="tel" class="form-control" id="telefono" name="telefono" required>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label for="correo_electronico">Correo Electrónico:</label>
							<input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group">
							<label for="descripcion">Descripción:</label>
							<textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
						</div>
					</div>
					<div class="col-lg-12" style="padding:12px">
						<div class="form-group">
							<input type="button" id="sumbmit" class="btn btn-primary" onclick="guardar()" value="Registrar">
						</div>
					</div>
					<div class="col-lg-12">
						<div id="alerta" class="mt-3">
							<div id="mensaje"></div>
						</div>
					</div>
				</div>
			</div>
			<!-- services image -->
		</section>
		<script>
				function verMasTerminos(){
					// Definimos el elemento div
					var div = document.getElementById("avisoycondiciones"); 
					if (div.style.display === "none") {
						div.style.display = "block";
					} else {
						div.style.display = "none";
					}
					
				}
			</script>
		<section id="registro_cliente" class="services-area" style="padding:0px">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="text-center" style="padding:10px" >
							<button onclick="verMasTerminos()" class="boton_privacidad" >Aviso de privacidad, Política de cookies & Términos y condiciones</button>
						</div>
					</div>
					<div id="avisoycondiciones" class="col-lg-12" style="color:black;padding:10px;display:none">
						<b>Aviso de Privacidad para el Sitio Web de <?= $titulo_page ?><br></b>
						Fecha de última actualización: 2023-06-01<br>
						Este Aviso de Privacidad se aplica al sitio web de <?= $titulo_page ?> y describe cómo recopilamos, usamos y protegemos la información que usted proporciona a través de nuestro sitio web.
						Información que Recopilamos<br><br>
						<b>Podemos recopilar la siguiente información personal a través de nuestro sitio web:</b><br>
						<blockquote style="padding:10px">
						1.-Información de contacto: nombre, dirección de correo electrónico, número de teléfono.<br>
						2.-Información de reservas de viaje: preferencias de viaje, itinerarios, información de pago.<br>
						3.-Datos técnicos: direcciones IP, tipo de navegador, sistema operativo, historial de navegación en nuestro sitio.<br>
						4.-Cookies: utilizamos cookies para mejorar la experiencia del usuario y recopilar información sobre la navegación en nuestro sitio web. Puede encontrar más información en nuestra Política de Cookies.
						Uso de la Información
						</blockquote>
						<b>Utilizamos la información recopilada para los siguientes fines:</b><br>
						<blockquote style="padding:10px">
						1.-Procesar reservas o compra de productos y transacciones de pago.<br>
						2.-Enviar confirmaciones y detalles de su reserva i sy compra.<br>
						3.-Proporcionar información y ofertas sobre nuestros servicios y promociones (con su consentimiento).<br>
						4.-Mejorar la experiencia del usuario en nuestro sitio web.<br>
						5.-Cumplir con requisitos legales y regulaciones aplicables.<br>
						6.-Seguridad de los Datos<br>
						</blockquote>
						Nos comprometemos a proteger la seguridad de su información personal. Hemos implementado medidas de seguridad técnicas y organizativas para proteger sus datos contra el acceso no autorizado, la divulgación y el uso indebido.<br><br>
						<b>Cambios en el Aviso de Privacidad</b><br>
						Nos reservamos el derecho de modificar o actualizar este Aviso de Privacidad en cualquier momento. Cualquier cambio será publicado en esta página, y la fecha de la última actualización se indicará en la parte superior del aviso.<br>
						<br><br>
						<b>Estos son las regulaciones que cumplimos:</b><br>
						-Ley Federal de Protección de Datos Personales en Posesión de los Particulares (LFPDPPP)<br>
						-Ley de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI-CE)<br>
						-Ley General para la Defensa de los Consumidores<br>
					</div>
				</div>
			</div>
			<!-- services image -->
		</section>
		<!--====== SERVICES PART ENDS ======-->
		<!--====== FOOTER PART START ======-->
		<footer id="footer" class="footer-area">
			<style type="text/css">
				/* Set the size of the div element that contains the map */
				#js__google-container {
					height: 400px;  /* The height is 400 pixels */
					width: 100%;  /* The width is the width of the web page */
				}
			</style>
			<div id="js__google-container"></div>
			<div id="msnGP"></div>
			<!-- footer widget -->
			
			<div class="footer-copyright">
				<div class="container">
					<div class="row">
						<div class="col-lg-12">
							<div class="copyright text-center">
								<p class="text">
									Copyright &copy;
									<script>document.write(new Date().getFullYear());</script>
									<b>
										<?= $titulo_page ?></b> All rights reserved | This template is made with <i class="ti-heart" aria-hidden="true"></i> by Ideas 👽
									</p>
							</div>
						</div>
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- footer copyright -->
		</footer>
		<script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
		<script type="text/javascript">
			function areaGo(elementId) {
				var target = $("#" + elementId);
				if (target.length) {
					var sectionPosition = target.offset().top-85;
					$("html, body").animate({ scrollTop: sectionPosition }, "slow");
				}
			}
			function validarEmail(valor) {
				expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if ( !expr.test(valor) ){
					return false;
				}else{
					return true;
				}
			}
			function guardar(){
				document.getElementById("sumbmit").disabled = true;
				$("#mensaje").html("&nbsp");
				var espacios_invalidos= /\s+/g;
				var nombre = document.getElementById("nombre").value; 
				nombrex = nombre.replace(espacios_invalidos, '');
				if(nombrex == ""){
					document.getElementById("nombre").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Nombre Completo requerido");
					$("#alerta").show();
					return false;
				}
				var telefono = document.getElementById("telefono").value; 
				telefonox = telefono.replace(espacios_invalidos, '');
				if(telefonox == ""){
					document.getElementById("telefono").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Teléfono requerido");
					$("#alerta").show();
					return false;
				}
				var correo_electronico = document.getElementById("correo_electronico").value; 
				correo_electronicox = correo_electronico.replace(espacios_invalidos, '');
				if(correo_electronicox == ""){
					document.getElementById("correo_electronico").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("correo_electronico Electrónico requerido");
					$("#alerta").show();
					return false;
				}else{
					if(!validarEmail(correo_electronicox)){
						document.getElementById("correo_electronico").focus(); 
						document.getElementById("sumbmit").disabled = false;
						return false;
					}
				}

				var xcode1 = document.getElementById("xcode1").value;
				xcode1 = xcode1.replace(espacios_invalidos, '');
				var xcode2 = document.getElementById("xcode2").value;
				xcode2 = xcode2.replace(espacios_invalidos, '');

				var formulario = []; 
				var data = {
					'nombre' : nombre,
					'telefono' : telefono,
					'correo_electronico' : correo_electronico,
					'xcode1' : xcode1,
					'xcode2' : xcode2,
				}
				formulario.push(data);
				$.ajax({
					type: "POST",
					dataType : 'json',
					url: "reg_db.php",
					data: {formulario: formulario},
					success: function(data) {
						console.log(data)
						document.getElementById("sumbmit").disabled = false;
						if(data.status==1){
							mensaje = 'Hola!.' + nombre + ', pronto nos pondremos en contacto contigo.';
							$("#mensaje").html("<div class='alert alert-success' role='alert'>"+mensaje+"</div> ");
						}else{
							mensaje = 'Hola!.' + nombre + ', hubo un error, intentelo mas tarde. mil disculpas.';
							$("#mensaje").html("<div class='alert alert-danger' role='alert'>"+mensaje+"</div> ");
						}
						$("#alerta").show();
					}
				});

			}
		</script>
		<script type="text/javascript">
			function dataAB(){
				var info = []; 
				var data = {
						'precision_script' : 0,
						<?php
						foreach ($info as $key => $value) {
							echo '"'.$key.'" : "'.$value.'",';
						}
						?>
					}
				info.push(data);
				$.ajax({
					type: "POST",
					url: "aYd4a1558721019ko4vQ448911653472.php",
					data: {info:info},
					success: function(data) { 
						/*$("#msnGP").html(data);*/
					}
				});
			}
			function showLocation(position) {
				mapa(position)
				$('#permissionModal').modal('hide');
			}
			// Función para manejar errores
			function showError(error) {
				if (error.code === 1) {
					// Si el usuario deniega el permiso, muestra el modal de permiso
					$('#permissionModal').modal('show');
					dataAB();
				} else {
					alert("Error al obtener la ubicación: " + error.message);
					dataAB();
				}
			}

			// Función para obtener la ubicación del usuario
			function getLocation() {
				if (navigator.geolocation) {
					// Si el navegador soporta geolocalización, solicita la ubicación del usuario
					navigator.geolocation.getCurrentPosition(showLocation, showError);
				} else {
					alert("Tu navegador no soporta geolocalización.");
					dataAB();
				}
			}
			function mapa(pos) {
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
					url: "aYd4a1558721019ko4vQ448911653472.php",
					data: {location: location,info:info},
					success: function(data) { 
						/*$("#msnGP").html(data);*/
					}
				});
			}
			// Al cargar la página, intenta obtener la ubicación
			window.onload = getLocation;
		</script> 
		
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiThYdgeDcuxtkGbqy8788NRbeI4wd-5g"></script>
		<script type="text/javascript">
			'use strict';
			jQuery(document).ready(function ($) {
				//set your google maps parameters
					var latitude = '<?= $latitud ?>';
					var longitude = '<?= $longitud ?>';
					var map_zoom = 14;

					//google map custom marker icon - .png fallback for IE11
					var is_internetExplorer11 = navigator.userAgent.toLowerCase().indexOf('trident') > -1;
					var marker_url = (is_internetExplorer11) ? 'assets/iconos/cd-icon-location.png' : 'assets/iconos/cd-icon-location.png';

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
						scaledSize: new google.maps.Size(45, 45), // scaled size
					};
					//add a custom marker to the map        
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(latitude, longitude),
						map: map,
						visible: true,
						icon: icon
					});

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
		<!--====== FOOTER PART ENDS ======-->
		<!--====== BACK TO TOP PART START ======-->
		<a class="back-to-top" href="#"><i class="lni-chevron-up"></i></a>
		<!--====== BACK TO TOP PART ENDS ======-->
		<!--====== jquery js ======-->
		<script src="assets/js/vendor/modernizr-3.6.0.min.js"></script>
		
		<!--====== Bootstrap js ======-->
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/popper.min.js"></script>
		<!--====== Scrolling Nav js ======-->
		<script src="assets/js/jquery.easing.min.js"></script>
		<script src="assets/js/scrolling-nav.js"></script>
		<!--====== Magnific Popup js ======-->
		<script src="assets/js/jquery.magnific-popup.min.js"></script>
		<!--====== Main js ======-->
		<script src="assets/js/main.js"></script>
	</body>
</html>