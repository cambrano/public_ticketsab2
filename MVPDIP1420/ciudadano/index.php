<?php
	header('Cache-Control: max-age=84600');
	@session_start(); 
	setcookie("Az801AB","",false,"/",false);
	setcookie("Az801AB","",false,"/",false);

	//include 'functions/configuracion.php';
	//include 'functions/usuario_permisos.php';
	//include 'functions/usuarios.php'; 

	include __DIR__.'/functions/configuracion.php';
	include __DIR__.'/functions/efs.php';
	include __DIR__.'/functions/usuarios.php';
	include __DIR__.'/functions/security.php';
	include __DIR__.'/functions/tool_xhpzab.php';

	//! Cookie de base de datos script
	setcookie("AB32BA51", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));


	$enlace_actual = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$enlace_actual = str_replace("index.php", "", $enlace_actual);
	$urlout = str_replace("/admin/index.php", "", $enlace_actual);

	$Fecha=date('Y-m-d');
	$nombre_plataforma_web = 'MONITOR - WEB APP';

	$length=16;
	$pageService=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length).time();
	setcookie("pageService",$pageService,time()+(60*60*8),"/",false);
	$config=configuracion();

	if($_COOKIE['id_usuario']==''){
		$logo = $logox= "https://".$_SERVER['HTTP_HOST'].'/assets/iconos/logo.png';
		$nombre_plataforma_web = date("H:i:s").'';
		$nombre_plataforma_descripcion = '';
	}else{
		$logo = $logox= "https://".$_SERVER['HTTP_HOST'].'/ops/imagen.php?id_img=logo_principal.png';
		$nombre_plataforma_web = 'MONITOR - WEB APP';
		$nombre_plataforma_descripcion = ', para el manejo de información';
	}
	$usuarioWeb=usuarios(); 
	$vencimientoSistema=vencimientoSistema();
	//$logo = $logox=" data:image/png;base64,".$base64;
	//echo '<img src="'.$logox.'" alt="Mi Imagen">';die;
?>
		<!DOCTYPE html>
		<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
		<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
		<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
		<!--[if gt IE 8]><!--> <html lang="es" class="no-js"> <!--<![endif]-->
			<head>
				<script type="text/javascript">
					ventana = self.window.name;
					if(ventana == 'MainWindow'){
						// no hace nada
						//window.open('index.php', '<?= $pageService ?>')
					}else{
						//guarda la cookie para que utilicemos 
					}
					console.log(ventana);
					//self.window.name = "blah";
				</script>
				<!-- meta character set -->
				<meta name="google-site-verification" content="+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34="/>
				<meta charset="utf-8">
				<!-- Always force latest IE rendering engine or request Chrome Frame -->
				<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
				<title><?= $nombre_plataforma_web ?></title>
				<!-- Meta Google -->

				<meta name="google" content="notranslate" />
				<!-- Meta Description -->
				<meta property="og:title" content="<?= $nombre_plataforma_web ?>" />
				<meta property="og:description" content="<?= $nombre_plataforma_web ?>">
				<meta name="keywords" content="<?= $nombre_plataforma_web ?>, ">
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
				<meta property="og:title" content="<?= $nombre_plataforma_web ?>" />
				<meta property="og:description" content="<?= $nombre_plataforma_web.$nombre_plataforma_descripcion ?>">
				<meta property="og:image" content="<?= $logox ?>" />
				
				<!-- Mobile Specific Meta -->
				<meta name="viewport" content="width=device-width, initial-scale=1">
				

				<link rel="stylesheet" type="text/css" href="css/style.css">
				<link rel="stylesheet" type="text/css" href="css/body.css">
				<link rel="stylesheet" type="text/css" href="css/modulos.css">
				<link rel="stylesheet" type="text/css" href="css/dashboard.css">
				<link rel="stylesheet" type="text/css" href="css/inputs.css">
				<link rel="stylesheet" type="text/css" href="css/reportes.css">

				<!-- Fontawesome Icon font -->
				<link rel="stylesheet" type="text/css" href="css/main.css">
				<!--
				<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> 
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>-->

				<script type="text/javascript" language="javascript" src="js/jquery.js"></script> 
				<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
				<script type="text/javascript" language="javascript" src="js/dataTables.bootstrap.min.js"></script>
				<script type="text/javascript" language="javascript" src="js/dataTables.responsive.min.js"></script>
				<script type="text/javascript" language="javascript" src="js/responsive.bootstrap.min.js"></script>


				<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="css/fixedHeader.bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="css/responsive.bootstrap.min.css">

				<!--   SELECT SEARCH   -->
				<link rel="stylesheet" type="text/css" href="css/select2.min.css">
				<script type="text/javascript" language="javascript" src="js/select2.min.js"></script>

				
				<link rel="stylesheet" href="css/bootstrap-select.css">
				<script src="js/bootstrap-select.js"></script>
				



				<!--  JS time  -->
				<script type="text/javascript" language="javascript" src="js/jquery-ui.js"></script>
				<script type="text/javascript" language="javascript" src="js/datepicket_es.js"></script>
				<script type="text/javascript" language="javascript" src="js/jquery.timepicker.js"></script>
				<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">
				<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">


				<!--   ChartJs   -->
				<script type="text/javascript" language="javascript" src="js/Chart.bundle.min.js"></script>
				<script type="text/javascript" language="javascript" src="js/Chart.roundedBarCharts.min.js"></script>
				



				<!--   Menu Principal   -->
				<script type="text/javascript" language="javascript" src="jsSistema/menu.js"></script>
				<script type="text/javascript" language="javascript" src="jsSistema/submenu.js"></script>
				<script type="text/javascript" language="javascript" src="jsSistema/form.js"></script>

				<link rel="stylesheet" href="css/pro.min.css">

				<script type="text/javascript">
					console.log(
						"%cDetente Amig@!","color:red;font-family:system-ui;font-size:4rem;-webkit-text-stroke: 1px black;font-weight:bold"
					);
					console.log(
						"%cEsta Función es para desarrolladores, si tienes dudas o te interesa el sistema comunícate con nosotros. Pero si quieres o intentas entrar al sistema, si lo logras comunícate y te invitamos la cena :P","color:gray;font-family:system-ui;font-size:1.4rem;-webkit-text-stroke: 1px black;font-weight:bold;padding:10px"
					);
				</script>

				<script src="npm/vue.min.js"></script>
				<script src="npm/apexcharts"></script>
				<script src="npm/vue-apexcharts"></script>

				<style type="text/css">
					html {
						min-height: 100%;
						position: relative;
					}
					body {
							margin: 0;
							margin-bottom: 40px;
							font-family: 'Avenir Next';
							src: url("css/fonts/AvenirNextLTPro-BoldCn1.otf");
							font-family: 'Avenir Next';
							background-color: #fafbfd;
							/*background-image:url('images/bg.png');*/
							background-repeat:no-repeat; 
							background-position:top;
							background-size: cover;
							background-attachment: fixed;
					}
					footer {
							background-color: rgba(0,0,0,.8);
							font-family: 'Avenir Next';
							src: url('css/fonts/AvenirNextLTPro-BoldCn1.otf');
							font-family: 'Avenir Next';
							font-size:8px; 
							text-align:center;
							text-transform: uppercase;
							float: left;
							padding: 10px; 
							letter-spacing: 3px; 
							color:white;
							position: fixed;
							bottom: 0;
							width: 100%; 
							text-align: center;
							z-index: 120;
					}

					.logoHome{
						height:45px;
						cursor: pointer;
						background-color:rgba(255,255,255,0.9);
						float: left;
					}
					.logoHome:hover{
						opacity: 0.5;
						
					}
					.logoHome:active{
						opacity: 0.9;
						
					}
					.botonPrincipal2{
						letter-spacing: 3px;
						width: 40px;
						top:44px;
						text-align: center; 
						float: left;
						font-size: 8px;
						margin-left:33px;
						color: white;
						border-color: red;
						color: white;
						position: absolute;
						background-color: black;
						opacity: 0.5;
						cursor: pointer;
					}
					.botonPrincipal{
						height: 45px;
						line-height: 49px;
						text-align: center; 
						float: left;
						font-size: 10px;
						margin-right:5px;
						margin-left:5px;
					}
					.botonPrincipal span {
						display: inline-block;
						vertical-align: middle;
						line-height: normal;
						color: white;
						padding: 5px;
						cursor: pointer;
						text-transform: uppercase;
					}

					.botonPrincipal span:hover {
						display: inline-block;
						vertical-align: middle;
						line-height: normal;
						color: gray; 
					}
					
					table.dataTable tbody td {
						color: black; 
						/*padding: 10px;*/
						font-size: 8pt;
						text-align: left;
						background-color: white;
						letter-spacing: 2px;
						text-transform: uppercase;
						border: 1px solid gray;
					}
					table.dataTable thead th {
						color: black;
						border: 1px solid gray; 
						padding: 10px;
						font-size: 8px;
						letter-spacing: 2px;
						font-weight: 10px;
						text-align: left;
						background-color: white;
						text-transform: uppercase;
					}
					table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr[role="row"]>th:first-child:before{
						top:7px;
						letter-spacing: 0px;
					}
					/*
					.dataTables_wrapper {
						color: black; 
						background-color: white;
						padding: 10px;
						text-transform: uppercase;
						 
						letter-spacing: 2px;
						font-weight: 900;
						text-align: left;
						widows: 100%;
					}
					*/
					table.dataTable {
							clear: both;
							margin-top: 6px !important;
							margin-bottom: 6px !important;
							max-width: none !important;
							border-collapse: separate !important;
							border-spacing: 0;
							color: black;
					}
					.dataTables_paginate{
						width: 100%;
						font-size: 8pt;
					}
					table.dataTable tr.odd td{ 
						background-color: #E2E4FF; cursor: pointer; 
					} 
					table.dataTable tr.even td { 
						background-color: white; cursor: pointer; 
					}
					table.dataTable tr:hover td {
						background-color: #B8B8B8;
					}
					.responsive {
						width: 40%;
						height: auto;
					}
					@media screen and (max-width: 820px) {
						.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
							width: 100%
						}
						.responsive {
							width: 100%;
							height: auto;
						}
						footer{
							position: absolute;
						}
						li.paginate_button.previous {
							display: inline;
						}
					 
						li.paginate_button.next {
							display: inline;
						}
					 
						li.paginate_button {
							display: none;
						}
						.dataTables_info{ 
							height: 50px; 
							overflow: hidden; ;
							display:none;

						}
						.btn btn-primary{
							height: 40px
						}
						.botonPrincipal span {
							display: inline-block;
							vertical-align: middle;
							line-height: normal;
							color: white;
							padding: 5px;
							cursor: pointer;
							text-transform: uppercase;
							border-radius: 4px;
							background-color:rgba(0,0,0,.2);
						}
						.select2 {
							width: 100% !important;
						}
					}
					.black_overlay {
						display: none;
						position: absolute;
						top: 0%;
						left: 0%;
						width: 100%;
						height: 100%;
						background-color: black;
						z-index: 2221001;
						-moz-opacity: 0.8;
						opacity: .80;
						filter: alpha(opacity=80);
					}
					.white_content {
						display: none;
						position: absolute;
						text-transform: none;
						top: 5%;
						left: 25%;
						width: 50%;
						height: 50%;
						padding: 16px;
						border: 2px solid white;
						background-color: white;
						z-index: 2221002;
						overflow: auto;
					}
				</style>
				<style type="text/css">
					.btn-primary,.btn-secondary,.btn-success,.btn-danger,.btn-warning,.btn-info,.btn-light,.btn-dark{
						height: 35px;
					}
					.bntImageSize{
						height: 20px;
					}
					.btnImage{
						display: inline-block;
					}
					.btnText{
						display: none;
					}
					@media only screen and (max-width:1200px) {
						/* For mobile phones: */
						.btnText{
							display: inline-block;
						}
						.btnImage{
							display: inline-block;
							height: 20px;
						}
					}
					.submenux{
						text-decoration:underline;
						display:inline-block;
						cursor: pointer;
					}
					.submenux:hover {
						background-color: gray;
						color: white;
					}
					.submenux:active {
						color: gray;
					}
					.opciones_botones{
						width:70vw; 
					}
					.opciones_botones_2{
						width:10vw; 
					}
					.opciones_botones_3{
						width:16vw; 
					}
					.opciones_botones_4{
						width:40vw; 
					}
					.container{
						padding-right: 0px;
						padding-left: 0px;
					}
					@media only screen and (max-width:1600px) {
						.opciones_botones_3{
							width:22vw;
						}

						.opciones_botones_4{
							width:32vw;
						}
					}
					@media only screen and (max-width:1100px) {
						.opciones_botones{
							width:70vw;
						}
						.opciones_botones_2{
							width:14vw;
						}
						.opciones_botones_3{
							width:50vw;
						}
						.opciones_botones_4{
							width:50vw;
						}
					}
					@media only screen and (max-width:820px) {
						.opciones_botones_2{
							text-align: center;
							width:79vw;
						}
						.opciones_botones_3{
							text-align: center;
							width:75vw;
						}
						.opciones_botones{
							text-align: center;
							width:79vw;
						}
						.opciones_botones_4{
							width:79vw;
						}
						.bt_responsive{
							display: block;
							width: 100%;
							margin-bottom: 15px;
							margin-top: 15px;
						}

					}
					@media only screen and (max-width:620px) {
						.opciones_botones_3{
							text-align: center;
							width:75vw;
						}
						.opciones_botones_2{
							text-align: center;
							width:75vw;
						}
						.opciones_botones{
							text-align: center;
							width:75vw;
						}
						.bt_responsive{
							display: block;
							width: 100%;
							margin-bottom: 5px;
							margin-top: 5px;
						}

						
					}
					@media only screen and (max-width:991px) {
						.navbar-header {
							float: none;
						}
						.navbar-left,.navbar-right {
							float: none !important;
						}
						.navbar-toggle {
							display: block;
						}
						.navbar-collapse {
							border-top: 1px solid transparent;
							box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
						}
						.navbar-fixed-top {
							top: 0;
							border-width: 0 0 1px;
						}
						.navbar-collapse.collapse {
							display: none!important;
						}
						.navbar-nav {
							float: none!important;
							margin-top: 7.5px;
						}
						.navbar-nav>li {
							float: none;
						}
						.navbar-nav>li>a {
							padding-top: 10px;
							padding-bottom: 10px;
						}
						.collapse.in{
							display:block !important;
						}
						.container{
							padding-right: 15px;
							padding-left: 15px;
						}
					}
					
					.myButtonCerrar {
						box-shadow: 0px 0px 0px 2px #9fb4f2;
						background:linear-gradient(to bottom, #7892c2 5%, #476e9e 100%);
						background-color:#7892c2;
						border-radius:10px;
						border:1px solid #4e6096;
						display:inline-block;
						cursor:pointer;
						color:#ffffff;
						font-family:Arial;
						font-size:19px;
						padding:5px 20px;
						text-decoration:none;
						text-shadow:0px 1px 0px #283966;
					}
					.myButtonCerrar:hover {
						background:linear-gradient(to bottom, #476e9e 5%, #7892c2 100%);
						background-color:#476e9e;
					}
					.myButtonCerrar:active {
						position:relative;
						top:1px;
					}
					.loader{
						height:100%;
						width:100vw;
						overflow:hidden;
						background-color:rgba(0, 0, 0, 0.8);
						position: fixed;
						z-index:20000;
					}
					.loaderSpin{
						text-align: center;
						height:90px;
						width:90px;
						border:10px solid #45474b;
						border-top-color: #2a88e6;
						position: relative;
						margin: auto;
						top:0;
						bottom:0;
						left:0;
						right:0;
						border-radius:50%;
						animation: spin 1.5s infinite linear;
						
					}
					@keyframes spin {
						100%{
							transform: rotate(360deg)
						}
					}
					.loaderContenedor{
						background-color:rgb(37, 37, 37);;
						height:115px;
						width:255px;
						position: absolute;
						margin: auto;
						top:0;
						bottom:0;
						left:0;
						right:0;
						border-radius: 12px;
						padding: 10px;
						display:block;
						
					}
					.loaderTextRow {
						display: flex;
						text-align:center;
					}

					.loaderTextColText {
						padding:0px 0px 0px 0px;
						flex: 50%;
						color:rgb(77, 150, 223);
						text-align:center;
						text-align: center;
					}
					.loaderTextColSpin {
						flex: 50%;
						color:rgb(77, 150, 223);
						text-align:center;
						text-align: center;
					}
				</style>
			</head>
			<script>
				$(window).on('load',function(){
					$(".loader").fadeOut(3000);
				});
			</script>
			<script>
				function cerrarModal(){
					document.getElementById("light").style.display = "none";
					document.getElementById("fade").style.display = "none";
				}
			</script>
			<body id="body">
				<div class="loader">
					<div class="loaderContenedor">
						<div class="loaderTextRow">
							<div class="loaderTextColText"><img src="<?= $logox ?>" width="90" ></div>
							<div class="loaderTextColSpin"><div class="column"><div class="loaderSpin"></div></div></div>
						</div>
					</div>
					
					
				</div>
				<div class="content">
					<div id="light" class="white_content">
						<button class="myButtonCerrar" onclick="cerrarModal()">Cerrar</button>
						<br>
						<img src="images/promo/1.png" style='width:100%'>
						<img src="images/promo/2.png" style='width:100%'>
						<img src="images/promo/3.png" style='width:100%'>
						<div style='width:100%;padding:20px'>Más información <a href='https://www.sistemaradar.com'>https://www.sistemaradar.com</a></div>
					</div>
					<div id="fade" class="black_overlay"></div>
					<!--
					Fixed Navigation
					==================================== -->
					<header id="navigation" class="navbar-inverse navbar-fixed-top animated-header">
						<div class="container">
							<div class="navbar-header">
								<!-- responsive nav button -->
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<!-- /responsive nav button -->
								<!-- logo -->
								
								<!-- logo -->
								<h1 class="navbar-brand"></h1>
								<img id="homeLogo" class="logoHome" src="<?= $logox; ?>"/>
								<div class="botonPrincipal2" id="home1" ><b>3.1.1</b></div>
								<div class="botonPrincipal" id="cerrarWeb" > <span>Cerrar</span></div>
								<!-- <div class="botonPrincipal" id="backarray" > <span>Regresar</span></div> -->
								<!-- /logo -->
							</div>
							<!-- main nav -->
							<nav class="collapse navbar-collapse navbar-right" role="navigation">
								<?php include "menu.php" ?>
							</nav>
							<!-- /main nav -->
							
						</div>
					</header>
					<!--
					End Fixed Navigation
					==================================== -->
					<?php
						include 'gps_usuarios.php';
					?>
					<main id="main">
						<!-- Testimonial section -->
							<div class="overlay">
								<div class="container">
									<div id="managerticketbody" class="managerticketbody" >
										<div class="mensajeInfo" id="mensajeSistem" style="text-align: center;text-transform: uppercase;" <?= $vencimientoSistema['status_div'] ?> >
											<?= $vencimientoSistema['mensaje']; ?>
										</div>
										<div id="homebody" <?php  $vencimientoSistema['status_home'] ?>>
											<?php
											// Motrar todos los errores de PHP
											//ini_set('error_reporting', E_ALL);
											if($_COOKIE['Paguinasub']== ""){
												setcookie("Paguinasub",encrypt_ab_checkSin("home.php"), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
												include 'home.php';
											}else{
												$pagggggxxx = decrypt_ab_checkSin($_COOKIE['Paguinasub']);
												if (
													strpos($pagggggxxx, 'seccionesIneReportes2021MatrizRentabilidad') === false && 
													strpos($pagggggxxx, 'seccionesIneReportes2018MatrizRentabilidad') === false
													) 
												{
													unset($_SESSION['Paguinasub']);
													unset($_SESSION['id_municipio']);
													unset($_SESSION['id_distrito_local']);
													unset($_SESSION['id_distrito_federal']);
													unset($_SESSION['paguinaId']);
													unset($_SESSION['reporte_Sistema']);
													//echo "No encontrado";
												}
												if (!file_exists($pagggggxxx)) {
													include 'home.php';
												} else {
													//echo "<pre>";
													//var_dump($pagggggxxx);
													//echo "</pre>";
													include $pagggggxxx;
												}
											}
											?>
										</div>
									</div>
								</div>
							</div>
						
						<!-- end Testimonial section -->
					</main>
					<footer>

							Copyright © <?= date(Y); ?> Ideas 👽.<br>
									All Rights Reserved.
					</footer>  
				</div>
				<!-- Essential jQuery Plugins
				================================================== -->
				<!-- Main jQuery -->
				<!-- Twitter Bootstrap -->
				<script type="text/javascript" language="javascript" src="js/bootstrap.min.js"></script>
				<!-- onscroll animation -->
				<script type="text/javascript" language="javascript" src="js/wow.min.js"></script>
			</body>
		</html>
		<?php
		die;
		function getMemoryUsageMBN() {
			$memoryBytes = memory_get_usage();
			$memoryMB = round($memoryBytes / 1024 / 1024, 2);
			return $memoryMB;
		}

		function getMemoryUsageMBP() {
			$memoryBytes = memory_get_peak_usage();
			$memoryMB = round($memoryBytes / 1024 / 1024, 2);
			return $memoryMB;
		}

		// Nombre del archivo CSV
		$csvFile = 'memory_usage.csv';

		// Obtener la memoria usada
		$memoryUsedN = getMemoryUsageMBN();
		$memoryUsedP = getMemoryUsageMBP();

		// Si el archivo CSV no existe, agregar las cabeceras
		if (!file_exists($csvFile)) {
			$header = "Fecha,Hora,Megabytes Normal,Megabytes Picos\n";
			file_put_contents($csvFile, $header);
		}

		// Convertir el valor de memoria a string con punto decimal
		$memoryUsedNStr = str_replace(',', '.', $memoryUsedN);
		$memoryUsedPStr = str_replace(',', '.', $memoryUsedP);

		// Crear una nueva línea de datos (fecha, hora, memoria usada)
		$newLine = date('Y-m-d,H:i:s') . ',' . $memoryUsedNStr. ',' . $memoryUsedPStr . "\n";

		// Agregar la línea al archivo CSV
		file_put_contents($csvFile, $newLine, FILE_APPEND);

		//echo "Memory usage: {$memoryUsed} MB\n";
		?>
		<?php
		function getMemoryUsageMBNSQL() {
			$memoryBytes = memory_get_usage();
			$memoryMB = round($memoryBytes / 1024 / 1024, 2);
			return $memoryMB;
		}

		function getMemoryUsageMBPSQL() {
			$memoryBytes = memory_get_peak_usage();
			$memoryMB = round($memoryBytes / 1024 / 1024, 2);
			return $memoryMB;
		}

		// Nombre del archivo CSV
		$csvFile = 'memory_usage_sql.csv';


		// Obtener la memoria utilizada por MySQL (usando Innodb_buffer_pool_pages_data)
		$result = $conexion->query("SHOW GLOBAL STATUS LIKE 'Innodb_buffer_pool_pages_data'");
		$row = $result->fetch_assoc();
		$innodbBufferPoolPagesData = $row['Value'];

		// Calcular la memoria en MB (usando una estimación de 16KB por página)
		$memoryUsedMysql = $innodbBufferPoolPagesData * 16 / 1024;

		

		// Obtener la memoria usada en PHP
		$memoryUsedN = getMemoryUsageMBNSQL();
		$memoryUsedP = getMemoryUsageMBPSQL();

		// Si el archivo CSV no existe, agregar las cabeceras
		if (!file_exists($csvFile)) {
			$header = "Fecha,Hora,Megabytes Normal,Megabytes Picos,Megabytes MySQL\n";
			file_put_contents($csvFile, $header);
		}

		// Convertir los valores de memoria a string con punto decimal
		$memoryUsedNStr = str_replace(',', '.', $memoryUsedN);
		$memoryUsedPStr = str_replace(',', '.', $memoryUsedP);
		$memoryUsedMysqlStr = str_replace(',', '.', $memoryUsedMysql);

		// Crear una nueva línea de datos (fecha, hora, memoria usada)
		$newLine = date('Y-m-d,H:i:s') . ',' . $memoryUsedNStr . ',' . $memoryUsedPStr . ',' . $memoryUsedMysqlStr . "\n";

		// Agregar la línea al archivo CSV
		file_put_contents($csvFile, $newLine, FILE_APPEND);

		//echo "Memory usage: {$memoryUsed} MB\n";
		?>







