    <?php

    if(!empty($_POST['mapa'][0])){
        $id_seccion_ine = $_POST['mapa'][0]['id_seccion_ine'];
        include __DIR__."/../functions/secciones_ine.php";
        include __DIR__."/../functions/secciones_ine_parametros.php";
        include __DIR__."/../functions/manzanas_ine.php";
        include __DIR__."/../functions/manzanas_ine_parametros.php";

		$secciones_ineDatosMapa = secciones_ineDatosMapa();
        $secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
        $origen['id_seccion_ine'] = $id_seccion_ine;
        $manzanas_ineDatosMapa = manzanas_ineDatosMapa($origen);
        $manzanas_ine_parametrosDatosMapa = manzanas_ine_parametrosDatosMapa('','',$id_seccion_ine,'','','','','');
        //$latitud = $_POST['mapa'][0]['latitud'];
        //$longitud = $_POST['mapa'][0]['longitud'];
        $latitud = $secciones_ineDatosMapa[$id_seccion_ine]['latitud'];
        $longitud = $secciones_ineDatosMapa[$id_seccion_ine]['longitud'];

        $latitud_punto = $_POST['mapa'][0]['latitud'];
        $longitud_punto = $_POST['mapa'][0]['longitud'];

    }
	?>
    <style type="text/css">
		.divMapaSecciones{
			width:150px;
			height:90px;
			margin: -10px 0px 0px 10px;
		}
		.info_content{
			text-align: left;
		}
		.info_titulo{
			width:100%;
			float:left;
			height:40px;
			text-align:center;
			border: 1px solid #e5e5e5;
			padding: 2px;
			background-color:#e5e5e5;
			vertical-align: middle;
		}
		.datos_seccion{
			width:100%;
			float:left;
			height:auto;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		@media screen and (max-width: 1281px) {
			.divMapaSecciones{
				width:200px;
				height:160px;
				margin: -10px 0px 0px 10px;
			}
			.info_content{
				text-align: center;
			}
			.datos_seccion{
				width:100%;
				height: auto;
			}
			.info_titulo{
				width:100%;
			}
		}
	</style>
	<script type="text/javascript">
		function myMapManzana(coordenadas=null,zoomCoordenada=null) {
			tipo_update="<?= $id ?>";
			
			if(tipo_update != ""){
				latitud="<?= $seccion_ine_ciudadanoDatos['latitud'] ?>";
				longitud="<?= $seccion_ine_ciudadanoDatos['longitud'] ?>";
				zoom=16;
			}else{
                if(coordenadas==null && zoomCoordenada==null){
                    latitud = "<?= $latitud ?>";
                    longitud = "<?= $longitud ?>";
                    zoom=16;
                }
                if(coordenadas != null && zoomCoordenada ==null){
                    var latitud = coordenadas.coords.latitude;
                    var longitud = coordenadas.coords.longitude; 
                    document.getElementById("latitud_r").value = latitud;
                    document.getElementById("longitud_r").value = longitud;
                    zoom=16;
                }
                if(coordenadas != null && zoomCoordenada != null){
                    latitud=coordenadas.lat;
                    longitud=coordenadas.lng;
                    zoom=zoomCoordenada;
                }
            }


			var style = 
			[
				{
					"featureType": "poi.business",
					"stylers": [
					{
						"color": "#ffffff"
					},
					{
						"visibility": "off"
					}
					]
				},
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
					"elementType": "labels.text",
					"stylers": [
					{
						"color": "#000000"
					},
					{
						"saturation": -100
					},
					{
						"visibility": "simplified"
					}
					]
				},
				{
					"featureType": "administrative.locality",
					"elementType": "labels.text.fill",
					"stylers": [
					{
						"color": "#000000"
					},
					{
						"saturation": 35
					},
					{
						"visibility": "on"
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

			var myLatlngSeccion = new google.maps.LatLng( latitud,longitud); 
			var myOptionsSeccion = {
				zoom: zoom,
				center: myLatlngSeccion,
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
			var mapSeccion = new google.maps.Map(document.getElementById("googleMapManzana"), myOptionsSeccion); 


			<?php
			foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
				$secciones_ineDatosMapa[$key]['numero'];
				$secciones_ineDatosMapa[$key]['latitud'];
				$secciones_ineDatosMapa[$key]['longitud'];
				$porcentaje = ($secciones_ineDatosMapa[$key]['ciudadanos'] / $secciones_ineDatosMapa[$key]['lista_nominal']) * 100;
				if(is_nan($porcentaje)){
					$porcentaje = 0;
				}
				$div = '<div class="divMapaSeccion">
							<h4>Sección: '.$secciones_ineDatosMapa[$key]['numero'].'</h4>
							<button class="btn btn-primary" onclick="seccionSelect('.$secciones_ineDatosMapa[$key]['id'].')">Asignar</button>
						</div>';
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

				
                if(empty($_POST['mapa'][0])){
                    $div = '<div class="divMapaSecciones">
						<div class="info_content">
							<div class="info_titulo">
								<h5>Información</h5><br>
							</div>
						</div>
						<div class="datos_seccion">
							<p>
								Sección: <b>'.$secciones_ineDatosMapa[$key]['numero'].'</b><br>
								<button class="btn btn-primary" onclick="seccionSelect('.$secciones_ineDatosMapa[$key]['id'].')">Asignar</button>
							</p>
						</div>
					</div>';
                }else{
                    $div = '<div class="divMapaSecciones">
						<div class="info_content">
							<div class="info_titulo">
								<h5>Información</h5><br>
							</div>
						</div>
						<div class="datos_seccion">
							<p>
								Sección: <b>'.$secciones_ineDatosMapa[$key]['numero'].'</b><br>
								<button class="btn btn-primary" onclick="generar_manzanasForm('.$secciones_ineDatosMapa[$key]['id'].')">Asignar</button>
							</p>
                            <p>
								Lista Nominal: <b>'.number_format($secciones_ineDatosMapa[$key]['lista_nominal'],0,'.',',').'</b><br>
								Ciudadanos: <b>'.number_format($secciones_ineDatosMapa[$key]['ciudadanos'],0,'.',',').'</b><br>
								Porcentaje Avance: <b>'.number_format($porcentaje,2,'.',',').'%</b><br>
							</p>
						</div>
					</div>';
                }
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

				$paths = "";

				foreach ($value as $keyT => $valueT) {
					$path = "secciones_ine_seccion".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";

					$paths .= $path.",";
				}

				if($tipo_uso_plataforma=='municipio'){
					if ($secciones_ineDatosMapa[$key]['id_municipio'] == $id_municipio ){
						if (in_array($secciones_ineDatosMapa[$key]['id'], $id_secciones_ine)) {
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
					if ($secciones_ineDatosMapa[$key]['id_distrito_local'] == $id_distrito_local ){
						if (in_array($secciones_ineDatosMapa[$key]['id'], $id_secciones_ine)) {
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
					if ($secciones_ineDatosMapa[$key]['id_distrito_federal'] == $id_distrito_federal ){
						if (in_array($secciones_ineDatosMapa[$key]['id'], $id_secciones_ine)) {
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
					if (in_array($secciones_ineDatosMapa[$key]['id'], $id_secciones_ine)) {
						$strokeColor='#000000';
						$fillColor='#000000';
					}else{
						$strokeColor='#001A36';
						$fillColor='#001A36';
					}
				}

				?>

				secciones_areaSeccion<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: '<?= $strokeColor ?>',
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: '<?= $fillColor ?>',
					fillOpacity: 0.35,
				});
				//secciones_areaSeccion<?= $key ?>.setMap(mapSeccion);
				infoWindowSeccion = new google.maps.InfoWindow();
				secciones_areaSeccion<?= $key ?>.addListener("click", (function(event){
					myLatlngSeccion = new google.maps.LatLng("<?= $secciones_ineDatosMapa[$key]['latitud'] ?>","<?= $secciones_ineDatosMapa[$key]['longitud'] ?>"); 
					infoWindowSeccion.setContent('<?= $div ?>');
					infoWindowSeccion.setPosition(myLatlngSeccion);
					infoWindowSeccion.open(mapSeccion);
				}));
				<?php
			}
			?>
			// Agregar un listener para detectar cambios en el mapa
			google.maps.event.addListener(mapSeccion, 'idle', function() {
				// Obtener los límites del mapa
				var bounds = mapSeccion.getBounds();
				var zoom = mapSeccion.getZoom();
				<?php
					foreach ($secciones_ineDatosMapa as $key => $value) {
						?>
						var vertices = secciones_areaSeccion<?= $key ?>.getPath().getArray();
						var visible = false;
						for (var i = 0; i < vertices.length; i++) {
							if (bounds.contains(vertices[i])) {
								// Si todos los vértices están dentro de los límites, mostrar el polígono
								var visible = true;
							}
						}
						if(visible){
							secciones_areaSeccion<?= $key ?>.setMap(mapSeccion);
						}else{
							//secciones_area<?= $key ?>.setMap(null);
						}
						<?php
					}
				?>
			});
			<?php
			foreach ($manzanas_ine_parametrosDatosMapa as $key => $value) {
				$manzanas_ineDatosMapa[$key]['numero'];
				$manzanas_ineDatosMapa[$key]['latitud'];
				$manzanas_ineDatosMapa[$key]['longitud'];
				$manzanas_ineDatosMapa[$key]['id_secciones_ine'];
				$porcentaje = ($manzanas_ineDatosMapa[$key]['ciudadanos'] / $manzanas_ineDatosMapa[$key]['lista_nominal']) * 100;
				if(is_nan($porcentaje)){
					$porcentaje = 0;
				}

				/*
				$div1 = '<div class="divMapaSeccion">
							<h4>Sección: '.$manzanas_ineDatosMapa[$key]['clave_seccion_ine'].'</h4>
							<h4>Manzana: '.$manzanas_ineDatosMapa[$key]['numero'].'</h4>
							<button class="btn btn-primary" onclick="manzanaSelect('.$manzanas_ineDatosMapa[$key]['id'].')">Asignar</button>
						</div>';
				*/
				$div = '<div class="divMapaSecciones">
							<div class="info_content">
								<div class="info_titulo">
									<h5>Información</h5><br>
								</div>
							</div>
							<div class="datos_seccion">
								<p>
									Sección: <b>'.$manzanas_ineDatosMapa[$key]['clave_seccion_ine'].'</b><br>
									Manzana: <b>'.$manzanas_ineDatosMapa[$key]['numero'].'</b><br>
									<button class="btn btn-primary" onclick="manzanaSelect('.$manzanas_ineDatosMapa[$key]['id'].')">Asignar</button>
								</p>
								<p>
									Lista Nominal: <b>'.number_format($manzanas_ineDatosMapa[$key]['lista_nominal'],0,'.',',').'</b><br>
									Ciudadanos: <b>'.number_format($manzanas_ineDatosMapa[$key]['ciudadanos'],0,'.',',').'</b><br>
									Porcentaje Avance: <b>'.number_format($porcentaje,2,'.',',').'%</b><br>
								</p>
							</div>
						</div>';

				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

				$paths = "";

				foreach ($value as $keyT => $valueT) {
					$path = "manzanas_ine_Seccion".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";

					$paths .= $path.",";
				}


				$strokeColor='#000000';
				$fillColor='#4287f5';

				?>
				
				manzanas_areaSeccion<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: '<?= $strokeColor ?>',
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: '<?= $fillColor ?>',
					fillOpacity: 0.2,
					zIndex:200,
				});
				manzanas_areaSeccion<?= $key ?>.setMap(mapSeccion);
				infoWindowSeccion = new google.maps.InfoWindow();
				manzanas_areaSeccion<?= $key ?>.addListener("click", (function(event){
					myLatlngSeccion = new google.maps.LatLng("<?= $manzanas_ineDatosMapa[$key]['latitud'] ?>","<?= $manzanas_ineDatosMapa[$key]['longitud'] ?>"); 
					infoWindowSeccion.setContent('<?= $div ?>');
					infoWindowSeccion.setPosition(myLatlngSeccion);
					infoWindowSeccion.open(mapSeccion);
				}));
				
				
				const label<?= $key ?> = new google.maps.Marker({
					label: {
						text: '<?= $manzanas_ineDatosMapa[$key]['numero'] ?>',
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
					map: mapSeccion,  // Inicialmente el label no se muestra en el mapa
				});
				const center<?= $key ?> = { lat: <?= $manzanas_ineDatosMapa[$key]['latitud'] ?>, lng: <?= $manzanas_ineDatosMapa[$key]['longitud'] ?> };
				label<?= $key ?>.setMap(mapSeccion);
				label<?= $key ?>.setPosition(center<?= $key ?>);
				google.maps.event.addListener(mapSeccion, 'zoom_changed', function() {
					//console.log(mapSeccion.getZoom());
					if (mapSeccion.getZoom() >= 16) {
						// Si el nivel de zoom es mayor o igual a 11, mostramos el label dentro del polígono
						if (google.maps.geometry.poly.containsLocation(center<?= $key ?>, manzanas_areaSeccion<?= $key ?>)) {
							label<?= $key ?>.setMap(mapSeccion);
							label<?= $key ?>.setPosition(center<?= $key ?>);
						} else {
							label<?= $key ?>.setMap(null);
						}
					} else {
					// Si el nivel de zoom es menor a 11, ocultamos el label
						label<?= $key ?>.setMap(null);
					}
				});
				infoWindowSeccion = new google.maps.InfoWindow();
				label<?= $key ?>.addListener("click", (function(event){
					const center<?= $key ?> = { lat: <?= $manzanas_ineDatosMapa[$key]['latitud'] ?>, lng: <?= $manzanas_ineDatosMapa[$key]['longitud'] ?> };
					infoWindowSeccion.setContent('<?= $div ?>');
					infoWindowSeccion.setPosition(center<?= $key ?>);
					infoWindowSeccion.open(mapSeccion);
				}));
				<?php
			}
			?>
			<?php
			if($latitud_punto!=''){
				?>
				const label = new google.maps.Marker({
					icon: {
						size: new google.maps.Size(10, 10),
						anchor: new google.maps.Point(0, 0),
						labelOrigin: new google.maps.Point(0, 0),
						scaledSize: new google.maps.Size(100, 30)
					},
					map: mapSeccion,  // Inicialmente el label no se muestra en el mapa
				});
				const center = { lat: <?= $latitud_punto ?>, lng: <?= $longitud_punto ?> };
				label.setMap(mapSeccion);
				label.setPosition(center);
				<?php
			}
			?>

		}
	</script>
	<div id="googleMapManzana" style="width:100%;height:400px;"></div>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMapManzana"></script>
	<script type="text/javascript">
		$(".loader").fadeOut(2000);
	</script>