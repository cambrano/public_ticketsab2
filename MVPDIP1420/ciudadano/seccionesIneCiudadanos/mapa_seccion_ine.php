<?php
    if(!empty($_POST['mapa'][0])){
        $id_seccion_ine = $_POST['mapa'][0]['id_seccion_ine'];
        include __DIR__."/../functions/secciones_ine.php";
        include __DIR__."/../functions/secciones_ine_parametros.php";
        include __DIR__."/../functions/manzanas_ine.php";
        include __DIR__."/../functions/manzanas_ine_parametros.php";

        $secciones_ineDatosMapa = secciones_ineDatosForm();
        $secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
        $origen['id_seccion_ine'] = $id_seccion_ine;
        $manzanas_ineDatosMapa = manzanas_ineDatosMapa($origen);
        $manzanas_ine_parametrosDatosMapa = manzanas_ine_parametrosDatosMapa('','',$id_seccion_ine,'','','','','');
        $latitud = $_POST['mapa'][0]['latitud'];
        $longitud = $_POST['mapa'][0]['longitud'];
    }
    if(!empty($id)){
        $origen_seccion['id_seccion_ine'] = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
        $manzanas_ineDatosMapa = manzanas_ineDatosMapaSinInfo($origen_seccion);
        $manzanas_ine_parametrosDatosMapa = manzanas_ine_parametrosDatosMapa('','',$seccion_ine_ciudadanoDatos['id_seccion_ine'],'','','','','');
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
		function myMap(coordenadas=null,zoomCoordenada=null) {
			tipo_update="<?= $id ?>";
			
			if(tipo_update != ""){
				latitud="<?= $seccion_ine_ciudadanoDatos['latitud'] ?>";
				longitud="<?= $seccion_ine_ciudadanoDatos['longitud'] ?>";
				zoom=15;
			}else{
                if(coordenadas==null && zoomCoordenada==null){
                    latitud = "<?= $latitud ?>";
                    longitud = "<?= $longitud ?>";
                    zoom=15;
                }
                if(coordenadas != null && zoomCoordenada ==null){
                    var latitud = coordenadas.coords.latitude;
                    var longitud = coordenadas.coords.longitude; 
                    document.getElementById("latitud_r").value = latitud;
                    document.getElementById("longitud_r").value = longitud;
                    zoom=15;
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

			var myLatlng = new google.maps.LatLng( latitud,longitud); 
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
			var map = new google.maps.Map(document.getElementById("googleMap"), myOptions); 
			marker = new google.maps.Marker({ 
				position: myLatlng,
				draggable: true,  
			});
			google.maps.event.addListener(marker, "dragend", function() { 
							getCoords(marker); 
			});
			marker.setMap(map); 
			getCoords(marker);


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
								<!--<button class="btn btn-primary" onclick="seccionSelect('.$secciones_ineDatosMapa[$key]['id'].')">Asignar</button>-->
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
								<!---<button class="btn btn-primary" onclick="seccionSelect('.$secciones_ineDatosMapa[$key]['id'].')">Asignar</button>-->
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
					$path = "secciones_ine_".$key."_".$keyT;
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

				secciones_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: '<?= $strokeColor ?>',
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: '<?= $fillColor ?>',
					fillOpacity: 0.35,
				});
				//secciones_area<?= $key ?>.setMap(map);
				/*
				infoWindow = new google.maps.InfoWindow();
				secciones_area<?= $key ?>.addListener("click", (function(event){
					myLatlng = new google.maps.LatLng("<?= $secciones_ineDatosMapa[$key]['latitud'] ?>","<?= $secciones_ineDatosMapa[$key]['longitud'] ?>"); 
					infoWindow.setContent('<?= $div ?>');
					infoWindow.setPosition(myLatlng);
					infoWindow.open(map);
				}));
				*/

				const label<?= $key ?> = new google.maps.Marker({
					label: {
						text: '<?= $secciones_ineDatosMapa[$key]['numero'] ?>',
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
					position: {lat: <?= $secciones_ineDatosMapa[$key]['latitud'] ?>, lng: <?= $secciones_ineDatosMapa[$key]['longitud'] ?>},
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
									<!---<button class="btn btn-primary" onclick="manzanaSelect('.$manzanas_ineDatosMapa[$key]['id'].')">Asignar</button>-->
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
					$path = "manzanas_ine_".$key."_".$keyT;
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
				
				manzanas_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: '<?= $strokeColor ?>',
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: '<?= $fillColor ?>',
					fillOpacity: 0.2,
					zIndex:2,
				});
				manzanas_area<?= $key ?>.setMap(map);
				infoWindow = new google.maps.InfoWindow();
				manzanas_area<?= $key ?>.addListener("click", (function(event){
					myLatlng = new google.maps.LatLng("<?= $manzanas_ineDatosMapa[$key]['latitud'] ?>","<?= $manzanas_ineDatosMapa[$key]['longitud'] ?>"); 
					infoWindow.setContent('<?= $div ?>');
					infoWindow.setPosition(myLatlng);
					infoWindow.open(map);
				}));
				

				<?php
			}
			?>
			//var controlMapaCambio = document.createElement('DIV');
			//controlMapaCambio.className = "mapaCambioPoligono";
			//controlMapaCambio.setAttribute("id", "mapaCambioPoligono");
			//controlMapaCambio.style.cursor = 'pointer';
			//controlMapaCambio.style.height = '64px';
			//controlMapaCambio.style.width = '64px';
			//controlMapaCambio.style.top = '11px';
			//controlMapaCambio.style.left = '120px';
			//controlMapaCambio.title = 'Mostar Colonias';
			//controlMapaCambio.addEventListener('click', mapaCambioPoligono);
			//controlMapaCambio.addEventListener("mouseover", mouseOver, false);
			//map.controls[google.maps.ControlPosition.TOP_LEFT].push(controlMapaCambio);

			var controlHiddenPoligono = document.createElement('DIV');
			controlHiddenPoligono.className = "mapaHiddenPoligono";
			controlHiddenPoligono.setAttribute("id", "mapaHiddenPoligono");
			//controlMapaCambio.style.cursor = 'pointer';
			//controlMapaCambio.style.height = '64px';
			//controlMapaCambio.style.width = '64px';
			//controlMapaCambio.style.top = '11px';
			//controlMapaCambio.style.left = '120px';
			controlHiddenPoligono.title = 'Quitar Capas';
			controlHiddenPoligono.addEventListener('click', mapaHiddenPoligono);
			//controlMapaCambio.addEventListener("mouseover", mouseOver, false);
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(controlHiddenPoligono);


			<?php
			foreach ($manzanas_ineDatosMapa as $key => $value) {
				# code...
			}

			?>


		}
		function getCoords(marker){ 
			document.getElementById("latitud").value=marker.getPosition().lat(); 
			document.getElementById("longitud").value=marker.getPosition().lng(); 
		}
		function mapaCambioPoligono(){
			alert('ir a colonias');
		}
		function mapaHiddenPoligono(){
			var latitud = document.getElementById("latitud").value;
			var longitud = document.getElementById("longitud").value;
			var mapa = [];
			var data = {   
					'latitud' : latitud, 
					'longitud' : longitud, 
				}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/mapahidden.php",
				data: {mapa:mapa},
				async: true,
				success: function(data) {
					$("#mapaLoad").html(data);
				}
			});
		}
	</script>
	<style>
		.mapaCambioPoligono{
			cursor:pointer; 
			height:54px; 
			width:64px; 
			top:11px; 
			left:120px;
			background-image: url(img/gps.png);
		}
		.mapaCambioPoligono:hover{
			opacity: 0.8;
			filter: alpha(opacity=80);
		}
		.mapaCambioPoligono:active{
			opacity: 0.9;
			filter: alpha(opacity=90);
		}
		.mapaHiddenPoligono{
			cursor:pointer; 
			height:54px; 
			width:64px; 
			top:11px; 
			left:190px;
			background-image: url(img/maphidden.png);
		}
		.mapaHiddenPoligono:hover{
			opacity: 0.8;
			filter: alpha(opacity=80);
		}
		.mapaHiddenPoligono:active{
			opacity: 0.9;
			filter: alpha(opacity=90);
		}
		@media only screen and (max-width:992px) {
			.mapaCambioPoligono{ 
				top:11px; 
				left:120px;
			}
			.mapaHiddenPoligono{ 
				top:11px; 
				left:190px;
			}
		}
	</style>
	<div id="googleMap" style="width:100%;height:400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
	<script type="text/javascript">
		$(".loader").fadeOut(2000);
	</script>