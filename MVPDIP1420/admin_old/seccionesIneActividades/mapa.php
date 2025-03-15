<?php
	include __DIR__.'/../functions/security.php'; 
	include __DIR__.'/../functions/timemex.php'; 
	@session_start();
	$_SESSION['reporte_Sistema']['columnas_sql'] = array(
		0 =>'clave',
		1 =>'folio',
		2 =>'nombre',
		3 =>'numero_contrato',
		4 =>'observaciones',
		5 =>'cedula',
		6 =>'empresa_adjudicada',
		7 =>'supervisor',
		8 =>'fecha_inicio',
		9 =>'fecha_final',
		10 =>'monto_total',
		11 =>'beneficiarios',
		12 =>'meta_cantidad',
		13 =>'unidad_completa',
		14 =>'tipo',
		15 =>'tipo_infraestructura',
		16 =>'municipio',
		17 =>'localidad',
		18 =>'colonia',
		19 =>'seccion',
		20 =>'distrito_local',
		21 =>'distrito_federal',
	);
	$_SESSION['reporte_Sistema']['columnas_nombres'] = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'nombre' ,'nombre' => 'Nombre' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'numero_contrato' ,'nombre' => 'Número de contrato' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'observaciones' ,'nombre' => 'Observaciones' ,'tipo' => 'string','mostrar' => 1 ),
		5 => array('row' => 'cedula' ,'nombre' => 'Cédula' ,'tipo' => 'string','mostrar' => 1 ),
		6 => array('row' => 'empresa_adjudicada' ,'nombre' => 'Empresa Adjudicada' ,'tipo' => 'string','mostrar' => 1 ),
		7 => array('row' => 'supervisor' ,'nombre' => 'supervisor' ,'tipo' => 'string','mostrar' => 1 ),

		8 => array('row' => 'fecha_inicio' ,'nombre' => 'Fecha Inicio' ,'tipo' => 'date','mostrar' => 1 ),
		9 => array('row' => 'fecha_final' ,'nombre' => 'Fecha Final' ,'tipo' => 'date','mostrar' => 1 ),
		
		10 => array('row' => 'monto_total' ,'nombre' => 'Monto Total' ,'tipo' => 'price','mostrar' => 1 ),
		11 => array('row' => 'beneficiarios' ,'nombre' => 'Beneficiarios' ,'tipo' => 'integer','mostrar' => 1 ),
		12 => array('row' => 'meta_cantidad' ,'nombre' => 'Meta Cantidad' ,'tipo' => 'integer','mostrar' => 1 ),
		13 => array('row' => 'unidad_completa' ,'nombre' => 'Unidad' ,'tipo' => 'string','mostrar' => 1 ),
		14 => array('row' => 'tipo' ,'nombre' => 'Tipo' ,'tipo' => 'string','mostrar' => 1 ),
		15 => array('row' => 'tipo_infraestructura' ,'nombre' => 'Tipo Infraestructura' ,'tipo' => 'string','mostrar' => 1 ),
		16 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string','mostrar' => 1 ),
		17 => array('row' => 'localidad' ,'nombre' => 'Localidad' ,'tipo' => 'string','mostrar' => 1 ), 
		18 => array('row' => 'colonia' ,'nombre' => 'Colonia' ,'tipo' => 'string','mostrar' => 1 ), 
		19 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'number','mostrar' => 1 ),
		20 => array('row' => 'distrito_local' ,'nombre' => 'Distrito Local' ,'tipo' => 'number','mostrar' => 1 ),
		21 => array('row' => 'distrito_federal' ,'nombre' => 'Distrito Federal' ,'tipo' => 'number','mostrar' => 1 ),
		//16 => array('row' => 'latitud' ,'nombre' => 'Latitud' ,'tipo' => 'string','mostrar' => 0 ),
		//17 => array('row' => 'longitud' ,'nombre' => 'Longitud' ,'tipo' => 'string','mostrar' => 0 ),
		
	);
	if(!empty($_POST)){
		include '../functions/secciones_ine.php';
		include '../functions/secciones_ine_parametros.php';
		include '../functions/secciones_ine_actividades.php';
		$punto=false;
		foreach ($_POST['searchTable'][0] as $key => $value) {
			//echo "XX".$key." = XX_SESSION['".$key."'];";
			//$_SESSION[$key]=mysqli_real_escape_string($conexion,$value);
			//echo "<br>";
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
		$total_registros= 14;
		$pagina = $_POST['mapa'][0]['pagina'];
		$order = $_POST['mapa'][0]['order'];
		$order_tipo = $_POST['mapa'][0]['order_tipo'];
		$orderby = " ORDER BY sia.{$_SESSION['reporte_Sistema']['columnas_sql'][$order]} {$order_tipo} ";
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
		$secciones_ine_actividadesDatosArray=secciones_ine_actividadesDatosArray($_POST['searchTable'][0],$orderby,$limit);
		
		$secciones_ineDatosMapa = secciones_ineDatosForm($search_map);
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,$id_distrito_local,$id_distrito_federal,'','');

		if($punto==false){
			$zoom = 14;
			if($secciones_ine_actividadesDatosArray[0]['latitud'] != '' ){
				$latitud = $secciones_ine_actividadesDatosArray[0]['latitud'];
				$longitud = $secciones_ine_actividadesDatosArray[0]['longitud'];
			}
		}else{
			$zoom = 14;
		}
	}else{
		$zoom = 14;
		$order = 0;
		$order_tipo = "desc";
		//$orderby = ' ORDER BY clave DESC';
		$orderby = " ORDER BY sia.{$_SESSION['reporte_Sistema']['columnas_sql'][$order]} {$order_tipo} ";
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
		$secciones_ine_actividadesDatosArray=secciones_ine_actividadesDatosArray($_POST['searchTable'][0],$orderby,$limit);
		
		$secciones_ineDatosMapa = secciones_ineDatosForm($search_map);
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,$id_distrito_local,$id_distrito_federal,'','');
	}
	foreach ($secciones_ine_actividadesDatosArray as $key => $value) {
		if($key==0){
			$latitud = $value['latitud'];
			$longitud = $value['longitud'];
		}
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
			height:200px;
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
			foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
				$secciones_ineDatosMapa[$key]['numero'];
				$secciones_ineDatosMapa[$key]['latitud'];
				$secciones_ineDatosMapa[$key]['longitud'];
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
				//secciones_area<?= $key ?>.setMap(map);
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

			// Agregar un listener para detectar cambios en el mapa
			google.maps.event.addListener(map, 'idle', function() {
				// Obtener los límites del mapa
				var bounds = map.getBounds();
				var zoom = map.getZoom();
				<?php
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

			var actividades = [
			<?php
			///actividades
			foreach ($secciones_ine_actividadesDatosArray as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['tipo']."'],";
			}
			?>
			];
			var infoWindowContent_actividades = [
				<?php
				foreach ($secciones_ine_actividadesDatosArray as $key => $value){
					foreach ($value as $keyT => $valueT) {
						if($keyT!='monto_total'){
							$value[$keyT] = preg_replace('([^A-Za-z0-9 :-])', '', $valueT);
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
											<h5>'.strtoupper($value['tipo']).'</h5>
										</div>
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="edit('.$value['id'].')">Ver Más</button>
										</div>
									</div>
									<div class="datos_top" style="width:100%;">
										Folio: <b>'.$value['folio'].'</b><br>
										Número de contrato: <b>'.$value['numero_contrato'].'</b><br>
										Cédula: <b>'.$value['cedula'].'</b><br>
										Empresa Adjudicada: <b>'.$value['empresa_adjudicada'].'</b><br>
										Supervisor: <b>'.$value['supervisor'].'</b><br>
										Tipo Infraestructura: <b>'.$value['tipo_infraestructura'].'</b><br>
										Beneficiarios: <b>'.number_format($value['beneficiarios'],0,"",",").'</b><br>
										Monto Total: <b>'.number_format($value['monto_total'],2,".",",").'</b><br>
										Meta: <b>'.number_format($value['meta_cantidad'],0,"",",").' '.$sup_indice.'</b><br>
										Nombre: <b><font style="">'.$value['nombre'].'</font></b><br>
										
									</div>
									<div class="datos_left">
										<p>
											Distrito Local: <b>'.$value['distrito_local'].'</b><br>
											Distrito Federal: <b>'.$value['distrito_federal'].'</b><br>
											Sección: <b>'.$value['seccion'].'</b><br>
										</p>
									</div>
									<div class="datos_right">
										<p>
											Fecha Inicio: <b>'.$value['fecha_inicio'].'|'.fechaNormalSimpleWDDMMAA_ES($value['fecha_inicio']).'</b><br>
											Fecha Final: <b>'.$value['fecha_final'].'|'.fechaNormalSimpleWDDMMAA_ES($value['fecha_final']).'</b><br>

											
										</p>
									</div>
									<div class="datos_right_bottom" style="width:100%;">
										Dirección : <b>'.$value['calle'].", ".$value['colonia'].", ".$value['codigo_postal'].", ".$value['municipio'].', '.$estado_nombre.' </b><br>
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
			//pinImageGreen = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
			var icon_candidato = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				url : 'images/iconos_partidos/puntero_candidato.png',
				scaledSize: new google.maps.Size(28, 31), // scaled size // width, height
			};
			var icon_visita = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				url : 'images/iconos_partidos/puntero_visita.png',
				scaledSize: new google.maps.Size(30, 28), // scaled size // width, height
			};
			var icon_apoyo = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				url : 'images/iconos_partidos/puntero_apoyo.png',
				scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
			};
			var icon_obra = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				url : 'images/iconos_partidos/puntero_obra.png',
				scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
			};
			var icon_accion = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				url : 'images/iconos_partidos/puntero_accion.png',
				scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
			};

			for (i = 0; i < actividades.length; i++) {
				if(actividades[i][3]=='candidato'){
					markerMilitates = new google.maps.Marker({
						position: new google.maps.LatLng(actividades[i][1], actividades[i][2]),
						map: map,
						icon: icon_candidato,
					});
				}else if (actividades[i][3]=='visita'){
					markerMilitates = new google.maps.Marker({
						position: new google.maps.LatLng(actividades[i][1], actividades[i][2]),
						map: map,
						icon: icon_visita,
					});
				}else if(actividades[i][3]=='apoyo'){
					markerMilitates = new google.maps.Marker({
						position: new google.maps.LatLng(actividades[i][1], actividades[i][2]),
						map: map,
						icon: icon_apoyo,
					});
				}else if(actividades[i][3]=='obra'){
					markerMilitates = new google.maps.Marker({
						position: new google.maps.LatLng(actividades[i][1], actividades[i][2]),
						map: map,
						icon: icon_obra,
					});
				}else{
					markerMilitates = new google.maps.Marker({
						position: new google.maps.LatLng(actividades[i][1], actividades[i][2]),
						map: map,
						icon: icon_accion,
					});
				}

				google.maps.event.addListener(markerMilitates, 'click', (function(markerMilitates, i) {
					return function() {
						infowindowCiudadanos.setContent(infoWindowContent_actividades[i][0]);
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