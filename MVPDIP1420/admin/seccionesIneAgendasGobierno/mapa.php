<?php
	if($reload_mapa == ""){
		include __DIR__.'/../functions/security.php'; 
		include __DIR__.'/../functions/timemex.php'; 
		@session_start();
		$columns = array(
			0 =>'clave',
			1 =>'tipo_gira',
			2 =>'dependencia_coordinadora',
			3 =>'eje_gobierno',
			4 =>'nombre',
			5 =>'fecha_hora',
			6 =>'num_beneficiarios',
			7 =>'num_asistentes',
			8 =>'observaciones',
			9 =>'municipio',
			10 =>'localidad',
			11 =>'colonia',
			12 =>'seccion',
			13 =>'distrito_local',
			14 =>'distrito_federal',
		);
		
		if(!empty($_POST)){
			include '../functions/secciones_ine.php';
			include '../functions/secciones_ine_parametros.php';
			include '../functions/secciones_ine_agendas_gobierno.php';
			include '../functions/secciones_ine_agendas_gobierno_puntos.php';
			$punto=false;
			foreach ($_POST['searchTable'][0] as $key => $value) {
				if($value!=""){
					$punto=true;
				}
			}
			$zoom = 14;
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
			$alias_array = array('municipio','localidad','tipo_gira','eje_gobierno','dependencia_coordinadora','fecha_hora','colonia','seccion','distrito_local','distrito_federal');
			if (in_array($columns[$order], $alias_array)) {
				$orderby = " ORDER BY {$columns[$order]} {$order_tipo} ";
			}else{
				$orderby = " ORDER BY sia.{$columns[$order]} {$order_tipo} ";
			}
			//$orderby = " ORDER BY sia.{$columns[$order]} {$order_tipo} ";
			$mostrardesde = $pagina * $total_registros;
			$limit = "LIMIT {$mostrardesde},11";

			if($tipo_uso_plataforma=='municipio'){
				$_POST['searchTable'][0]['id_municipio']=$id_municipio;
				$search_map['id_municipio']=$id_municipio;
			}elseif($tipo_uso_plataforma=='distrito_local'){
				$_POST['searchTable'][0]['id_distrito_local']=$id_distrito_local;
				$search_map['id_distrito_local']=$id_distrito_local;
			}elseif($tipo_uso_plataforma=='distrito_federal'){
				$_POST['searchTable'][0]['id_distrito_federal']=$id_distrito_federal;
				$search_map['id_distrito_federal']=$id_distrito_federal;
			}
			$secciones_ine_agendas_gobiernoDatosArray=secciones_ine_agendas_gobiernoDatosArray($_POST['searchTable'][0],$orderby,$limit);
			//echo count($secciones_ine_agendas_gobiernoDatosArray);
			$secciones_ineDatosMapa = secciones_ineDatosForm($search_map);
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,$id_distrito_local,$id_distrito_federal,'','');

			if($punto==false){
				$zoom = 14;
				if($secciones_ine_agendas_gobiernoDatosArray[0]['latitud'] != '' ){
					$latitud = $secciones_ine_agendas_gobiernoDatosArray[0]['latitud'];
					$longitud = $secciones_ine_agendas_gobiernoDatosArray[0]['longitud'];
				}
			}else{
				$zoom = 14;
			}
			/*
			echo date("H:i:s");
			echo "<pre>";
			echo "<table border=1>";
			foreach ($secciones_ine_agendas_gobiernoDatosArray as $key => $value) {
					echo "<tr>";
					foreach ($value as $keyT => $valueT) {
						if($keyT=='locaciones'){
							echo "<td style='padding:10px'>";
							//var_dump($valueT);
							echo "</td>";
						}else{
							echo "<td style='padding:10px'>";
							echo $valueT;
							echo "</td>";
						}
					}
					echo "</tr>";
			}
			echo "</table>";
			echo "</pre>";
			*/
		}else{
			$zoom = 14;
			$order = 0;
			$order_tipo = "desc";
			//$orderby = ' ORDER BY clave DESC';
			$orderby = " ORDER BY sia.{$columns[$order]} {$order_tipo} ";
			$limit = ' LIMIT 0,11 ';
			if($tipo_uso_plataforma=='municipio'){
				$_POST['searchTable'][0]['id_municipio']=$id_municipio;
				$search_map['id_municipio']=$id_municipio;
			}elseif($tipo_uso_plataforma=='distrito_local'){
				$_POST['searchTable'][0]['id_distrito_local']=$id_distrito_local;
				$search_map['id_distrito_local']=$id_distrito_local;
			}elseif($tipo_uso_plataforma=='distrito_federal'){
				$_POST['searchTable'][0]['id_distrito_federal']=$id_distrito_federal;
				$search_map['id_distrito_federal']=$id_distrito_federal;
			}
			//$secciones_ine_agendas_gobiernoDatosArray=secciones_ine_agendas_gobiernoDatosArray($_POST['searchTable'][0],$orderby,$limit);
			//$secciones_ineDatosMapa = secciones_ineDatosForm($search_map);
			//$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,$id_distrito_local,$id_distrito_federal,'','');
		}

		foreach ($secciones_ine_agendas_gobiernoDatosArray as $key => $value) {
			$id_seccion_ine_agenda_gobiernos[] = $value['id'];
			if($key==0){
				$latitud = $value['latitud'];
				$longitud = $value['longitud'];
			}
		}

		//$secciones_ine_agendas_gobierno_puntosDatosMapa = secciones_ine_agendas_gobierno_puntosDatosMapa('','',$id_seccion_ine_agenda_gobiernos);


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
		<style type="text/css">
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
			.info_seccion_ganador{
				width:100%;
				float:left;
				height:auto;
				text-align:left;
				border: 1px solid #cecece;
				padding: 2px 2px 2px 9px ;
				background-color:#cecece;
			}
			.info_seccion_ganador_button{
				width:100%;
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
				width:30%;
				float:left;
				height:70px;
				text-align:left;
				border: 1px solid gray;
				padding: 4px 0px 4px 10px;
			}
			.datos_right,.datos_right_bottom{
				width:70%;
				float:left;
				height:70px;
				text-align:left;
				border: 1px solid gray;
				padding: 4px 0px 4px 10px;
			}
			.datos_top{
				width:70%;
				float:left;
				height: auto;
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
					height:490px;
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
				.datos_top,.datos_right,.datos_left{
					width:100%;
					height: auto;
				}
				.datos_right_bottom{
					width:100%;
					height: 120px
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
				zoom = 14;
				var latitud = '<?=$latitud ?>';
				var longitud = '<?=$longitud ?>';
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
				<?php
				foreach ($secciones_ineDatosMapa as $key => $value) {
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
					secciones_area<?= $key ?>.addListener("click", (function(event){
						myLatlng = new google.maps.LatLng("<?= $secciones_ineDatosMapa[$key]['latitud'] ?>","<?= $secciones_ineDatosMapa[$key]['longitud'] ?>"); 
						infoWindow.setContent('<?= $div ?>');
						infoWindow.setPosition(myLatlng);
						infoWindow.open(map);
					}));
					infoWindow = new google.maps.InfoWindow();
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


				var giras = [
				<?php
				///giras
				foreach ($secciones_ine_agendas_gobiernoDatosArray as $keyP => $valueP) {
					foreach ($valueP['locaciones'] as $keyT => $valueT) {
						echo "['".$valueP['id']."', ".$valueT['latitud'].", ".$valueT['longitud'].",'".$valueT['tipo']."'],";
						$puntos[]=1;
					}
				}
				?>
				];
				var infoWindowContent_giras = [
					<?php
					foreach ($secciones_ine_agendas_gobiernoDatosArray as $key => $value){
						foreach ($value['locaciones'] as $keyZZ => $valueZZ) {
							foreach ($value as $keyT => $valueT) {
								if($keyT!='monto_total'){
									//$value[$keyT] = preg_replace('([^A-Za-z0-9 :-])', '', $valueT);
								}
							}
							if($value['superindice'] != ''){
								$sup_indice=$value['unidad'].'<sup>'.$value['superindice'].'</sup>';
							}else{
								$sup_indice=$value['unidad'];
							}
							$div = '<div class="divMapa">
										<div class="info_content">
											<h4>Clave: '.$value['clave'].'</h4>
											<div class="info_titulo">
												<h5>Tipo:</h5>
											</div>
											<div class="info_seccion_ganador">
												<h5>'.mb_strtoupper($value['tipo_gira'], 'UTF-8').'</h5>
											</div>
											<div class="info_seccion_ganador_button">
												<button class="button button4" onclick="edit('.$value['id'].')">Ver Más</button>
											</div>
										</div>
										<div class="datos_top" style="width:100%;">
											Nombre: <b><font style="">'.$value['nombre'].'</font></b><br>
											Eje Gobierno: <b><font style="">'.$value['eje_gobierno'].'</font></b><br>
											Dependencia: <b><font style="">'.$value['dependencia_coordinadora'].'</font></b><br><br>
											Num Asistentes: <b><font style="">'.$value['num_asistentes'].'</font></b><br>
											Num Beneficiario: <b><font style="">'.$value['num_beneficiarios'].'</font></b><br>
										</div>
										<div class="datos_top" style="width:100%;">
											<p>
												Distrito Local(es): <b>'.$value['distrito_local'].'</b><br>
												Distrito Federal(es): <b>'.$value['distrito_federal'].'</b><br>
												Sección(es): <b>'.$value['seccion'].'</b><br>
											</p>
										</div>
										<div class="datos_top" style="width:100%;">
											<p>
												';
												$lastKey = array_key_last($value['locaciones']); // Obtener la última clave del array
												if(!empty($value['locaciones'])){
													$div .= "Evento(s)<br>";
												}
												foreach ($value['locaciones'] as $keyT => $valueT) {
													$fechas = explode(" ", $valueT['fecha_hora']);
													$div .= "<b>".fechaNormalSimpleWDDMMAA_ES($fechas[0])."<br>".$fechas[1]."</b><br>";
													// Agregar <br><br> solo si no es el último elemento
													$div .= "Sección: <b>".$valueT['seccion_ine']."</b><br>";
													$div .= 'Dirección : <b>'.$valueT['calle'].", ".$valueT['colonia'].", ".$valueT['codigo_postal'].", ".$valueT['municipio'].', '.$estado_nombre.' </b>';
													if ($keyT !== $lastKey) {
														$div .= "<br><br>";
													}
												}
											$div .= '
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

				var infowindowCiudadanos = new google.maps.InfoWindow();
				var markerMilitates, i;
				//pinImageGreen = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
				var icon_junta = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/iconos_partidos/puntero_junta.png',
					scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
				};
				var icon_visita = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/iconos_partidos/puntero_visita.png',
					scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
				};
				var icon_caminata = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/iconos_partidos/puntero_caminata.png',
					scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
				};

				for (i = 0; i < giras.length; i++) {
					if (giras[i][3]=='visita'){
						markerMilitates = new google.maps.Marker({
							position: new google.maps.LatLng(giras[i][1], giras[i][2]),
							map: map,
							icon: icon_visita,
						});
					}else if(giras[i][3]=='caminata'){
						markerMilitates = new google.maps.Marker({
							position: new google.maps.LatLng(giras[i][1], giras[i][2]),
							map: map,
							icon: icon_caminata,
						});
					}else{
						markerMilitates = new google.maps.Marker({
							position: new google.maps.LatLng(giras[i][1], giras[i][2]),
							map: map,
							icon: icon_junta,
						});
					}

					google.maps.event.addListener(markerMilitates, 'click', (function(markerMilitates, i) {
						return function() {
							infowindowCiudadanos.setContent(infoWindowContent_giras[i][0]);
							infowindowCiudadanos.open(map, markerMilitates);
						}
					})(markerMilitates, i));
				}
			}
			function getCoordsLimites(marker){ 
				//var latitud=document.getElementById("latitud").value=marker.getPosition().lat();
				// var longitud=document.getElementById("longitud").value=marker.getPosition().lng(); 
			}
		</script> 
		<div id="mapa" style="width:100%;height:400px;"></div>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
		<?php
	}
?>