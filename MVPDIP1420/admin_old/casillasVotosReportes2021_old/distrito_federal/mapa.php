<?php
	include __DIR__.'/../../functions/security.php'; 
	function truncar($numero, $digitos){
		$truncar = 10**$digitos;
		return intval($numero * $truncar) / $truncar;
	}
	@session_start();
	
	$distritos_federales_parametrosDatosMapa = distritos_federales_parametrosDatosMapa();

		$sql="
			SELECT
			dl.id,
			dl.numero,
			dl.latitud,
			dl.longitud,
			(SELECT SUM(cvp2021.votos)  FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC LIMIT 1) partido_ganador_votos,

			(SELECT cvp2021.id_partido_2021  FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC LIMIT 1) partido_ganador_id,

			(SELECT p2021Ganador.color_background  FROM casillas_votos_partidos_2021 cvp2021 LEFT JOIN partidos_2021 p2021Ganador ON p2021Ganador.id= cvp2021.id_partido_2021  WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC LIMIT 1) partido_ganador_background,

			(SELECT p2021Ganador.color_border  FROM casillas_votos_partidos_2021 cvp2021 LEFT JOIN partidos_2021 p2021Ganador ON p2021Ganador.id= cvp2021.id_partido_2021  WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC LIMIT 1) partido_ganador_border,

			(SELECT p2021Ganador.nombre_corto  FROM casillas_votos_partidos_2021 cvp2021 LEFT JOIN partidos_2021 p2021Ganador ON p2021Ganador.id= cvp2021.id_partido_2021  WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC LIMIT 1) partido_ganador_nombre_corto,

			(SELECT p2021Ganador.icono  FROM casillas_votos_partidos_2021 cvp2021 LEFT JOIN partidos_2021 p2021Ganador ON p2021Ganador.id= cvp2021.id_partido_2021  WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC LIMIT 1) partido_ganador_icono,

			(SELECT p2021Ganador.logo  FROM casillas_votos_partidos_2021 cvp2021 LEFT JOIN partidos_2021 p2021Ganador ON p2021Ganador.id= cvp2021.id_partido_2021  WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC LIMIT 1) partido_ganador_logo,
			
			(SELECT p2021Sistema.nombre_corto FROM partidos_2021 p2021Sistema WHERE p2021Sistema.principal = 1 AND p2021Sistema.tipo = 2 LIMIT 1 ) partido_sistema_corto,

			(SELECT p2021Sistema.color_border FROM partidos_2021 p2021Sistema WHERE p2021Sistema.principal = 1 AND p2021Sistema.tipo = 2 LIMIT 1 ) partido_sistema_border,
			(SELECT p2021Sistema.color_background FROM partidos_2021 p2021Sistema WHERE p2021Sistema.principal = 1 AND p2021Sistema.tipo = 2 LIMIT 1 ) partido_sistema_background,
			(SELECT p2021Sistema.logo  FROM partidos_2021 p2021Sistema WHERE p2021Sistema.principal = 1 AND p2021Sistema.tipo = 2 LIMIT 1 ) partido_sistema_logo,
			(SELECT (SELECT SUM(cvp2021.votos)  FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.id_partido_2021 = p2021Sistema.id AND cvp2021.tipo = 2 )  FROM partidos_2021 p2021Sistema WHERE p2021Sistema.principal = 1 AND p2021Sistema.tipo = 2 LIMIT 1 ) partido_sistema_votos,

			(SELECT SUM(cv2021.votos_nulos) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_distrito_federal = dl.id AND cv2021.tipo = 2 ) votos_nulos,
			(SELECT SUM(cv2021.votos_can_nreg) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_distrito_federal = dl.id AND cv2021.tipo = 2 ) votos_can_nreg,
			(SELECT SUM(cv2021.lista_nominal) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_distrito_federal = dl.id AND cv2021.tipo = 2 ) lista_nominal,
			(SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_distrito_federal = dl.id AND cvp2021.tipo = 2 ) votos_validos,
			(SELECT COUNT(si.id) FROM secciones_ine si WHERE si.id_distrito_federal = dl.id ) secciones,
			(SELECT COUNT(cv2021.id) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_distrito_federal = dl.id AND cv2021.tipo = 2 ) casillas
			FROM distritos_federales dl 
			WHERE 1 = 1 AND dl.id = '{$id_distrito_federal}' ";
		$sql;
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_distritos_federales[$row['id']]=$row;
			//$datos_distritos_federales[$row['id']]['poligonos']=$distritos_federales_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}
		 

		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','',$id_distrito_federal,'','');
		$sql="
			SELECT
				si.id,
				si.clave,
				si.numero,
				si.latitud,
				si.longitud,
				si.partido_ganador_votos,
				si.partido_ganador_id,
				p.color_background partido_ganador_background,
				p.color_border partido_ganador_border,
				p.nombre_corto partido_ganador_nombre_corto,
				p.icono partido_ganador_icono,
				p.logo partido_ganador_logo,

				si.partido_sistema_id,
				si.partido_sistema_nombre_corto,
				si.partido_sistema_nombre,
				si.partido_sistema_border,
				si.partido_sistema_background,
				si.partido_sistema_logo,
				si.partido_sistema_votos,
				si.votos_nulos,
				si.votos_can_nreg,
				si.lista_nominal,
				si.votos_validos,
				si.casillas
				FROM(
					SELECT 
						si.id,
						si.clave,
						si.numero,
						si.latitud,
						si.longitud,
						(SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.tipo = 2 AND cvp2021.id_seccion_ine = si.id AND cvp2021.id_distrito_federal = '{$id_distrito_federal}' GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC limit 1 ) partido_ganador_votos,

						(SELECT cvp2021.id_partido_2021 FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.tipo = 2 AND cvp2021.id_seccion_ine = si.id AND cvp2021.id_distrito_federal = '{$id_distrito_federal}' GROUP BY cvp2021.id_partido_2021 ORDER BY SUM(cvp2021.votos) DESC limit 1 ) partido_ganador_id,

						(SELECT p2021.id  FROM partidos_2021 p2021 WHERE p2021.principal = '1' AND p2021.tipo = 2 LIMIT 1) partido_sistema_id,
						(SELECT p2021.nombre_corto  FROM partidos_2021 p2021 WHERE p2021.principal = '1' AND p2021.tipo = 2 LIMIT 1) partido_sistema_nombre_corto,
						(SELECT p2021.nombre  FROM partidos_2021 p2021 WHERE p2021.principal = '1' AND p2021.tipo = 2 LIMIT 1) partido_sistema_nombre,
						(SELECT p2021.color_border  FROM partidos_2021 p2021 WHERE p2021.principal = '1' AND p2021.tipo = 2 LIMIT 1) partido_sistema_border,
						(SELECT p2021.color_background  FROM partidos_2021 p2021 WHERE p2021.principal = '1' AND p2021.tipo = 2 LIMIT 1) partido_sistema_background,
						(SELECT p2021.logo  FROM partidos_2021 p2021 WHERE p2021.principal = '1' AND p2021.tipo = 2 LIMIT 1) partido_sistema_logo,
						(SELECT SUM(cvp2021.votos)  FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.tipo = 2 AND cvp2021.id_distrito_federal = '{$id_distrito_federal}' AND EXISTS (SELECT p.id  FROM partidos_2021 p WHERE p.principal = '1' AND p.id = cvp2021.id_partido_2021 AND p.tipo = 2) AND cvp2021.id_seccion_ine=si.id) partido_sistema_votos,
						(SELECT SUM(IFNULL(cv2021.votos_nulos,0)) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.id_distrito_federal = '{$id_distrito_federal}' AND cv2021.tipo = 2 ) votos_nulos,
						(SELECT SUM(IFNULL(cv2021.votos_can_nreg,0)) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.id_distrito_federal = '{$id_distrito_federal}' AND cv2021.tipo = 2 ) votos_can_nreg,
						(SELECT SUM(IFNULL(cv2021.lista_nominal,0)) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.id_distrito_federal = '{$id_distrito_federal}' AND cv2021.tipo = 2 ) lista_nominal,
						(SELECT SUM(IFNULL(cvp2021.votos,0)) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_seccion_ine = si.id AND cvp2021.id_distrito_federal = '{$id_distrito_federal}' AND cvp2021.tipo = 2 ) votos_validos,
						(SELECT GROUP_CONCAT(cv2021.codigo) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.id_distrito_federal = '{$id_distrito_federal}' AND cv2021.tipo = 2 ) casillas
					FROM secciones_ine si
					WHERE si.id_distrito_federal = '{$id_distrito_federal}'
				) as si
			LEFT JOIN partidos_2021 p
			ON si.partido_ganador_id = p.id
			WHERE p.tipo = 2
		";
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_secciones_ine[$row['id']]=$row;
			//$datos_distritos_federales[$row['id']]['poligonos']=$distritos_federales_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}


		include __DIR__."/../../functions/casillas_votos_2021.php";
		include __DIR__."/../../functions/casillas_votos_partidos_2021.php";
		include __DIR__."/../../functions/tipos_casillas.php";
		$id_seccion_ine;
		$casillas_votos_2021Datos = casillas_votos_2021DatosMapa('',$id_seccion_ine,' codigo ASC ',2);
		$casillas_votos_partidos_2021_partidosDatos = casillas_votos_partidos_2021_partidosDatos('','',$id_seccion_ine,2);
		foreach ($casillas_votos_2021Datos as $key => $value) {
			$voto_mayor=0;
			foreach ($casillas_votos_partidos_2021_partidosDatos as $keyT => $valueT) {
				if($value['id'] == $valueT['id_casilla_voto_2021']){
					$votos_validos = $valueT['votos'] + $votos_validos ;
					if($datos_secciones_ine[$id_seccion_ine]['partido_sistema_id'] == $valueT['id_partido_2021']) {
						$casillas_votos_2021Datos[$key]['votos_partido_sistema'] = $valueT['votos'];
						$casillas_votos_2021Datos[$key]['partido_sistema_datos'] = $valueT;
					}

					if($voto_mayor < $valueT['votos']){
						$voto_mayor = $valueT['votos'];
						$casillas_votos_2021Datos[$key]['partido_ganador_datos'] = $valueT;

						$casillas_votos_2021Datos[$key]['votos_partidos'][]=$valueT;

					}else{
						$casillas_votos_2021Datos[$key]['votos_partidos'][]=$valueT;
					}

				}
			}
			$casillas_votos_2021Datos[$key]['votos_validos'] = $votos_validos;
			$casillas_votos_2021Datos[$key]['votos_totales'] = $votos_validos + $value['votos_nulos'] + $value['votos_can_nreg'];


			$datos_casillas_votos_2021[$key]['id'] = $value['id'];
			$datos_casillas_votos_2021[$key]['clave']= $value['clave'];
			$datos_casillas_votos_2021[$key]['codigo'] = $value['codigo'];
			$datos_casillas_votos_2021[$key]['latitud'] = $value['latitud'];
			$datos_casillas_votos_2021[$key]['longitud'] = $value['longitud'];
			$datos_casillas_votos_2021[$key]['partido_ganador_votos'] = $casillas_votos_2021Datos[$key]['partido_ganador_datos']['votos'];
			$datos_casillas_votos_2021[$key]['partido_ganador_id'] = $casillas_votos_2021Datos[$key]['partido_ganador_datos']['id'];
			$datos_casillas_votos_2021[$key]['partido_ganador_background'] = $casillas_votos_2021Datos[$key]['partido_ganador_datos']['color_background'];
			$datos_casillas_votos_2021[$key]['partido_ganador_border'] = $casillas_votos_2021Datos[$key]['partido_ganador_datos']['color_border'];
			$datos_casillas_votos_2021[$key]['partido_ganador_nombre_corto'] = $casillas_votos_2021Datos[$key]['partido_ganador_datos']['nombre_corto'];
			$datos_casillas_votos_2021[$key]['partido_ganador_icono'] = $casillas_votos_2021Datos[$key]['partido_ganador_datos']['icono'];
			$datos_casillas_votos_2021[$key]['partido_ganador_logo'] = $casillas_votos_2021Datos[$key]['partido_ganador_datos']['logo'];
			$datos_casillas_votos_2021[$key]['partido_sistema_id'] = $casillas_votos_2021Datos[$key]['partido_sistema_datos']['id'];
			$datos_casillas_votos_2021[$key]['partido_sistema_nombre_corto'] = $casillas_votos_2021Datos[$key]['partido_sistema_datos']['id'];
			$datos_casillas_votos_2021[$key]['partido_sistema_nombre'] = $casillas_votos_2021Datos[$key]['partido_sistema_datos']['nombre'];
			$datos_casillas_votos_2021[$key]['partido_sistema_border'] = $casillas_votos_2021Datos[$key]['partido_sistema_datos']['color_border'];
			$datos_casillas_votos_2021[$key]['partido_sistema_background'] = $casillas_votos_2021Datos[$key]['partido_sistema_datos']['color_background'];
			$datos_casillas_votos_2021[$key]['partido_sistema_logo'] = $casillas_votos_2021Datos[$key]['partido_sistema_datos']['logo'];
			$datos_casillas_votos_2021[$key]['partido_sistema_votos'] = $casillas_votos_2021Datos[$key]['votos_partido_sistema'];
			$datos_casillas_votos_2021[$key]['votos_nulos'] = $value['votos_nulos'];
			$datos_casillas_votos_2021[$key]['votos_can_nreg'] = $value['votos_can_nreg'];
			$datos_casillas_votos_2021[$key]['lista_nominal'] = $value['lista_nominal'];
			$datos_casillas_votos_2021[$key]['votos_validos'] = $casillas_votos_2021Datos[$key]['votos_validos'];
			$datos_casillas_votos_2021[$key]['municipio'] = $value['municipio'];
			$datos_casillas_votos_2021[$key]['federalidad'] = $value['federalidad'];
			$datos_casillas_votos_2021[$key]['calle'] = $value['calle'];
			$datos_casillas_votos_2021[$key]['colonia'] = $value['colonia'];
			$datos_casillas_votos_2021[$key]['status'] = $value['status'];

			$participacion_ciudadana = ($casillas_votos_2021Datos[$key]['votos_totales'] / $value['lista_nominal'] ) * 100;
			$participacion_ciudadana = truncar($participacion_ciudadana, 2);

			$casillas_votos_2021Datos[$key]['participacion_ciudadana'] = $participacion_ciudadana;

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
		.button_link:visited,.button_link:link{
			margin-top: 10px;
			top:5px;
			position: relative;
			background-color: #808080;
			border: none;
			color: white;
			text-align: center;
			text-decoration: none;
			cursor: pointer;
			padding: 5px;
			width: 100%;
		}
		.button_link:hover{
			background-color: #b0b0b0;
		}

		.button_link:active{
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
			zoom=12;
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
					"featureType": "road.federal",
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
				streetViewControl: true,
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
			foreach ($distritos_federales_parametrosDatosMapa as $key => $value) {
				$distritos_federalesDatosMapa[$key]['numero'];
				$distritos_federalesDatosMapa[$key]['latitud'];
				$distritos_federalesDatosMapa[$key]['longitud'];
				$paths = "";
				foreach ($value as $keyT => $valueT) {
					$path = "distritos_federales_".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";

					$paths .= $path.",";
				}
				if($datos_distritos_federales[$key]['partido_ganador_background']=="" || $key != $id_distrito_federal ){
					$datos_distritos_federales[$key]['partido_ganador_border'] = "000000";
					$datos_distritos_federales[$key]['partido_ganador_background'] = "000000";
				}
				?>
				distritos_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $datos_distritos_federales[$key]['partido_ganador_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $datos_distritos_federales[$key]['partido_ganador_background'] ?>",
					fillOpacity: 0.35,
				});
				distritos_area<?= $key ?>.setMap(map);
				<?php
			}
			?>


			<?php
			foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
				$datos_secciones_ine[$key]['numero'];
				$datos_secciones_ine[$key]['latitud'];
				$datos_secciones_ine[$key]['longitud'];
				$div = '<div class="divMapaSeccion">
							<h4>Sección: '.$datos_secciones_ine[$key]['numero'].'</h4>
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
				if($datos_secciones_ine[$key]['partido_ganador_background']=="" || $key != $id_seccion_ine ){
					$datos_secciones_ine[$key]['partido_ganador_border'] = "000000";
					$datos_secciones_ine[$key]['partido_ganador_background'] = "000000";
				}
				?>
				secciones_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $datos_secciones_ine[$key]['partido_ganador_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $datos_secciones_ine[$key]['partido_ganador_background'] ?>",
					fillOpacity: 0.35,
				});
				secciones_area<?= $key ?>.setMap(map);
				<?php
			}
			?>



			///marcadores o puntos
			var marcadores = [
			<?php
			foreach ($datos_distritos_federales as $key => $value) {
				if($value['id'] != $id_distrito_federal){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['partido_ganador_logo']."' ],";
				}
			}
			foreach ($datos_secciones_ine as $key => $value) {
				if($id_seccion_ine != $value['id']){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['partido_ganador_logo']."' ],";
				}
			}
			foreach ($datos_casillas_votos_2021 as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['partido_ganador_logo']."' ],";
			}
			?>
			];
			///informacion del marcador
			var infoWindowContent = [
					<?php
					foreach ($datos_distritos_federales as $key => $value){
						if($value['id'] != $id_distrito_federal){
							$votos_totales = 0;
							$votos_totales = $votos_totales + $value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg'];
							$porcentaje_partido_ganador = ($value['partido_ganador_votos'] / $value['votos_validos'] )*100;
							$porcentaje_partido_ganador = truncar($porcentaje_partido_ganador, 2);
							$porcentaje_partido_sistema = ($value['partido_sistema_votos'] / $value['votos_validos'] )*100;
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
										<h4>Distrito Federal: '.$value['numero'].'</h4>
										<div class="info_titulo" style="width:100%">
											<h5>Votación '.$ano.'</h5>
										</div>
										<div class="info_seccion_ganador_button" style="width:100%">
											<button class="button button4" onclick="verMasDistritoFederal('.$value['id'].')">Ver Más</button>
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
						$votos_totales = 0;
						$votos_totales = $votos_totales + $value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg'];
						$porcentaje_partido_ganador = ($value['partido_ganador_votos'] / $value['votos_validos'] )*100;
						$porcentaje_partido_ganador = truncar($porcentaje_partido_ganador, 2);
						$porcentaje_partido_sistema = ($value['partido_sistema_votos'] / $value['votos_validos'] )*100;
						$porcentaje_partido_sistema = truncar($porcentaje_partido_sistema, 2);
						$diferencia_votos = $value['partido_ganador_votos'] - $value['partido_sistema_votos'];

						$datos_secciones_ine[$key]['votos_totales'] = $votos_totales;
						$datos_secciones_ine[$key]['porcentaje_partido_ganador'] = $porcentaje_partido_ganador;
						$datos_secciones_ine[$key]['porcentaje_partido_sistema'] = $porcentaje_partido_sistema;
						$datos_secciones_ine[$key]['diferencia_votos'] = $diferencia_votos;
						$datos_secciones_ine[$key]['diferencia_porcentaje'] = $porcentaje_partido_ganador - $porcentaje_partido_sistema;
						$datos_secciones_ine[$key]['diferencia_porcentaje'] = truncar($datos_secciones_ine[$key]['diferencia_porcentaje'], 2);

						$participacion_ciudadana = 0;
						if($votos_totales != 0){
							$participacion_ciudadana = ($votos_totales / $value['lista_nominal'] ) * 100;
							$participacion_ciudadana = truncar($participacion_ciudadana, 2);
						}else{
							$participacion_ciudadana =0 ;
						}

						$datos_secciones_ine[$key]['participacion_ciudadana']=$participacion_ciudadana;

						$logo = $value['partido_ganador_logo'];
						$logo_partido_sistema = $value['partido_sistema_logo'];
						$div = '<div class="divMapa">
									<div class="info_content">
										<h4>Sección: '.$value['numero'].'</h4>
										<div class="info_titulo">
											<h5>Votación 2018</h5>
										</div>
										<div class="info_seccion_ganador">
											Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
											Partido Ganador: <b>'.$value['partido_ganador_nombre_corto'].'</b><br>
										</div>
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="verMasSeccion('.$value['id'].')">Ver Más</button>
										</div>
									</div>
									<div class="datos_votos">
										<p>
											Votos Válidos: <b>'.number_format($value['votos_validos'], 0, '.', ',').'</b><br>
											Votos Nulos: <b>'.number_format($value['votos_nulos'], 0, '.', ',').'</b><br>
											Votos CAN NREG: <b>'.number_format($value['votos_can_nreg'], 0, '.', ',').'</b><br>
											Votos Totales: <b>'.number_format($votos_totales, 0, '.', ',').'</b><br>
											P. Ciudadana: <b>'.number_format($participacion_ciudadana, 2, '.', ',').'%</b><br>
										</p>
									</div>
									<div class="datos_votos">
										<div style="width:100%;text-align:center;padding:0px">
											<img src="images/logos_partidos/'.$logo.'" style="width: 30px ">
										</div>
										<p style="padding:0px;text-align:left;">
											Votos Ganador: <b>'.number_format($value['partido_ganador_votos'], 0, '.', ',').'</b><br>
											Votos % Ganador: <b>'.$porcentaje_partido_ganador.'%</b><br>
										</p>
									</div>
									<div class="logo_partido">
										<center>
											<img src="images/logos_partidos/'.$logo_partido_sistema.'" style="width: 40px ">
										</center>
									</div>
									<div class="datos_partido">
										<p>
											Votos Totales: <b>'.number_format($value['partido_sistema_votos'], 0, '.', ',').'</b><br>
											Votos % Partido: <b>'.$porcentaje_partido_sistema.'%</b><br>
											Dif. de Votos: <b>'.number_format($diferencia_votos, 0, '.', ',').'</b><br>
										</p>
									</div>
								</div>';
						$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
						?>
						['<?= $div ?>'],
						<?php
					}
					foreach ($datos_casillas_votos_2021 as $key => $value){
						$votos_totales = 0;
						$votos_totales = $votos_totales + $value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg'];
						$porcentaje_partido_ganador = ($value['partido_ganador_votos'] / $value['votos_validos'] )*100;
						$porcentaje_partido_ganador = truncar($porcentaje_partido_ganador, 2);
						$porcentaje_partido_sistema = ($value['partido_sistema_votos'] / $value['votos_validos'] )*100;
						$porcentaje_partido_sistema = truncar($porcentaje_partido_sistema, 2);
						$diferencia_votos = $value['partido_ganador_votos'] - $value['partido_sistema_votos'];

						$datos_secciones_ine[$key]['votos_totales'] = $votos_totales;
						$datos_secciones_ine[$key]['porcentaje_partido_ganador'] = $porcentaje_partido_ganador;
						$datos_secciones_ine[$key]['porcentaje_partido_sistema'] = $porcentaje_partido_sistema;
						$datos_secciones_ine[$key]['diferencia_votos'] = $diferencia_votos;
						$datos_secciones_ine[$key]['diferencia_porcentaje'] = $porcentaje_partido_ganador - $porcentaje_partido_sistema;
						$datos_secciones_ine[$key]['diferencia_porcentaje'] = truncar($datos_secciones_ine[$key]['diferencia_porcentaje'], 2);
						$participacion_ciudadana = 0;
						if($votos_totales != 0){
							$participacion_ciudadana = ($votos_totales / $value['lista_nominal'] ) * 100;
							$participacion_ciudadana = truncar($participacion_ciudadana, 2);
						}else{
							$participacion_ciudadana =0 ;
						}

						$logo = $value['partido_ganador_logo'];
						$logo_partido_sistema = $value['partido_sistema_logo'];
						$div = '<div class="divMapa">
									<div class="info_content">
										<h4>Casilla: '.$value['codigo'].'</h4>
										<div class="info_titulo">
											<h5>Votación 2021</h5>
										</div>
										<div class="info_seccion_ganador">
											Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
											Partido Ganador: <b>'.$value['partido_ganador_nombre_corto'].'</b><br>
										</div>
										<div class="info_seccion_ganador_button">
											<a class="button_link" href="https://maps.google.com/?q='.$value['latitud'].','.$value['longitud'].'" target="_blank" >Ruta GoogleMaps</a>


										</div>
									</div>
									<div class="datos_votos_direccion">
										<p>
											Dirección: <b>'.$value['calle'].'.</b><br>
											Colonia: <b>'.$value['colonia'].'.</b><br>
										</p>
									</div>
									<div class="datos_votos_direccion">
										<p>
											Federalidad: <b>'.$value['federalidad'].'.</b><br>
											Municipio: <b>'.$value['municipio'].'.</b><br>
										</p>
									</div>
									<div class="datos_votos">
										<p>
											Votos Válidos: <b>'.number_format($value['votos_validos'], 0, '.', ',').'</b><br>
											Votos Nulos: <b>'.number_format($value['votos_nulos'], 0, '.', ',').'</b><br>
											Votos CAN NREG: <b>'.number_format($value['votos_can_nreg'], 0, '.', ',').'</b><br>
											Votos Totales: <b>'.number_format($votos_totales, 0, '.', ',').'</b><br>
											P. Ciudadana: <b>'.number_format($participacion_ciudadana, 2, '.', ',').'%</b><br>
										</p>
									</div>
									<div class="datos_votos">
										<div style="width:100%;text-align:center;padding:0px">
											<img src="images/logos_partidos/'.$logo.'" style="width: 30px ">
										</div>
										<p style="padding:0px;text-align:left;">
											Votos Ganador: <b>'.number_format($value['partido_ganador_votos'], 0, '.', ',').'</b><br>
											Votos % Ganador: <b>'.$porcentaje_partido_ganador.'%</b><br>
										</p>
									</div>
									<div class="logo_partido">
										<center>
											<img src="images/logos_partidos/'.$logo_partido_sistema.'" style="width: 40px ">
										</center>
									</div>
									<div class="datos_partido">
										<p>
											Votos Totales: <b>'.number_format($value['partido_sistema_votos'], 0, '.', ',').'</b><br>
											Votos % Partido: <b>'.$porcentaje_partido_sistema.'%</b><br>
											Dif. de Votos: <b>'.number_format($diferencia_votos, 0, '.', ',').'</b><br>
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


			for (i = 0; i < marcadores.length; i++) {
				var icon = {
					//url: 'assets/images/iconos/cd-icon-location.png', // url
					url : 'images/iconos_partidos/'+ marcadores[i][3],
					scaledSize: new google.maps.Size(20, 22), // scaled size
					 
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
		}
		function getCoordsLimites(marker){ 
			//var latitud=document.getElementById("latitud").value=marker.getPosition().lat();
			// var longitud=document.getElementById("longitud").value=marker.getPosition().lng(); 
		}

		function verMasSeccion(valor){
			link="casillasVotosReportes2021/distrito_federal/index.php?id="+valor; 
			var link2="casillasVotosReportes2021/distrito_federal/index.php";
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
		function verMasDistritoFederal(valor){
			link="seccionesIneReportes2021/distrito_federal/index.php?id="+valor; 
			var link2="seccionesIneReportes2021/distrito_federal/index.php";
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
	