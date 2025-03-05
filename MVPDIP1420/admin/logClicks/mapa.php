<?php
	if($reload_mapa==""){
		include __DIR__.'/../functions/security.php';
		include __DIR__.'/../functions/zonas_importantes.php';
		@session_start();
		//var_dump($_POST);
		if(!empty($_POST)){
			$columna = array(
				'fechaR',
				'server_name',
				'os',
				'browser',
				'ip',
				'fbclid',
				'loc',
				'loc_script',
				'ip_type',
				'type',
				'direccion_completa',
				'hostname',
				'isp',
				'org',
				'domain',
				'user_agent'
			);
			include '../functions/log_clicks.php';
			if($_POST['mapa'][0]['order']==""){
				$_POST['mapa'][0]['order'] =0;
			}
			if($_POST['mapa'][0]['order_tipo']==""){
				$_POST['mapa'][0]['order_tipo'] ="desc";
			}
			$zoom = 2;
			$pagina = $_POST['mapa'][0]['pagina'];
			$total_registros= 11;
			$order = $_POST['mapa'][0]['order'];
			$order_tipo = $_POST['mapa'][0]['order_tipo'];
			$orderby = " ORDER BY {$columna[$order]} {$order_tipo} ";
			$mostrardesde = $pagina * $total_registros;
			$limit = "LIMIT {$mostrardesde},11";
			$log_clicksDatosArray=log_clicksDatosArray($_POST['searchTable'][0],$orderby,$limit);

			if($_POST['searchTable'][0]['alerta']!='x'){
				$origen['tipo'] = $_POST['searchTable'][0]['alerta'];
				$zonas_importantesDatosArray = zonas_importantesDatosArray($origen);
			}
		}else{
			//$latitud = "20.7098786";
			//$longitud = "-89.0943377";
			$zoom = 2;
			$orderby = ' ORDER BY fechaR DESC';
			$limit = 'LIMIT 0,11';
			$log_clicksDatosArray=log_clicksDatosArray($_POST['searchTable'][0],$orderby,$limit);
		}
	?>
		<style type="text/css">
			.divMapa{
				width:350px;
				height:150px;
				margin: -10px 0px 0px 10px;
			}
			.divMapaSeccion{
				width:150px;
				height:20px;
				margin: -10px 0px 0px 10px;
			}
			.info_content{
				width: 100%;
			}
			.info_titulo{
				width:30%;
				float:left;
				height:40px;
				text-align:center;
				border: 1px solid #e5e5e5;
				padding: 2px;
				background-color:#e5e5e5;
				vertical-align: middle;
			}
			.info_seccion_ganador{
				width:70%;
				float:left;
				height:40px;
				text-align:left;
				border: 1px solid #cecece;
				padding: 2px 2px 2px 9px ;
				background-color:#cecece;
			}
			.info_seccion_ganador_button{
				width:30%;
				float:left;
				height:40px;
				text-align:left;
				border: 1px solid #cecece;
				padding: 6px 5px 0px 2px ;
				background-color:#cecece;
			}

			.info_seccion_ganador_button > button{
				background-color: #808080;
				border: none;
				color: white;
				text-align: center;
				text-decoration: none;
				cursor: pointer;
				padding: 5px;
				width: 100%;
			}

			.info_seccion_ganador_button > button:hover{
				background-color: #b0b0b0;
			}

			.info_seccion_ganador_button > button:active{
				background-color: black;  
			}


			.datos_left{
				width:50%;
				float:left;
				height:auto;
				text-align:left;
				border: 1px solid gray;
				padding: 4px 4px 4px 10px;
			}
			.datos_right{
				width:50%;
				float:left;
				height:auto;
				text-align:left;
				border: 1px solid gray;
				padding: 4px 4px 4px 10px;
			}
			.datos_right_bottom{
				width:50%;
				float:left;
				height:auto;
				text-align:left;
				border: 1px solid gray;
				padding: 4px 4px 4px 10px;
			}
			.datos_top{
				width:70%;
				float:left;
				height:auto;
				text-align:left;
				border: 1px solid gray;
				padding: 4px 0px 4px 10px;
			}
			.logo_partido{
				width:40%;
				float:left;
				height:auto;
				text-align:left;
				border: 1px solid #00923f;
				padding: 10px 0px 2px 5px;
				background-color:#e36962;
				color:white;
			}
			.datos_partido{
				width:70%;
				float:left;
				height:auto;
				text-align:left;
				border: 1px solid #00923f;
				padding: 5px 0px 2px 5px;
				background-color:#e36962;
				color:white;
			}
			@media screen and (max-width: 1281px) {
				.info_content{
					text-align: center;
				}
				.divMapa{
					width:167px;
					height:auto;
					margin: -10px 0px 0px 10px;
				}
				.info_titulo,.info_seccion_ganador_button{
					width:100%;
				}
				.info_seccion_ganador{
					width:100%;
					text-align: center;
				}
				.datos_votos{
					width:100%;
					height: 90px;
				}
				.datos_top,.datos_left{
					width:100%;
				}
				.datos_right{
					width:100%;
					height: auto;
				}
				.datos_right_bottom{
					width:100%;
					height: auto
				}
				.datos{
					width:100%;
					height: auto;
				}
				.logo_partido{
					width:100%;
					height: 60px;
				}
				.datos_partido{
					width:100%;
					height: auto;
				}
				.gm-style-iw  { 
					min-width: 110px !important; 
					padding: 22px 12px 2px 0px !important;
				}
				/*
				.gm-style-iw div, .gm-style-iw {
					overflow: hidden !important;
					max-width: 9999px !important;
					max-height: 9999px !important;
				}
				*/
			}
		</style>
		<script type="text/javascript">
			function myMap(){
				zoom=6;
				var latitud='<?=$latitud ?>';
				var longitud='<?=$longitud ?>';
				//orientacion del mapa o vision

				var style = 
				[
					{
						"featureType": "administrative",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#d6e2e6"
							}
						]
					},
					{
						"featureType": "administrative",
						"elementType": "geometry.stroke",
						"stylers": [
							{
								"color": "#cfd4d5"
							}
						]
					},
					{
						"featureType": "administrative",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#7492a8"
							}
						]
					},
					{
						"featureType": "administrative.neighborhood",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"lightness": 25
							}
						]
					},
					{
						"featureType": "landscape.man_made",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#dde2e3"
							}
						]
					},
					{
						"featureType": "landscape.man_made",
						"elementType": "geometry.stroke",
						"stylers": [
							{
								"color": "#cfd4d5"
							}
						]
					},
					{
						"featureType": "landscape.natural",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#dde2e3"
							}
						]
					},
					{
						"featureType": "landscape.natural",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#7492a8"
							}
						]
					},
					{
						"featureType": "landscape.natural.terrain",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "poi",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#dde2e3"
							}
						]
					},
					{
						"featureType": "poi",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#588ca4"
							}
						]
					},
					{
						"featureType": "poi",
						"elementType": "labels.icon",
						"stylers": [
							{
								"saturation": -100
							}
						]
					},
					{
						"featureType": "poi.park",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#a9de83"
							}
						]
					},
					{
						"featureType": "poi.park",
						"elementType": "geometry.stroke",
						"stylers": [
							{
								"color": "#bae6a1"
							}
						]
					},
					{
						"featureType": "poi.sports_complex",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#c6e8b3"
							}
						]
					},
					{
						"featureType": "poi.sports_complex",
						"elementType": "geometry.stroke",
						"stylers": [
							{
								"color": "#bae6a1"
							}
						]
					},
					{
						"featureType": "road",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#41626b"
							}
						]
					},
					{
						"featureType": "road",
						"elementType": "labels.icon",
						"stylers": [
							{
								"saturation": -45
							},
							{
								"lightness": 10
							},
							{
								"visibility": "on"
							}
						]
					},
					{
						"featureType": "road.highway",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#c1d1d6"
							}
						]
					},
					{
						"featureType": "road.highway",
						"elementType": "geometry.stroke",
						"stylers": [
							{
								"color": "#a6b5bb"
							}
						]
					},
					{
						"featureType": "road.highway",
						"elementType": "labels.icon",
						"stylers": [
							{
								"visibility": "on"
							}
						]
					},
					{
						"featureType": "road.highway.controlled_access",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#9fb6bd"
							}
						]
					},
					{
						"featureType": "road.arterial",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#ffffff"
							}
						]
					},
					{
						"featureType": "road.local",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#ffffff"
							}
						]
					},
					{
						"featureType": "transit",
						"elementType": "labels.icon",
						"stylers": [
							{
								"saturation": -70
							}
						]
					},
					{
						"featureType": "transit.line",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#b4cbd4"
							}
						]
					},
					{
						"featureType": "transit.line",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#588ca4"
							}
						]
					},
					{
						"featureType": "transit.station",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "transit.station",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#008cb5"
							},
							{
								"visibility": "on"
							}
						]
					},
					{
						"featureType": "transit.station.airport",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"saturation": -100
							},
							{
								"lightness": -5
							}
						]
					},
					{
						"featureType": "water",
						"elementType": "geometry.fill",
						"stylers": [
							{
								"color": "#a6cbe3"
							}
						]
					}
				];

				var myLatlng = new google.maps.LatLng(latitud,longitud); 
				var myOptions = {
					zoom: zoom,
					center: myLatlng,
					styles: style,
					panControl: true,
					zoomControl: true,
					mapTypeControl: true,
					streetViewControl: true,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					scrollwheel: true,
					minZoom: zoom - 113,
					maxZoom: zoom + 113,
				}
				var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
				var map = new google.maps.Map(document.getElementById("mapa"), myOptions); 
				marker1 = new google.maps.Marker({ 
					position: myLatlng,
					draggable: false,
					icon: pinImage,
				});
				<?php
					if($sinDireccion){
						echo "marker.setMap(map);getCoords(marker);";
					}
				?>
				var marcadores = [
					<?php
						foreach ($log_clicksDatosArray as $key => $value) {
							if($value['latitud_script']!=""){
								echo "['".$value['id']."', ".$value['latitud_script'].", ".$value['longitud_script'].",'SI_SCRIPT'],";
							}else{
								echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'NO_SCRIPT'],";
							}
						}
					?>
				];
				var infoWindowContent = [
					<?php
						foreach ($log_clicksDatosArray as $key => $value){
							foreach ($value as $keyT => $valueT) {
								$value[$keyT] = preg_replace('([^A-Za-z0-9 .,:-])', '', $valueT);
							}
							if($value['lg'] != "EN"){
								$value['lg'] = 'ES';
							}
							if($value['latitud_script']!=""){
								$div = '<div class="divMapa">
											<div class="info_content">
												<div class="info_titulo">
													<h5>Script Name:</h5>
												</div>
												<div class="info_seccion_ganador">
													<h5>'.$value['script_name'].'</h5>
												</div>
											</div>
											<div class="datos_left">
												<p>
													Fecha R: <b>'.$value['fechaR'].'</b><br>
													Server Name: <b>'.$value['server_name'].'</b><br>
													Browser: <b>'.$value['browser'].'</b><br>
													OS: <b>'.$value['os'].'</b><br>
													IP: <b>'.$value['ip'].'</b><br>
													Hostname: <b>'.$value['hostname'].'</b><br>
													Idioma: <b>'.$value['lg'].'</b><br>
												</p>
											</div>
											<div class="datos_right">
												<p>
													Ip Type: <b>'.$value['ip_type'].'</b><br>
													ISP: <b>'.$value['isp'].'</b><br>
													ORG: <b>'.$value['org'].'</b><br>
													ASNAME: <b>'.$value['asname'].'</b><br>
												</p>
											</div>
											<div class="datos_right_bottom" style="width:100%;">
												<p>
													Location: <b>'.$value['loc'].'</b><br>
													Location Script: <b>'.$value['loc_script'].'</b><br>
													City: <b>'.$value['city'].'</b><br>
													Región: <b>'.$value['region'].'</b><br>
													Country: <b>'.$value['country'].'</b><br>
													Calle: <b>'.$value['direccion_calle'].'</b><br>
													Numero: <b>'.$value['direccion_numero'].'</b><br>
													Colonia: <b>'.$value['direccion_colonia'].'</b><br>
													Dirección: <b>'.$value['direccion_completa'].'</b><br>
													Zipcode: <b>'.$value['zip_code'].'</b><br><br>
												</p>
											</div>';
								$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
								?>
								['<?= $div ?>'],
								<?php
							}else{
								$value['loc_script'] = 'NO PROPORCIONADO';
								$div = '<div class="divMapa">
											<div class="info_content">
												<div class="info_titulo">
													<h5>Script Name:</h5>
												</div>
												<div class="info_seccion_ganador">
													<h5>'.$value['script_name'].'</h5>
												</div>
											</div>
											<div class="datos_left">
												<p>
													Fecha R: <b>'.$value['fechaR'].'</b><br>
													Server Name: <b>'.$value['server_name'].'</b><br>
													Browser: <b>'.$value['browser'].'</b><br>
													OS: <b>'.$value['os'].'</b><br>
													IP: <b>'.$value['ip'].'</b><br>
													Hostname: <b>'.$value['hostname'].'</b><br>
													Idioma: <b>'.$value['lg'].'</b><br>
												</p>
											</div>
											<div class="datos_right">
												<p>
													Ip Type: <b>'.$value['ip_type'].'</b><br>
													ISP: <b>'.$value['isp'].'</b><br>
													ORG: <b>'.$value['org'].'</b><br>
													ASNAME: <b>'.$value['asname'].'</b><br>
												</p>
											</div>
											<div class="datos_right_bottom" style="width:100%;">
												<p>
													Location: <b>'.$value['loc'].'</b><br>
													Location Script: <b>'.$value['loc_script'].'</b><br>
													City: <b>'.$value['city'].'</b><br>
													Región: <b>'.$value['region'].'</b><br>
													Country: <b>'.$value['country'].'</b><br>
													Calle: <b>'.$value['direccion_calle'].'</b><br>
													Numero: <b>'.$value['direccion_numero'].'</b><br>
													Colonia: <b>'.$value['direccion_colonia'].'</b><br>
													Dirección: <b>'.$value['direccion_completa'].'</b><br>
													Zipcode: <b>'.$value['zip_code'].'</b><br><br>
												</p>
											</div>';
								$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
								?>
								['<?= $div ?>'],
								<?php
							}
						}
					?>
				];
				var infowindow = new google.maps.InfoWindow();
				var marker, i;
				pinImageRed = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
				pinImageGreen = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
				pinImageYellow = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/yellow-dot.png');
				for (i = 0; i < marcadores.length; i++) {
					if(marcadores[i][3]=='SI_SCRIPT'){
						var pinImage = pinImageGreen;
					}else{
						var pinImage = pinImageYellow;
					}
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
						map: map,
						icon: pinImage,
					});
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infowindow.setContent(infoWindowContent[i][0]);
							infowindow.open(map, marker);
						}
					})(marker, i));
				}
				// Agregar un listener para detectar cambios en el mapa
				google.maps.event.addListener(map, 'idle', function() {
					// Obtener los límites del mapa
					var bounds = map.getBounds();
					var zoom = map.getZoom();
					<?php
						foreach ($secciones_ineDatosMapa as $key => $value) {
							?>
							// Verificar si los marcadores están dentro de los límites del mapa
							if (bounds.contains(label<?= $key ?>.getPosition())) {
								//console.log(map.getZoom())
								if (map.getZoom() >= 13) {
									label<?= $key ?>.setMap(map);
								}else{
									label<?= $key ?>.setMap(null);
								}
							} else {
								label<?= $key ?>.setMap(null);
							}
							<?php
						}
						foreach ($secciones_ineDatosMapa as $key => $value) {
							?>
							var vertices = secciones_area<?= $key ?>.getPath().getArray();
							var visible = false;
							for (var i = 0; i < vertices.length; i++) {
								if (bounds.contains(vertices[i])) {
									// Si todos los vértices están dentro de los límites, mostrar el polígono
									var visible = true;
								}
							}
							if(visible){
								secciones_area<?= $key ?>.setMap(map);
							}else{
								//secciones_area<?= $key ?>.setMap(null);
							}
							<?php
						}
					?>
				});
				var zonas = [
				<?php
				///zonas
				foreach ($zonas_importantesDatosArray as $key => $value) {
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['tipo']."'],";
				}
				?>
				];
				<?php
				foreach ($zonas_importantesDatosArray as $key => $value) {
					if($value['tipo']=='Amigo'){
						?>
						color_200 = '#008000';
						color_100 = '#009000'; //25% saturado
						color_30 = '#109010'; //25% lighter
						color_10 = '#0a5c0a'; //25% darker
						<?php
					}elseif ($value['tipo']=='Hostil') {
						?>
						color_200 = '#b00000';
						color_100 = '#c60000'; //25% saturado
						color_30 = '#c61616'; //25% lighter
						color_10 = '#7f0e0e'; //25% darker
						<?php
					}elseif ($value['tipo']=='Neutro') {
						?>
						color_200 = '#346beb';
						color_100 = '#1d62ff'; //25% saturado
						color_30 = '#82a0e5'; //25% lighter
						color_10 = '#2755be'; //25% darker
						<?php
					}else{
						?>
						color_200 = '#4cb9fa';
						color_100 = '#36beff'; //25% saturado
						color_30 = '#a5d6f3'; //25% lighter
						color_10 = '#249ae1'; //25% darker
						<?php
					}
					?>
					const pos_<?= $value['id'] ?> = {
						lat: <?= $value['latitud'] ?>,
						lng: <?= $value['longitud'] ?>,
					};
					map.setCenter(pos_<?= $value['id'] ?>);
					const circle200mts_<?= $value['id'] ?> = new google.maps.Circle({
						strokeColor: color_200,
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: color_200,
						fillOpacity: 0.35,
						map: map,
						center: pos_<?= $value['id'] ?>,
						radius: 200, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
					});
					const circle100mts_<?= $value['id'] ?> = new google.maps.Circle({
						strokeColor: color_100,
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: color_100,
						fillOpacity: 0.35,
						map: map,
						center: pos_<?= $value['id'] ?>,
						radius: 100, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
					});
					const circle30mts_<?= $value['id'] ?> = new google.maps.Circle({
						strokeColor: color_30,
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: color_30,
						fillOpacity: 0.35,
						map: map,
						center: pos_<?= $value['id'] ?>,
						radius: 30, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
					});
					const circle10mts_<?= $value['id'] ?> = new google.maps.Circle({
						strokeColor: color_10,
						strokeOpacity: 0.8,
						strokeWeight: 2,
						fillColor: color_10,
						fillOpacity: 0.35,
						map: map,
						center: pos_<?= $value['id'] ?>,
						radius: 10, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
					});
					<?php
				}
				?>
				var infoWindowContent_zonas = [
					<?php
					foreach ($zonas_importantesDatosArray as $key => $value){
						$tipo = mb_strtoupper($value['tipo'], 'UTF-8');
						foreach ($value as $keyT => $valueT) {
							$value[$keyT] = preg_replace('([^A-Za-z0-9 :-])', '', $valueT);
						}
						$div = '<div class="divMapa">
										<div class="info_content">
											<h4></h4>
											<div class="info_titulo">
												<h5>Tipo:</h5>
											</div>
											<div class="info_seccion_ganador">
												<h5>'.strtoupper($tipo).'</h5>
											</div>
										</div>
										<div class="datos_top" style="width:100%;">
											Nombre: <b><font style="">'.$value['nombre'].'</font></b><br><br>
											<p>
												Distrito Local: <b>'.$value['distrito_local'].'</b><br>
												Distrito Federal: <b>'.$value['distrito_federal'].'</b><br>
												Sección: <b>'.$value['seccion'].'</b><br>
											</p>
											Dirección : <b>'.$value['calle'].", ".$value['colonia'].", ".$value['codigo_postal'].", ".$value['municipio'].', '.$estado_nombre.' </b><br>
										</div>
									</div>';
						$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
					?>
						['<?= $div ?>'],
					<?php
					}
					?>
				];

				var infowindowZonas = new google.maps.InfoWindow();
				var markerZonas, i;
				var iconAmigo = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/puntos/punto_amigo.png',
					scaledSize: new google.maps.Size(32, 32), // scaled size
				};
				var iconHostil = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/puntos/punto_hostil.png',
					scaledSize: new google.maps.Size(32, 32), // scaled size
				};
				var iconNeutro = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/puntos/punto_neutro.png',
					scaledSize: new google.maps.Size(32, 32), // scaled size
				};
				var iconInteres = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/puntos/punto_interes.png',
					scaledSize: new google.maps.Size(32, 32), // scaled size
				};

				for (i = 0; i < zonas.length; i++) {
					if(zonas[i][3] =='Amigo'){
						icon = iconAmigo;
					}else if(zonas[i][3] =='Hostil'){
						icon = iconHostil;
					}else if(zonas[i][3] =='Neutro'){
						icon = iconNeutro;
					}else{
						icon = iconInteres;
					}
					markerZonas = new google.maps.Marker({
						position: new google.maps.LatLng(zonas[i][1], zonas[i][2]),
						map: map,
						icon: icon,
					});

					google.maps.event.addListener(markerZonas, 'click', (function(markerZonas, i) {
						return function() {
							infowindowZonas.setContent(infoWindowContent_zonas[i][0]);
							infowindowZonas.open(map, markerZonas);
						}
					})(markerZonas, i));
				}
			}
			function getCoords(marker){ 
				//var latitud=document.getElementById("latitud").value=marker.getPosition().lat();
				// var longitud=document.getElementById("longitud").value=marker.getPosition().lng(); 
			}
		</script> 
		<div id="mapa" style="width:100%;height:400px;"></div>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>  
		<?php
	}