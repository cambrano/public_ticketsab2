<?php
	include __DIR__.'/../functions/security.php'; 

	@session_start();
	$tipo_perfil_usuario = $RowUser['id_perfil_usuario'];
	$columns = array( 
		// datatable column index  => database column name
		0 =>'seccion',
		1 =>'militante_registro',
		/*2 =>'militante_registro',*/
		2 =>'clave_elector',
		3 =>'curp',
		4 =>'nombre',
		5 =>'apellido_paterno',
		6 =>'apellido_materno',
		7 =>'fecha_nacimiento',
		8 =>'calle',
		9 =>'num_int',
		10 =>'num_ext',
		11 =>'colonia',
		12 =>'municipio',
		13 =>'localidad',
		14 =>'gps',
	);
	if(!empty($_POST)){
		setcookie("searchTableLN", json_encode($_POST['searchTable'][0]),time()+(60*60*8),"/",false);
		setcookie("searchOpcionesLN", json_encode($_POST['searchOpciones'][0]),time()+(60*60*8),"/",false);
		include '../functions/secciones_ine.php';
		include '../functions/secciones_ine_parametros.php';
		include '../functions/lista_nominal.php';
		$zoom = 11;
		if($_POST['mapa'][0]['order']==""){
			$_POST['mapa'][0]['order'] =0;
		}
		if($_POST['mapa'][0]['order_tipo']==""){
			$_POST['mapa'][0]['order_tipo'] ="desc";
		}
		
		//$orderby = ' ORDER BY clave DESC';
		$pagina = $_POST['mapa'][0]['pagina'];
		$total_registros= $_POST['searchOpciones'][0]['tipo_limite'];
		$pagina = $_POST['mapa'][0]['pagina'];
		$order = $_POST['mapa'][0]['order'];
		$order_tipo = $_POST['mapa'][0]['order_tipo'];
		$orderby = " ORDER BY {$columns[$order]} {$order_tipo} ";
		$mostrardesde = $pagina * $total_registros;
		if($total_registros!='x'){
			$limit = "LIMIT ".$mostrardesde.",$total_registros";
		}


		$id_secciones_ine = explode(",", $_POST['searchTable'][0]['id_seccion_ine']);
		$id_cuartel = explode(",", $_POST['searchTable'][0]['id_cuartel']);
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','','','','');
		$lista_nominalDatosArray=lista_nominalDatosArray($_POST['searchTable'][0],$orderby,$limit,$RowUser['id_perfil_usuario'],$RowUser['id']);
		$secciones_ineDatosMapa = secciones_ineDatosForm($origen);

		$gps_vacio = 0;
        foreach ($lista_nominalDatosArray as $key => $value) {
			$ciudadanos_secciones_ine[$value['id_seccion_ine']] = $value['id_seccion_ine'];
            if($gps_vacio==0 && $value['latitud']!=''){
				$gps_vacio = 1;
                $latitud = $value['latitud'];
                $longitud = $value['longitud'];
            }
        }
		include __DIR__."/../functions/manzanas_ine.php";
        include __DIR__."/../functions/manzanas_ine_parametros.php";
		$origen['id_seccion_ine'] = $ciudadanos_secciones_ine;
		$manzanas_ineDatosMapa = manzanas_ineDatosMapaSinInfo($origen);
        $manzanas_ine_parametrosDatosMapa = manzanas_ine_parametrosDatosMapa('','',$ciudadanos_secciones_ine,'','','','','');
	}

?> 
	<div style="width:100%;padding:10px;font-size:18px">
		Registros Mostrados: <b><?= number_format(count($lista_nominalDatosArray),0,",",".") ?></b>
	</div>
<?php
	if(!empty($lista_nominalDatosArray)){
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
				height:60px;
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
					height: 80px;
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

				// generar lista de puntos una sola vez
				var puntos = [
					<?php
					foreach ($lista_nominalDatosArray as $key => $value) {
						if($value['latitud']==''|| $value['latitud']=='2' ){
							$value['latitud'] = $latitud;
							$value['longitud'] = $longitud;
						}
						echo "new google.maps.LatLng(".$value['latitud'].",".$value['longitud']."),";
					}
					?>
				];

				// crear heatmap con los datos y el radio del área de calor
				var heatmap = new google.maps.visualization.HeatmapLayer({
					data: puntos,
					map: map,
					radius: 20, 
				});

				// actualizar los puntos de la capa de calor cuando cambie la vista del mapa
				map.addListener('bounds_changed', function() {
					var bounds = map.getBounds();
					var visiblePuntos = [];

					// seleccionar sólo los puntos visibles en la vista actual
					for (var i = 0; i < puntos.length; i++) {
						if (bounds.contains(puntos[i])) {
							visiblePuntos.push(puntos[i]);
						}
					}
					
					heatmap.setData(visiblePuntos);
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
				

				<?php
					foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
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
									$fillOpacity = '0.19';
								}else{
									$strokeColor='#001A36';
									$fillColor='#001A36';
									$fillOpacity = '0.19';
								}
							}else{
								$strokeColor='#36000B';
								$fillColor='#36000B';
								$fillOpacity = '0.4';
							}
						}elseif($tipo_uso_plataforma=='distrito_local'){
							if ($secciones_ineDatosMapa[$key]['id_distrito_local'] == $id_distrito_local ){
								if (in_array($secciones_ineDatosMapa[$key]['id'], $id_secciones_ine)) {
									$strokeColor='#000000';
									$fillColor='#000000';
									$fillOpacity = '0.19';
								}else{
									$strokeColor='#001A36';
									$fillColor='#001A36';
									$fillOpacity = '0.19';
								}
							}else{
								$strokeColor='#36000B';
								$fillColor='#36000B';
								$fillOpacity = '0.4';
							}
						}elseif($tipo_uso_plataforma=='distrito_federal'){
							if ($secciones_ineDatosMapa[$key]['id_distrito_federal'] == $id_distrito_federal ){
								if (in_array($secciones_ineDatosMapa[$key]['id'], $id_secciones_ine)) {
									$strokeColor='#000000';
									$fillColor='#000000';
									$fillOpacity = '0.19';
								}else{
									$strokeColor='#001A36';
									$fillColor='#001A36';
									$fillOpacity = '0.19';
								}
							}else{
								$strokeColor='#36000B';
								$fillColor='#36000B';
								$fillOpacity = '0.4';
							}
						}else{
							if (in_array($secciones_ineDatosMapa[$key]['id'], $id_secciones_ine)) {
								$strokeColor='#000000';
								$fillColor='#000000';
								$fillOpacity = '0.19';
							}else{
								if($_POST['searchTable'][0]['id_municipio'] == $secciones_ineDatosMapa[$key]['id_municipio'] && $_POST['searchTable'][0]['id_municipio']!='' ){
									$strokeColor='#001A36';
									$fillColor='#001A36';
									$fillOpacity = '0.19';
								}elseif($_POST['searchTable'][0]['id_distrito_local'] == $secciones_ineDatosMapa[$key]['id_distrito_local'] && $_POST['searchTable'][0]['id_distrito_local']!='' ){
									$strokeColor='#001A36';
									$fillColor='#001A36';
									$fillOpacity = '0.19';
								}elseif($_POST['searchTable'][0]['id_distrito_federal'] == $secciones_ineDatosMapa[$key]['id_distrito_federal'] && $_POST['searchTable'][0]['id_distrito_federal']!='' ){
									$strokeColor='#001A36';
									$fillColor='#001A36';
									$fillOpacity = '0.19';
								}else{
									$strokeColor='#36000B';
									$fillColor='#36000B';
									$fillOpacity = '0.4';
								}
							}
						}
						?>
						secciones_area<?= $key ?> = new google.maps.Polygon({
							paths: [<?= $paths ?>],
							strokeColor: '<?= $strokeColor ?>',
							strokeOpacity: 0.8,
							strokeWeight: 2,
							fillColor: '<?= $fillColor ?>',
							fillOpacity: '<?= $fillOpacity ?>',
						});
						//secciones_area<?= $key ?>.setMap(map);
						const label<?= $key ?> = new google.maps.Marker({
							label: {
								text: '<?= $secciones_ineDatosMapa[$key]['numero'] ?>',
								color: 'red',
								fontSize: '25px'
							},
							icon: {
								url: '',
								size: new google.maps.Size(20, 20),
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
				<?php
					foreach ($manzanas_ine_parametrosDatosMapa as $key => $value) {
						$div = '<div class="divMapaSecciones\"><div class="info_content"><div class="info_titulo" style="width:100%"><h5>Información</h5><br></div></div><div class="datos_seccion"><p>Sección: <b>'.$manzanas_ineDatosMapa[$key]['clave_seccion_ine'].'</b><br>Manzana: <b>'.$manzanas_ineDatosMapa[$key]['numero'].'</b><br></p></div></div>';
						$div = preg_replace("/[\r|\n|\r]+/", " ", $div);
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
						//manzanas_areaSeccion<?= $key ?>.setMap(map);
						const labelManzana<?= $key ?> = new google.maps.Marker({
							position: new google.maps.LatLng(<?= $manzanas_ineDatosMapa[$key]['latitud'] ?>, <?= $manzanas_ineDatosMapa[$key]['longitud'] ?>),
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
							map: null,
						});
						//labelManzana<?= $key ?>.setMap(map);
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
								if (map.getZoom() >= 14) {
									label<?= $key ?>.setMap(map);
								}else{
									label<?= $key ?>.setMap(null);
								}
							}else{
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
								secciones_area<?= $key ?>.setMap(null);
							}
							<?php
						}
					?>
					<?php
						foreach ($manzanas_ineDatosMapa as $key => $value) {
							?>
							// Verificar si los marcadores están dentro de los límites del mapa
							if (bounds.contains(labelManzana<?= $key ?>.getPosition())) {
								//console.log(map.getZoom())
								if (map.getZoom() >= 16) {
									labelManzana<?= $key ?>.setMap(map);
									manzanas_areaSeccion<?= $key ?>.setMap(map);
								}else{
									labelManzana<?= $key ?>.setMap(null);
									manzanas_areaSeccion<?= $key ?>.setMap(null);
								}
							} else {
								labelManzana<?= $key ?>.setMap(null);
								manzanas_areaSeccion<?= $key ?>.setMap(null);
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
		<div id="mapa" style="width:100%;height:600px;"></div>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap&libraries=visualization&v=weekly" defer></script>
		<?php
	}
	?>

	