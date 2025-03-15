<?php
	include __DIR__.'/../functions/security.php'; 

	@session_start();
	$tipo_perfil_usuario = $RowUser['id_perfil_usuario'];
	/*
	$_SESSION['reporte_Sistema']['columnas_sql'] = array(
		0 =>'clave', 
		1 =>'folio', 
		2 =>'curp',
		3 =>'clave_elector',
		4 =>'ocr',
		5 =>'tipo_seccion',
		6 =>'seccion',
		7 =>'manzana',
		8 =>'distrito_local',
		9 =>'distrito_federal',
		10 =>'distancia_km',
		11 =>'tipo_ciudadano',
		12 =>'relacionado',
		13 =>'nombre_completo',
		14 =>'sexo',
		15 =>'fecha_nacimiento',
		16 =>'whatsapp',
		17 =>'celular',
		18 =>'telefono',
		19 =>'correo_electronico',
		20 =>'municipio',
		21 =>'localidad',
		22 =>'latitud',
		23 =>'longitud',
		24 =>'categorias',
		25 =>'medio_registro',
		26 =>'distancia_alert',
		27 =>'seguimientos',
		28 =>'status_verificacion',
		29 =>'documentos_oficiales',
		30 =>'programas_apoyos',
		31 =>'programas_apoyos_categorias',
		32 =>'militantes_partidos',
	);*/
	$_SESSION['reporte_Sistema']['columnas_nombres'] = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'curp' ,'nombre' => 'C.U.R.P' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'clave_elector' ,'nombre' => 'Clave Elector' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'ocr' ,'nombre' => 'OCR' ,'tipo' => 'string','mostrar' => 0 ),
		5 => array('row' => 'tipo_seccion' ,'nombre' => 'Tipo Sección','tipo' => 'string','mostrar' => 1),
		6 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1),
		7 => array('row' => 'manzana' ,'nombre' => 'Manzana' ,'tipo' => 'string','mostrar' => 1),
		8 => array('row' => 'distrito_local' ,'nombre' => 'D. Local' ,'tipo' => 'string','mostrar' => 1),
		9 => array('row' => 'distrito_federal' ,'nombre' => 'D. Federal' ,'tipo' => 'string','mostrar' => 1),
		10 => array('row' => 'distancia_km' ,'nombre' => 'D.(km) Aprox' ,'tipo' => 'string','mostrar' => 1),
		11 => array('row' => 'tipo_ciudadano' ,'nombre' => 'Tipo Ciudadano' ,'tipo' => 'string','mostrar' => 1),
		12 => array('row' => 'relacionado' ,'nombre' => 'Relacionado' ,'tipo' => 'string','mostrar' => 0),
		13 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo','tipo' => 'string','mostrar' => 1),
		14 => array('row' => 'sexo' ,'nombre' => 'Sexo' ,'tipo' => 'string','mostrar' => 1),
		15 => array('row' => 'fecha_nacimiento' ,'nombre' => 'F. Nacimiento' ,'tipo' => 'date','mostrar' => 1),
		16 => array('row' => 'whatsapp' ,'nombre' => 'Whatsapp' ,'tipo' => 'string','mostrar' => 1),
		17 => array('row' => 'celular' ,'nombre' => 'Celular' ,'tipo' => 'string','mostrar' => 1),
		18 => array('row' => 'telefono' ,'nombre' => 'Teléfono' ,'tipo' => 'string','mostrar' => 1),
		19 => array('row' => 'correo_electronico' ,'nombre' => 'Correo Electrónico' ,'tipo' => 'string','mostrar' => 1),
		20 => array('row' => 'direccion' ,'nombre' => 'Dirección' ,'tipo' => 'string','mostrar' => 0),
		21 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string','mostrar' => 1),
		22 => array('row' => 'localidad' ,'nombre' => 'Localidad' ,'tipo' => 'string','mostrar' => 1),
		23 => array('row' => 'latitud' ,'nombre' => 'Latitud' ,'tipo' => 'string','mostrar' => 0),
		24 => array('row' => 'longitud' ,'nombre' => 'Longitud' ,'tipo' => 'string','mostrar' => 0),
		25 => array('row' => 'categorias' ,'nombre' => 'Categorías' ,'tipo' => 'string','mostrar' => 1),
		26 => array('row' => 'medio_registro' ,'nombre' => 'Medio Registro' ,'tipo' => 'string','mostrar' => 1),
		27 => array('row' => 'distancia_alert' ,'nombre' => 'Alerta Distancia' ,'tipo' => 'string','mostrar' => 1),
		28 => array('row' => 'seguimientos' ,'nombre' => 'Seguimientos' ,'tipo' => 'string','mostrar' => 1),
		29 => array('row' => 'status_verificacion' ,'nombre' => 'Verificación' ,'tipo' => 'string','mostrar' => 1),
		30 => array('row' => 'documentos_oficiales' ,'nombre' => 'Documentos Oficiales' ,'tipo' => 'string','mostrar' => 1),
		31 => array('row' => 'programas_apoyos' ,'nombre' => 'Programas Apoyos' ,'tipo' => 'string','mostrar' => 1),
		32 => array('row' => 'programas_apoyos_categorias' ,'nombre' => 'Programas Apoyos Categorías' ,'tipo' => 'string','mostrar' => 1),
		33 => array('row' => 'militantes_partidos' ,'nombre' => 'Militante' ,'tipo' => 'string','mostrar' => 1),
		34 => array('row' => 'observaciones' ,'nombre' => 'Observaciones' ,'tipo' => 'string','mostrar' => 0),
	);
	foreach ($_SESSION['reporte_Sistema']['columnas_nombres'] as $key => $value) {
		if($value['mostrar']==1){
			$_SESSION['reporte_Sistema']['columnas_sql'][] = $value['row'];
		}
	}
	$total_registros=500;
	if(!empty($_POST)){
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
		//$total_registros= 5;
		$pagina = $_POST['mapa'][0]['pagina'];
		$order = $_POST['mapa'][0]['order'];
		$order_tipo = $_POST['mapa'][0]['order_tipo'];
		if($_SESSION['reporte_Sistema']['columnas_sql'][$order]=="relacionado"){
			$_SESSION['reporte_Sistema']['columnas_sql'][$order] = "id_seccion_ine_ciudadano_compartido";
		}
		$orderby = " ORDER BY {$_SESSION['reporte_Sistema']['columnas_sql'][$order]} {$order_tipo} ";
		$mostrardesde = $pagina * $total_registros;
		//$limit = "LIMIT {$mostrardesde},{$total_registros}";

		$id_secciones_ine = explode(",", $_POST['searchTable'][0]['id_seccion_ine']);
		$id_cuartel = explode(",", $_POST['searchTable'][0]['id_cuartel']);

		/*
		if($tipo_uso_plataforma=='municipio'){
			$_POST['searchTable'][0]['id_municipio'] = $id_municipio;
			$_SESSION['searchTable']['id_municipio'] = $id_municipio;
			$origen['id_municipio']=$id_municipio;
			//id_municipio // id_distrito_local / id_distrito_federal
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,'','','','');
		}elseif($tipo_uso_plataforma=='distrito_local'){
			$_POST['searchTable'][0]['id_distrito_local'] = $id_distrito_local;
			$_SESSION['searchTable']['id_distrito_local'] = $id_distrito_local;
			$origen['id_distrito_local']=$id_distrito_local;
			//id_municipio // id_distrito_local / id_distrito_federal
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','',$id_distrito_local,'','','');
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			$_POST['searchTable'][0]['id_distrito_federal'] = $id_distrito_federal;
			$_SESSION['searchTable']['id_distrito_federal'] = $id_distrito_federal;
			$origen['id_distrito_federal']=$id_distrito_federal;
			//id_municipio // id_distrito_local / id_distrito_federal
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','',$id_distrito_federal,'','');
		}else{
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','','','','');
		}
		*/
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','','','','');
		$secciones_ine_ciudadanosDatosArray=secciones_ine_ciudadanosDatosArray($_POST['searchTable'][0],$orderby,$limit,$RowUser['id_perfil_usuario'],$RowUser['id']);
		$secciones_ineDatosMapa = secciones_ineDatosMapa($origen);
		
	}else{
		$zoom = 11;
		$order = 0;
		$order_tipo = "desc";
		//$orderby = ' ORDER BY clave DESC';
		$orderby = " ORDER BY e.{$_SESSION['reporte_Sistema']['columnas_sql'][$order]} {$order_tipo} ";
		//$limit = " LIMIT 0,{$total_registros} ";
		//$origen['id_municipio']=$id_municipio;
		/*
		if($tipo_uso_plataforma=='municipio'){
			$_POST['searchTable'][0]['id_municipio'] = $id_municipio;
			$_SESSION['searchTable']['id_municipio'] = $id_municipio;
			$origen['id_municipio']=$id_municipio;
			//id_municipio // id_distrito_local / id_distrito_federal
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,'','','','');
		}elseif($tipo_uso_plataforma=='distrito_local'){
			$_POST['searchTable'][0]['id_distrito_local'] = $id_distrito_local;
			$_SESSION['searchTable']['id_distrito_local'] = $id_distrito_local;
			$origen['id_distrito_local']=$id_distrito_local;
			//id_municipio // id_distrito_local / id_distrito_federal
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','',$id_distrito_local,'','','');
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			$_POST['searchTable'][0]['id_distrito_federal'] = $id_distrito_federal;
			$_SESSION['searchTable']['id_distrito_federal'] = $id_distrito_federal;
			$origen['id_distrito_federal']=$id_distrito_federal;
			//id_municipio // id_distrito_local / id_distrito_federal
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','',$id_distrito_federal,'','');
		}else{
			$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','','','','');
		}
		*/
		//$_POST['searchTable'][0]['id_municipio'] = $id_municipio;
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','','','','');
		$secciones_ine_ciudadanosDatosArray=secciones_ine_ciudadanosDatosArray($_POST['searchTable'][0],$orderby,$limit,$RowUser['id_perfil_usuario'],$RowUser['id']);
		$secciones_ineDatosMapa = secciones_ineDatosMapa($origen);
	}

	$_SESSION['reporte_Sistema']['database'] = $secciones_ine_ciudadanosDatosArray;
	foreach ($secciones_ine_ciudadanosDatosArray as $key => $value) {
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
			height:140px;
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
				height: 190px;
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

			heatmap = new google.maps.visualization.HeatmapLayer({
				data: getPoints(),
				map: map,
				radius: 20, // set custom radius to 20 pixels
				bounds: map.getBounds()
			});

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
				$porcentaje = ($secciones_ineDatosMapa[$key]['ciudadanos'] / $secciones_ineDatosMapa[$key]['lista_nominal']) * 100;
				if(is_nan($porcentaje)){
					$porcentaje = 0;
				}
				$div = '<div class="divMapaSecciones">
							<div class="info_content">
								<div class="info_titulo">
									<h5>Sección:</h5>
								</div>
								<div class="info_seccion_ganador">
									<h5>'.$secciones_ineDatosMapa[$key]['numero'].'</h5>
								</div>
								<div class="info_seccion_ganador_button">
									<!--<button class="button button4" onclick="mostrarSeccionCiudadanos('.$secciones_ineDatosMapa[$key]['id'].')">Ver Más</button>--->
								</div>
							</div>
							<div class="datos_seccion">
								<p>
									Lista Nominal: <b>'.number_format($secciones_ineDatosMapa[$key]['lista_nominal'],0,'.',',').'</b><br>
									Ciudadanos: <b>'.number_format($secciones_ineDatosMapa[$key]['ciudadanos'],0,'.',',').'</b><br>
									Porcentaje Avance: <b>'.number_format($porcentaje,2,'.',',').'%</b><br>
								</p>
							</div>
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
					fillOpacity: 0.21,
				});
				
				infoWindow = new google.maps.InfoWindow();
				secciones_area<?= $key ?>.addListener("click", (function(event){
					myLatlng = new google.maps.LatLng("<?= $secciones_ineDatosMapa[$key]['latitud'] ?>","<?= $secciones_ineDatosMapa[$key]['longitud'] ?>"); 
					infoWindow.setContent('<?= $div ?>');
					infoWindow.setPosition(myLatlng);
					infoWindow.open(map);
				}));
				

				const label<?= $key ?> = new google.maps.Marker({
					label: {
						text: '<?= $secciones_ineDatosMapa[$key]['numero'] ?>',
						color: 'red',
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
	<div id="mapa" style="width:100%;height:800px;"></div>
	<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>---->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap&libraries=visualization&v=weekly" defer></script>
	