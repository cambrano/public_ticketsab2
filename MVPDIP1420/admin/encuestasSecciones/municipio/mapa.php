<?php
	include __DIR__."/../../functions/secciones_ine_parametros.php";
	include __DIR__."/../../functions/secciones_ine.php";
	
	@session_start();
	//var_dump($_POST);
	if(!empty($_POST)){
		include __DIR__.'/../../functions/security.php';
		include __DIR__."/../../functions/municipios_parametros.php";
		include __DIR__."/../../functions/tool_xhpzab.php";
		$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
		$id_municipio = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);


		$municipios_parametrosDatosMapa = municipios_parametrosDatosMapa($id_municipio,$id_estado);
		$sql="
			SELECT 
			m.id,
			m.clave,
			m.municipio,
			m.latitud,
			m.longitud,
			( SELECT COUNT(*) FROM secciones_ine_ciudadanos s WHERE s.id_municipio = m.id ) totales_ciudadanos,
			( SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_municipio = m.id AND s.id_encuesta ='{$id_encuesta}' ) totales_encuestados,
			( SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_municipio = m.id AND s.id_encuesta ='{$id_encuesta}' ) / ( SELECT COUNT(*) FROM secciones_ine_ciudadanos s WHERE s.id_municipio = m.id  )*100 porcentaje,
			(SELECT COUNT(*) FROM secciones_ine s WHERE s.id_municipio = m.id) secciones,
			( SELECT s.fecha_hora FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_municipio = m.id AND s.id_encuesta ='{$id_encuesta}' ORDER BY s.fecha_hora DESC LIMIT 1) ultima_encuesta,
			( SELECT (SELECT si.numero FROM secciones_ine si WHERE si.id = s.id_seccion_ine )  FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_municipio = m.id AND s.id_encuesta ='{$id_encuesta}' AND s.id_municipio = m.id ORDER BY s.fecha_hora DESC LIMIT 1) ultima_encuesta_seccion
			FROM municipios m
			WHERE m.id_estado='{$id_estado}'  
		";
		if($id_municipio!=''){
			$sql.= " AND m.id = '{$id_municipio}' " ;
		}
		$sql.= " ORDER BY porcentaje DESC ";
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$row['totales_no_encuestados'] = $row['totales_ciudadanos'] - $row['totales_encuestados'];
			$datos_municipios_encuestados[$row['id']]=$row;
			$datos_municipios[] = $row;
		}

		$id_seccion_ine = $_POST['searchTable'][0]['id_seccion_ine'];

		$sql="
			SELECT 
			si.id,
			si.clave,
			si.numero,
			si.latitud,
			si.longitud,
			( SELECT COUNT(*) FROM secciones_ine_ciudadanos s WHERE s.id_seccion_ine = si.id ) totales_ciudadanos,
			( SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_seccion_ine = si.id AND s.id_encuesta ='{$id_encuesta}' ) totales_encuestados,
			( SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_seccion_ine = si.id AND s.id_encuesta ='{$id_encuesta}' ) / 
			( SELECT COUNT(*) FROM secciones_ine_ciudadanos s WHERE s.id_seccion_ine = si.id  )*100 porcentaje,
			( SELECT s.fecha_hora FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_seccion_ine = si.id AND s.id_encuesta ='{$id_encuesta}' ORDER BY s.fecha_hora DESC LIMIT 1) ultima_encuesta
			FROM secciones_ine si
			WHERE si.id_municipio ='{$id_municipio}' 
		";
		if($id_seccion_ine !=''){
			$sql.= " AND si.id IN ({$id_seccion_ine}) ";
		}
		$sql;
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$row['totales_no_encuestados'] = $row['totales_ciudadanos'] - $row['totales_encuestados'];
			$datos_secciones_encuestados[$row['id']]=$row;
			$latitud = $row['latitud'];
			$longitud = $row['longitud']; 
		}


	}else{
		$zoom="8";
		$orderby = ' ORDER BY fechaR DESC';
		$limit = 'LIMIT 0,84';
		$municipios_parametrosDatosMapa = municipios_parametrosDatosMapa($id_municipio,$id_estado);
	}

	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,'','','','');

?>
	<style type="text/css">
		.divMapa{
			width:450px;
			height:120px;
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
			padding: 6px 0px 0px 4px ;
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


		.datos_votos{
			width:50%;
			float:left;
			height:75px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.logo_partido{
			width:30%;
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
				height:460px;
				margin: -10px 0px 0px 10px;
			}
			.info_titulo,.info_seccion_ganador_button{
				width:100%;
			}
			.info_seccion_ganador{
				width:100%;
			}
			.datos_votos{
				width:100%;
				height: 90px;
			}
			.datos{
				width:100%;
				height: 70px;
			}
			.logo_partido{
				width:100%;
				height: 60px;
			}
			.datos_partido{
				width:100%;
				height: 65px;
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
			zoom=14;
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

			<?php
			foreach ($datos_distritos_federales as $key => $value) {
				$paths = "";
				foreach ($distritos_federales_parametrosDatosMapa[$value['id']] as $keyT => $valueT) {
					$path = "distritos_federales_".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";
					$paths .= $path.",";
				}
				if($value['partido_ganador_background']==""){
					$value['partido_ganador_border'] = "000000";
					$value['partido_ganador_background'] = "000000";
				}
				?>
				distritos_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $value['partido_ganador_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $value['partido_ganador_background'] ?>",
					fillOpacity: 0.35,
				});
				distritos_area<?= $key ?>.setMap(map);
				<?php
			}
			?>


			///marcadores o puntos
			var marcadores = [
			<?php
			foreach ($datos_municipios_encuestados as $key => $value) {
				if($value['id'] != $id_municipio){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['partido_ganador_logo']."' ],";
				}
			}
			?>
			<?php
			foreach ($datos_secciones_encuestados as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['partido_ganador_logo']."' ],";
			}
			?>
			];
			///informacion del marcador
			var infoWindowContent = [
					<?php
					foreach ($datos_municipios_encuestados as $key => $value){
						if($value['id'] != $id_municipio){
							$div = '<div class="divMapa">
									<div class="info_content">
										<h4>Municipio: '.$value['municipio'].'</h4>
										<div class="info_titulo">
											<h5>Encuestas</h5>
										</div>
										<div class="info_seccion_ganador">
											R.Ciudadano: <b>'.number_format($value['totales_ciudadanos'], 0, '.', ',').'</b><br>
											Secciones: <b>'.number_format($value['secciones'], 0, '.', ',').'</b><br>
										</div>
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="encuestasSeccionesMunicipio('.$value['id'].')">Ver Más</button>
										</div>
									</div>
									<div class="datos_votos">
										<p>
											Encuestados Porcentaje: <b>'.number_format($value['porcentaje'], 0, '.', ',').'%</b><br>
											Encuestados: <b>'.number_format($value['totales_encuestados'], 0, '.', ',').'</b><br>
											No Encuestados: <b>'.number_format($value['totales_no_encuestados'], 0, '.', ',').'</b><br>
										</p>
									</div>
									<div class="datos_votos">
										<p>
											Ultima Encuesta:<br>
											Sección: <b>'.$value['ultima_encuesta_seccion'].'</b><br>
											Fecha: <b>'.$value['ultima_encuesta'].'</b><br>
										</p>
									</div> 
								</div>';
							$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
							?>
							['<?= $div ?>'],
							<?php
						}
					}

					foreach ($datos_secciones_encuestados as $key => $value){
						if($value['id'] != $id_municipio){
							$div = '<div class="divMapa">
									<div class="info_content">
										<h4>Sección: '.$value['numero'].'</h4>
										<div class="info_titulo">
											<h5>Encuestas</h5>
										</div>
										<div class="info_seccion_ganador">
											R.Ciudadano: <b>'.number_format($value['totales_ciudadanos'], 0, '.', ',').'</b><br>
										</div>
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="encuestasSeccion('.$value['id'].')">Ver Más</button>
										</div>
									</div>
									<div class="datos_votos">
										<p>
											Encuestados Porcentaje: <b>'.number_format($value['porcentaje'], 0, '.', ',').'%</b><br>
											Encuestados: <b>'.number_format($value['totales_encuestados'], 0, '.', ',').'</b><br>
											No Encuestados: <b>'.number_format($value['totales_no_encuestados'], 0, '.', ',').'</b><br>
										</p>
									</div>
									<div class="datos_votos">
										<p>
											Ultima Encuesta:<br>
											Fecha: <b>'.$value['ultima_encuesta'].'</b><br>
										</p>
									</div> 
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


			for (i = 0; i < marcadores.length; i++) {
				var icon = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/iconos_partidos/puntero_ciudadano.png',
					scaledSize: new google.maps.Size(22, 22), // scaled size
				};

				marker = new google.maps.Marker({
					position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
					map: map,
					icon: icon
				});


				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infowindow.setContent(infoWindowContent[i][0]);
						infowindow.open(map, marker);
					}
				})(marker, i));
			}
			<?php
			foreach ($datos_secciones_encuestados as $key => $value) {
				$div = '<div class="divMapaSeccion">
							<h4>Sección: '.$secciones_ineDatosMapa[$key]['numero'].'</h4>
						</div>';
				$div = '<div class="divMapaSecciones">
							<div class="info_content">
								<div class="info_titulo" style="width:100%;">
									<h5>Información</h5><br>
								</div>
							</div>
							<div class="datos_seccion">
								<p>
									Sección: <b>'.$secciones_ineDatosMapa[$key]['numero'].'</b><br>
								</p>
							</div>
						</div>';
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
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
					fillOpacity: 0.35,
				});
				<?php
			}
			?>
			// Agregar un listener para detectar cambios en el mapa
			google.maps.event.addListener(map, 'idle', function() {
				// Obtener los límites del mapa
				var bounds = map.getBounds();
				var zoom = map.getZoom();
				<?php
					foreach ($datos_secciones_encuestados as $key => $value) {
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
	