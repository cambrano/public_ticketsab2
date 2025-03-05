<?php
		include 'MVPDIP1420/admin/functions/error.php';
		include 'MVPDIP1420/admin/functions/configuracion.php';
		include 'MVPDIP1420/admin/functions/efs.php';
		include 'MVPDIP1420/admin/functions/log_clicks.php';
		include 'MVPDIP1420/admin/functions/ips_bloqueados.php';
		include 'MVPDIP1420/admin/functions/plataformas.php';
		$_GET['i']="1";
		if($_GET['i']!=""){
			$loginx="block";
			$loginx1="none";
		}else{
			$loginx="none";
			$loginx1="block";
		}

		@session_start();
		$config=configuracion();
		$enlace_actual = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$enlace_actual = str_replace("login.php", "", $enlace_actual);
		$vencimientoSistema=vencimientoSistema();
		//$base64 = mostrarImagenBase64('logo_principal.png');
		//$logo = $logox=" data:image/png;base64,".$base64;

		$ruta_logo = "assets/iconos/logo.png";
		//$imagen_binaria = file_get_contents($ruta_logo);
		// Convierte la imagen en base64
		//$base64 = base64_encode($imagen_binaria);
        //$logo = $logox = "data:image/png;base64," . $base64;
		$logo = $logox= $ruta_logo;

		

		//generamos cookies para que nunca sepan cual es el valor del condicional para seleccionar la tabla 
		date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
		setlocale(LC_ALL,"es_ES");
		$length=32; 
		$mk_id=time();
		$gen_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
		$genid_agencia=$gen_id.$mk_id; 
		setcookie("ch201AB",$genid_agencia,time()+(60*60*8),"/",false);


		///generamos una session para que no pueda acceder desde otro servidor 
		$length=32; 
		$mk_id=time();
		$gen_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
		$security_login=$gen_id.$mk_id;
		//setcookie("Az801AB",$security_login,time()+(60*60*8),"/",false); 
		setcookie("Az801AB", $security_login, array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		$nombre_plataforma_web = 'Laguna del sabor - WEB APP';
		$nombre_plataforma_web = 'Amor Yucateco - WEB APP';
		$nombre_plataforma_web = 'Amor al Cabrito';
		$nombre_plataforma_web = 'Sabores locales';
		$nombre_plataforma_web = 'Sabor de playa';
		//$nombre_plataforma_web = 'Sistema Radar - WEB APP';
		//include 'home.php';
		/*
		20.9620765
		//-89.5774203
		9026
		20.9620765,-89.5774203
		*/
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip= $_SERVER['REMOTE_ADDR'];
		}
		$log_clicksConteo = log_clicksConteo('20',$ip,'60',date('Y-m-d H:i:s'));
		if($log_clicksConteo['bloqueo'] == 1 ){
			$bloquearIP = ip_bloqueadoInsert($ip,date('Y-m-d H:i:s'));
			//! hacemos un curl post para que modifique el archivo httacces de los servidores
			$plataformasDatos = plataformasDatos();
			foreach ($plataformasDatos as $key => $value) {
				$url = $value['url'];
				$codigo_dispositivo = $value['key_acceso'];
				
				// Datos que deseas enviar
				$data = array(
					'codigo_dispositivo' => $codigo_dispositivo
				);

				// Inicializar cURL
				$ch = curl_init();
				// Establecer la URL a la que se enviarán los datos
				curl_setopt($ch, CURLOPT_URL, $url);
				// Establecer que se enviarán datos mediante POST
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				// Convertir los datos a formato de cadena
				$postData = http_build_query($data);
				// Establecer los datos que se enviarán
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				// Indicar que quieres recibir la respuesta del servidor
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// Ejecutar la solicitud y guardar la respuesta en una variable
				$response = curl_exec($ch);
				// Verificar si ocurrió algún error
				if(curl_errno($ch)){
					echo 'Error al enviar la solicitud: ' . curl_error($ch);
				}
				// Cerrar la conexión cURL
				curl_close($ch);
				// Imprimir la respuesta del servidor
				$response;
			}// Ruta del archivo .htaccess
			//header("Refresh:0");
			echo "Muchos Intentos :P";
			die;
		}
		//header("Refresh:0");
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
		<title><?= $nombre_plataforma_web ?></title>
		<!-- Meta Google -->

		<meta name="google" content="notranslate" />
		<!-- Meta Description -->
		<meta name="description" content="<?= $nombre_plataforma_web ?>">
		<meta name="keywords" content="<?= $nombre_plataforma_web ?>, para el manejo de información">
		<meta name="author" content="X domian">

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
		<meta property="og:description" content="<?= $nombre_plataforma_web ?>, para el manejo de información">
		<meta property="og:image" content="<?= $logox ?>" />

		<!-- Twitter Meta Tags -->
		<meta name="twitter:card" content="summary_large_image">
		<meta property="twitter:domain" content="<?= $urlout ?>">
		<meta property="twitter:url" content="https://www.coahuilaradar.tk/">
		<meta name="twitter:title" content="<?= $nombre_plataforma_web ?>">
		<meta name="twitter:description" content="<?= $nombre_plataforma_web ?>, para el manejo de información">
		<meta name="twitter:image" content="<?= $logox ?>">


		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1">


		<link href="MVPDIP1420/admin/css/body.css" rel="stylesheet" type="text/css" /> 
		<link href="MVPDIP1420/admin/css/inputs.css" rel="stylesheet" type="text/css" > 
		<!-- Fontawesome Icon font -->
		<link rel="stylesheet" href="MVPDIP1420/admin/css/main.css">
		<script type="text/javascript">
			self.name = "MainWindow";
		</script>
		<script type="text/javascript" language="javascript" src="MVPDIP1420/admin/js/jquery.js"></script>
		<link href="MVPDIP1420/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

		<script type="text/javascript">
			console.log(
				"%cDetente Amig@!","color:red;font-family:system-ui;font-size:4rem;-webkit-text-stroke: 1px black;font-weight:bold"
			);
			console.log(
				"%cEsta Función es para desarrolladores, si tienes dudas o te interesa el sistema comunícate con nosotros. Pero si quieres o intentas entrar al sistema, si lo logras comunícate y te invitamos la cena :P","color:gray;font-family:system-ui;font-size:1.4rem;-webkit-text-stroke: 1px black;font-weight:bold;padding:10px"
			);
		</script>
		
		<script language="javascript" type="text/javascript">
			function SubmitUser(){
				document.getElementById("mensaje").innerHTML = "&nbsp;";
				document.getElementById("mensaje").style.borderBottom= "";
				document.getElementById("loginBtn").disabled = true;


				var usuario = document.getElementById("usuario").value;
				usuario = usuario.trim();
				var usuario = usuario.replace(/^\s+|\s+$/g, "");
				var password = document.getElementById("password").value;
				password = password.trim();
				var password = password.replace(/^\s+|\s+$/g, ""); 
				if( usuario == ""){
					document.getElementById("labeUser").style.color = "Red";
					document.getElementById("usuario").focus();
					document.getElementById("usuario").style.border= "1px solid #ff0000";
					document.getElementById("usuario").value="";
					document.getElementById("loginBtn").disabled = false;
					return false;
				}
				if( password == ""){
					document.getElementById("labelPass").style.color = "Red";
					document.getElementById("password").focus();
					document.getElementById("password").style.border= "1px solid #ff0000";
					document.getElementById("loginBtn").disabled = false;
					document.getElementById("password").value="";
					return false;
				}

				var latitud_script = document.getElementById("latitud_script").value;
				var longitud_script = document.getElementById("longitud_script").value;
				var precision_script = document.getElementById("precision_script").value;
				var loc_script = document.getElementById("loc_script").value;

				if(latitud_script =="" || longitud_script =="" || precision_script =="" || loc_script ==""){
					document.getElementById("mensaje").innerHTML = "Debes activar tu geolocation para poder trabajar mejor con usted.";
					document.getElementById("mensaje").style.borderBottom= "1px solid red";
					document.getElementById("mensaje").style.color = "Red";
					return false;
				}

				var tipo = '<?= $genid_agencia ?>';
				var dataString = 'usuario='+usuario+'&password='+password+'&tipo='+tipo+'&latitud_script='+latitud_script+'&longitud_script='+longitud_script+'&precision_script='+precision_script+'&loc_script='+loc_script;
				 $.ajax({
					type: "POST",
					url: "validusermanager.php",
					data: dataString,
					success: function(data) { 
						if(data == "SI"){
							document.getElementById("validuser").submit();
						}else{
							/*
							document.getElementById("labeUser").style.color = "Red";
							document.getElementById("usuario").focus();
							document.getElementById("usuario").style.border= "1px solid #ff0000";
							document.getElementById("labelPass").style.color = "Red"; 
							document.getElementById("password").style.border= "1px solid #ff0000";*/
							document.getElementById("loginBtn").disabled = false;
							document.getElementById("mensaje").innerHTML = "Usuario Invalido";
							document.getElementById("mensaje").style.borderBottom= "1px solid red";
							document.getElementById("mensaje").style.color = "Red";
							document.getElementById("mensaje").innerHTML = data+'1';
							document.getElementById("mensaje").innerHTML = 'Sin Acceso(a)...';
						}
					}
				});
			}
			$(document).ready(function() {	
				$('#usuario').blur(function(){
					var usuario = document.getElementById("usuario").value;
					usuario = usuario.trim();
					//document.getElementById("labeUser").style.color = "#ff0000";
					//document.getElementById("usuario").style.border= "1px solid #fff";
					var usuario = usuario.replace(/^\s+|\s+$/g, "");
						if( usuario == ""){
							//document.getElementById("labeUser").style.color = "Red"; 
							//document.getElementById("usuario").style.border= "1px solid #ff0000";
							document.getElementById("usuario").value='';
							return false;
						}
				}); 
				$('#password').blur(function(){
					var password = document.getElementById("password").value;
					password = password.trim();
					//document.getElementById("labelPass").style.color = "#ff0000";
					//document.getElementById("password").style.border= "1px solid #fff";
					var password = password.replace(/^\s+|\s+$/g, "");
					if( password == ""){
						//document.getElementById("labelPass").style.color = "Red"; 
						//document.getElementById("password").style.border= "1px solid #ff0000";
						document.getElementById("password").value='';
						return false;
					}
				});
			});
			function validar(e) {
				document.getElementById("mensaje").innerHTML = "&nbsp;";
				document.getElementById("mensaje").style.borderBottom= "";
				tecla = (document.all) ? e.keyCode : e.which;
				if (tecla==13){
					document.getElementById("loginBtn").disabled = true;
					var latitud_script = document.getElementById("latitud_script").value;
					var longitud_script = document.getElementById("longitud_script").value;
					var precision_script = document.getElementById("precision_script").value;
					var loc_script = document.getElementById("loc_script").value;

					if(latitud_script =="" || longitud_script =="" || precision_script =="" || loc_script ==""){
						document.getElementById("mensaje").innerHTML = "Debes activar tu geolocation para poder trabajar mejor con usted.";
						document.getElementById("mensaje").style.borderBottom= "1px solid red";
						document.getElementById("mensaje").style.color = "Red";
						return false;
					}
					var usuario = document.getElementById("usuario").value;
					usuario = usuario.trim();
					var usuario = usuario.replace(/^\s+|\s+$/g, "");
					var password = document.getElementById("password").value;
					password = password.trim();
					var password = password.replace(/^\s+|\s+$/g, "");
					if(usuario==""){
						document.getElementById("usuario").focus();
						return false;
					}
					if(password==""){
						document.getElementById("password").focus();
						return false;
					}
					var tipo = '<?= $genid_agencia ?>';
					var dataString = 'usuario='+usuario+'&password='+password+'&tipo='+tipo+'&latitud_script='+latitud_script+'&longitud_script='+longitud_script+'&precision_script='+precision_script+'&loc_script='+loc_script;
					$.ajax({
						type: "POST",
						url: "validusermanager.php",
						data: dataString,
						success: function(data) {
							if(data == "SI"){
								document.getElementById("validuser").submit(); 
							}else{
								document.getElementById("loginBtn").disabled = false;
								//document.getElementById("mensaje").innerHTML = data;
								document.getElementById("mensaje").innerHTML = "Usuario Invalido";
								document.getElementById("mensaje").style.borderBottom= "1px solid red";
								document.getElementById("mensaje").style.color = "Red";
								document.getElementById("mensaje").innerHTML = data+'2';
								document.getElementById("mensaje").innerHTML = 'Sin Acceso(b)...';
							}
						}
					});

				}
			}
		</script>


		<style type="text/css">
			body {
					background-color: white;
					font-family: 'Avenir Next';
					src: url("MVPDIP1420/css/fonts/AvenirNextLTPro-BoldCn.otf");
					background-color: #fafbfd;
					background-image:url('img/bg.jpg');
					background-repeat:no-repeat; 
					background-position:top;
					background-size: cover;
					background-attachment: fixed;
			}
			.login{
				text-align: left;
				display: table;
				margin: auto;
			}
			.managerticketbody{
				width: 350px;
				margin: auto;
				margin-top: 5%;
			}
			@media screen and (max-width: 820px) {
				.login{
					width: 100%;
					text-align: left;
					display: table;
					margin: auto;
				}
				.managerticketbody{
					margin-top: 5%;
					width: 100%;
				}
				input[type=button]{
					width: 100%;
					margin-top: 10px;
					height: 40px;
					text-transform: uppercase;
				}
				input[type=text],select,input[type=password]{
					width: 100%;
					margin-top: 5px;
					margin-bottom: 5px;
					height: 40px;
					font-size: 12pt;
				}
			}
		</style> 
	</head>
	<body id="body"> 
		<!--
		Fixed Navigation
		==================================== -->
		<!--
		End Fixed Navigation
		==================================== -->
		
		<main >
			<!-- Testimonial section -->
			<div class="overlay">
				<div class="container">
					<div class="managerticketbody" style="background-color:rgba(255,255,255,0.9);" >
						<div id="homebody">
							<div style="display: block;padding: 25px">
								<div style="width: 100%;text-align: center;">
									<img width="120px" src="<?= $logox; ?>" />
								</div>
								<div style="width: 100%;text-align: center;">
									<label class="tituloForm">
										<center>
											<font style="font-size: 22px;letter-spacing: 2px;color: #2d4f6b">Bienvenido</font><br>
											<!--<font style="font-size: 12px;"><?= $config['nombre'] ?></font>-->
										</center>
										<br>
									</label>
								</div>
								<div style="width: 100%;text-align: center;display: inline-block;">
									<div class="login" style="width: 100%">
										<div id="mensaje">&nbsp;</div>
										<form action="wp.php" method="post"  id="validuser" name="validuser">
											<div style="width: 100%;display:inline-block">
												<div class="sucForm" style="width: 100%;">
													<label class="labelForm"  id="labeUser" style="color: #2d4f6b">Usuario<font color="#FF0004">*</font></label><br>
													<input class="inputlogin" style="width: 100%" onkeypress="validar(event)" type="text" name="usuario" pattern="[A-Za-z0-9_-]{1,15}" autocomplete="new-text"  id="usuario" value=""/>
												</div>
												<br><br>
												<label class="labelForm"  id="labelPass" style="color: #2d4f6b">Contraseña<font color="#FF0004">*</font></label><br>
												<input class="inputlogin" onkeypress="validar(event)" type="password" name="password" pattern="[A-Za-z0-9_-]{1,15}" autocomplete="new-password" id="password" value=""/>
												<br><br>
												<input type="hidden" name="tipo" value="<?= $genid_agencia ?>">
												<input type="hidden" name="latitud_script" value="" id="latitud_script">
												<input type="hidden" name="longitud_script" value="" id="longitud_script">
												<input type="hidden" name="precision_script" value="" id="precision_script">
												<input type="hidden" name="loc_script" value="" id="loc_script">

												<div style="width: 100%">
													<input type="button" id="loginBtn" onClick="SubmitUser();"  style="width: 100%;" value="INICIAR SESI&Oacute;N">
												</div>
												<div style="height: 30px; padding-top: 4px;display: none;  "><input type="checkbox" name="remember" id="remember" value="SI">
													<label class="labelForm" style="font-size: 9px"  id="labeltemaname"> ¿Recordarme? </label><br>
												</div>
												<div style="width: 100%; margin-top: 10px">
													<div style="background-color: black;color: #f1f1f1; padding: 15px; text-align: center;">
														<a style="color: yellow; font-size: 18px" href="index.php">Regresar al inicio</a>
														<?php
														if($cottt==1){
															?>
															<br><br>
															Deseas un demo?<br>
															<font style="font-size: 13px">
																Comunícate con nosotros para darte un recorrido de la plataforma.<br><br>
																<a style="color: red; font-size: 18px" href="mailto:demo@sistemaradar.com">demo@sistemaradar.com</a>
																<br>
															</font>
															<?php
														}
														?>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end Testimonial section -->
		</main>
		<?php
			include "aYd4a1558721019ko4vQ448911653472.php";
			$info['fbclid'] = $_GET['fbclid'];
			$info['lg'] = $_GET['lg']; 
			$info['request_method'] = $_SERVER['REQUEST_METHOD'];
			$info['request_uri'] = $_SERVER['REQUEST_URI'];
			$info['script_name'] = $_SERVER['SCRIPT_NAME'];
			$info['php_self'] = $_SERVER['PHP_SELF'];
			$info;
		?>
		<div id="msnGP" ></div>
		<script type="text/javascript">
			localize();
			function localize(){
				if(navigator.geolocation){
					navigator.geolocation.getCurrentPosition(mapa,error);
				}else{
					//alert('Tu navegador no soporta geolocalizacion.');
					dataAB();
				}
			}
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
					url: "aYd4a1558721019ko4vQ448911653472.php",
					data: {info:info},
					success: function(data) { 
						//$("#msnGP").html(data);
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
				document.getElementById("latitud_script").value = latitud;
				document.getElementById("longitud_script").value = longitud;
				document.getElementById("precision_script").value = precision;
				document.getElementById("loc_script").value = loc;

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
						//$("#msnGP").html(data);
					}
				});



			}
			function error(errorCode){
				if(errorCode.code == 1){
					//alert("Debes activar tu geolocation para poder trabajar mejor con usted.");
					document.getElementById("mensaje").innerHTML = "Debes activar tu geolocation para poder visualizar correctamente los mapas del sistema gracias.";
					document.getElementById("mensaje").style.borderBottom= "1px solid red";
					document.getElementById("mensaje").style.color = "Red";
				}
				else if (errorCode.code==2){
					//alert("Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.");
					document.getElementById("mensaje").innerHTML = "Posicion no disponible,Debes activar tu geolocation para poder visualizar correctamente los mapas del sistema gracias.";
					document.getElementById("mensaje").style.borderBottom= "1px solid red";
					document.getElementById("mensaje").style.color = "Red";
				}
				else{
					//alert("Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.");
					document.getElementById("mensaje").innerHTML = "Ha ocurrido un error,Debes activar tu geolocation para poder visualizar correctamente los mapas del sistema gracias.";
					document.getElementById("mensaje").style.borderBottom= "1px solid red";
					document.getElementById("mensaje").style.color = "Red";
				}
			}
		</script>
	</body>
</html>