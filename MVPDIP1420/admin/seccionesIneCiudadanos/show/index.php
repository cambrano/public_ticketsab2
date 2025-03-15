<?php
	ini_set('max_execution_time', 6000);
	@session_start();
	
	include __DIR__."../../../functions/security.php";
	include __DIR__."../../../functions/configuracion.php";
	include __DIR__."../../../functions/timemex.php";
	include __DIR__."../../../functions/secciones_ine_ciudadanos.php";
	include __DIR__."../../../functions/localidades.php";
	include __DIR__."../../../functions/municipios.php";
	include __DIR__."../../../functions/efs.php";
	
	$configuracion = configuracionDatos();
	$pageService=$_GET['cot'];
	if($pageService==""){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}

	// Palabra clave para encriptar y desencriptar
	$palabra_clave = "sistemaRadarAB";
	// Algoritmo de encriptación
	$algoritmo = "AES-256-CBC";
	// Vector de inicialización
	$iv = 'AB';
	$otra_variable = $_GET["cot"];
	$id_seccion_ine_ciudadano = urlencode(openssl_decrypt($otra_variable, $algoritmo, $palabra_clave, 0, $iv));
	$id_seccion_ine_ciudadano = $_GET["cot"];
	$sql = "SELECT 
				sc.id,
				sc.clave,
				sc.folio,
				sc.nombre_completo,
				sc.fecha_nacimiento,
				sc.nombre,
				sc.apellido_paterno,
				sc.apellido_materno,
				sc.sexo,
				sc.telefono,
				sc.celular,
				sc.whatsapp,
				sc.correo_electronico,
				sc.calle,
				sc.num_ext,
				sc.num_int,
				sc.colonia,
				sc.latitud,
				sc.longitud,
				sc.curp,
				sc.clave_elector,
				sc.vigencia,
				sc.manzana,
				sc.distancia_km_r,
				sc.id_seccion_ine_ciudadano_compartido
			FROM secciones_ine_ciudadanos sc
			WHERE sc.id='{$id_seccion_ine_ciudadano}'
			LIMIT 1";
	$resultado = $conexion->query($sql);
	$data=$resultado->fetch_assoc();
	if(empty($data)){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}
?>
		<!DOCTYPE html>
		<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
		<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
		<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
		<!--[if gt IE 8]><!--> <html lang="es" class="no-js"> <!--<![endif]-->
			<head>
				<!-- meta character set -->
				<meta name="google-site-verification" content="+nxGUDJ4QpAZ5l9Bsjdi102tLVC21AIh5d1Nl23908vVuFHs34="/>
				<meta charset="utf-8">
				<!-- Always force latest IE rendering engine or request Chrome Frame -->
				<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
				<title>Familia</title>
				<!-- Meta Google -->

				<meta name="google" content="notranslate" />
				<!-- Meta Description -->
				<meta property="og:title" content="Familia" />
				<meta property="og:description" content="Familia">
				<meta name="keywords" content="Familia, ">
				<meta name="author" content="A1XCZ">

				<!-- Mobile Specific Meta -->
				<meta name="viewport" content="width=device-width, initial-scale=1">
				

				<link rel="stylesheet" type="text/css" href="../../css/style.css">
				<link rel="stylesheet" type="text/css" href="../../css/body.css">

				<!-- Fontawesome Icon font -->
				<link rel="stylesheet" type="text/css" href="../../css/main.css">

				<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script> 
				


				<link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
				


				<link rel="stylesheet" href="../../css/pro.min.css">

				<script type="text/javascript">
					console.log(
						"%cDetente Amig@!","color:red;font-family:system-ui;font-size:4rem;-webkit-text-stroke: 1px black;font-weight:bold"
					);
					console.log(
						"%cEsta Función es para desarrolladores, si tienes dudas o te interesa el sistema comunícate con nosotros. Pero si quieres o intentas entrar al sistema, si lo logras comunícate y te invitamos la cena :P","color:gray;font-family:system-ui;font-size:1.4rem;-webkit-text-stroke: 1px black;font-weight:bold;padding:10px"
					);
				</script> 

				<style type="text/css">
					html {
						min-height: 100%;
						position: relative;
					}
					body {
							margin: 0;
							margin-bottom: 40px;
							font-family: 'Avenir Next';
							src: url("../../css/fonts/AvenirNextLTPro-BoldCn1.otf");
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
							src: url('../../css/fonts/AvenirNextLTPro-BoldCn1.otf');
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
					@media screen and (max-width: 820px) {
						footer{
							position: absolute;
						}
					}
				</style>
			</head>
			<script>
				function buscador_familia(clave_elector, id_seccion_ine_ciudadano_compartido) {
					return new Promise(function(resolve, reject) {
						var dataString = 'search_clave_elector=' + clave_elector;
						if (id_seccion_ine_ciudadano_compartido) {
							dataString += '&search_id_seccion_ine_ciudadano_compartido=' + id_seccion_ine_ciudadano_compartido;
						} else {
							// Si `familia` es null o undefined, solo usamos `clave_elector`
							dataString = 'search_clave_elector=' + clave_elector;
						}

						$.ajax({
							type: "POST",
							url: "../../seccionesIneCiudadanos/buscador_familia.php",
							data: dataString,
							dataType: 'json',
							success: function(data) {
								resolve(data);  // Resolviendo la promesa con los datos recibidos.
							},
							error: function(error) {
								reject(error);  // Rechazando la promesa en caso de error.
							}
						});
					});
				}

				async function bucleFamilia(clave_elector) { 
					let allData = []; // Array para almacenar todos los datos recibidos
					let id_seccion_ine_ciudadano_compartido = null;
					let primeraVuelta = true;
					let primera_data = [];
					while (true) {
						let data;
						try {
							if (primeraVuelta) {
								data = await buscador_familia(clave_elector, '');
								if(data.id == null){
									break
								}
								primera_data = data;
								primeraVuelta = false; // Cambiar a la segunda vuelta
							} else {
								data = await buscador_familia('', id_seccion_ine_ciudadano_compartido); // Solo enviar `familia` en la segunda vuelta
							}
							allData.push(data); // Almacenar los datos en el array
						} catch (error) {
							console.error('Error en la llamada AJAX:', error);
							return;  // Salir del bucle en caso de error
						}

						if (!primeraVuelta) { // En la segunda vuelta y siguientes
							if (data.status === '0') {
								break;  // Salir del bucle cuando `status` es '0'
							} else {
								// Si hay un `id_seccion_ine_ciudadano_compartido`, lo usamos como `familia` para la siguiente búsqueda
								if (data.id_seccion_ine_ciudadano_compartido) {
									id_seccion_ine_ciudadano_compartido = data.id_seccion_ine_ciudadano_compartido;
								}else{
									break;
								}
								// Esperar un tiempo antes de hacer la siguiente solicitud (opcional)
								await new Promise(resolve => setTimeout(resolve, 1000)); // Espera de 1 segundo
							}
						}
					}
					if (primera_data && Object.keys(primera_data).length > 0) {
						crearTablaConDatos(allData,primera_data);
					}
				}
				function crearTablaConDatos(dataArray, primera_data) {
					const table = document.createElement('table');
					table.border = '1';

					// Crear la fila de encabezados
					const headerRow = table.insertRow();
					const headers = ["CLAVE", "FOLIO", "TIPO", "NOMBRE COMPLETO"];
					
					headers.forEach(header => {
						const cell = headerRow.insertCell();
						cell.style.padding = '5px';
						cell.style.backgroundColor = 'gray';
						cell.style.color = 'black';
						cell.textContent = header;
					});
					// Invertir el orden del array
					dataArray.reverse();
					// Crear filas para cada conjunto de datos
					dataArray.forEach(data => {
						const row = table.insertRow();
						if(data.id == primera_data.id){
							backgroundColor = '#FDFD96';
						}else{
							backgroundColor = '#f2f2f2';
						}
						// Añadir celdas de acuerdo a los encabezados
						const claveCell = row.insertCell();
						claveCell.style.padding = '5px';
						claveCell.style.backgroundColor = backgroundColor; // Color de fondo alternativo
						claveCell.style.color = 'black'; // Color del texto negro
						claveCell.textContent = data.clave || '';

						const folioCell = row.insertCell();
						folioCell.style.padding = '5px';
						folioCell.style.backgroundColor = backgroundColor; // Color de fondo alternativo
						folioCell.style.color = 'black'; // Color del texto negro
						folioCell.textContent = data.folio || '';

						const tipoCell = row.insertCell();
						tipoCell.style.padding = '5px';
						tipoCell.style.backgroundColor = backgroundColor; // Color de fondo alternativo
						tipoCell.style.color = 'black'; // Color del texto negro
						tipoCell.textContent = data.tipo || '';

						const nombreCompletoCell = row.insertCell();
						nombreCompletoCell.style.padding = '5px';
						nombreCompletoCell.style.backgroundColor = backgroundColor; // Color de fondo alternativo
						nombreCompletoCell.style.color = 'black'; // Color del texto negro
						nombreCompletoCell.textContent = data.nombre_completo || '';
					});

					// Convertir la tabla a HTML
					const tableHTML = table.outerHTML;
					// Mostrar mensaje en el div con la tabla generada
					
					$("#mensaje_ine_tabla").html("<center><h4>Estructura</h4><hr>"+tableHTML+"</center>");
				}
				bucleFamilia("<?= $data['clave_elector'] ?>")
			</script>
			<body id="body">
				<div class="content">
					<!--
					Fixed Navigation
					==================================== -->
					<!--
					End Fixed Navigation
					==================================== -->
					<main id="main">
						<!-- Testimonial section -->
							<div class="overlay">
								<div class="container">
									<div id="managerticketbody" class="managerticketbody" style="margin-top: 20px !important;padding:5px;">
										<div id="homebody">
											<center>
												<div id="mensaje_ine_tabla" style="text-align:center;padding:10px 0px 20px 0px ">
													<img src="../../img/load.gif">
												</div>
											</center>
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
				
				<!-- onscroll animation -->
			</body>
		</html>