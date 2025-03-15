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
			var dataTable = $('#sub_eventos-tabla').DataTable( {
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
						"targets": [0,1,2,5,6,7,8,10,17,18,21],
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
		}
    </script>
    <style>
		.fila-desactivada {
			/*background-color: #f5f5f5 !important; /* Cambia el fondo de la fila */
			color: #a9a9a9 !important;           /* Cambia el color del texto */
			pointer-events: none !important;     /* Deshabilita eventos del mouse */
			background-color: #f2dede !important; /* Color rojo claro */
			opacity: 0.6; /* Añadir opacidad */
		}
	</style>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Datos Fecha y Dirección</label>
	</div>
	<div class="sucForm" style="width:100%">
		<div id="mensaje_sub_evento" class="mensajeSolo" ></div>
	</div>
	<div class="sucForm" style="display:none">
		<label class="labelForm" id="labeltemaname">id<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="id_agenda" autocomplete="off"  id="id_agenda" value="" placeholder="" /><br>
	</div>
	<div class="sucForm" style="display:none">
		<label class="labelForm" id="labeltemaname">row<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="row" autocomplete="off"  id="row" value="" placeholder="" /><br>
	</div>
	
	<div class="sucForm">
		<td style="padding:5px">
			<input class="inputlogin" type="checkbox" name="chk_fecha" autocomplete="off" id="chk_fecha" value="1"/>
		</td>
		<label class="labelForm" for="chk_fecha" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $seccion_ine_agenda_gobiernoDatos['fecha'] ?>" placeholder="" /><br>
	</div>

	<div class="sucForm">
		<td style="padding:5px">
			<input class="inputlogin" type="checkbox" name="chk_hora" autocomplete="off" id="chk_hora" value="1"/>
		</td>
		<label class="labelForm"  for="chk_hora" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $seccion_ine_agenda_gobiernoDatos['hora'] ?>" placeholder="" /><br>
	</div>
	<div class="sucForm" style="width:100%">
		<td style="padding:5px">
			<input class="inputlogin" type="checkbox" name="chk_direccion" autocomplete="off" id="chk_direccion" value="1"/>
		</td>
		<label class="labelForm" for="chk_direccion" id="labeltemaname">Dirección</label><br>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
		<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
		<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >
			<?php
			echo paises($seccion_ine_agenda_gobiernoDatos['id_pais']);
			?>
		</select>
	</div>
	
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display:none;">
		<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
		<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);" disabled="disabled" >  
			<?php
			echo estados($seccion_ine_agenda_gobiernoDatos['id_estado']);
			?>
		</select>
	</div>
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
		<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
		<select   name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">  
			<?php
			echo municipios($seccion_ine_agenda_gobiernoDatos['id_municipio'],$seccion_ine_agenda_gobiernoDatos['id_estado']);
			?>
		</select>
	</div>
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
		<select   name="id_localidad" id="id_localidad" class='myselect' onchange="locationLocalidad(this)">  
			<?php
			echo localidades($seccion_ine_agenda_gobiernoDatos['id_localidad'],$seccion_ine_agenda_gobiernoDatos['id_municipio'],$seccion_ine_agenda_gobiernoDatos['id_estado']);
			?>
		</select>
	</div>
	<div class="sucForm" style="display:none">
		<div id="localidades_asignadas_seccion"></div>
	</div>
	
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
		<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="calle" autocomplete="off" id="calle" value="<?= $seccion_ine_agenda_gobiernoDatos['calle'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
	</div>

	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname">Num Ext.</label><br>
		<input class="inputlogin" type="text" name="num_ext" autocomplete="off"  id="num_ext" value="<?= $seccion_ine_agenda_gobiernoDatos['num_ext'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
	</div>

	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname">Num Int.</label><br>
		<input class="inputlogin" type="text" name="num_int" autocomplete="off"  id="num_int" value="<?= $seccion_ine_agenda_gobiernoDatos['num_int'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
	</div>

	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="colonia" autocomplete="off"  id="colonia" value="<?= $seccion_ine_agenda_gobiernoDatos['colonia'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
	</div> 


	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="codigo_postal" autocomplete="off"  id="codigo_postal" value="<?= $seccion_ine_agenda_gobiernoDatos['codigo_postal'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="latitud" autocomplete="off"  id="latitud" value="<?= $seccion_ine_agenda_gobiernoDatos['latitud'] ?>" placeholder="" maxlength="120" onkeypress="" /><br>
	</div>

	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" type="text" name="longitud" autocomplete="off"  id="longitud" value="<?= $seccion_ine_agenda_gobiernoDatos['longitud'] ?>" placeholder="" maxlength="120" onkeypress=" " /><br>
	</div>
	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
		<label class="labelForm" id="labeltemaname"><br></label><br>
		<input type="button" value="Generar Mapa Coordenadas" onclick="generar_mapa_coordenadas()">
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sección<font color="#FF0004">*</font></label><br>
		<select class="myselect" id="id_seccion_ine" >
			<?php
			echo secciones_ine($seccion_ine_agenda_gobiernoDatos['id_seccion_ine'],$id_municipio);
			?>
		</select><br>
	</div>
	<div class="sucForm" style="width:100%">
		<label class="labelForm" id="labeltemaname"><br></label><br>
		<input type="button" id="agregar-btn" value="Agregar Fecha y Dirección" style="width:100%" onclick="agregarFechaDireccion()">
	</div>
	<div class="sucForm" style="width: 100%">
		<input type="hidden" name="latitud_r" id="latitud_r" value="<?= $seccion_ine_agenda_gobiernoDatos['latitud_r'] ?>" placeholder="latitud">
		<input type="hidden" name="longitud_r" id="longitud_r" value="<?= $seccion_ine_agenda_gobiernoDatos['longitud_r'] ?>" placeholder="longitud">
	</div>
	
	<br><br>
    <div id="mapa">
        <div id="googleMap" style="width:100%;height:400px;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
    </div>
	<div class="sucForm" style="width:100%;">
		<table id="sub_eventos-tabla"   class="table table-striped table-bordered  cell-border compact stripe" style="width:100%">
			<thead>
				<tr>
					<th>Orden</th>
					<th>idRow</th>
					<th>id</th>
					<th>Fecha</th>
					<th>Hora</th>
					<th>Id Pais</th>
					<th>Id Estado</th>
					<th>Id Municipio</th>
					<th>Id Localidad</th>
					<th>Localidad</th>
					<th>Id Sección</th>
					<th>Sección</th>
					<th>Colonia</th>
					<th>Calle</th>
					<th>Num Int.</th>
					<th>Num Ext.</th>
					<th>Codigo Postal</th>
					<th>Id Distrito Local</th>
					<th>Id Distrito Federal</th>
					<th>Latitud</th>
					<th>Longitud</th>
					<th>Status</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$num = 0;
					foreach ($secciones_ine_agendas_gobierno_locacionesDatos as $key => $value) {
						
						echo "<tr>";
						echo "<td>".$value['orden']."</td>";
						echo "<td>".$num."</td>";
						echo "<td>".$value['id']."</td>";
						echo "<td>".$value['fecha']."</td>";
						echo "<td>".$value['hora']."</td>";
						echo "<td>".$value['id_pais']."</td>";
						echo "<td>".$value['id_estado']."</td>";
						echo "<td>".$value['id_municipio']."</td>";
						echo "<td>".$value['id_localidad']."</td>";
						echo "<td>".$value['localidad']."</td>";
						echo "<td>".$value['id_seccion_ine']."</td>";
						echo "<td>".$value['seccion_ine']."</td>";
						echo "<td>".$value['colonia']."</td>";
						echo "<td>".$value['calle']."</td>";
						echo "<td>".$value['num_int']."</td>";
						echo "<td>".$value['num_ext']."</td>";
						echo "<td>".$value['codigo_postal']."</td>";
						echo "<td>".$value['id_distrito_local']."</td>";
						echo "<td>".$value['id_distrito_federal']."</td>";
						echo "<td>".$value['latitud']."</td>";
						echo "<td>".$value['longitud']."</td>";
						echo "<td>1</td>";
						$boton='<button class="btn btn-primary btn-sm" onclick="editarFila(this, '.$num.')">Editar</button>';
						$boton.='<button class="btn btn-danger btn-sm" onclick="eliminarFila(this, '.$num.')">Eliminar</button>';
						
						echo "<td>{$boton}</td>";
						echo "</tr>";
						$num ++;
					}
				?>
			</tbody>
		</table>
	</div>
	<script>
    function agregarFechaDireccion() {
		document.getElementById("agregar-btn").disabled = true;
		document.getElementById("mensaje_sub_evento").classList.remove("mensajeSucces");
		document.getElementById("mensaje_sub_evento").classList.remove("mensajeError");
		$("#mensaje_sub_evento").html("&nbsp");
		var coma= /,/g;
		var espacios_invalidos= /\s+/g;

		var fecha = document.getElementById("fecha").value; 
		if(fecha == ""){
			document.getElementById("fecha").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Fecha requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		if(!fechaValida(fecha)){ 
			document.getElementById("fecha").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Fecha Válida requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var hora = document.getElementById("hora").value; 
		if(hora == ""){
			document.getElementById("hora").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Hora requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var id_pais = document.getElementById("id_pais").value; 
		if(id_pais == ""){
			document.getElementById("id_pais").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Pais requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		//alert(codigo_postal);
		var id_estado = document.getElementById("id_estado").value; 
		if(id_estado == ""){
			document.getElementById("id_estado").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Estado requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		//alert(id_estado);
		var id_municipio = document.getElementById("id_municipio").value; 
		if(id_municipio == ""){
			document.getElementById("id_municipio").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Municipio requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		//alert(id_municipio);

		//alert(id_municipio);
		var id_localidad = document.getElementById("id_localidad").value; 
		if(id_localidad == ""){
			document.getElementById("id_localidad").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Localidad requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		var calle = document.getElementById("calle").value; 
		if(calle == ""){
			document.getElementById("calle").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Calle requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		var num_ext = document.getElementById("num_ext").value;
		var num_int = document.getElementById("num_int").value;
		//alert(calle);
		var colonia = document.getElementById("colonia").value; 
		if(colonia == ""){
			document.getElementById("colonia").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Colonia requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		var codigo_postal = document.getElementById("codigo_postal").value;
		codigo_postal = codigo_postal.replace(espacios_invalidos, '');
		if(codigo_postal == ""){
			document.getElementById("codigo_postal").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Codigo Postal requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var longitud = document.getElementById("longitud").value;
		longitud = longitud.replace(espacios_invalidos, '');
		if(longitud == ""){
			document.getElementById("longitud").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Longitud requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var latitud = document.getElementById("latitud").value;
		latitud = latitud.replace(espacios_invalidos, '');
		if(latitud == ""){
			document.getElementById("latitud").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Latitud requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var id_seccion_ine = document.getElementById("id_seccion_ine").value; 
		if(id_seccion_ine == ""){
			document.getElementById("id_seccion_ine").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Sección requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		



        const tablaElement = document.getElementById("sub_eventos-tabla");
        const tablaDataTable = $('#sub_eventos-tabla').DataTable(); // Inicializa la instancia de DataTable

        const datos = {
			id_agenda: document.getElementById("id_agenda").value,
            fecha: document.getElementById("fecha").value,
            hora: document.getElementById("hora").value,
            id_pais: document.getElementById("id_pais") ? document.getElementById("id_pais").value : "",
            id_estado: document.getElementById("id_estado") ? document.getElementById("id_estado").value : "",
            id_municipio: document.getElementById("id_municipio").value,
            id_localidad: document.getElementById("id_localidad").value,
            localidad: document.getElementById("id_localidad").options[document.getElementById("id_localidad").selectedIndex].text,
            id_seccion_ine: document.getElementById("id_seccion_ine").value,
            seccion_ine: document.getElementById("id_seccion_ine").options[document.getElementById("id_seccion_ine").selectedIndex].text,
            colonia: document.getElementById("colonia").value,
            calle: document.getElementById("calle").value,
            numInt: document.getElementById("num_int").value,
            numExt: document.getElementById("num_ext").value,
            codigoPostal: document.getElementById("codigo_postal").value,
            latitud: document.getElementById("latitud").value,
            longitud: document.getElementById("longitud").value
        };

       

        const valoresFila = [
            tablaDataTable.rows().count() + 1, // Orden
            tablaDataTable.rows().count(),    // idRow
            datos.id_agenda,                          // id (puedes ajustar este valor si es necesario)
            datos.fecha,
            datos.hora,
            datos.id_pais,
            datos.id_estado,
            datos.id_municipio,                // ID Municipio repetido
            datos.id_localidad,
            datos.localidad,
            datos.id_seccion_ine,
            datos.seccion_ine,
            datos.colonia,
            datos.calle,
            datos.numInt,
            datos.numExt,
            datos.codigoPostal,
            "-",                              // Id Distrito Local
            "-",                              // Id Distrito Federal
            datos.latitud,
            datos.longitud,
            "1",                               // Status
            `<button class="btn btn-primary btn-sm" onclick="editarFila(this, ${tablaDataTable.rows().count()})">Editar</button>
             <button class="btn btn-danger btn-sm" onclick="eliminarFila(this, ${tablaDataTable.rows().count()})">Eliminar</button>` // Botón de editar y eliminar
        ];

        // Agregar la nueva fila al DataTable
        tablaDataTable.row.add(valoresFila).draw();

        // Opcional: Resetear los valores de los inputs después de agregar
		const chkHora = document.getElementById('chk_hora');
		if (!chkHora.checked) {
			document.getElementById("hora").value = "";
		}
		const chkFecha = document.getElementById('chk_fecha');
		if (!chkFecha.checked) {
			document.getElementById("fecha").value = "";
		}
		const chkDireccion = document.getElementById('chk_direccion');
		if (!chkDireccion.checked) {
			document.getElementById("id_localidad").value = "";
			document.getElementById("colonia").value = "";
			document.getElementById("calle").value = "";
			document.getElementById("num_int").value = "";
			document.getElementById("num_ext").value = "";
			document.getElementById("codigo_postal").value = "";
			document.getElementById("latitud").value = "";
			document.getElementById("longitud").value = "";
			const selects = $('#id_localidad, #id_seccion_ine');
			selects.val('').trigger('change');	
		}
        
		document.getElementById("agregar-btn").disabled = false;
    }

    // Función para eliminar una fila
    function eliminarFila(btn, index) {
        const tablaDataTable = $('#sub_eventos-tabla').DataTable(); // Obtiene la instancia del DataTable
        const row = tablaDataTable.row(index); // Utiliza el índice para obtener la fila
        if (row) {
            row.remove().draw(); // Elimina la fila y redibuja la tabla
        } else {
            console.error("No se pudo encontrar la fila en el DataTable.");
        }
    }

    // Función para editar una fila
    function editarFila(btn, index) {
        const tablaDataTable = $('#sub_eventos-tabla').DataTable(); // Obtiene la instancia del DataTable
        const rowData = tablaDataTable.row(index).data(); // Obtiene los datos de la fila seleccionada
		const row = tablaDataTable.row(index); // Obtiene la fila mediante su índice
		if (row.node()) { // Asegúrate de que el nodo DOM exista
			$(row.node()).addClass('fila-desactivada'); // Usa .node() para obtener el nodo DOM
			row.cell({ row: index, column: 22 }).data('').draw();
		} else {
			console.error("No se pudo encontrar la fila en el DataTable.");
		}

        // Rellenar los campos de formulario con los datos de la fila
		document.getElementById("row").value = rowData[1];
		document.getElementById("id_agenda").value = rowData[2];
        document.getElementById("fecha").value = rowData[3];
        document.getElementById("hora").value = rowData[4];
        document.getElementById("id_pais").value = rowData[5];
        document.getElementById("id_estado").value = rowData[6];
        id_municipio = document.getElementById("id_municipio").value = rowData[7];
        id_localidad = document.getElementById("id_localidad").value = rowData[8];
		id_seccion_ine = document.getElementById("id_seccion_ine").value = rowData[10];
        document.getElementById("colonia").value = rowData[12];
        document.getElementById("calle").value = rowData[13];
        document.getElementById("num_int").value = rowData[14];
        document.getElementById("num_ext").value = rowData[15];
        document.getElementById("codigo_postal").value = rowData[16];
        

        // Modificar el botón de agregar a un botón de actualizar
        document.getElementById("agregar-btn").value = "Actualizar";
        document.getElementById("agregar-btn").setAttribute("onclick", `actualizarFila(${index})`);

		$('#id_municipio').val(id_municipio).trigger('change');
		$('#id_seccion_ine').val(id_seccion_ine).trigger('change');
		document.getElementById("latitud").value = rowData[19];
        document.getElementById("longitud").value = rowData[20];
		
		
		setTimeout(() => {
			actualizacionCampos(rowData); // Ejecuta la función después de 2 segundos
		}, 1000);

    }

	function actualizacionCampos(rowData){
		$('#id_localidad').val(id_localidad).trigger('change');
		document.getElementById("latitud").value = rowData[19];
        document.getElementById("longitud").value = rowData[20];
		generar_mapa_coordenadas();
	}

    // Función para actualizar la fila
    function actualizarFila(index) {
		document.getElementById("agregar-btn").disabled = true;
		document.getElementById("mensaje_sub_evento").classList.remove("mensajeSucces");
		document.getElementById("mensaje_sub_evento").classList.remove("mensajeError");
		$("#mensaje_sub_evento").html("&nbsp");
		var coma= /,/g;
		var espacios_invalidos= /\s+/g;

		var fecha = document.getElementById("fecha").value; 
		if(fecha == ""){
			document.getElementById("fecha").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Fecha requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		if(!fechaValida(fecha)){ 
			document.getElementById("fecha").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Fecha Válida requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var hora = document.getElementById("hora").value; 
		if(hora == ""){
			document.getElementById("hora").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Hora requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var id_pais = document.getElementById("id_pais").value; 
		if(id_pais == ""){
			document.getElementById("id_pais").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Pais requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		//alert(codigo_postal);
		var id_estado = document.getElementById("id_estado").value; 
		if(id_estado == ""){
			document.getElementById("id_estado").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Estado requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		//alert(id_estado);
		var id_municipio = document.getElementById("id_municipio").value; 
		if(id_municipio == ""){
			document.getElementById("id_municipio").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Municipio requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		//alert(id_municipio);

		//alert(id_municipio);
		var id_localidad = document.getElementById("id_localidad").value; 
		if(id_localidad == ""){
			document.getElementById("id_localidad").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Localidad requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		var calle = document.getElementById("calle").value; 
		if(calle == ""){
			document.getElementById("calle").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Calle requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		var num_ext = document.getElementById("num_ext").value;
		var num_int = document.getElementById("num_int").value;
		//alert(calle);
		var colonia = document.getElementById("colonia").value; 
		if(colonia == ""){
			document.getElementById("colonia").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Colonia requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}
		var codigo_postal = document.getElementById("codigo_postal").value;
		codigo_postal = codigo_postal.replace(espacios_invalidos, '');
		if(codigo_postal == ""){
			document.getElementById("codigo_postal").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Codigo Postal requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var longitud = document.getElementById("longitud").value;
		longitud = longitud.replace(espacios_invalidos, '');
		if(longitud == ""){
			document.getElementById("longitud").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Longitud requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var latitud = document.getElementById("latitud").value;
		latitud = latitud.replace(espacios_invalidos, '');
		if(latitud == ""){
			document.getElementById("latitud").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Latitud requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

		var id_seccion_ine = document.getElementById("id_seccion_ine").value; 
		if(id_seccion_ine == ""){
			document.getElementById("id_seccion_ine").focus(); 
			document.getElementById("agregar-btn").disabled = false;
			$("#mensaje_sub_evento").html("Sección requerido");
			document.getElementById("mensaje_sub_evento").classList.add("mensajeError");
			return false;
		}

        const tablaDataTable = $('#sub_eventos-tabla').DataTable(); // Obtiene la instancia del DataTable
		const row = tablaDataTable.row(index); // Obtiene la fila mediante su índice
		if (row.node()) { // Asegúrate de que el nodo DOM exista
			$(row.node()).removeClass('fila-desactivada'); // Usa .node() para obtener el nodo DOM
		} else {
			console.error("No se pudo encontrar la fila en el DataTable.");
		}
        const datos = {
			id: document.getElementById("id_agenda").value,
			row: document.getElementById("row").value,
			fecha: document.getElementById("fecha").value,
            fecha: document.getElementById("fecha").value,
            hora: document.getElementById("hora").value,
            id_pais: document.getElementById("id_pais") ? document.getElementById("id_pais").value : "",
            id_estado: document.getElementById("id_estado") ? document.getElementById("id_estado").value : "",
            id_municipio: document.getElementById("id_municipio").value,
            id_localidad: document.getElementById("id_localidad").value,
            localidad: document.getElementById("id_localidad").options[document.getElementById("id_localidad").selectedIndex].text,
            id_seccion_ine: document.getElementById("id_seccion_ine").value,
            seccion_ine: document.getElementById("id_seccion_ine").options[document.getElementById("id_seccion_ine").selectedIndex].text,
            colonia: document.getElementById("colonia").value,
            calle: document.getElementById("calle").value,
            numInt: document.getElementById("num_int").value,
            numExt: document.getElementById("num_ext").value,
            codigoPostal: document.getElementById("codigo_postal").value,
            latitud: document.getElementById("latitud").value,
            longitud: document.getElementById("longitud").value
        };

        // Actualizar los datos de la fila seleccionada
		if(datos.id==""){
			datos.id== ""
		}
		if(datos.row==""){
			datos.row== index
		}
        tablaDataTable.row(datos.row).data([
            tablaDataTable.rows().count() + 1,
            datos.row,
			datos.id,
            datos.fecha,
            datos.hora,
            datos.id_pais,
            datos.id_estado,
            datos.id_municipio,
            datos.id_localidad,
            datos.localidad,
            datos.id_seccion_ine,
            datos.seccion_ine,
            datos.colonia,
            datos.calle,
            datos.numInt,
            datos.numExt,
            datos.codigoPostal,
            "-",
            "-",
            datos.latitud,
            datos.longitud,
            "1",
            `<button class="btn btn-primary btn-sm" onclick="editarFila(this, ${index})">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="eliminarFila(this, ${index})">Eliminar</button>`
        ]).draw();

        // Resetear el formulario
		document.getElementById("agregar-btn").value = "Agregar Fecha y Dirección";
        document.getElementById("agregar-btn").setAttribute("onclick", "agregarFechaDireccion()");
		document.getElementById("id_agenda").value = "";
        document.getElementById("row").value = "";


		 // Opcional: Resetear los valores de los inputs después de agregar
		const chkHora = document.getElementById('chk_hora');
		if (!chkHora.checked) {
			document.getElementById("hora").value = "";
		}
		const chkFecha = document.getElementById('chk_fecha');
		if (!chkFecha.checked) {
			document.getElementById("fecha").value = "";
		}
		const chkDireccion = document.getElementById('chk_direccion');
		if (!chkDireccion.checked) {
			document.getElementById("id_localidad").value = "";
			document.getElementById("colonia").value = "";
			document.getElementById("calle").value = "";
			document.getElementById("num_int").value = "";
			document.getElementById("num_ext").value = "";
			document.getElementById("codigo_postal").value = "";
			document.getElementById("latitud").value = "";
			document.getElementById("longitud").value = "";
			const selects = $('#id_localidad, #id_seccion_ine');
			selects.val('').trigger('change');	
		}
		document.getElementById("agregar-btn").disabled = false;
    }
</script>
