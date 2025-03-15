<?php
    $partido_logo = "SIN PARTIDO";

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
	<head>
		<!-- meta character set -->
		<meta name="google-site-verification" content="+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34="/>
				<meta charset="utf-8">
				<!-- Always force latest IE rendering engine or request Chrome Frame -->
				<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
				<title>Hola!</title>
				<!-- Meta Google -->

				<meta name="google" content="notranslate" />
				<!-- Meta Description -->
				<meta name="description" content="Credencial Digital de militante autorizado.">
				<meta name="keywords" content="Credencial Digital de militante autorizado.">
				<meta name="author" content="AB Soluciones">

				<!-- Iconos -->
				<link rel="apple-touch-icon" sizes="57x57" href="img/foto_anom.png" />
				<link rel="apple-touch-icon" sizes="114x114" href="img/foto_anom.png" />
				<link rel="apple-touch-icon" sizes="72x72" href="img/foto_anom.png" />
				<link rel="apple-touch-icon" sizes="144x144" href="img/foto_anom.png" />
				<link rel="apple-touch-icon" sizes="60x60" href="img/foto_anom.png" />
				<link rel="apple-touch-icon" sizes="120x120" href="img/foto_anom.png" />
				<link rel="apple-touch-icon" sizes="76x76" href="img/foto_anom.png" />
				<link rel="apple-touch-icon" sizes="152x152" href="img/foto_anom.png" />
				<link rel="icon" type="image/png" sizes="196x196" href="img/foto_anom.png" />
				<link rel="icon" type="image/png" sizes="96x96" href="img/foto_anom.png" />
				<link rel="icon" type="image/png" sizes="32x32" href="img/foto_anom.png" />
				<link rel="icon" type="image/png" sizes="16x16" href="img/foto_anom.png" />
				<link rel="icon" type="image/png" sizes="128x128" href="img/foto_anom.png" />
				<meta name="application-name" content="&nbsp;"/>
				<meta name="msapplication-TileColor" content="#FFFFFF" />
				<meta name="msapplication-TileImage" content="img/foto_anom.png" />
				<meta name="msapplication-square70x70logo" content="img/foto_anom.png" />
				<meta name="msapplication-square150x150logo" content="img/foto_anom.png" />
				<meta name="msapplication-wide310x150logo" content="img/foto_anom.png" />
				<meta name="msapplication-square310x310logo" content="img/foto_anom.png" />


				<meta property="og:url" content="<?= $urlout ?>" />
				<meta property="og:locale:alternate" content="es_ES" />
				<meta property="og:type" content="website" />
				<meta property="og:title" content="Credencial Digital de militante autorizado." />
				<meta property="og:description" content="Credencial Digital de militante autorizado.">
				<meta property="og:image" content="img/foto_anom.png" />
				
				<!-- Mobile Specific Meta -->
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<style>
		/* go on then, styles go here.. knock yourself out! */


		/*
		* Envato Remix 2 Submission
		* Author : Surjith S M
		* URL: http://themeforest.net/user/surjithctly
		* version: 1.0
		* Last Updated : 16 June 2015
		*/


		/*Web fonts please..*/

		@import url(https://fonts.googleapis.com/css?family=Roboto:500,300,700,400);

		/*Lets start*/

		html,
		body {
		min-height: 100%;
		}

		.b222ody {
		background: #3b4864 url('backgrounds/X3wxCv8.png') no-repeat center;
		background-size: cover;
		font-family: 'Roboto', sans-serif;
		font-weight: 300;
		color: #535353;
		}
		body {
		background: #B5B2B2 ;
		font-family: 'Roboto', sans-serif;
		font-weight: 300;
		color: #535353;
		}

		p {
		font-family: 'Roboto', sans-serif;
		line-height: 1.6;
		text-align: center;
		font-size: 18px;
		font-weight: 400;
		}


		/*Oh the Card. Here you go*/

		.profile-card {
			margin: 100px auto;
			margin-top: 5%;
			max-width: 400px;
			background: #FFF;
			border-radius: 5px;
			overflow: hidden;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
			animation: fadeIn 2s;
			transition: all 0.6s ease;
			transform-style: preserve-3d;
		}

		.profile-ca22rd header a {
			display: block;
			background: #ccc url('header/header.png') no-repeat;
			background-size: cover;
			min-height: 220px;
			margin-bottom: 70px;
			position: relative;
			transition: all 2s ease;
		}
		.profile-card header a {
			background: #<?= $color_background ?> url('img/foto_anom.png') no-repeat;
			background-size:contain;
			background-position: center;
			display: block;
			min-height: 180px;
			margin-bottom: 10px;
			position: relative;
			transition: all 2s ease;
		}

		.profile-card header a:hover {
			transform: scale(1.02);
		}


		/*umm The Avatar.. Swoosh!! */

		.profile-card header img {
			border: solid 3px #FFF;
			display: block;
			border-width: 4px;
			-webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.20);
			box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.20);
			position: absolute;
			bottom: 20px;
			left: 50%;
			max-width: 100px;
			transform: translateX(-50%);
		}

		.profile-card header h1,
		.profile-card header h2 {
			margin: 5px 30px;
			text-align: center;
		}

		.profile-card header h1 {
			font-family: 'Roboto', sans-serif;
			font-weight: 500;
			color: #4b4b4b;
		}

		.profile-card header h2 {
			font-family: 'Roboto', sans-serif;
			font-weight: 400;
			font-size: 14px;
			text-transform: uppercase;
			color: #aeaeae;
		}


		/*You.. there.. bio*/

		.profile-bio {
			margin: 0px 10px 30px 10px;
		}


		/*Be Social*/

		.profile-social-links {
			list-style: none;
			display: table;
			width: 100%;
			border-top: 1px solid #ededed;
			margin: 0;
			padding: 0;
		}

		.profile-social-links li {
			display: table-cell;
			min-width: 1px;
		}

		.profile-social-links li a {
			padding: 20px 5px;
			display: block;
			text-align: center;
			border-left: 1px solid #ededed;
			transition: all 0.5s ease;
		}

		.profile-social-links li:first-child a {
			border-left: 0;
		}

		.profile-social-links li img {
			max-width: 30px;
			filter: grayscale(100%);
			opacity: 0.5;
			transition: all 0.5s ease;
		}

		.profile-social-links li:last-child img {
			opacity: 0.2;
		}

		.profile-social-links a:hover {
			background: #f6f6f6;
		}

		.profile-social-links a:hover img {
			filter: grayscale(0%);
			opacity: 1;
		}


		/* Animations Area 
		Warning: Entry Restricted
		*/

		@keyframes fadeIn {
		0% {
			opacity: 0;
		}
		100% {
			opacity: 1;
		}
		}
		@media screen and (max-width: 820px) {
				.profile-card{
				width: 100%; 
				max-width:;
				}
				
			}
	</style>
	</head>
	<body id="body">
		<!-- this is the markup. you can change the details (your own name, your own avatar etc.) but don’t change the basic structure! -->
		
		<center>
			<h1>Credencial Digital</h1>
		</center>
		<aside class="profile-card">
				<header>
						<!-- here’s the avatar -->
						<a href="#">
						<?php
						if($fotografia ==""){
							?>
							<img src="img/foto_anom.png" alt="foto" >
							<?php
						}else{
							?>
							<img src="<?= $image ?>" alt="foto" >
							<?php
						}
						?>
						</a>
				</header>
				<div class="profile-bio">
					<center>
						<div style="width:100%;">
                            Si deseas unirte a nosotros, por favor habla con nuestro personal. ¡Gracias!
						</div>
					</center>
				</div>
		</aside>
		<!-- that’s all folks! -->
		<script src="js/jquery-3.3.1.min.js"></script>
	</body>
</html>