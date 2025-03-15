<?php
	// Evitar el almacenamiento en caché en el navegador
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	$id_seccion_ine_ciudadano = $_GET['cot'] ?? '';
	$codigo_seccion_ine_ciudadano = $_GET['ck'] ?? '';
	$id_militante_partido = $_GET['hex'] ?? '';
	$tipo = $_GET['tipo'] ?? '';
	if (!empty($codigo_seccion_ine_ciudadano) && !empty($id_seccion_ine_ciudadano) && !empty($id_militante_partido)) {
	    include '../MVPDIP1420/admin/functions/db.php';
	    include '../MVPDIP1420/admin/functions/efs.php';
	    include '../MVPDIP1420/admin/keySistema/key.php';

	    $codigo_seccion_ine_ciudadanox = mysqli_real_escape_string($conexion, $codigo_seccion_ine_ciudadano);
	    $id_seccion_ine_ciudadanox = mysqli_real_escape_string($conexion, $id_seccion_ine_ciudadano);
	    $id_militante_partidox = mysqli_real_escape_string($conexion, $id_militante_partido);

	    if ($stmt = mysqli_prepare($conexion, "
	        SELECT 
	            mp.clave,
	            mp.folio,
	            sic.nombre,
	            sic.apellido_paterno,
	            sic.apellido_materno,
	            sic.fecha_nacimiento,
	            m.municipio,
	            mp.fecha,
	            mp.name,
	            pl.logo AS partido_logo,
	            pl.color_background AS color_background,
	            pl.color_font AS color_font,
	            pl.nombre_corto AS partido_nombre_corto,
	            pl.nombre AS partido_nombre
	            
	        FROM militantes_partidos mp
	        LEFT JOIN partidos_legados pl
	        ON mp.id_partido_legado = pl.id
	        LEFT JOIN secciones_ine_ciudadanos sic
	        ON mp.id_seccion_ine_ciudadano = sic.id
	        LEFT JOIN municipios m
	        ON sic.id_municipio = m.id
	        WHERE sic.id = ? AND sic.codigo_seccion_ine_ciudadano LIKE ? AND mp.id = ?
	        LIMIT 1"
	    )) {
			$codigo_seccion_ine_ciudadanox = $codigo_seccion_ine_ciudadanox . '%'; 
	        mysqli_stmt_bind_param($stmt, "sss", $id_seccion_ine_ciudadanox, $codigo_seccion_ine_ciudadanox, $id_militante_partidox);
	        
	        if (mysqli_stmt_execute($stmt)) {
	            mysqli_stmt_bind_result($stmt, 
	                $clave,
	                $folio,
	                $nombre, 
	                $apellido_paterno, 
	                $apellido_materno,
	                $fecha_nacimiento,
	                $municipio,
	                $fecha,
	                $fotografia,
	                $partido_logo,
	                $color_background,
	                $color_font,
	                $partido_nombre_corto,
	                $partido_nombre,
	            );

	            if (mysqli_stmt_fetch($stmt)) {
	                // Datos obtenidos correctamente
	            } else {
	                $errorDatos = array(
	                    'message' => 'Militante no encontrado!',
	                );
	            }

	            mysqli_stmt_close($stmt);
	        } else {
	            $errorDatos = array(
	                'message' => 'Militante no encontrado statement!',
	                'error' => 'Error en la ejecución del statement: ' . mysqli_stmt_error($stmt)
	            );
	        }
	    } else {
	        $errorDatos = array(
	            'message' => 'Militante no encontrado statement!',
	            'error' => 'Error al preparar el statement: ' . mysqli_error($conexion)
	        );
	    }
	}

	if(!empty($errorDatos)){
		header("Location: index.php ");
		die;
	}
	$mostrarImagenBase64 = mostrarImagenBase64($fotografia);
	$image = "data:image/png;base64,".$mostrarImagenBase64;
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
				<title>Credencial Digital : <?= $nombre ?> <?= $apellido_paterno ?> <?= $apellido_materno ?> </title>
				<!-- Meta Google -->

				<meta name="google" content="notranslate" />
				<!-- Meta Description -->
				<meta name="description" content="Credencial Digital de militante autorizado.">
				<meta name="keywords" content="Credencial Digital de militante autorizado.">
				<meta name="author" content="AB Soluciones">

				<!-- Iconos -->
				<link rel="apple-touch-icon" sizes="57x57" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="apple-touch-icon" sizes="114x114" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="apple-touch-icon" sizes="72x72" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="apple-touch-icon" sizes="144x144" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="apple-touch-icon" sizes="60x60" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="apple-touch-icon" sizes="120x120" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="apple-touch-icon" sizes="76x76" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="apple-touch-icon" sizes="152x152" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="icon" type="image/png" sizes="196x196" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="icon" type="image/png" sizes="96x96" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="icon" type="image/png" sizes="32x32" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="icon" type="image/png" sizes="16x16" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<link rel="icon" type="image/png" sizes="128x128" href="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<meta name="application-name" content="&nbsp;"/>
				<meta name="msapplication-TileColor" content="#FFFFFF" />
				<meta name="msapplication-TileImage" content="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<meta name="msapplication-square70x70logo" content="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<meta name="msapplication-square150x150logo" content="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<meta name="msapplication-wide310x150logo" content="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				<meta name="msapplication-square310x310logo" content="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />


				<meta property="og:url" content="<?= $urlout ?>" />
				<meta property="og:locale:alternate" content="es_ES" />
				<meta property="og:type" content="website" />
				<meta property="og:title" content="Credencial Digital de militante autorizado." />
				<meta property="og:description" content="Credencial Digital de militante autorizado.">
				<meta property="og:image" content="../ops/logo_partido.php?id_img=<?= $partido_logo ?>" />
				
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
			background: #<?= $color_background ?> url('../ops/logo_partido.php?id_img=<?= $partido_logo ?>') no-repeat;
			background-size:contain;
			background-position: center;
			display: block;
			min-height: 220px;
			margin-bottom: 70px;
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
			bottom: -40px;
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
			margin: 30px;
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
				<!-- bit of a bio; who are you? -->
				<div class="profile-bio">
					<center>
						<div style="width:100%;">
							<table style="width:100%">
								<tr>
									<td style="font-size:14px;background-color:#<?= $color_background ?>;color:white;padding-left:5px">Registro:</td>
									<td style=" border-bottom: 1px solid black;padding-left:5px"><b style="color:#<?= $color_background ?>;font-size:20px"><?= $fecha ?></b></td>
								</tr>
								<tr>
									<td colspan="2"></td>
								</tr>
								<tr>
									<td style="font-size:14px;background-color:#<?= $color_background ?>;color:white;padding-left:5px">Clave:</td>
									<td style=" border-bottom: 1px solid black;padding-left:5px"><b style="color:#<?= $color_background ?>;font-size:20px"><?= $clave ?></b></td>
								</tr>
								<tr>
									<td style="font-size:14px;background-color:#<?= $color_background ?>;color:white;padding-left:5px">Folio:</td>
									<td style=" border-bottom: 1px solid black;padding-left:5px"><b style="color:#<?= $color_background ?>;font-size:20px"><?= $folio ?></b></td>
								</tr> 
								
								<tr>
									<td colspan="2"></td>
								</tr>
								<tr>
									<td colspan="2"></td>
								</tr>
								<tr>
									<td style="font-size:14px;background-color:#<?= $color_background ?>;color:white;padding-left:5px">Nombre(s):</td>
									<td style=" border-bottom: 1px solid black;padding-left:5px"><b style="color:#<?= $color_background ?>;font-size:20px"><?= $nombre ?></b></td>
								</tr>
								<tr>
									<td style="font-size:14px;background-color:#<?= $color_background ?>;color:white;padding-left:5px">Apellidos:</td>
									<td style=" border-bottom: 1px solid black;padding-left:5px"><b style="color:#<?= $color_background ?>;font-size:20px"><?= $apellido_paterno.' '.$apellido_materno  ?></b></td>
								</tr>
								<tr>
									<td style="font-size:14px;background-color:#<?= $color_background ?>;color:white;padding-left:5px">Municipio:</td>
									<td style=" border-bottom: 1px solid black;padding-left:5px"><b style="color:#<?= $color_background ?>;font-size:20px"><?= $municipio ?></b></td>
								</tr>
								
							</table>
						</div>
					</center>
				</div>
				<!-- some social links to show off -->
		</aside>
		<!-- that’s all folks! -->
		<script src="js/jquery-3.3.1.min.js"></script>
	</body>
</html>