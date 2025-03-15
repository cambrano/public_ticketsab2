    <?php
    if(!empty($_POST['mapa'][0])){
		$latitud = $_POST['mapa'][0]['latitud'];
		$longitud = $_POST['mapa'][0]['longitud'];
    }else
    ?>
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
			controlHiddenPoligono.className = "mapaShowPoligono";
			controlHiddenPoligono.setAttribute("id", "mapaShowPoligono");
			//controlMapaCambio.style.cursor = 'pointer';
			//controlMapaCambio.style.height = '64px';
			//controlMapaCambio.style.width = '64px';
			//controlMapaCambio.style.top = '11px';
			//controlMapaCambio.style.left = '120px';
			controlHiddenPoligono.title = 'Mostrar Capas';
			controlHiddenPoligono.addEventListener('click', mapaShowPoligono);
			//controlMapaCambio.addEventListener("mouseover", mouseOver, false);
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(controlHiddenPoligono);


		}
		function getCoords(marker){ 
			document.getElementById("latitud").value=marker.getPosition().lat(); 
			document.getElementById("longitud").value=marker.getPosition().lng(); 
		}
		function mapaCambioPoligono(){
			alert('ir a colonias');
		}
		function mapaShowPoligono(){
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
				url: "seccionesIneCiudadanos/mapashow.php",
				data: {searchTable: searchTable,mapa:mapa},
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
		.mapaShowPoligono{
			cursor:pointer; 
			height:54px; 
			width:54px; 
			top:11px; 
			left:190px;
			background-image: url(img/gps.png);
		}
		.mapaShowPoligono:hover{
			opacity: 0.8;
			filter: alpha(opacity=80);
		}
		.mapaShowPoligono:active{
			opacity: 0.9;
			filter: alpha(opacity=90);
		}
		@media only screen and (max-width:992px) {
			.mapaCambioPoligono{ 
				top:11px; 
				left:120px;
			}
			.mapaShowPoligono{ 
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