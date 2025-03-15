<?php
    @session_start();
    if(!empty($_POST['puntero'][0])){
        include '../functions/secciones_ine.php';
		include '../functions/secciones_ine_parametros.php';
        
        $data = $_POST['puntero'][0];
        if($data['tipo'] == 'add'){
            $data['key'] = count($_SESSION['puntos_line'])+2;
            $latitud = $_POST['puntero'][0]['latitud'];
            $longitud = $_POST['puntero'][0]['longitud'];
            $id = 1;
            $punto_line_ultimo =  end($_SESSION['puntos_line']);
            $orden_key = $punto_line_ultimo['orden'] + 1;
            $lineas_array = $_SESSION['puntos_line'][] = array('latitud' => $latitud, 'longitud' => $longitud ,'status' => 1,'orden' => $orden_key  );

        }
        if($data['tipo'] == 'delete'){
            $latitud = $_POST['puntero'][0]['latitud'];
            $longitud = $_POST['puntero'][0]['longitud'];
            $_SESSION['puntos_line'][$data['key']]['status'] = 0;
            $id = 1;
        }
        if($data['tipo'] == 'editPunto'){
            $latitud = $_SESSION['puntos_line'][$data['key']]['latitud'];
            $longitud = $_SESSION['puntos_line'][$data['key']]['longitud'];
            //$data['key'] = $data['key'] - 1;
            $id = 1;
        }

        if($data['tipo'] == 'edit'){
            $_SESSION['puntos_line'][$data['key']]['latitud'] = $data['latitud'];
            $_SESSION['puntos_line'][$data['key']]['longitud'] = $data['longitud'];
            $data['key'] = count($_SESSION['puntos_line'])+2;
            $id = 1;
            $punto_line_ultimo =  end($_SESSION['puntos_line']);
            $latitud = $punto_line_ultimo['latitud'];
            $longitud = $punto_line_ultimo['longitud'];
        }

        

        $lineas_array = $_SESSION['puntos_line'];
    }else{
        $data['key'] = count($_SESSION['puntos_line'])+2;
        //unset($_SESSION['puntos_line']);
    }
    if($id_municipio!=''){
		$search_map['id_municipio']=$id_municipio;
	}elseif($id_distrito_local!=''){
		$search_map['id_distrito_local']=$id_distrito_local;
	}else{
		$search_map['id_distrito_federal']=$id_distrito_federal;
	}
	$secciones_ineDatosMapa = secciones_ineDatosForm($search_map);
	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,$id_distrito_local,$id_distrito_federal,'','');

?>
    <script>
        function myMap(coordenadas=null,zoomCoordenada=null) {
            zoom = 14;
            var tipo_update = '<?= $id ?>';
            if(coordenadas != null && zoomCoordenada ==null){
                var latitud = coordenadas.coords.latitude;
                var longitud = coordenadas.coords.longitude;
                document.getElementById("latitud_r").value = latitud;
                document.getElementById("longitud_r").value = longitud;
                zoom = 14;
            }
            if(tipo_update != ''){
				latitud=<?= $latitud ?>;
				longitud=<?= $longitud ?>;
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
            var map = new google.maps.Map(document.getElementById("googleMap"), myOptions); 

            ///
            const rutaCoordinates = [
                <?php
                foreach ($lineas_array as $key => $value) {
                    if($value['status'] == 1){
                        ?>
                        { lat: <?= $value['latitud'] ?>, lng: <?= $value['longitud'] ?> },
                        <?php
                    }
                }
                ?>
            ];
            const rutaPath = new google.maps.Polyline({
                path: rutaCoordinates,
                geodesic: true,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2,
            });

            rutaPath.setMap(map);


            var ciudadanos = [
			<?php
			///ciudadanos
            $orden = 1;
			foreach ($lineas_array as $key => $value) {
                if($value['status'] == 1){
                    if($key != $data['key']){
                        echo "['".$key."', ".$value['latitud'].", ".$value['longitud'].",".$orden."],";
                    }else{
                        $ordenx = $orden;
                    }
                    $orden ++;
                }
			}
			?>
			];
            var infoWindowContent_Puntero = [
				<?php
				foreach ($lineas_array as $key => $value){
                    if($value['status'] == 1){
                        $div = '<div class="divMapa">
									<div class="info_content">
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="editPuntero('.$key.')">Editar</button>
                                            <button class="button button4" onclick="deletePuntero('.$key.')">Eliminar</button>
										</div>
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
            var infowindowPuntero = new google.maps.InfoWindow();
			var markerMilitates, i;
            for (i = 0; i < ciudadanos.length; i++) {
                //pinImageGreen = new google.maps.MarkerImage();
                var icon = {
                    url: 'images/puntos_numeros/'+ciudadanos[i][3]+'.png',
				    scaledSize: new google.maps.Size(28, 28), // scaled size
                };
				markerMilitates = new google.maps.Marker({
					position: new google.maps.LatLng(ciudadanos[i][1], ciudadanos[i][2]),
					map: map,
					icon: icon,
				});


				google.maps.event.addListener(markerMilitates, 'click', (function(markerMilitates, i) {
					return function() {
						infowindowPuntero.setContent(infoWindowContent_Puntero[i][0]);
						infowindowPuntero.open(map, markerMilitates);
					}
				})(markerMilitates, i));
			}
            <?php
                if($data['tipo'] == 'editPunto'){
                    $orden = $ordenx;
                }
            ?>
            var icon = {
                url: 'images/puntos_numeros/<?= $orden ?>_blue.png',
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
            foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
                $secciones_ineDatosMapa[$key]['numero'];
                $secciones_ineDatosMapa[$key]['latitud'];
                $secciones_ineDatosMapa[$key]['longitud'];
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

        }
    </script>
    <div id="googleMap" style="width:100%;height:400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>