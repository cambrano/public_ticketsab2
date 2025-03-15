<?php
	if($reload_mapa == ""){
		include __DIR__.'/../functions/security.php'; 
		@session_start();
		$tipo_perfil_usuario = $RowUser['id_perfil_usuario'];
		if(!empty($_POST)){
			$columns = array( 
				// datatable column index  => database column name
				0 =>'clave',
				1 =>'plataforma',
				2 =>'folio',
				3 =>'curp',
				4 =>'clave_elector',
				5 =>'tipo_seccion',
				6 =>'seccion',
				7 =>'manzana',
				8 =>'distrito_local',
				9 =>'distrito_federal',
				10 =>'distancia_km',
				11 =>'tipo_ciudadano',
				12 =>'nombre_completo',
				13 =>'sexo',
				14 =>'fecha_nacimiento',
				15 =>'whatsapp',
				16 =>'celular',
				17 =>'telefono',
				18 =>'correo_electronico',
				19 =>'municipio',
				20 =>'localidad',
				21 =>'categorias',
				22 =>'medio_registro',
				23 =>'distancia_alert',
				24 =>'seguimientos',
				25 =>'status_verificacion',
				26 =>'documentos_oficiales',
				27 =>'programas_apoyos',
				28 =>'programas_apoyos_categorias',
				29 =>'militantes_partidos',
			);
			setcookie("searchTableSIC", json_encode($_POST['searchTable'][0]),time()+(60*60*8),"/",false);
			setcookie("searchOpcionesSIC", json_encode($_POST['searchOpciones'][0]),time()+(60*60*8),"/",false);
			$searchOpciones = $_POST['searchOpciones'][0];
			include '../functions/secciones_ine.php';
			include '../functions/secciones_ine_parametros.php';
			include '../functions/secciones_ine_ciudadanos.php';

			$zoom = 11;
			if($_POST['mapa'][0]['order']==""){
				$_POST['mapa'][0]['order'] =0;
			}
			if($_POST['mapa'][0]['order_tipo']==""){
				$_POST['mapa'][0]['order_tipo'] ="desc";
			}
			//$orderby = ' ORDER BY clave DESC';
			$pagina = $_POST['mapa'][0]['pagina'];
			$total_registros= $searchOpciones['tipo_limite'];
			$pagina = $_POST['mapa'][0]['pagina'];
			$order = $_POST['mapa'][0]['order'];
			$order_tipo = $_POST['mapa'][0]['order_tipo'];
			if($columns[$order]=="relacionado"){
				$orderby[$order] = "id_seccion_ine_ciudadano_compartido";
			}
			$orderby = " ORDER BY {$columns[$order]} {$order_tipo} ";
			$mostrardesde = $pagina * $total_registros;
			if($total_registros!='x'){
				$limit = "LIMIT ".$mostrardesde.",$total_registros";
			}


			$id_secciones_ine = explode(",", $_POST['searchTable'][0]['id_seccion_ine']);
			$id_cuartel = explode(",", $_POST['searchTable'][0]['id_cuartel']);

			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','','','','');
			$secciones_ineDatosMapa = secciones_ineDatosMapa($origen);
			$secciones_ine_ciudadanosDatosArray=secciones_ine_ciudadanosDatosArray($_POST['searchTable'][0],$orderby,$limit,$RowUser['id_perfil_usuario'],$RowUser['id']);

			

			foreach ($secciones_ine_ciudadanosDatosArray as $key => $value) {
				if($key==0){
					$latitud = $value['latitud'];
					$longitud = $value['longitud'];
				}
				else{
					//echo $value['latitud'];
					//echo $value['longitud'];
					//echo "<br>";
					//unset ($secciones_ine_ciudadanosDatosArray [$key]);
				}
			}

			if($searchOpciones['tipo_mapa']!='sin_mapa' || $searchOpciones['tipo_mapa']=='' && !empty($_POST['searchOpciones']) ){
				?>
				<style type="text/css">
					
					.divMapaSecciones{
						width:250px;
						height:90px;
						margin: -10px 0px 0px 10px;
					}
					.divMapa{
						width:450px;
						height:150px;
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
							height:360px;
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
						zoom=14;
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
						var infoWindow = new google.maps.InfoWindow({
							content: "Mi Ubicación Actual"
						});
						navigator.geolocation.getCurrentPosition(function(position) {
							var myLatlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
							var marker1 = new google.maps.Marker({
								position: myLatlng,
								draggable: false,
								animation: google.maps.Animation.BOUNCE,
								icon: pinImage,
								map: map
							});
							google.maps.event.addListener(marker1, "dragend", function() {
								getCoords(marker1);
							});
							google.maps.event.addListener(marker1, "click", function() {
									infoWindow.open(map, marker1);
							});
						});
						/*
						function getPoints() {
							return [
								<?php
								///ciudadanos
								foreach ($secciones_ine_ciudadanosDatosArray as $key => $value) {
								echo "new google.maps.LatLng(".$value['latitud'].",".$value['longitud']."),";
								}
								?>
							];
						}
						*/
						var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
						var map = new google.maps.Map(document.getElementById("mapa"), myOptions);
						var infoWindow = new google.maps.InfoWindow({
							content: "Mi Ubicación Actual"
						});
						/*
						heatmap = new google.maps.visualization.HeatmapLayer({
							data: getPoints(),
							map: map,
							radius: 20, // set custom radius to 20 pixels
							bounds: map.getBounds()
						});
						*/
						// Carga los datos de PHP como un arreglo JSON
						const seccionesData = <?php echo json_encode($secciones_ine_ciudadanosDatosArray); ?>;

						// Genera los puntos para el Heatmap
						function getPoints111() {
							return seccionesData.map(coord => new google.maps.LatLng(coord.latitud, coord.longitud));
						}
						function getPoints() {
							const points = seccionesData.map(coord => {
								const { latitud, longitud } = coord;

								// Verificar si latitud o longitud son NaN o inválidos
								if (!latitud || !longitud || isNaN(latitud) || isNaN(longitud)) {
									console.error("Coordenada inválida encontrada:", coord);
									return null; // Retornar null para evitar usar datos inválidos
								}

								// Si es válido, crea un punto LatLng
								return new google.maps.LatLng(latitud, longitud);
							});

							// Filtrar valores nulos en caso de que haya datos inválidos
							return points.filter(point => point !== null);
						}


						// Crea el Heatmap con los puntos generados
						const heatmap = new google.maps.visualization.HeatmapLayer({
							data: getPoints(),
							map: map,
							radius: 20 // Establece el radio personalizado a 20 píxeles
						});

						// Actualiza los límites del mapa si es necesario
						heatmap.set('bounds', map.getBounds());
		
						var markers = [];
						<?php
							foreach ($secciones_ineDatosMapa as $key => $value) {
								$porcentaje = ($value['ciudadanos'] / $value['lista_nominal']) * 100;
								if(is_nan($porcentaje)){
									$porcentaje = 0;
								}
								$div = '<div class="divMapaSecciones">
											<div class="info_content">
												<div class="info_titulo">
													<h5>Sección:</h5>
												</div>
												<div class="info_seccion_ganador">
													<h5>'.$value['numero'].'</h5>
												</div>
												<div class="info_seccion_ganador_button">
													<button class="button button4" onclick="mostrarSeccionCiudadanos('.$value['id'].')">Ver Más</button>
												</div>
											</div>
											<div class="datos_seccion">
												<p>
													Lista Nominal: <b>'.number_format($value['lista_nominal'],0,'.',',').'</b><br>
													Ciudadanos: <b>'.number_format($value['ciudadanos'],0,'.',',').'</b><br>
													Porcentaje Avance: <b>'.number_format($porcentaje,2,'.',',').'%</b><br>
												</p>
											</div>
										</div>';
								$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
								if($tipo_uso_plataforma=='municipio'){
									if ($value['id_municipio'] == $id_municipio ){
										if (in_array($value['id'], $id_secciones_ine)) {
											$strokeColor='#000000';
											$fillColor='#000000';
										}else{
											$strokeColor='#001A36';
											$fillColor='#001A36';
										}
									}else{
										$strokeColor='#36000B';
										$fillColor='#36000B';
									}
								}elseif($tipo_uso_plataforma=='distrito_local'){
									if ($value['id_distrito_local'] == $id_distrito_local ){
										if (in_array($value['id'], $id_secciones_ine)) {
											$strokeColor='#000000';
											$fillColor='#000000';
										}else{
											$strokeColor='#001A36';
											$fillColor='#001A36';
										}
									}else{
										$strokeColor='#36000B';
										$fillColor='#36000B';
									}
								}elseif($tipo_uso_plataforma=='distrito_federal'){
									if ($value['id_distrito_federal'] == $id_distrito_federal ){
										if (in_array($value['id'], $id_secciones_ine)) {
											$strokeColor='#000000';
											$fillColor='#000000';
										}else{
											$strokeColor='#001A36';
											$fillColor='#001A36';
										}
									}else{
										$strokeColor='#36000B';
										$fillColor='#36000B';
									}
								}else{
									if (in_array($value['id'], $id_secciones_ine)) {
										$strokeColor='#000000';
										$fillColor='#000000';
									}else{
										$strokeColor='#001A36';
										$fillColor='#001A36';
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
									fillOpacity: 0.35,
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
							for (var i = 0; i < markers.length; i++) {
								if (bounds.contains(markers[i].getPosition())) {
									markers[i].setVisible(true);
								} else {
									markers[i].setVisible(false);
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
				<?php
					if($tipo_perfil_usuario=='1' || $tipo_perfil_usuario=='2' ){
						?>
							
						<?php
					}
				?>
				<div style="width:100%;padding:10px;font-size:18px">
					Registros Mostrados: <b><?= number_format(count($secciones_ine_ciudadanosDatosArray),0,",",".") ?></b>
				</div>
				<div id="mapa" style="width:100%;height:400px;"></div>
				<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap&libraries=visualization&v=weekly" defer></script>
				<?php
			}
		}
	}
?>