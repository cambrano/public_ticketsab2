<?php
    @session_start();
    if(!empty($_POST)){
        include '../functions/secciones_ine.php';
		include '../functions/secciones_ine_parametros.php';
    }
    $api_maps="AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI";
    $zoom = 16;
    if($id_municipio!=''){
		$search_map['id_municipio']=$id_municipio;
	}elseif($id_distrito_local!=''){
		$search_map['id_distrito_local']=$id_distrito_local;
	}else{
		$search_map['id_distrito_federal']=$id_distrito_federal;
	}
	$secciones_ineDatosMapa = secciones_ineDatosForm($search_map);
	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,$id_distrito_local,$id_distrito_federal,'','');
    $orden = 1;
?>
    <script type="text/javascript">
		var editedRowId = null; // Variable para almacenar temporalmente el ID de la fila editada
		$(document).ready(function() {
			var dataTable = $('#respuestan-tabla').DataTable( {
				"responsive": true,
				"ordering": true,
				"pageLength": 11,
				"retrieve": true,
				"info": false,
				"processing": true,
				"searching": false,
				"paging": false,
				"sPaginationType": "full_numbers",
				"order": [[ 0, "asc" ]],
				"fixedHeader": true,
				"fixedHeader": {
					header: true,
				},
				"aoColumnDefs": [
					{
						"bSortable": false,
						"aTargets": [1,2,3,4 ]
					}, 
					{
						"targets": [],
						"visible": false
					}
				],
				"serverSide": false,
				"scrollY": "100%", 
				"scrollX": "100%",

				"language": {
					"sProcessing":     "Procesando...",
					//"sLengthMenu":     "Mostrar _MENU_ registros",
					"sLengthMenu": ' ',
					"sSearch":         "Buscar:",
					"sZeroRecords":    "Registro no encontrados",
					"sEmptyTable":     "No Existe Registros",
					"sInfo":           "Mostrar  (_START_ a _END_) de _TOTAL_ Registros",//
					"sInfoEmpty":      "Mostrando Registros del 0 al 0 de Total de 0 Registros",//
					"sInfoFiltered":   "(Filtrado de _MAX_ Total Registros)",//
					//"sInfoPostFix":    "",
					//"sUrl":            "",
					//"sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst":    "<<",
						"sLast":     ">>",
						"sNext":     ">",
						"sPrevious": "<"
					},
					"oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					},
				},
			});
		});
        var map;
		var markers = [];
		var lines = [];
		var infoWindows = []; // Define the infoWindows array
        function myMap(coordenadas=null,zoomCoordenada=null) {
			tipo_update="<?= $id ?>";
			if(coordenadas==null && zoomCoordenada==null){
				latitud=<?= $latitud ?>;
				longitud=<?= $longitud ?>;
				zoom = 14;
			}
			if(tipo_update != null){
				latitud=<?= $latitud ?>;
				longitud=<?= $longitud ?>;
				zoom=<?= $zoom ?>;
			}
			if(coordenadas != null && zoomCoordenada ==null){
				var latitud = coordenadas.coords.latitude;
				var longitud = coordenadas.coords.longitude;
				document.getElementById("latitud_r").value = latitud;
				document.getElementById("longitud_r").value = longitud;
				zoom = 14;
			}

			if(coordenadas != null && zoomCoordenada != null){
				latitud=coordenadas.lat;
				longitud=coordenadas.lng;
				zoom=zoomCoordenada;
			}
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
            map = new google.maps.Map(document.getElementById("googleMap"), myOptions);
			var icon = {
                url: 'images/puntos/estas_aqui.png',
                scaledSize: new google.maps.Size(30, 30), // scaled size
            };
            marker = new google.maps.Marker({ 
                position: myLatlng,
                draggable: true, 
                zIndex: 9999999,
                icon: icon,
            });
            google.maps.event.addListener(marker, "dragend", function() { 
                            getCoords(marker); 
            });
            marker.setMap(map); 
            getCoords(marker);

			<?php
			foreach ($secciones_ineDatosMapa as $key => $value) {
				$div = '<div class="divMapaSeccion">
							<h4>Sección: '.$secciones_ineDatosMapa[$key]['numero'].'</h4>
							<button class="btn btn-primary" onclick="seccionSelect('.$secciones_ineDatosMapa[$key]['id'].')">Asignar</button>
						</div>';
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);

				$div = '<div class="divMapaSecciones">
						<div class="info_content">
							<div class="info_titulo">
								<h5>Información</h5><br>
							</div>
						</div>
						<div class="datos_seccion">
							<p>
								Sección: <b>'.$secciones_ineDatosMapa[$key]['numero'].'</b><br>
								<button class="btn btn-primary" onclick="seccionSelect('.$secciones_ineDatosMapa[$key]['id'].')">Asignar</button>
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
            google.maps.event.addListener(map, "dblclick", function (event) {
                console.log("Doble clic en el mapa");
                deleteMarker(event.latLng);
            });
		}
		function createMarker() {
			var latitud = document.getElementById("latitud").value;
			var longitud = document.getElementById("longitud").value;
			var markerPosition = new google.maps.LatLng(latitud, longitud);

			var numeroPin = markers.length + 1;
			var icon = {
				url: 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + numeroPin + '|FF776B|000000',
				//url: 'images/puntos_numeros/'++'_blue.png',
				//scaledSize: new google.maps.Size(30, 45), // Tamaño personalizado
				//labelOrigin: new google.maps.Point(15, 40), // Ajusta la posición del número
			};
			// Create a marker with an info window
			var markerLabel = {
				text: numeroPin.toString(), // Convierte el número a texto
				color: "#FFFFFF", // Color del texto del label (blanco)
				fontSize: "16px", // Tamaño de fuente del label
			};
			var marker = new google.maps.Marker({
				position: markerPosition,
				draggable: false,
				map: map,
				label: markerLabel,
			});

			// Create an info window for the marker
			var infoWindow = new google.maps.InfoWindow({
				content: '<div><p>Marcador: '+ numeroPin +'</p><button onclick="deleteMarker(' + markers.length + ')">Borrar marcador</button></div>',
			});

			// Open the info window when the marker is clicked
			google.maps.event.addListener(marker, 'click', function () {
				infoWindow.open(map, marker);
			});

			// Add the marker to the markers array
			markers.push(marker);
			infoWindows.push(infoWindow); // Agregar el infoWindow al arreglo infoWindows

			// Redraw lines connecting markers
			drawLines();
			// Agregar una fila al DataTable
			var dataTable = $('#respuestan-tabla').DataTable();
			var newRowData = [numeroPin, markers.length, '', latitud, longitud,1]; // Reemplaza esto con los datos que deseas agregar a la fila
			dataTable.row.add(newRowData).draw();
			// Obtener el nodo de la fila recién agregada
			var rowNode = dataTable.row().nodes().to$().last();
			// Agregar la clase 'nueva-fila' al nodo de la fila
			rowNode.addClass('nueva-fila');
		}

        function createMarker1() {
			var latitud = document.getElementById("latitud").value;
			var longitud = document.getElementById("longitud").value;
			var markerPosition = new google.maps.LatLng(latitud, longitud);

			// Create a marker with an info window
			var marker = new google.maps.Marker({
				position: markerPosition,
				draggable: false,
				map: map,
			});

			// Create an info window content with a delete button
			var infoWindowContent = '<div><p>Marcador </p><button onclick="deleteMarker(' + markers.length + ')">Borrar marcador</button></div>';

			// Create an info window for the marker
			var infoWindow = new google.maps.InfoWindow({
				content: infoWindowContent,
			});

			// Open the info window when the marker is clicked
			google.maps.event.addListener(marker, 'click', function () {
				infoWindow.open(map, marker);
			});

			// Add the marker to the markers array
			markers.push(marker);

			// Redraw lines connecting markers
			drawLines();
		}
        function drawLines() {
            // Limpiar líneas anteriores
            for (var i = 0; i < lines.length; i++) {
                lines[i].setMap(null);
            }
            lines = [];

            // Dibujar líneas entre marcadores
            for (var i = 0; i < markers.length - 1; i++) {
                var line = new google.maps.Polyline({
                    path: [markers[i].getPosition(), markers[i + 1].getPosition()],
                    map: map,
                    strokeColor: "#000000",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                });
                lines.push(line);
            }
        }
		function actualizarNumerosMarcadores1() {
			for (var i = 0; i < markers.length; i++) {
				// Actualiza el número del marcador (puedes personalizar el formato como desees)
				var nuevoNumero = i + 1;
				
				// Actualiza el contenido del info window para mostrar el nuevo número
				var infoWindowContent = '<div><p>Marcador ' + nuevoNumero + '</p><button onclick="deleteMarker(' + i + ')">Delete Marker</button></div>';
				
				// Actualiza el info window del marcador
				markers[i].infoWindow.setContent(infoWindowContent);
				
			}
		}
		function actualizarNumerosMarcadores_good() {
			var dataTable = $('#respuestan-tabla').DataTable();
			for (var i = 0; i < markers.length; i++) {
				if (markers[i] && infoWindows[i]) {
					// Actualiza el número del marcador (puedes personalizar el formato como desees)
					var nuevoNumero = i + 1;

					// Crea un objeto MarkerLabel para asignar el nuevo número al label
					var markerLabel = {
						text: nuevoNumero.toString(), // Convierte el número a texto
						color: "#FFFFFF", // Color del texto del label (blanco)
						fontSize: "16px", // Tamaño de fuente del label
					};

					// Actualiza el label del marcador con el nuevo número
					markers[i].setOptions({ label: markerLabel });

					// Actualiza el contenido del info window para mostrar el nuevo número
					var infoWindowContent = '<div><p>Marker ' + nuevoNumero + '</p><button onclick="deleteMarker(' + i + ')">Delete Marker</button></div>';

					// Actualiza el info window del marcador
					infoWindows[i].setContent(infoWindowContent);

					// Actualiza el número y el atributo 'id' de la fila en el DataTable
					var rowId = 'row-' + i;
					$(rows[i]).find('td:first').text(nuevoNumero);
					$(rows[i]).attr('id', rowId);

					// Actualiza el atributo 'id' de la fila en el DataTable para que coincida con el número del marcador
					dataTable.row('#' + rowId).nodes().to$().attr('id', rowId);
				}
			}
		}
		function actualizarNumerosMarcadores2() {
			var dataTable = $('#respuestan-tabla').DataTable();
			console.log(markers);
			for (var i = 0; i < markers.length; i++) {
				if (markers[i] && infoWindows[i]) {
					// Verificar si el marcador está en el mapa
					if (markers[i].getMap()) {
						// Actualiza el número del marcador (puedes personalizar el formato como desees)
						var nuevoNumero = i + 1;

						// Crea un objeto MarkerLabel para asignar el nuevo número al label
						var markerLabel = {
							text: nuevoNumero.toString(), // Convierte el número a texto
							color: "#FFFFFF", // Color del texto del label (blanco)
							fontSize: "16px", // Tamaño de fuente del label
						};

						// Actualiza el label del marcador con el nuevo número
						markers[i].setOptions({ label: markerLabel });

						// Actualiza el contenido del info window para mostrar el nuevo número
						var infoWindowContent = '<div><p>Marker ' + nuevoNumero + '</p><button onclick="deleteMarker(' + i + ')">Delete Marker</button></div>';

						// Actualiza el info window del marcador
						infoWindows[i].setContent(infoWindowContent);

						// Actualiza el número y el atributo 'id' de la fila en el DataTable
						var rowId = 'row-' + i;
						dataTable.row('#' + rowId).data([nuevoNumero, nuevoNumero, '', markers[i].getPosition().lat(), markers[i].getPosition().lng(), 1]).draw();
					}
				}
			}
		}
		var markersTemp = []; // Arreglo temporal para guardar los marcadores existentes

		function actualizarNumerosMarcadores() {
			// Guardar los marcadores existentes en el arreglo temporal
			markersTemp = markers.slice(0);

			// Eliminar todos los marcadores existentes y limpiar el mapa
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(null);
			}
			markers = [];
			infoWindows = [];
			drawLines();

			// Crear nuevos marcadores con índices y labels actualizados
			var dataTable = $('#respuestan-tabla').DataTable();
			// Obtener todos los datos de la tabla
			var puntos = dataTable.rows().data().toArray();
			// Eliminamos los puntos
			dataTable.clear().draw();
			

			for (var i = 0; i < markersTemp.length; i++) {
				var latitud = markersTemp[i].getPosition().lat();
				var longitud = markersTemp[i].getPosition().lng();

				// Crea un objeto MarkerLabel para asignar el nuevo número al label
				var markerLabel = {
					text: (i + 1).toString(), // Convierte el número a texto
					color: "#FFFFFF", // Color del texto del label (blanco)
					fontSize: "16px", // Tamaño de fuente del label
				};

				// Crea un nuevo marcador con el label actualizado
				var marker = new google.maps.Marker({
					position: { lat: latitud, lng: longitud },
					draggable: false,
					map: map,
					label: markerLabel,
				});

				// Crea un info window para el nuevo marcador
				var infoWindowContent = '<div><p>Marcador: ' + (i + 1) + '</p><button onclick="deleteMarker(' + i + ')">Borrar marcador</button></div>';
				var infoWindow = new google.maps.InfoWindow({
					content: infoWindowContent,
				});

				// Abre el info window cuando se hace clic en el marcador
				attachInfoWindow(marker, infoWindow);

				// Agrega el nuevo marcador y info window a los arreglos
				markers.push(marker);
				infoWindows.push(infoWindow);

				// Agrega una fila al DataTable
				//var newRowData = [(i + 1), (i), '', longitud, longitud, 1]; // Reemplaza esto con los datos que deseas agregar a la fila
				//dataTable.row.add(newRowData).draw();
			}

			// Dibujar las líneas entre los nuevos marcadores
			drawLines();

			// Agregamos los nuevos points en la tabla
			indexTable = 1
			puntos.forEach(element => {
				
				// Agrega una fila al DataTable
				var newRowData = [indexTable, indexTable, element[2], element[3], element[4], 1]; // Reemplaza esto con los datos que deseas agregar a la fila
				dataTable.row.add(newRowData).draw();
				indexTable++;
			});
			
		}

		// Función para adjuntar el evento 'click' al marcador y abrir la infowindow correspondiente
		function attachInfoWindow(marker, infoWindow) {
			google.maps.event.addListener(marker, 'click', function () {
				infoWindow.open(map, this);
			});
		}

		function deleteMarker(markerIndex) {
			// Imprime información de depuración
			//console.log('marcador:' + markerIndex); // Muestra el índice del marcador
			//console.log('marcadores:' + markers.length); // Muestra la cantidad de marcadores en el arreglo
			//console.log('lineas: ' + lines.length); // Muestra la cantidad de líneas en el arreglo

			// Verifica si el índice del marcador está dentro del rango válido
			if (markerIndex >= 0 && markerIndex < markers.length) {
				// Verifica si hay una línea asociada al marcador
				if (markerIndex < lines.length) {
					// Elimina la línea asociada del mapa
					lines[markerIndex].setMap(null);
					// Elimina la línea del arreglo de líneas
					lines.splice(markerIndex, 1);
				}

				// Verifica si hay una ventana de información asociada al marcador
				if (markerIndex < infoWindows.length) {
					// Cierra y elimina la ventana de información
					infoWindows[markerIndex].close();
					infoWindows.splice(markerIndex, 1);
				}
				// Elimina el marcador del mapa
				markers[markerIndex].setMap(null);

				// Elimina el marcador del arreglo de marcadores
				markers.splice(markerIndex, 1);

				var dataTable = $('#respuestan-tabla').DataTable();
				dataTable.row(markerIndex).remove().draw();

				// Redibuja las líneas que conectan los marcadores restantes
				drawLines();
				actualizarNumerosMarcadores();
			}
		}
    </script>
    <div class="sucForm">
        <input type="button" id="agregar_punto" value="Agregar Marcador" onclick="createMarker();">
    </div>
	
    <div id="mapa">
        <div id="googleMap" style="width:100%;height:400px;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
    </div>
	<div class="sucForm" style="width:100%;display: none">
		<table id="respuestan-tabla"   class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
			<thead>
				<tr>
					<th>Orden</th>
					<th>idRow</th>
					<th>id</th>
					<th>Latitud</th>
					<th>Longitud</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$num = 0;
					foreach ($secciones_ine_actividades_puntosDatos as $key => $value) {
						$num ++;
						echo "<tr>";
						echo "<td>".$value['orden']."</td>";
						echo "<td>".$num."</td>";
						echo "<td>".$value['id']."</td>";
						echo "<td>".$value['latitud']."</td>";
						echo "<td>".$value['longitud']."</td>";
						echo "<td>1</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table>
	</div>
	<script>
		startTablePuntos();
		function startTablePuntos() {
			// Crear nuevos marcadores con índices y labels actualizados
			var dataTable = $('#respuestan-tabla').DataTable();
			// Obtener todos los datos de la tabla
			var puntos = dataTable.rows().data().toArray();
			puntos.forEach(element => {
				id = element[2];
				orden = element[0];
				latitud = parseFloat(element[3]); // Convertir a número
				longitud = parseFloat(element[4]); // Convertir a número

				// Verificar si latitud y longitud son números válidos
				if (!isNaN(latitud) && !isNaN(longitud)) {
					var markerLabel = {
						text: orden.toString(), // Convierte el número a texto
						color: "#FFFFFF", // Color del texto del label (blanco)
						fontSize: "16px", // Tamaño de fuente del label
					};

					// Crea un nuevo marcador con el label actualizado
					var marker = new google.maps.Marker({
						position: { lat: latitud, lng: longitud },
						draggable: false,
						map: map,
						label: markerLabel,
					});

					// Crea un info window para el nuevo marcador
					var infoWindowContent = '<div><p>Marcador: ' + (orden) + '</p><button onclick="deleteMarker(' + (orden-1) + ')">Borrar marcador</button></div>';
					var infoWindow = new google.maps.InfoWindow({
						content: infoWindowContent,
					});

					// Abre el info window cuando se hace clic en el marcador
					attachInfoWindow(marker, infoWindow);

					// Agrega el nuevo marcador y info window a los arreglos
					markers.push(marker);
					infoWindows.push(infoWindow);
					drawLines();
				} else {
					console.error("Coordenadas inválidas: latitud=" + latitud + ", longitud=" + longitud);
				}
			});
		}

		
	</script>