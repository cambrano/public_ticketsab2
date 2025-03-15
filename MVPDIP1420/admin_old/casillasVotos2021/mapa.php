<?php
	include __DIR__.'/../functions/security.php';
	include __DIR__.'/../functions/partidos_2021.php';
	$partido_2021PrincipalDatos = partido_2021PrincipalDatos();

	@session_start();
	//var_dump($_POST);
	if(!empty($_POST)){
		include '../functions/secciones_ine.php';
		include '../functions/secciones_ine_parametros.php';
		include '../functions/casillas_votos_2021.php';
		foreach ($_POST['searchTable'][0] as $key => $value) {
			//echo "XX".$key." = XX_SESSION['".$key."'];";
			$_SESSION[$key]=mysqli_real_escape_string($conexion,$value);
			//echo "<br>";
		}

		$zoom = 13;
		$orderby = ' ORDER BY clave DESC';
		$pagina = $_POST['mapa'][0]['pagina'];
		$total_registros= 11;
		$mostrardesde = $pagina * $total_registros;
		$limit = "LIMIT {$mostrardesde},11";
		$casillas_votos_2021DatosArray=casillas_votos_2021DatosArray($_POST['searchTable'][0],$orderby,$limit);


		$secciones_ineDatosArray=secciones_ineDatosArray('','','');
		$secciones_ine_parametrosDatos = secciones_ine_parametrosDatos('','',' id_seccion_ine,orden ASC');

		foreach ($secciones_ine_parametrosDatos as $key => $value) {
			$secciones_area[$value['id_seccion_ine']][] = $value ;
		}



	}else{

		$zoom = 13;
		$orderby = ' ORDER BY fechaR DESC';
		$limit = 'LIMIT 0,11';
		$secciones_ineDatosArray=secciones_ineDatosArray('','','');

		$secciones_ine_parametrosDatos = secciones_ine_parametrosDatos('','',' id_seccion_ine,orden ASC');
		$casillas_votos_2021DatosArray=casillas_votos_2021DatosArray($_POST['searchTable'][0],$orderby,$limit);
		foreach ($secciones_ine_parametrosDatos as $key => $value) {
			$secciones_area[$value['id_seccion_ine']][] = $value ;
		}
	}

?> 
	<style type="text/css">
		.divMapa{
			width:450px;
			height:40px;
			margin: -10px 0px 0px 10px;
		}
		.divMapaSeccion{
			width:150px;
			height:60px;
			margin: -10px 0px 0px 10px;
		}
		.info_titulo{
			width:30%;
			float:left;
			height:50px;
			text-align:center;
			border: 1px solid #e5e5e5;
			padding: 2px;
			background-color:#e5e5e5;
			vertical-align: middle;
		}
		.info_content{
			width: 70%;
			float:left;
		}
		.info_contentSeccion{
			width: 100%;
			float:left;
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
			padding: 6px 5px 0px 2px ;
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
			width:100%;
			float:left;
			height:90px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.logo_partido{
			width:30%;
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
		@media screen and (max-width: 820px) {
			.divMapa{
				width:167px;
				height:130px;
				margin: -10px 0px 0px 10px;
			}
			.info_titulo,.info_seccion_ganador_button{
				width:100%;
			}
			.info_seccion_ganador{
				width:100%;
			}
			.datos_votos{
				width:100%;
			}
			.datos_partido,.logo_partido{
				width:100%;
			}
		}
	</style>
	<script type="text/javascript">
		function myMap(){
			zoom=13;
			var latitud='<?=$latitud ?>';
			var longitud='<?=$longitud ?>';
			var myLatlng = new google.maps.LatLng(latitud,longitud); 
			var myOptions = {
				zoom: zoom,
				center: myLatlng,
			}

			var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
			var map = new google.maps.Map(document.getElementById("mapa"), myOptions); 
			marker1 = new google.maps.Marker({ 
				position: myLatlng,
				draggable: false,
				icon: pinImage,
			});
			google.maps.event.addListener(marker1, "dragend", function(){getCoords(marker1);});


			var visitas = [
			<?php
			///visitas
			foreach ($casillas_votos_2021DatosArray as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'SI_SCRIPT'],";
			}
			?>
			];
			var infoWindowContent_visitas = [
				<?php
				foreach ($casillas_votos_2021DatosArray as $key => $value){
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
									</div>
									<div class="info_seccion_ganador_button">
										<button class="button button4" onclick="edit('.$value['id'].')">Ver Más</button>
									</div>
									<div class="datos_votos" >
										<p>
											Sección: <b>'.$value['seccion'].'</b><br>
											Tipo: <b>'.$value['tipo_casilla'].'</b><br>
											Casilla: <b>'.$value['codigo'].'</b><br>
											Dirección : <b>'.$value['calle'].", ".$value['colonia'].", ".$value['codigo_postal'].'</b><br>
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
			var markerMilitates, i;
			//pinImageRed = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
			pinImageGreen = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
			//pinImageYellow = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/yellow-dot.png');
			var icon = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				url : 'images/iconos_partidos/partido_sistema.png',
				scaledSize: new google.maps.Size(28, 28), // scaled size
				 
			};


			for (i = 0; i < visitas.length; i++) {
				markerMilitates = new google.maps.Marker({
					position: new google.maps.LatLng(visitas[i][1], visitas[i][2]),
					map: map,
					icon: pinImageGreen,
				});


				google.maps.event.addListener(markerMilitates, 'click', (function(markerMilitates, i) {
					return function() {
						infowindowCiudadanos.setContent(infoWindowContent_visitas[i][0]);
						infowindowCiudadanos.open(map, markerMilitates);
					}
				})(markerMilitates, i));
			}

			<?php
			foreach ($secciones_area as $key => $value) {
				echo "secciones_ine".$key." = [";
				foreach ($value as $keyT => $valueT) {
					echo "{ lat: ".$valueT['latitud'].", lng: ".$valueT['longitud']." },";
				}
				echo "];";
				?>
				secciones_area<?= $key ?> = new google.maps.Polygon({
					paths: secciones_ine<?= $key ?>,
					strokeColor: "#<?= $partido_2021PrincipalDatos['color_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $partido_2021PrincipalDatos['color_background'] ?>",
					fillOpacity: 0.35,
				});
				secciones_area<?= $key ?>.setMap(map);
				<?php
			}
			?>

			var marcadores = [
				<?php
					foreach ($secciones_ineDatosArray as $key => $value) {
						echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'SI_SCRIPT'],";
					}
				?>
			];
			var infoWindowContent = [
					<?php
					foreach ($secciones_ineDatosArray as $key => $value){

						$div = '<div class="divMapaSeccion">
									<div style="width:100px;margin:0 0 20px 20px;height:60px;">
										<div class="info_contentSeccion" style="width: 100%">
											<h3>Sección: '.$value['numero'].'</h3>
										</div>
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
			//pinImageRed = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
			//pinImageGreen = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
			//pinImageYellow = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/yellow-dot.png');
			var icon = {
				//url: 'assets/images/iconos/cd-icon-location.png', // url
				/*url : 'images/iconos_partidos/partido_sistema.png',*/
				url : 'images/iconos_partidos/<?= $partido_2021PrincipalDatos['icono'] ?>',
				scaledSize: new google.maps.Size(28, 28), // scaled size
				 
			};


			for (i = 0; i < marcadores.length; i++) {
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
	</script> 
	<div id="mapa" style="width:100%;height:400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>

	