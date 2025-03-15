<?php
	include __DIR__.'/../functions/security.php'; 
	@session_start();
	//var_dump($_POST);
	if(!empty($_POST)){
		include '../functions/distritos_federales.php';
		include '../functions/distritos_federales_parametros.php';
		foreach ($_POST['searchTable'][0] as $key => $value) {
			//echo "XX".$key." = XX_SESSION['".$key."'];";
			$_SESSION[$key]=mysqli_real_escape_string($conexion,$value);
			if($value!=""){
				$busqueda = true;
			}
			//echo "<br>";
		}

		$orderby = ' ORDER BY numero DESC';
		$pagina = $_POST['mapa'][0]['pagina'];
		$total_registros= 11;
		$mostrardesde = $pagina * $total_registros;
		$limit = "LIMIT {$mostrardesde},11";

		//LIMIT 11 ,11
		//LIMIT 22 ,11
		//LIMIT 33 ,11


		$distritos_federalesDatosArray=distritos_federalesDatosArray($_POST['searchTable'][0],$orderby,$limit);
		$distritos_federales_parametrosDatos = distritos_federales_parametrosDatos('','',' id_seccion_ine,orden ASC',$limit);
		foreach ($distritos_federales_parametrosDatos as $key => $value) {
			$distritos_area[$value['id_seccion_ine']][] = $value ;
		}

	}else{
		$zoom = 11;
		$orderby = ' ORDER BY numero DESC';
		$limit = 'LIMIT 0,11';
		$distritos_federalesDatosMapa = distritos_federalesDatosMapa();
		$distritos_federales_parametrosDatosMapa = distritos_federales_parametrosDatosMapa();
	}


 

?> 
	<script type="text/javascript">
		function myMap(){
			zoom=8;
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
				streetViewControl: false,
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

			<?php
			foreach ($distritos_federales_parametrosDatosMapa as $key => $value) {
				$distritos_federalesDatosMapa[$key]['numero'];
				$distritos_federalesDatosMapa[$key]['latitud'];
				$distritos_federalesDatosMapa[$key]['longitud'];
				$div = '<div class="divMapaSeccion">
							<h4>Distrio Federal: '.$distritos_federalesDatosMapa[$key]['numero'].'</h4>
						</div>';
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

				$paths = "";

				foreach ($value as $keyT => $valueT) {
					$path = "distritos_federales_".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";

					$paths .= $path.",";
				}
				?>


				distritos_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#000000",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#000000",
					fillOpacity: 0.35,
				});
				distritos_area<?= $key ?>.setMap(map);
				distritos_area<?= $key ?>.addListener("click", (function(event){
					myLatlng = new google.maps.LatLng("<?= $distritos_federalesDatosMapa[$key]['latitud'] ?>","<?= $distritos_federalesDatosMapa[$key]['longitud'] ?>"); 
					infoWindow.setContent('<?= $div ?>');
					infoWindow.setPosition(myLatlng);
					infoWindow.open(map);
				}));
				infoWindow = new google.maps.InfoWindow();
				<?php
			}

			if($busqueda){
				?>
				var marcadores = [
				<?php
					foreach ($distritos_federalesDatosMapa as $key => $value) {
						echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'SI_SCRIPT'],";
					}
				?>
				];
				var infoWindowContent = [
						<?php
						foreach ($distritos_federalesDatosMapa as $key => $value){

							$div = '<div style="width:300px;margin:0 0 20px 20px;height:60px;">
										<div class="info_content" style="width: 100%">
											<h3>Distrito Federal: '.$value['numero'].'</h3>
											<p>
												Latitud: <b>'.$value['latitud'].'</b><br>
												Longitud: <b>'.$value['longitud'].'</b><br>
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
	