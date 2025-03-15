<?php
	include __DIR__.'/../functions/security.php'; 
	@session_start();
	//var_dump($_POST);
	if(!empty($_POST)){
		include '../functions/secciones_ine.php';
		include '../functions/secciones_ine_parametros.php';
		include '../functions/secciones_ine_ciudadanos_check_2024.php';
		$zoom = 11;
		if($_POST['mapa'][0]['order']==""){
			$_POST['mapa'][0]['order'] =0;
		}
		if($_POST['mapa'][0]['order_tipo']==""){
			$_POST['mapa'][0]['order_tipo'] ="desc";
		}
		$columns = array(
			0 =>'clave',
			1 =>'seccion',
			2 =>'distancia_km',
			3 =>'relacionado',
			4 =>'nombre_completo',
			5 =>'check_out_hora',
			6 =>'fecha_nacimiento',
			7 =>'whatsapp',
			8 =>'celular',
		);
		//$orderby = ' ORDER BY clave DESC';
		$pagina = $_POST['mapa'][0]['pagina'];
		$total_registros= 11;
		$pagina = $_POST['mapa'][0]['pagina'];
		$order = $_POST['mapa'][0]['order'];
		$order_tipo = $_POST['mapa'][0]['order_tipo'];
		if($columns[$order]=="relacionado"){
			$orderby[$order] = "id_seccion_ine_ciudadano_compartido";
		}
		$orderby = " ORDER BY {$columns[$order]} {$order_tipo} ";
		$mostrardesde = $pagina * $total_registros;
		$limit = "LIMIT {$mostrardesde},11";


		$secciones_ine_ciudadanosCheck2024DatosArray=secciones_ine_ciudadanosCheck2024DatosArray($_POST['searchTable'][0],$orderby,$limit);
		$secciones_ineDatosMapa = secciones_ineDatosMapaCheck2024();
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
	}else{
		$zoom = 11;
		$order = 0;
		$order_tipo = "desc";
		//$orderby = ' ORDER BY clave DESC';
		$orderby = " ORDER BY {$columns[$order]} {$order_tipo} ";
		$limit = ' LIMIT 0,11 ';
		$secciones_ine_ciudadanosCheck2024DatosArray=secciones_ine_ciudadanosCheck2024DatosArray($_POST['searchTable'][0],$orderby,$limit);
		$secciones_ineDatosMapa = secciones_ineDatosMapaCheck2024();
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
	}

	$secciones_ine_ciudadanosCheck2024DatosArray;

	foreach ($secciones_ine_ciudadanosCheck2024DatosArray as $key => $value) {
		if($key==0){
			$latitud = $value['latitud'];
			$longitud = $value['longitud'];
		}
	}

?> 
	<style type="text/css">
		.divMapaSecciones{
			width:250px;
			height:90px;
			margin: -10px 0px 0px 10px;
		}
		.divMapa{
			width:450px;
			height:auto;
			margin: -10px 0px 0px 10px;
		}
		.divMapaSeccion{
			width:150px;
			height:20px;
			margin: -10px 0px 0px 10px;
		}
		.info_tituloSecciones{
			width:30%;
			float:left;
			height:40px;
			text-align:center;
			border: 1px solid #e5e5e5;
			padding: 2px;
			background-color:#e5e5e5;
			vertical-align: middle;
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


		.datos{
			width:100%;
			float:left;
			height:auto;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.datos_seccion{
			width:100%;
			float:left;
			height:auto;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.datos_right{
			width:70%;
			float:left;
			height:70px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.logo_partido{
			width:40%;
			float:left;
			height:60px;
			text-align:left;
			border: 1px solid #00923f;
			padding: 10px 0px 2px 5px;
			background-color:#e36962;
			color:white;
		}
		.datos_partido{
			width:70%;
			float:left;
			height:60px;
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
			.divMapaSecciones{
				width:167px;
				height:160px;
				margin: -10px 0px 0px 10px;
			}
			.info_titulo,.info_seccion_ganador_button{
				width:100%;
			}
			.info_seccion_ganador{
				text-align:center;
				width:100%;
			}
			.datos_votos{
				width:100%;
				height: 90px;
			}
			.datos{
				width:100%;
				height: auto;
			}
			.datos_seccion{
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
		.button-link {
			display: inline-block;
			padding: 10px 20px;
			background-color: #a6a6a6;
			color: #fff;
			text-decoration: none;
			border-radius: 4px;
		}

		.button-link:hover {
			background-color: #0056b3;
		}

		.button-link:active {
			background-color: #003d80;
		}

		.button-link:hover,
		.button-link:active {
			color: #fff;
		}

		/* Media queries para tamaño completo en dispositivos móviles */
		@media only screen and (max-width: 600px) {
			.button-link {
				display: block;
				width: 100%;
			}
		}
	</style>
	<script type="text/javascript">
		function myMap(){
			zoom = 11;
			var latitud='<?=$latitud ?>';
			var longitud='<?=$longitud ?>';
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
			google.maps.event.addListener(marker1, "dragend", function(){getCoords(marker1);});


			var ciudadanos = [
			<?php
			///ciudadanos
			foreach ($secciones_ine_ciudadanosCheck2024DatosArray as $key => $value) {
				echo "['".$value['id']."', '".$value['latitud']."' , '".$value['longitud']."','".$value['check_in']."','".$value['check_out']."'],";
			}
			?>
			];
			var infoWindowContent_ciudadanos = [
				<?php
				foreach ($secciones_ine_ciudadanosCheck2024DatosArray as $key => $value){
					foreach ($value as $keyT => $valueT) {
						//$value[$keyT] = preg_replace('([^A-Za-z0-9 :-])', '', $valueT);
					}
					if($value['telefono'] == ""){
						$value['telefono'] = "-";
					}
					if($value['celular'] == ""){
						$value['celular'] = "-";
					}
					if($value['whatsapp'] == ""){
						$value['whatsapp'] = "-";
					}
					
					$div = '<div class="divMapa">
									<div class="info_content">
										<h4>Clave: '.$value['clave'].'</h4>
										<div class="info_titulo">
											<h5>Tipo Ciudadano:</h5>
										</div>
										<div class="info_seccion_ganador">
											<h5>'.$value['tipo_ciudadano'].'</h5>
										</div>
									</div>
									<div class="datos">
										<p>
											Sección: <b>'.$value['seccion'].'</b><br>
											Check OUT: <b>'.$value['check_out_hora'].'</b><br>
											D. Aprox: <b>'.$value['distancia_km'].' km</b><br>
											Nombre Completo: <b>'.$value['nombre_completo'].'</b><br>
											Celular: <b>'.$value['celular'].'</b><br>
											Whatsapp: <a href="https://api.whatsapp.com/send/?phone=52'.$value['whatsapp'].'&text&app_absent=0" target="_blank">'.$value['whatsapp'].'</a></b><br>
											Dirección : <b>'.$value['calle'].", ".$value['colonia'].", ".$value['codigo_postal'].", ".$value['municipio'].', '.$estado_nombre.' </b><br>
										</p>
									</div>
								</div>';
					$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
				?>
					['<?= $div ?>'],
				<?php
				}
				?>
			];

			var infowindowCiudadanos = new google.maps.InfoWindow();
			var markerMilitates, i;
			//pinImageRed = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
			pinImageGreen = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
			//pinImageYellow = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/yellow-dot.png');
			var icon = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				url : 'images/iconos_partidos/partido_sistema.png',
				scaledSize: new google.maps.Size(28, 28), // scaled size
			};

			pinImageRed = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png'); 
			pinImageGreen = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
			pinImageYellow = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/yellow-dot.png');
			for (i = 0; i < ciudadanos.length; i++) {

				if(ciudadanos[i][3]=='1' && ciudadanos[i][4]=='1'){
					var pinImage = pinImageGreen;
				}
				else if(ciudadanos[i][3]=='1' && ciudadanos[i][4]!='1'){
					var pinImage = pinImageYellow;
				}
				else if(ciudadanos[i][3]!='1' && ciudadanos[i][4]=='1'){
					var pinImage = pinImageGreen;
				}
				else{
					var pinImage = pinImageRed;
				}

				markerMilitates = new google.maps.Marker({
					position: new google.maps.LatLng(ciudadanos[i][1], ciudadanos[i][2]),
					map: map,
					icon: pinImage,
				});


				google.maps.event.addListener(markerMilitates, 'click', (function(markerMilitates, i) {
					return function() {
						infowindowCiudadanos.setContent(infoWindowContent_ciudadanos[i][0]);
						infowindowCiudadanos.open(map, markerMilitates);
					}
				})(markerMilitates, i));
			}

			<?php
				foreach ($secciones_ineDatosMapa as $key => $value) {
					$porcentaje = ($value['ciudadanos'] / $value['lista_nominal']) * 100;
					if(is_nan($porcentaje)){
						$porcentaje = 0;
					}
					$porcentaje_check = ($value['ciudadanos_check'] / $value['ciudadanos']) * 100;
					if(is_nan($porcentaje_check)){
						$porcentaje_check = 0;
					}
				
					$variable_encontrar = $porcentaje;
					$semaforo_activo = false;
					if($secciones_ine_ciudadanos_avance_semaforoDatos['status'] == 1 ){
						$semaforo_activo = true;
						$rango_verde_inicial = $secciones_ine_ciudadanos_avance_semaforoDatos['verde_rango_inicial'];
						$rango_verde_final = $secciones_ine_ciudadanos_avance_semaforoDatos['verde_rango_final'];

						$rango_amarillo_inicial = $secciones_ine_ciudadanos_avance_semaforoDatos['amarillo_rango_inicial'];
						$rango_amarillo_final = $secciones_ine_ciudadanos_avance_semaforoDatos['amarillo_rango_final'];

						$rango_rojo_inicial = $secciones_ine_ciudadanos_avance_semaforoDatos['rojo_rango_inicial'];
						$rango_rojo_final = $secciones_ine_ciudadanos_avance_semaforoDatos['rojo_rango_final'];
						if (floatval($variable_encontrar) == floatval($rango_verde_inicial) || (floatval($variable_encontrar) >= floatval($rango_verde_inicial) && floatval($variable_encontrar) <= floatval($rango_verde_final))) {
							$strokeColor='#008000';
							$fillColor='#008000';
							$opacidad = '0.3';
							$seccion_semaforo = 'verde';
						}elseif(floatval($variable_encontrar) == floatval($rango_amarillo_inicial) || (floatval($variable_encontrar) >= floatval($rango_amarillo_inicial) && floatval($variable_encontrar) <= floatval($rango_amarillo_final))) {
							$strokeColor='#FFFF00';
							$fillColor='#FFFF00';
							$opacidad = '0.3';
							$seccion_semaforo = 'amarillo';
						}elseif(floatval($variable_encontrar) == floatval($rango_rojo_inicial) || (floatval($variable_encontrar) >= floatval($rango_rojo_inicial) && floatval($variable_encontrar) <= floatval($rango_rojo_final))) {
							$strokeColor='#FF0000';
							$fillColor='#FF0000';
							$opacidad = '0.3';
							$seccion_semaforo = 'rojo';
						}else{
							$strokeColor='#001A36';
							$fillColor='#001A36';
							$opacidad = '0.3';
						}
					}else{

						if(!empty($secciones_ine_ciudadanos_secciones_avance_semaforoDatos[$value['id']])){
							$semaforo_activo = true;
							$rango_verde_inicial = $secciones_ine_ciudadanos_secciones_avance_semaforoDatos[$value['id']]['verde_rango_inicial'];
							$rango_verde_final = $secciones_ine_ciudadanos_secciones_avance_semaforoDatos[$value['id']]['verde_rango_final'];

							$rango_amarillo_inicial = $secciones_ine_ciudadanos_secciones_avance_semaforoDatos[$value['id']]['amarillo_rango_inicial'];
							$rango_amarillo_final = $secciones_ine_ciudadanos_secciones_avance_semaforoDatos[$value['id']]['amarillo_rango_final'];

							$rango_rojo_inicial = $secciones_ine_ciudadanos_secciones_avance_semaforoDatos[$value['id']]['rojo_rango_inicial'];
							$rango_rojo_final = $secciones_ine_ciudadanos_secciones_avance_semaforoDatos[$value['id']]['rojo_rango_final'];

							$variable_encontrar = $value['ciudadanos'];

							if (intval($variable_encontrar) == intval($rango_verde_inicial) || (intval($variable_encontrar) >= intval($rango_verde_inicial) && intval($variable_encontrar) <= floatval($rango_verde_final))) {
								$strokeColor='#008000';
								$fillColor='#008000';
								$opacidad = '0.3';
								$seccion_semaforo = 'verde';
							}elseif(intval($variable_encontrar) == intval($rango_amarillo_inicial) || (intval($variable_encontrar) >= intval($rango_amarillo_inicial) && intval($variable_encontrar) <= intval($rango_amarillo_final))) {
								$strokeColor='#FFFF00';
								$fillColor='#FFFF00';
								$opacidad = '0.3';
								$seccion_semaforo = 'amarillo';
							}elseif(intval($variable_encontrar) == intval($rango_rojo_inicial) || (intval($variable_encontrar) >= intval($rango_rojo_inicial) && intval($variable_encontrar) <= intval($rango_rojo_final))) {
								$strokeColor='#FF0000';
								$fillColor='#FF0000';
								$opacidad = '0.3';
								$seccion_semaforo = 'rojo';
							}else{
								$strokeColor='#001A36';
								$fillColor='#001A36';
								$opacidad = '0.3';
							}


						}else{
							$strokeColor='#001A36';
							$fillColor='#001A36';
							$opacidad = '0.4';
						}
					}
					if($semaforo_activo){
						$div_semaforo = '<div class="datos_seccion">
						<b>Semáforo</b>
						<table style="width:90%;text-align: center;">
							<tr>
							<td style="text-align: left;background-color:#FFA07A;padding-left:10px">Rojo</td><td style="background-color:#FF6B6B;color:black">'.$rango_rojo_inicial.'</td><td style="background-color:#FF6B6B;color:black" >a</td><td style="background-color:#FF6B6B;color:black">'.$rango_rojo_final.'</td>
							</tr>

							<tr>
							<td style="text-align: left;background-color:#FFFF99;padding-left:10px">Amarillo</td><td style="background-color:#FFFFC0;color:black">'.$rango_amarillo_inicial.'</td><td style="background-color:#FFFFC0;color:black" >a</td><td style="background-color:#FFFFC0;color:black">'.$rango_amarillo_final.'</td>
							</tr>

							<tr>
							<td style="text-align: left;background-color:#98FB98;padding-left:10px">Verde</td><td style="background-color:#9EE09E;color:black">'.$rango_verde_inicial.'</td><td style="background-color:#9EE09E;color:black" >a</td><td style="background-color:#9EE09E;color:black">'.$rango_verde_final.'</td>
							</tr>
						</table>
					</div>';
					}else{
						$div_semaforo = '';
					}
					$div = '<div class="divMapaSecciones">
								<div class="info_content">
									<div class="info_titulo">
										<h5>Sección:</h5>
									</div>
									<div class="info_seccion_ganador">
										<h5>'.$value['numero'].'</h5>
									</div>
								</div>
								<div class="datos_seccion">
									<p>
										Lista Nominal: <b>'.number_format($value['lista_nominal'],0,'.',',').'</b><br>
										Ciudadanos: <b>'.number_format($value['ciudadanos'],0,'.',',').'</b><br>
										Porcentaje Avance: <b>'.number_format($porcentaje,2,'.',',').'%</b><br>
										<br>
										Contador Bingo: <b>'.number_format($value['ciudadanos_check'],0,'.',',').'</b><br>
										Porcentaje bingo: <b>'.number_format($porcentaje_check,2,'.',',').'%</b><br>
									</p>
								</div>
								'.$div_semaforo.'
							</div>';
					$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

					if($tipo_uso_plataforma=='municipio'){
						if ($value['id_municipio'] == $id_municipio ){
							if (in_array($value['id'], $id_secciones_ine)) {
								//$strokeColor='#000000';
								//$fillColor='#000000';
								$opacidad = '0.6';
							}else{
								//$strokeColor='#001A36';
								//$fillColor='#001A36';
							}
						}else{
							$strokeColor='#36000B';
							$fillColor='#36000B';
							$strokeColor='#001A36';
							$fillColor='#001A36';
							$opacidad = '0.2';
						}
					}elseif($tipo_uso_plataforma=='distrito_local'){
						if ($value['id_distrito_local'] == $id_distrito_local ){
							if (in_array($value['id'], $id_secciones_ine)) {
								$opacidad = '0.6';
								//$strokeColor='#000000';
								//$fillColor='#000000';
							}else{
								//$strokeColor='#001A36';
								//$fillColor='#001A36';
							}
						}else{
							$strokeColor='#36000B';
							$fillColor='#36000B';
							$strokeColor='#001A36';
							$fillColor='#001A36';
							$opacidad = '0.2';
						}
					}elseif($tipo_uso_plataforma=='distrito_federal'){
						if ($value['id_distrito_federal'] == $id_distrito_federal ){
							if (in_array($value['id'], $id_secciones_ine)) {
								$opacidad = '0.6';
								//$strokeColor='#000000';
								//$fillColor='#000000';
							}else{
								//$strokeColor='#001A36';
								//$fillColor='#001A36';
							}
						}else{
							$strokeColor='#36000B';
							$fillColor='#36000B';
							$strokeColor='#001A36';
							$fillColor='#001A36';
							$opacidad = '0.2';
						}
					}else{
						if (in_array($value['id'], $id_secciones_ine)) {
							$strokeColor='#000000';
							$fillColor='#000000';
							$opacidad = '0.3';
						}else{
							//$strokeColor='#001A36';
							//$fillColor='#001A36';
						}
					}



					$paths = "";
					foreach ($secciones_ine_parametrosDatosMapa[$key] as $keyT => $valueT) {
						$path = "secciones_ine_".$key."_".$keyT;
						echo $path." = [";
						foreach ($valueT as $keyH => $valueH) {
							echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
						}
						echo "];";
						$paths .= $path.",";
					}
					?>
					secciones_area<?= $key ?> = new google.maps.Polygon({
						paths: [<?= $paths ?>],
						strokeColor: '<?= $strokeColor ?>',
						strokeOpacity: 0.8,
						strokeWeight: 1,
						fillColor: '<?= $fillColor ?>',
						fillOpacity:  '<?= $opacidad ?>',
					});
					infoWindow = new google.maps.InfoWindow();
					secciones_area<?= $key ?>.addListener("click", (function(event){
						myLatlng = new google.maps.LatLng("<?= $value['latitud'] ?>","<?= $value['longitud'] ?>"); 
						infoWindow.setContent('<?= $div ?>');
						infoWindow.setPosition(myLatlng);
						infoWindow.open(map);
					}));
					const label<?= $key ?> = new google.maps.Marker({
						label: {
							text: '<?= $value['numero'] ?>',
							color: 'white',
							fontSize: '15px'
						},
						icon: {
							url: '',
							size: new google.maps.Size(10, 10),
							anchor: new google.maps.Point(0, 0),
							labelOrigin: new google.maps.Point(0, 0),
							scaledSize: new google.maps.Size(100, 30)
						},
						position: {lat: <?= $value['latitud'] ?>, lng: <?= $value['longitud'] ?>},
						map: null,  // Inicialmente el label no se muestra en el mapa
					});
					<?php
				}
			?>
			// Agregar un listener para detectar cambios en el mapa
			google.maps.event.addListener(map, 'idle', function() {
				// Obtener los límites del mapa
				var bounds = map.getBounds();
				var zoom = map.getZoom();
				for (var i = 0; i < markerMilitates.length; i++) {
					if (bounds.contains(markerMilitates[i].getPosition())) {
						markerMilitates[i].setVisible(true);
					} else {
						markerMilitates[i].setVisible(false);
					}
				}
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
		}
		function getCoordsLimites(marker){ 
			//var latitud=document.getElementById("latitud").value=marker.getPosition().lat();
			// var longitud=document.getElementById("longitud").value=marker.getPosition().lng(); 
		}
	</script> 
	<div id="mapa" style="width:100%;height:400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>

	