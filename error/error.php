<?php
	session_start();
	header('Cache-Control: max-age=84600');
	$_SERVER["HTTP_ACCEPT_LANGUAGE"];
	$idioma =strtoupper(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2));
	//echo $idioma;
	//die;
	if($idioma!="ES"){
		$idioma="EN";
	}
	$idioma;

	$errorServer=$_GET['cot'];
	$queryString= $_SERVER['REDIRECT_QUERY_STRING'];
	parse_str($queryString, $get_array);
	$_GET=$get_array;

	if($idioma=="ES"){
		$botonError="Volver a la página de inicio";
		$segundos="segundos";
		$reset="Reinicio en";
		$descriptionError="La url solicitada no se encontró en este servidor. Eso es lo que sabemos.";
		$pageLocateMini="es-mx";
		$pageLocateMini="es";
	}

	if($idioma=="EN"){
		$botonError="Back To Home Page";
		$segundos="seconds";
		$reset="Reset in";
		$descriptionError="The requested url was not found on this server.That is all we know.";
		$pageLocateMini="en-us";
		$pageLocateMini="en";
	}
	$urlPath='https://'.$_SERVER['SERVER_NAME'].'/error/';
	$actual_link = 'https://'.$page_configuracionDatos_out[0]['url'].$_SERVER['SCRIPT_NAME'];
	$urlPathError='https://'.$_SERVER['SERVER_NAME']."/error";
	$logox="https://".$_SERVER['SERVER_NAME']."/assets/iconos/logo.png";



?>
<!DOCTYPE html>
<html lang="<?= $pageLocateMini ?>" class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Error 👽</title>
		<meta name="description" content="<?= $descriptionError ?>">
		<meta name="author" content="un alien">
		<!-- mobile specific metas
			================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<!-- CSS
			================================================== -->
		<!-- script
			================================================== -->
		<!-- favicons
			================================================== -->
		<link rel="icon" type="image/png" href="<?= $logox ?>">
		<!--  
			Stylesheets
			=============================================
			-->
		<!-- Default stylesheets-->
		<link href="<?= $urlPath ?>assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- Template specific stylesheets-->
		<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Volkhov:400i" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/animate.css/animate.css" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/components-font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/et-line-font/et-line-font.css" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/flexslider/flexslider.css" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/owl.carousel/dist/assets/owl.carousel.min.css" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/owl.carousel/dist/assets/owl.theme.default.min.css" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/magnific-popup/dist/magnific-popup.css" rel="stylesheet">
		<link href="<?= $urlPath ?>assets/lib/simple-text-rotator/simpletextrotator.css" rel="stylesheet">
		<!-- Main stylesheet and color file-->
		<link href="<?= $urlPath ?>assets/css/style.css" rel="stylesheet">
		<link id="color-scheme" href="<?= $urlPath ?>assets/css/colors/default.css" rel="stylesheet">
		<script type="text/javascript">
			function pageRedirect(page){
				window.location.href = page+".php<?= $pageLg ?>";
			}
		</script>
		<script>
		/*
		ProgressCountdown(10, 'pageBeginCountdown', 'pageBeginCountdownText').then(
			value => alert(`Reset: ${value}.`)
		);
		*/
		ProgressCountdown(10, 'pageBeginCountdown', 'pageBeginCountdownText').then(
			value =>{
				document.getElementById("link").style.display = "block";
				/*alert(`Redirección: ${value}.`);*/
				//alert(`Redireccionando Espere...`);
				//return value;
				//window.location.href = "https://<?= $_SERVER["SERVER_NAME"] ?>/index.php<?= $pageLg ?>";
				return value;
			}
		);

		function ProgressCountdown(timeleft, bar, text) {
			return new Promise((resolve, reject) => {
				var countdownTimer = setInterval(() => {
					timeleft--;
					document.getElementById(bar).value = timeleft;
					document.getElementById(text).textContent = timeleft;
					if (timeleft <= 0) {
						clearInterval(countdownTimer);
						resolve(true);
					}
				}, 1000);
			});
		}
		</script>
	</head>
	<body data-spy="scroll" data-target=".onpage-navigation" data-offset="60">
		<main>
			<section class="home-section home-parallax home-fade home-full-height bg-dark bg-dark-30" id="home" data-background="https://<?= $_SERVER["SERVER_NAME"] ?>/assets/images/header-bg.jpg">
				<div class="titan-caption">
					<div class="caption-content">
						<br><br>
						<div class="font-alt mb-30 titan-title-size-4">Error <?= $errorServer ?></div>
						<div class="row begin-countdown">
							<div class="col-md-12 text-center">
								<progress value="10" max="10" id="pageBeginCountdown" style="width: 100%" ></progress>
								<p> <?= $reset ?> <span id="pageBeginCountdownText">10 </span> <?= $segundos ?></p>
							</div>
						</div>
						<div style="word-wrap: break-word;width: 100%; font-size: 20px;text-align: center;display: none" id="link" >
						</div>
						<div class="font-alt">
							<?= $descriptionError ?>
						</div>
						<div class="font-alt mt-30"><a class="btn btn-w btn-round" href="https://<?= $_SERVER["SERVER_NAME"] ?>/index.php<?= $pageLg ?>"><?= $botonError ?></a></div>
						<br>
					</div>
				</div>
				<div id="msnGP" style="background-color:red;"></div>
			</section>
		</main>
		<!--  
			JavaScripts
			=============================================
			-->
		<script src="<?= $urlPath ?>assets/lib/jquery/dist/jquery.js"></script>
		<script src="<?= $urlPath ?>assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="<?= $urlPath ?>assets/lib/wow/dist/wow.js"></script>
		<script src="<?= $urlPath ?>assets/lib/jquery.mb.ytplayer/dist/jquery.mb.YTPlayer.js"></script>
		<script src="<?= $urlPath ?>assets/lib/isotope/dist/isotope.pkgd.js"></script>
		<script src="<?= $urlPath ?>assets/lib/imagesloaded/imagesloaded.pkgd.js"></script>
		<script src="<?= $urlPath ?>assets/lib/flexslider/jquery.flexslider.js"></script>
		<script src="<?= $urlPath ?>assets/lib/owl.carousel/dist/owl.carousel.min.js"></script>
		<script src="<?= $urlPath ?>assets/lib/smoothscroll.js"></script>
		<script src="<?= $urlPath ?>assets/lib/magnific-popup/dist/jquery.magnific-popup.js"></script>
		<script src="<?= $urlPath ?>assets/lib/simple-text-rotator/jquery.simple-text-rotator.min.js"></script>
		<script src="<?= $urlPath ?>assets/js/plugins.js"></script>
		<script src="<?= $urlPath ?>assets/js/main.js"></script>
		<?php
		$info['request_method'] = $_SERVER['REQUEST_METHOD'];
		$info['request_uri'] = $_SERVER['REQUEST_URI'];
		$info['script_name'] = $_SERVER['SCRIPT_NAME'];
		$info['php_self'] = $_SERVER['PHP_SELF'];
		$info['php_self'] = $_SERVER['PHP_SELF'];
		if($_COOKIE['id_usuario']!=''){
			$info['usuario_sesiones'] = '2';
		}
		$info;
		?>
		<script>
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
				var enlaceRaiz = window.location.origin;
				$.ajax({
					type: "POST",
					url: enlaceRaiz+"/aYd4a1558721019ko4vQ448911653472.php",
					data: {info:info},
					success: function(data) { 
						/*$("#msnGP").html(data);*/
					}
				});
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
				var enlaceRaiz = window.location.origin;
				$.ajax({
					type: "POST",
					url: enlaceRaiz+"/aYd4a1558721019ko4vQ448911653472.php",
					data: {location: location,info:info},
					success: function(data) { 
						//$("#msnGP").html(data);
					}
				});
			}
			window.onload = getLocation;
		</script>
	</body>
</html>