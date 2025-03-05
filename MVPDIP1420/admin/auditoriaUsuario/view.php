<?php
	include '../functions/security.php';
	include '../functions/configuracion.php';
	include '../functions/usuario_permisos.php';
	include '../functions/usuarios.php';
	include '../functions/log_usuarios.php';
	$enlace_actual = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$enlace_actual = str_replace("index.php", "", $enlace_actual);
	$urlout = str_replace("../admin/index.php", "", $enlace_actual);
	
	@session_start(); 
	$Fecha=date('Y-m-d');
	$config=configuracion();
	$usuarioWeb=usuarios(); 
	if($config['logo']==""){
		$logo= $enlace_actual."../configuracion/img/logo.png";
		$logox="../configuracion/img/logo.png";
	}else{
		$logo= $enlace_actual."../configuracion/img/logo_over.png";
		$logox="../configuracion/img/logo_over.png";
	}
?>
		<!DOCTYPE html>
		<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
		<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
		<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
		<!--[if gt IE 8]><!--> <html lang="es" class="no-js"> <!--<![endif]-->
			<head>
				<!-- meta character set -->
				<meta name="google-site-verification" content="GjS07HKs8tMjTRTh2XEsr3LpbJUIhaJ7rC-yS0HzmNM" />
				<meta charset="utf-8">
				<!-- Always force latest IE rendering engine or request Chrome Frame -->
				<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
				<title>SADA - Sistema Adminsitrador de Agencias de viajes</title>
				<!-- Meta Google -->

				<meta name="google" content="notranslate" />
				<!-- Meta Description -->
				<meta name="description" content="SADA - Sistema Adminsitrador de Agencias de viajes">
				<meta name="keywords" content="SADA - Sistema Administrador de Agencias de viajes, travel administration">
				<meta name="author" content="Alejandro Cambrano">

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
				<meta property="og:title" content="SADA - Sistema Adminsitrador de Agencias de viajes" />
				<meta property="og:description" content="SADA - Sistema Administrador de Agencias de viajes, travel administration">
				<meta property="og:image" content="<?= $logox ?>" />
				
				<!-- Mobile Specific Meta -->
				<meta name="viewport" content="width=device-width, initial-scale=1">
				

				<link rel="stylesheet" type="text/css" href="../css/style.css">
				<link rel="stylesheet" type="text/css" href="../css/body.css">
				<link rel="stylesheet" type="text/css" href="../css/modulos.css">
				<link rel="stylesheet" type="text/css" href="../css/dashboard.css">
				<link rel="stylesheet" type="text/css" href="../css/inputs.css">
				<link rel="stylesheet" type="text/css" href="../css/reportes.css">

				<!-- Fontawesome Icon font -->
				<link rel="stylesheet" type="text/css" href="../css/main.css">
				<!--
				<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> 
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/../js/jquery.dataTables.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/../js/dataTables.bootstrap.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/../js/dataTables.responsive.min.js"></script>
				<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/2.2.3/../js/responsive.bootstrap.min.js"></script>-->

				<script type="text/javascript" language="javascript" src="../js/jquery.js"></script> 
				<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js"></script>
				<script type="text/javascript" language="javascript" src="../js/dataTables.bootstrap.min.js"></script>
				<script type="text/javascript" language="javascript" src="../js/dataTables.responsive.min.js"></script>
				<script type="text/javascript" language="javascript" src="../js/responsive.bootstrap.min.js"></script>


				<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="../css/fixedHeader.bootstrap.min.css">
				<link rel="stylesheet" type="text/css" href="../css/responsive.bootstrap.min.css">

				<!--   SELECT SEARCH   -->
				<link rel="stylesheet" type="text/css" href="../css/select2.min.css">
				<script type="text/javascript" language="javascript" src="../js/select2.min.js"></script>


				<!--  JS time  -->
				<script type="text/javascript" language="javascript" src="../js/jquery-ui.js"></script>
				<script type="text/javascript" language="javascript" src="../js/datepicket_es.js"></script>
				<script type="text/javascript" language="javascript" src="../js/jquery.timepicker.js"></script>
				<link rel="stylesheet" type="text/css" href="../css/jquery.timepicker.css">
				<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">


				<!--   ChartJs   -->
				<script type="text/javascript" language="javascript" src="../js/Chart.bundle.min.js"></script>


				<!--   Menu Principal   -->
				<script type="text/javascript" language="javascript" src="../jsSistema/menu.js"></script>
				<script type="text/javascript" language="javascript" src="../jsSistema/form.js"></script>
				<!-- Essential jQuery Plugins
				================================================== -->
				<!-- Main jQuery -->
				<!-- Twitter Bootstrap -->
				<script type="text/javascript" language="javascript" src="../js/bootstrap.min.js"></script>
				<!-- onscroll animation -->
				<script type="text/javascript" language="javascript" src="../js/wow.min.js"></script>
				<style type="text/css">
					html {
						min-height: 100%;
						position: relative;
					}
					body {
							margin: 0;
  							margin-bottom: 40px;
							font-family: 'Avenir Next';
							src: url("../css/fonts/AvenirNextLTPro-BoldCn1.otf");
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
							src: url('../css/fonts/AvenirNextLTPro-BoldCn1.otf');
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
						background-color: hsla(0, 100%, 90%, 0.3);;
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
					@media screen and (max-width: 820px) {
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
					.btn-info,.btn-danger{
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
					@media only screen and (max-width:620px) {
						/* For mobile phones: */
						.btnText{
							display: inline-block;
						}
						.btnImage{
							display: none;
							height: 20px;
						}
					}
				</style>
			</head>

			<body id="body">
				 
				 
				<!--
				Fixed Navigation
				==================================== -->
				<header id="navigation" class="navbar-inverse navbar-fixed-top animated-header">
				</header>
				<!--
				End Fixed Navigation
				==================================== -->
				
				<main>
					<!-- Testimonial section -->
						<div class="overlay">
							<div class="container">
								<div class="managerticketbody" style="margin-top: 20px;" >
									<div id="homebody">
										 
											<?php
											$id=$_GET['cot']; 
											$logo_usuarioDatos=logo_usuarioDatos($id);

											$id=$logo_usuarioDatos['id_columna'];
											$id_usuario=$logo_usuarioDatos['id_usuario'];
											$tabla=$logo_usuarioDatos['tabla'];
											$operacion=$logo_usuarioDatos['operacion'];
											$nombre_usuario=$logo_usuarioDatos['nombre_usuario'];
											$fechaR=$logo_usuarioDatos['fechaR'];


											//var_dump($logo_usuarioDatos);
											$tabla;
											include "modulos/{$tabla}.php"; 
											
											?> 
									</div>
								</div>
							</div>
						</div>
					<!-- end Testimonial section -->
				</main>
				<footer>

						Copyright © <?= date(Y); ?> Alien Web Service.<br>
								All Rights Reserved.
				</footer>
			</body>
		</html>