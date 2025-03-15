<?php
	include __DIR__.'/../functions/security.php'; 
	@session_start();
	$_SESSION['reporte_Sistema']['columnas_sql'] = array(
		0 =>'clave',
		1 =>'seccion',
		2 =>'distancia_km',
		3 =>'relacionado',
		4 =>'nombre_completo',
		5 =>'check_in_fecha_hora',
		6 =>'check_out_fecha_hora',
		7 =>'fecha_nacimiento',
		8 =>'whatsapp',
		9 =>'celular',
	);
	$_SESSION['reporte_Sistema']['columnas_nombres'] = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1),
		2 => array('row' => 'distancia_km' ,'nombre' => 'D.(km) Aprox' ,'tipo' => 'string','mostrar' => 1),
		3 => array('row' => 'relacionado' ,'nombre' => 'Relacionado' ,'tipo' => 'string','mostrar' => 1),
		4 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo','tipo' => 'string','mostrar' => 1),
		5 => array('row' => 'check_in_date_hora' ,'nombre' => 'C. IN' ,'tipo' => 'string','mostrar' => 1),
		6 => array('row' => 'check_out_date_hora' ,'nombre' => 'C. OUT' ,'tipo' => 'string','mostrar' => 1),
		7 => array('row' => 'date_nacimiento' ,'nombre' => 'F. Nacimiento' ,'tipo' => 'date','mostrar' => 1),
		8 => array('row' => 'whatsapp' ,'nombre' => 'Whatsapp' ,'tipo' => 'string','mostrar' => 1),
		9 => array('row' => 'celular' ,'nombre' => 'Celular' ,'tipo' => 'string','mostrar' => 1),
	);
	//var_dump($_POST);
	if(!empty($_POST)){
		include '../functions/secciones_ine.php';
		include '../functions/secciones_ine_parametros.php';
		include '../functions/secciones_ine_ciudadanos_check_2021.php';
		$zoom = 11;
		if($_POST['mapa'][0]['order']==""){
			$_POST['mapa'][0]['order'] =0;
		}
		if($_POST['mapa'][0]['order_tipo']==""){
			$_POST['mapa'][0]['order_tipo'] ="desc";
		}
		//$orderby = ' ORDER BY clave DESC';
		$pagina = $_POST['mapa'][0]['pagina'];
		$total_registros= 11;
		$pagina = $_POST['mapa'][0]['pagina'];
		$order = $_POST['mapa'][0]['order'];
		$order_tipo = $_POST['mapa'][0]['order_tipo'];
		if($_SESSION['reporte_Sistema']['columnas_sql'][$order]=="relacionado"){
			$_SESSION['reporte_Sistema']['columnas_sql'][$order] = "id_seccion_ine_ciudadano_compartido";
		}
		$orderby = " ORDER BY {$_SESSION['reporte_Sistema']['columnas_sql'][$order]} {$order_tipo} ";
		$mostrardesde = $pagina * $total_registros;
		$limit = "LIMIT {$mostrardesde},11";


		$secciones_ine_ciudadanosCheck2021DatosArray=secciones_ine_ciudadanosCheck2021DatosArray($_POST['searchTable'][0],$orderby,$limit);
		$secciones_ineDatosMapa = secciones_ineDatosMapa();
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
	}else{
		$zoom = 11;
		$order = 0;
		$order_tipo = "desc";
		//$orderby = ' ORDER BY clave DESC';
		$orderby = " ORDER BY e.{$_SESSION['reporte_Sistema']['columnas_sql'][$order]} {$order_tipo} ";
		$limit = ' LIMIT 0,11 ';
		$secciones_ine_ciudadanosCheck2021DatosArray=secciones_ine_ciudadanosCheck2021DatosArray($_POST['searchTable'][0],$orderby,$limit);
		$secciones_ineDatosMapa = secciones_ineDatosMapa();
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
	}

	$_SESSION['reporte_Sistema']['database'] = $secciones_ine_ciudadanosCheck2021DatosArray;

	foreach ($secciones_ine_ciudadanosCheck2021DatosArray as $key => $value) {
		if($key==0){
			$latitud = $value['latitud'];
			$longitud = $value['longitud'];
		}
	}

 

?> 
	<style type="text/css">
		.divMapa{
			width:450px;
			height:180px;
			margin: -10px 0px 0px 10px;
		}
		.divMapaSeccion{
			width:150px;
			height:20px;
			margin: -10px 0px 0px 10px;
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
			width:40%;
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
			height:150px;
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
		@media screen and (max-width: 820px) {
			.divMapa{
				width:167px;
				height:230px;
				margin: -10px 0px 0px 10px;
			}
			.info_titulo,.info_seccion_ganador_button{
				width:100%;
			}
			.info_seccion_ganador{
				width:100%;
				text-align: center;
			}
			.datos_right,.datos_left{
				width:100%;
			}
			.datos_partido,.logo_partido{
				width:100%;
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
			foreach ($secciones_ine_ciudadanosCheck2021DatosArray as $key => $value) {
				echo "['".$value['id']."', '".$value['latitud']."' , '".$value['longitud']."','".$value['check_in']."','".$value['check_out']."'],";
			}
			?>
			];
			var infoWindowContent_ciudadanos = [
				<?php
				foreach ($secciones_ine_ciudadanosCheck2021DatosArray as $key => $value){
					foreach ($value as $keyT => $valueT) {
						$value[$keyT] = preg_replace('([^A-Za-z0-9 :-])', '', $valueT);
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
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="edit('.$value['id'].')">Ver Más</button>
										</div>
									</div>
									<div class="datos">
										<p>
											Sección: <b>'.$value['seccion'].'</b><br>
											Check IN: <b>'.$value['check_in_hora'].'</b><br>
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
					var pinImage = pinImageYellow;
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
			foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
				$secciones_ineDatosMapa[$key]['numero'];
				$secciones_ineDatosMapa[$key]['latitud'];
				$secciones_ineDatosMapa[$key]['longitud'];
				$div = '<div class="divMapaSeccion">
							<h4>Sección: '.$secciones_ineDatosMapa[$key]['numero'].'</h4>
						</div>';
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

				$paths = "";

				foreach ($value as $keyT => $valueT) {
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
					strokeColor: "#000000",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#000000",
					fillOpacity: 0.35,
				});
				secciones_area<?= $key ?>.setMap(map);
				secciones_area<?= $key ?>.addListener("click", (function(event){
					myLatlng = new google.maps.LatLng("<?= $secciones_ineDatosMapa[$key]['latitud'] ?>","<?= $secciones_ineDatosMapa[$key]['longitud'] ?>"); 
					infoWindow.setContent('<?= $div ?>');
					infoWindow.setPosition(myLatlng);
					infoWindow.open(map);
				}));
				infoWindow = new google.maps.InfoWindow();
				<?php
			}
			?>
		}
		function getCoordsLimites(marker){ 
			//var latitud=document.getElementById("latitud").value=marker.getPosition().lat();
			// var longitud=document.getElementById("longitud").value=marker.getPosition().lng(); 
		}
	</script> 
	<div id="mapa" style="width:100%;height:400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>

	