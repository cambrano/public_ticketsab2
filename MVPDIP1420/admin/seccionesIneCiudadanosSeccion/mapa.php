<?php
	if($reload_mapa == ""){
		include __DIR__.'/../functions/security.php';
		include __DIR__.'/../functions/tool_xhpzab.php';
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
			include '../functions/manzanas_ine.php';
			include '../functions/manzanas_ine_parametros.php';
			$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
			$origen['id_seccion_ine'] = $id_seccion_ine;
			$manzanas_ineDatosMapa = manzanas_ineDatosMapa($origen);
			$manzanas_ine_parametrosDatosMapa = manzanas_ine_parametrosDatosMapa('','',$id_seccion_ine,'','','','','');

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
			unset($origen);
			$secciones_ineDatosMapa = secciones_ineDatosMapa($origen);
			$secciones_ine_ciudadanosDatosArray=secciones_ine_ciudadanosDatosArray($_POST['searchTable'][0],$orderby,$limit,$RowUser['id_perfil_usuario'],$RowUser['id']);

			foreach ($secciones_ine_ciudadanosDatosArray as $key => $value) {
				if($key==0){
					$latitud = $value['latitud'];
					$longitud = $value['longitud'];
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
						height: auto;
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
					.info_seccion_manzana{
						width:70%;
						float:left;
						height:40px;
						text-align:left;
						border: 1px solid #cecece;
						padding: 2px 2px 2px 9px ;
						background-color:#cecece;
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
						var infoWindowYo = new google.maps.InfoWindow({
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
								infoWindowYo.open(map, marker1);
							});
						});
						navigator.geolocation.getCurrentPosition(function (position) {
							const pos = {
								lat: position.coords.latitude,
								lng: position.coords.longitude,
							};

							// Centrar el mapa en la ubicación actual del usuario
							//map.setCenter(pos);

							// Crear el círculo con ondas alrededor de la ubicación actual
							const circle1 = new google.maps.Circle({
								strokeColor: '#dfeb34',
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillColor: '#dfeb34',
								fillOpacity: 0.35,
								map: map,
								center: pos,
								radius: 10, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
							});
							// Agregar el marcador azul
							const marker = new google.maps.Marker({
								position: pos,
								map: map,
								draggable: false, // Marcador no draggable
								icon: {
								path: google.maps.SymbolPath.CIRCLE,
								scale: 10,
								fillColor:'#eb3434',
								fillOpacity: 0.8,
								strokeWeight: 1,
								strokeColor: '#eb3434',
								},
							});
						});


						navigator.geolocation.getCurrentPosition(function (position) {
							const pos = {
								lat: position.coords.latitude,
								lng: position.coords.longitude,
							};

							// Centrar el mapa en la ubicación actual del usuario
							//map.setCenter(pos);

							// Crear el círculo con ondas alrededor de la ubicación actual
							const circle1 = new google.maps.Circle({
								strokeColor: '#eb3434',
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillColor: '#eb3434',
								fillOpacity: 0.35,
								map: map,
								center: pos,
								radius: 30, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
							});
						});

						navigator.geolocation.getCurrentPosition(function (position) {
							const pos = {
								lat: position.coords.latitude,
								lng: position.coords.longitude,
							};

							// Centrar el mapa en la ubicación actual del usuario
							//map.setCenter(pos);

							// Crear el círculo con ondas alrededor de la ubicación actual
							const circle1 = new google.maps.Circle({
								strokeColor: '#346beb',
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillColor: '#346beb',
								fillOpacity: 0.35,
								map: map,
								center: pos,
								radius: 60, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
							});
						});
						navigator.geolocation.getCurrentPosition(function (position) {
							const pos = {
								lat: position.coords.latitude,
								lng: position.coords.longitude,
							};

							// Centrar el mapa en la ubicación actual del usuario
							//map.setCenter(pos);

							// Crear el círculo con ondas alrededor de la ubicación actual
							const circle1 = new google.maps.Circle({
								strokeColor: '#eb3434',
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillColor: '#eb3434',
								fillOpacity: 0.35,
								map: map,
								center: pos,
								radius: 60, // Radio del círculo en metros (puedes ajustarlo según tus necesidades)
							});
						});
		
		
						var ciudadanos = [
						<?php
						///ciudadanos
						foreach ($secciones_ine_ciudadanosDatosArray as $key => $value) {
							echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'SI_SCRIPT'],";
						}
						?>
						];
						var infoWindowContent_ciudadanos = [
							<?php
							foreach ($secciones_ine_ciudadanosDatosArray as $key => $value){
								foreach ($value as $keyT => $valueT) {
									if($keyT != 'latitud' && $keyT != 'longitud' && $keyT != 'distancia_km') {
										$value[$keyT] = preg_replace('/[^A-Za-z0-9 :-]/', '', $valueT);
										//$value[$keyT] = preg_replace('/[^A-Za-z0-9 :-\.]/', '', $valueT);

									}
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
														Manzana: <b>'.$value['manzana'].'</b><br>
														D. Aprox: <b>'.$value['distancia_km'].' km</b><br>
														Nombre Completo: <b>'.$value['nombre_completo'].'</b><br>
														Whatsapp: <a href="https://api.whatsapp.com/send/?phone=52'.$value['whatsapp'].'&text&app_absent=0" target="_blank">'.$value['whatsapp'].'</a></b><br>
														Teléfono: <b>'.$value['telefono'].'</b><br>
														Celular: <b>'.$value['celular'].'</b><br>
														Dirección : <b>'.$value['calle'].", #".$value['num_ext'].", ".$value['colonia'].", ".$value['municipio'].', '.$estado_nombre.' </b><br>
														<a class="button-link" target="_blank" href="https://maps.google.com/?q='.$value['latitud'].','.$value['longitud'].'">Google Maps</a>
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
						var markers = [];
		
						for (var i = 0; i < ciudadanos.length; i++) {
							var marker = new google.maps.Marker({
								position: new google.maps.LatLng(ciudadanos[i][1], ciudadanos[i][2]),
								map: map,
								animation: google.maps.Animation.DROP,
								icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
								visible: false // hide markers initially
							});
							markers.push(marker);
							google.maps.event.addListener(marker, 'click', (function(marker, i) {
								return function() {
									infowindowCiudadanos.setContent(infoWindowContent_ciudadanos[i][0]);
									infowindowCiudadanos.open(map, marker);
								}
							})(marker, i));
						}
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
									if ($value['id'] == $id_seccion_ine) {
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
									if ($value['id'] == $id_seccion_ine) {
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
									if ($value['id'] == $id_seccion_ine) {
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
								if ($value['id'] == $id_seccion_ine) {
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
							<?php
							if($key==$id_seccion_ine){
								$color_label ='black';
							}else{
								$color_label ='white';
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
									color: '<?= $color_label ?>',
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
						<?php
						foreach ($manzanas_ineDatosMapa as $key => $value) {
							$porcentaje = ($value['ciudadanos'] / $value['lista_nominal']) * 100;
							if(is_nan($porcentaje)){
								$porcentaje = 0;
							}
							$div = '<div class="divMapaSecciones">
										<div class="info_content">
											<div class="info_titulo">
												<h5>Manzana:</h5>
											</div>
											<div class="info_seccion_manzana">
												<h5>'.$value['numero'].'</h5>
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
							$paths = "";
							foreach ($manzanas_ine_parametrosDatosMapa[$key] as $keyT => $valueT) {
								$path = "manzanas_ine_".$key."_".$keyT;
								echo $path." = [";
								foreach ($valueT as $keyH => $valueH) {
									echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
								}
								echo "];";
								$paths .= $path.",";
							}
							$strokeColor='#000000';
							$fillColor='#FFFF00';
							?>
							manzanas_area<?= $key ?> = new google.maps.Polygon({
								paths: [<?= $paths ?>],
								strokeColor: '<?= $strokeColor ?>',
								strokeOpacity: 0.8,
								strokeWeight: 1,
								fillColor: '<?= $fillColor ?>',
								fillOpacity: 0.55,
								zIndex:2,
							});
							//manzanas_area<?= $key ?>.setMap(map);
							infoWindow = new google.maps.InfoWindow();
							manzanas_area<?= $key ?>.addListener("click", (function(event){
								myLatlng = new google.maps.LatLng("<?= $manzanas_ineDatosMapa[$key]['latitud'] ?>","<?= $manzanas_ineDatosMapa[$key]['longitud'] ?>"); 
								infoWindow.setContent('<?= $div ?>');
								infoWindow.setPosition(myLatlng);
								infoWindow.open(map);
							}));
							/* ocultamos el label de las manzanas ab
							const labelManzana<?= $key ?> = new google.maps.Marker({
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
								map: map,  // Inicialmente el label no se muestra en el mapa
							});
							const center<?= $key ?> = { lat: <?= $value['latitud'] ?>, lng: <?= $value['longitud'] ?> };
							labelManzana<?= $key ?>.setMap(map);
							labelManzana<?= $key ?>.setPosition(center<?= $key ?>);
							google.maps.event.addListener(map, 'zoom_changed', function() {
								//console.log(mapSeccion.getZoom());
								if (map.getZoom() >= 16) {
									// Si el nivel de zoom es mayor o igual a 11, mostramos el label dentro del polígono
									if (google.maps.geometry.poly.containsLocation(center<?= $key ?>, manzanas_area<?= $key ?>)) {
										labelManzana<?= $key ?>.setMap(map);
										labelManzana<?= $key ?>.setPosition(center<?= $key ?>);
									} else {
										labelManzana<?= $key ?>.setMap(null);
									}
								} else {
								// Si el nivel de zoom es menor a 11, ocultamos el label
								labelManzana<?= $key ?>.setMap(null);
								}
							});
							*/
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
								foreach ($manzanas_ineDatosMapa as $key => $value) {
									?>
									var vertices = manzanas_area<?= $key ?>.getPath().getArray();
									var visible = false;
									for (var i = 0; i < vertices.length; i++) {
										if (bounds.contains(vertices[i])) {
											// Si todos los vértices están dentro de los límites, mostrar el polígono
											var visible = true;
										}
									}
									if(visible){
										manzanas_area<?= $key ?>.setMap(map);
									}else{
										//manzanas_area<?= $key ?>.setMap(null);
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
					Manzanas totales : <b><?= COUNT($manzanas_ineDatosMapa) ?></b>
					<br>
					Registros Mostrados: <b><?= number_format(count($secciones_ine_ciudadanosDatosArray),0,",",".") ?></b>
				</div>
				<div id="mapa" style="width:100%;height:400px;"></div>
				<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
				<?php
			}
		}
	}
?>