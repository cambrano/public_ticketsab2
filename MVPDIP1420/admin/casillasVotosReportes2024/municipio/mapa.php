<?php
	include __DIR__.'/../../functions/security.php'; 
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	@session_start();
	//$tipo = 0;
	//$ano = 2024;

	$no_data['NoData'] = array(
		'id' => '0',
		'clave' => 'No Data',
		'nombre_corto' => 'No Data',
		'principal' => 0,
		'logo' => 'no_data.png',
		'color_border' => '',
		'color_background' => '',
		'votos_individual' => 0,
		'coaliciones' => '',
		'votos_coaliciones' => 0,
		'votos_totales' => 0
	);
	$zoom="8";
	$orderby = ' ORDER BY fechaR DESC';
	$limit = 'LIMIT 0,84';
	$municipios_parametrosDatosMapa = municipios_parametrosDatosMapa();
	$sql="
		SELECT
			main.id,
			main.clave,
			main.municipio,
			main.latitud,
			main.longitud
		FROM municipios main
		WHERE main.id_estado = '{$id_estado}'
	";
	if($id_municipio !=''){
		#$sql .= " AND main.id = {$id_municipio} ";
	}
	$result = $conexion->query($sql); 
	$num=0; 
	while($row=$result->fetch_assoc()){
		
		$datos_municipios[$row['id']]=$row;
		//$datos_municipios[$row['id']]['poligonos']=$municipios_parametrosDatosMapa[$row['id']];
		$num=$num+1;
	}
	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,'','','','');
	$sql="
		SELECT
			p.id,
			p.clave,
			p.nombre_corto,
			p.nombre,
			p.logo,
			p.color_border,
			p.color_background,
			SUM(cvp.votos) votos,
			p.clave_partidos_coaliciones,
			p.principal,
			cvp.id_municipio,
			cvp.id_seccion_ine
		FROM  casillas_votos_partidos_2024 cvp
		LEFT JOIN partidos_2024 p
		ON p.id = cvp.id_partido_2024
		WHERE cvp.id_municipio='{$id_municipio}' AND cvp.tipo = '{$tipo}' 
		GROUP BY cvp.id_seccion_ine,cvp.id_partido_2024
	";
	$result = $conexion->query($sql);
	while($row=$result->fetch_assoc()){
		if($row['clave_partidos_coaliciones'] == ''){
			unset($row['clave_partidos_coaliciones']);
		}
		if($row['principal'] == ''){
			unset($row['principal']);
		}
		#$datos_partidos[$num]=$row;
		//? Colocamos en su arrelgo segun sea el tipo de partido
		if($row['clave_partidos_coaliciones'] != ''){
			$partidos_coaliciones[$row['id_seccion_ine']][$row['clave_partidos_coaliciones']]=$row;
		}else{
			$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['clave']]=$row;
		} 
		$num=$num+1;
	}
	$sql="
		SELECT
			si.id,
			si.clave,
			si.numero,
			si.latitud,
			si.longitud,
			si.tipo,
			si.id_municipio,
			(SELECT m.municipio FROM municipios m WHERE m.id = si.id_municipio) municipio,
			(SELECT COUNT(cv.id) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') casillas,
			(SELECT SUM(cv.lista_nominal) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}' ) lista_nominal,
			(SELECT SUM(cv.votos_nulos) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_nulos,
			(SELECT SUM(cv.votos_can_nreg) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_can_nreg,
			(SELECT SUM(cv.votos) FROM casillas_votos_partidos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_validos
		FROM secciones_ine si
		WHERE si.id_municipio = '$id_municipio'
	";
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$row['votos_totales'] = $row['votos_nulos'] + $row['votos_can_nreg'] + $row['votos_validos'];
		$row['participacion_ciudadana'] = truncar((($row['votos_totales'] / $row['lista_nominal'])*100), 2);
		$datos_secciones_ine[$row['id']]=$row;

		//? Tomamos como princial el partido sin coalicion

		unset($ordena_votos_individual);
		unset($ordena_votos_totales);
		foreach ($partidos_sin_coaliciones[$row['id']] as $clave => $array) {
			//? Colocamos en 0 la suma de coalciones para que no se sume con los demas
			//? El arreglo de coalciones lo vaciamos para que no se agregen de los demas partidos
			$sum_coaliciones = 0;
			unset($coaliciones); 
			unset($coalicion_orden_individual);
			foreach ($partidos_coaliciones[$row['id']] as $nombre_corto => $arraysc) {
				//? Vemos si el nombre corto esta en la coalicion para agregarlo
				//? Si es negativo sigue con el siguiente
				$posicion_coalicion = explode(',', $nombre_corto);
				/*
				$pos = strpos($nombre_corto, $array['nombre_corto']);
				echo $nombre_corto;
				echo "-----";
				echo $array['nombre_corto'];
				echo "-----";
				echo var_dump($pos);
				echo "<br>";
				if ($pos !== false ) {
				*/
				if (in_array($array['clave'], $posicion_coalicion)) {
					$coaliciones_array = explode(",", $nombre_corto);
					foreach ($coaliciones_array as $partido => $votos) {
						$coaliciones[$votos] = $partidos_sin_coaliciones[$row['id']][$votos];
						//! Importante
						//? Buscamos si existe en el arrey para que no se repita
						//* votos == nombre del partido segun la coalicion
						//* [] == colocamos arreglo vacio por que puede ser que uno o mas partidos tengan los mismos votos
						#$coalicion_orden_individual[$clave][$nombre_corto][ $partidos_sin_coaliciones[$votos]['votos'] ][]=$votos;
						$search_coalicion = array_search($votos, $coalicion_orden_individual[$partidos_sin_coaliciones[$row['id']][$votos]['votos'] ]);
						if($search_coalicion === NULL){
							$coalicion_orden_individual[$partidos_sin_coaliciones[$row['id']][$votos]['votos'] ][]= $votos;
						}
					}
					$sum_coaliciones = $sum_coaliciones + $arraysc['votos'];
				}
			}
			//? Nuestro Principal arreglo
			//* clave == nombre del partido
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['id'] = $array['id'];
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['clave'] = $clave;
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['nombre_corto'] = $array['nombre_corto'];
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['nombre'] = $array['nombre'];
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['principal'] = $array['principal'];
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['logo'] = $array['logo'];
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['color_border'] = $array['color_border'];
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['color_background'] = $array['color_background'];

			$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] = $array['votos'];
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['coaliciones_sin_orden'] = $coaliciones;
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones'] = $sum_coaliciones;

			//! Importante
			//? Ordenamos las coaliciones por votos en individual
			$total_votos_individual = 0;
			krsort($coalicion_orden_individual);
			foreach ($coalicion_orden_individual as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['partidos'][$clave]['coaliciones_orden_votos_individual'][$partido]=$votos;
					if($clave != $partido){
						$total_votos_individual = $total_votos_individual + $votos;
					}
				}
			}
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] = $total_votos_individual;
			$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_totales'] = $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] + $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones'] + $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] ;


			$ordena_votos_individual[$row['id']][$array['votos']] [] = $clave ;
			$ordena_votos_totales[$row['id']][ $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_totales'] ] [] = $clave ;

			#$partidos_orden_individual[ $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] ][$array['votos']][] = $array['nombre_corto']; 
		}
		//! Importante
		//? Ordenamos los partidos
		krsort($ordena_votos_individual[$row['id']]);
		krsort($ordena_votos_totales[$row['id']]);
		$validador = 0;
		foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
			foreach ($partidos_array as $index => $partido) {
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido]=$votos;
				$validador = $validador + $votos;
				if(empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'])){
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] = $partido;
					if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}elseif (empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'])  ) {
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $partido;
					if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}else{
					if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['sistema'] = $partido;
					}
				}
			}
		}
		if($validador <= 0){
			$datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] ='NoData';
		}
		$validador = 0;
		foreach ($ordena_votos_totales[$row['id']] as $votos => $partidos_array) {
			foreach ($partidos_array as $index => $partido) {
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido]=$votos;
				$validador = $validador + $votos;
				if(empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'])){
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] = $partido;
					$primera_fuerza = $partido;
					if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}elseif (empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza']) && empty($datos_secciones_ine[$row['id']]['partidos'][$partido]['coaliciones_orden_votos_individual'][$primera_fuerza]  )  ) {
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $partido;
					if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}else{
					if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['sistema'] = $partido;
					}
				}
			}
		}
		if($validador <= 0){
			$datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] ='NoData';
		}
		/*
		if( $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] == 0 ){
			$datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] ='NoData';
		}
		if( $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] == 0 ){
			$datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] = 'NoData';
		}
		*/
	}


?>
	<style type="text/css">
		.divMapa{
			width:450px;
			height:230px;
			margin: -10px 0px 0px 2px;
		}
		.divMapaTerritorio{
			width:180px;
			height:100px;
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
			height:139px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.logo_partido{
			width:30%;
			float:left;
			height:105px;
			text-align:left;
			border: 1px solid #00923f;
			padding: 10px 0px 2px 5px;
			background-color:#e36962;
			color:white;
		}
		.datos_partido{
			width:70%;
			float:left;
			height:105px;
			text-align:left;
			border: 1px solid #00923f;
			padding: 5px 0px 2px 5px;
			background-color:#e36962;
			color:white;
		}
		.gm-style-iw  { 
			min-width: 125px !important; 
			padding: 22px 0px 2px 8px !important;
		}
		@media screen and (max-width: 1250px) {
			.divMapa{
				width: 40vw;
				margin: -10px 0px 0px 2px;
			}
			.divMapaTerritorio{
				/*width: 20vw;*/
				margin: -10px 0px 0px 2px;
			}
			.info_content{
				text-align: center;
			}
			.info_titulo{
				text-align: center;
			}

			.info_seccion_ganador{
				height: auto
			}

			.info_content,
			.info_titulo,
			.info_seccion_ganador,
			.info_seccion_ganador_button,
			.datos_votos,
			.logo_partido,
			.datos_partido{
				width:100%;
			}
			.datos_votos{
				padding: 5px 2px 2px 2px;
				height: auto
			}
			.logo_partido{
				height: 60px;
			}
			.datos_partido{
				padding: 5px 2px 2px 2px;
				height: auto;
			}
			.gm-style-iw  { 
				min-width: 125px !important; 
				padding: 22px 2px 2px 2px !important;
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
			zoom=16;
			var latitud='<?=$datos_secciones_ine[$id_seccion_ine]['latitud'] ?>';
			var longitud='<?=$datos_secciones_ine[$id_seccion_ine]['longitud'] ?>';
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
			var myLatlng = new google.maps.LatLng(latitud, longitud); 
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
				foreach ($datos_municipios as $key => $value) {
					$paths = "";
					foreach ($municipios_parametrosDatosMapa[$value['id']] as $keyT => $valueT) {
						$path = "municipios_".$key."_".$keyT;
						echo $path." = [";
						foreach ($valueT as $keyH => $valueH) {
							echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
						}
						echo "];";
						$paths .= $path.",";
					}
					if($value['partido_ganador_background']=="" || $key != $id_municipio ){
						$value['partido_ganador_border'] = "000000";
						$value['partido_ganador_background'] = "000000";
					}
					$value['partido_ganador_border'] = "000000";
					$value['partido_ganador_background'] = "000000";
					?>
					municipio_area<?= $key ?> = new google.maps.Polygon({
						paths: [<?= $paths ?>],
						strokeColor: "#<?= $value['partido_ganador_border'] ?>",
						strokeOpacity: 0.8,
						strokeWeight: 1,
						fillColor: "#<?= $value['partido_ganador_background'] ?>",
						fillOpacity: 0.35,
					});
					municipio_area<?= $key ?>.setMap(map);
					<?php
				}
			?>
			<?php
				foreach ($datos_secciones_ine as $key => $value) {
					$div = '<div class="divMapaSeccion">
								<h4>Sección: '.$datos_secciones_ine[$key]['numero'].'</h4>
							</div>';
					$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
					$paths = "";
					foreach ($secciones_ine_parametrosDatosMapa[$value['id']] as $keyT => $valueT) {
						$path = "secciones_ine_".$key."_".$keyT;
						echo $path." = [";
						foreach ($valueT as $keyH => $valueH) {
							echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
						}
						echo "];";
						$paths .= $path.",";
					}
					$primera_fuerza = $value['orden_votos_individual']['primera_fuerza'];
					$color_background = $value['partidos'][$primera_fuerza]['color_background'] ;
					$color_border = $value['partidos'][$primera_fuerza]['color_border'] ;
					if($primera_fuerza == ""){
						$color_background = "000000";
						$color_border = "000000";
					}elseif ( $primera_fuerza =='NoData') {
						$color_background = "f4f4f4";
						$color_border = "101110";
					}elseif ( $key != $id_seccion_ine) {
						$color_background = "000000";
						$color_border = "000000";
					}
					?>
					secciones_area<?= $key ?> = new google.maps.Polygon({
						paths: [<?= $paths ?>],
						strokeColor: "#<?= $color_border ?>",
						strokeOpacity: 0.8,
						strokeWeight: 1,
						fillColor: "#<?= $color_background ?>",
						fillOpacity: 0.35,
					});
					<?php
				}
			?>

			///marcadores o puntos
			var marcadores = [
			<?php
			foreach ($datos_municipios as $key => $value) {
				if($value['id'] != $id_municipio){
					#echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'numero','".$value['numero'].".png' ],";
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'municipio','municipio.png' ],";
				}
			}
			foreach ($datos_secciones_ine as $key => $value) {
				$primera_fuerza = $value['orden_votos_individual']['primera_fuerza'];
				if($primera_fuerza =='NoData'){
					$logo = $no_data[$primera_fuerza]['logo'];
				}else{
					$logo = $value['partidos'][$primera_fuerza]['logo'] ;
				}

				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$logo."' ],";
			}
			?>
			];
			///informacion del marcador
			var infoWindowContent = [
				<?php
				foreach ($datos_municipios as $key => $value){
					if($value['id'] != $id_municipio){
						$votos_totales = 0;
						$votos_totales = $value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg'];
						$porcentaje_partido_ganador = ($value['partido_ganador_votos'] / $votos_totales)*100;
						$porcentaje_partido_ganador = truncar($porcentaje_partido_ganador, 2);
						$porcentaje_partido_sistema = ($value['partido_sistema_votos'] / $votos_totales )*100;
						$porcentaje_partido_sistema = truncar($porcentaje_partido_sistema, 2);
						$diferencia_votos = $value['partido_ganador_votos'] - $value['partido_sistema_votos'];
						$participacion_ciudadana = 0;
						if($votos_totales != 0){
							$participacion_ciudadana = ($votos_totales / $value['lista_nominal'] ) * 100;
						}else{
							$participacion_ciudadana =0 ;
						}
						$logo = $value['partido_ganador_logo'];
						$logo_partido_sistema = $value['partido_sistema_logo'];
						$div = '<div class="divMapaTerritorio">
									<div class="info_content">
										<h4>Municipio: '.$value['municipio'].'</h4>
										<div class="info_titulo" style="width:100%">
											<h5>Votación '.$ano.'</h5>
										</div>
										<div class="info_seccion_ganador_button" style="width:100%">
											<button class="button button4" onclick="verMasMunicipio('.$value['id'].')">Ver Más</button>
										</div>
									</div>
								</div>';
						$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
						?>
						['<?= $div ?>'],
						<?php
					}
				}
				foreach ($datos_secciones_ine as $key => $value){
					unset($coali_primera_fuerza);
					unset($coali_segunda_fuerza);
					unset($texto);

					$primera_fuerza = $value['orden_votos_individual']['primera_fuerza'];
					$segunda_fuerza = $value['orden_votos_individual']['segunda_fuerza'];
					if($primera_fuerza=='NoData'){
						$datos_segunda_fuerza = $datos_primera_fuerza =$no_data['NoData'];
					}else{
						$datos_primera_fuerza = $value['partidos'][$primera_fuerza];
						$datos_segunda_fuerza = $value['partidos'][$segunda_fuerza];
						foreach ($datos_primera_fuerza['coaliciones_orden_votos_individual'] as $partido => $votos) {
							if($primera_fuerza != $partido){
								$texto[] = $partido.': '.$votos;
							}
						}
						$coali_primera_fuerza = implode(", ", $texto);
						unset($texto);
						foreach ($datos_segunda_fuerza['coaliciones_orden_votos_individual'] as $partido => $votos) {
							if($segunda_fuerza != $partido){
								$texto[] = $partido.': '.$votos;
							}
						}
						$coali_segunda_fuerza = implode(", ", $texto);
						unset($texto);
					}

					$div = '<div class="divMapa">
								<div class="info_content">
									<h4>Sección: '.$value['numero'].'</h4>
									<div class="info_titulo">
										<h5>Votación '.$ano.'</h5>
									</div>
									<div class="info_seccion_ganador">
										Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
										Partido Ganador: <b>'.$datos_primera_fuerza['nombre_corto'].'</b><br>
									</div>
									<div class="info_seccion_ganador_button">
										<button class="button button4" onclick="verMasSeccion('.$value['id'].')">Ver Más</button>
									</div>
								</div>
								<div class="datos_votos">
									<p>
										Casillas: <b>'.number_format($value['casillas'], 0, '.', ',').'</b><br><br>
										Votos Validos: <b>'.number_format($value['votos_validos'], 0, '.', ',').'</b><br>
										Votos Nulos: <b>'.number_format($value['votos_nulos'], 0, '.', ',').'</b><br>
										Votos CAN NREG: <b>'.number_format($value['votos_can_nreg'], 0, '.', ',').'</b><br>
										Votos Totales: <b>'.number_format($value['votos_totales'], 0, '.', ',').'</b><br>
										P. Ciudadana: <b>'.number_format($value['participacion_ciudadana'], 2, '.', ',').'%</b><br>
									</p>
								</div>
								<div class="datos_votos">
									<div style="width:100%;text-align:center;padding:0px">
										<img src="images/logos_partidos/'.$datos_primera_fuerza['logo'].'" style="width: 30px ">
									</div>
									<p style="padding:0px;text-align:left;">
										Votos Individual: <b>'.number_format($datos_primera_fuerza['votos_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Ind: <b>'.number_format($datos_primera_fuerza['votos_coaliciones_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Boletas: <b>'.number_format($datos_primera_fuerza['votos_coaliciones'], 0, '.', ',').'</b><br>
										Votos Total: <b>'.number_format($datos_primera_fuerza['votos_totales'], 0, '.', ',').'</b><br>
										Coaliciones: <b>'.$coali_primera_fuerza.'</b><br>
									</p>
								</div>
								<div class="logo_partido">
									<center>
										<img src="images/logos_partidos/'.$datos_segunda_fuerza['logo'].'" style="width: 40px ">
									</center>
								</div>
								<div class="datos_partido">
									<p style="padding:0px;text-align:left;">
										Votos Individual: <b>'.number_format($datos_segunda_fuerza['votos_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Ind: <b>'.number_format($datos_segunda_fuerza['votos_coaliciones_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Boletas: <b>'.number_format($datos_segunda_fuerza['votos_coaliciones'], 0, '.', ',').'</b><br>
										Votos Total: <b>'.number_format($datos_segunda_fuerza['votos_totales'], 0, '.', ',').'</b><br>
										Coaliciones: <b>'.$coali_segunda_fuerza.'</b><br>
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
			var markers = [];
			for (i = 0; i < marcadores.length; i++) {
				if(marcadores[i][3]==''){
					var icon = {
						//url: 'assets/images/iconos/cd-icon-location.png', // url
						scaledSize: new google.maps.Size(20, 22), // scaled size
					};
				}else{
					if(marcadores[i][3]=='numero'){
						var icon = {
							//url: 'assets/images/iconos/cd-icon-location.png', // url
							url : 'images/puntos_numeros/'+ marcadores[i][4],
							scaledSize: new google.maps.Size(20, 22), // scaled size
						};
					} else if(marcadores[i][3]=='municipio'){
						var icon = {
							//url: 'assets/images/iconos/cd-icon-location.png', // url
							url : 'images/puntos/'+ marcadores[i][4],
							scaledSize: new google.maps.Size(22, 22), // scaled size
						};
					}else{
						var icon = {
							//url: 'assets/images/iconos/cd-icon-location.png', // url
							url : 'images/iconos_partidos/'+ marcadores[i][3],
							scaledSize: new google.maps.Size(20, 22), // scaled size
						};
					}
				}
				marker = new google.maps.Marker({
					position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
					map: map,
					icon: icon,
					visible: false 
				});
				markers.push(marker);
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infowindow.setContent(infoWindowContent[i][0]);
						infowindow.open(map, marker);
					}
				})(marker, i));
			}
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
					foreach ($datos_secciones_ine as $key => $value) {
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

		function verMasMunicipio(valor){
			link="seccionesIneReportes2024/municipio/index.php?id="+valor; 
			var link2="seccionesIneReportes2024/municipio/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
		}

		function verMasSeccion(valor){
			link="casillasVotosReportes2024/municipio/index.php?id="+valor; 
			var link2="casillasVotosReportes2024/municipio/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link+"&refresh=1");
		}
	</script>
	<div id="mapa" style="width:100%;height:400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>  