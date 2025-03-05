<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('security','zonas_importantes',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		</script>
		<?php
		die;
	}

	$api_maps="AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI";
	//$api_maps="AIzaSyD_TgaVmoOnFwxJ8hhPOlE_pJehZiuin4Y";
	$zoom = 14;
	if($zona_importanteDatos['longitud']=="" || $zona_importanteDatos['latitud']=="" ){
		$zoom = 14;
	}else{
		$longitud=$zona_importanteDatos['longitud'];
		$latitud=$zona_importanteDatos['latitud'];
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
	<script type="text/javascript">
		function seccionSelect(valor){
			var id_seccion_ine = valor
			//enviar documento
			var seccion_ine = []; 
			var data = {    
				'id_seccion_ine' : id_seccion_ine,
			}
			seccion_ine.push(data);
			$.ajax({
				type: "POST",
				url: "localidadesSecciones/localidades_secciones_seccion.php",
				data: {seccion_ine: seccion_ine},
				success: function(data) {
					$("#localidades_asignadas_seccion").html(data);
				}
			});
			$('#id_seccion_ine').val(valor);
			$('#id_seccion_ine').select2().trigger('change');
		}
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


			pinImageGreen = new google.maps.MarkerImage('https://maps.gstatic.com/mapfiles/ms2/micons/green-dot.png');
			markerMilitates = new google.maps.Marker({
				position: new google.maps.LatLng("<?= $seccion_ine_ciudadanoDatos['latitud_r'] ?>", "<?= $seccion_ine_ciudadanoDatos['longitud_r'] ?>"),
				map: map,
				icon: pinImageGreen,
			});
			markerMilitates.setMap(map); 

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
		function getCoords(marker){ 
			document.getElementById("latitud").value=marker.getPosition().lat(); 
			document.getElementById("longitud").value=marker.getPosition().lng(); 
		}
		function generar_mapa_coordenadas(){
			document.getElementById("sumbmit").disabled = true;
			var espacios_invalidos= /\s+/g;
			var latitud = document.getElementById("latitud").value; 
			latitud = latitud.replace(espacios_invalidos, '');
			if(latitud == ""){
				document.getElementById("latitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				return false;
			}
			var longitud = document.getElementById("longitud").value; 
			longitud = longitud.replace(espacios_invalidos, '');
			if(longitud == ""){
				document.getElementById("longitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				return false;
			}
			location['lat'] = latitud;
			location['lng'] = longitud;
			zoom=18;
			myMap(location,zoom);
		}
		function generar_mapa() {
			var id_pais = document.getElementById("id_pais").value;
			if(id_pais == ""){
				document.getElementById("id_pais").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_pais").style.border= "";
			}
			var id_estado = document.getElementById("id_estado").value;
			if(id_estado == ""){
				document.getElementById("id_estado").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_estado").style.border= "";
			}
			var id_municipio = document.getElementById("id_municipio").value;
			if(id_municipio == ""){
				document.getElementById("id_municipio").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_municipio").style.border= "";
			}
			var id_localidad = document.getElementById("id_localidad").value;
			if(id_localidad == ""){
				document.getElementById("id_localidad").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("id_localidad").style.border= "";
			}
			var calle = document.getElementById("calle").value;
			var calle = calle.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("calle").value=calle;
			if(calle == ""){
				document.getElementById("calle").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("calle").style.border= "";
			}
			var num_ext = document.getElementById("num_ext").value;
			var num_int = document.getElementById("num_int").value;

			var colonia = document.getElementById("colonia").value;
			var colonia = colonia.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("colonia").value=colonia;
			if(colonia == ""){
				document.getElementById("colonia").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("colonia").style.border= "";
			}

			var codigo_postal = document.getElementById("codigo_postal").value;
			var codigo_postal = codigo_postal.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("codigo_postal").value=codigo_postal;
			if(codigo_postal == ""){
				document.getElementById("codigo_postal").style.border= "1px solid red";
				return false;
			}else{
				document.getElementById("codigo_postal").style.border= "";
			}

			var direccion_completa = []; 
			var data = {
					'id_pais' : id_pais,
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'calle' : calle,
					'num_int' : num_int,
					'num_ext' : num_ext,
					'colonia' : colonia, 
					'codigo_postal' : codigo_postal,
					'tipo' : 'datos_formulario',
			}
			direccion_completa.push(data);

			$.ajax({
				type: "POST",
				dataType: "json",
				url: "mapas/rapidapi_trueway.php",
				data: {direccion_completa: direccion_completa},
				success: function(data) { 
					if(data.mensaje =='OK' && data.api_mensaje==null){
						if(data.location==null){
							alert("Error al Generar El mapa, Contacte con el área de soporte.");
						}else{
							/*console.log(data.location);*/
							zoom=18;
							myMap(data.location,zoom);
						}
					}else{
						alert("Error al Generar El mapa, Contacte con el área de soporte."+data.api_mensaje);
					}

				}
			});
		}
	</script>
	<script type="text/javascript">
		function locationEstado(){
			var id_estado = document.getElementById("id_estado").value;
			if(id_estado == ""){
				document.getElementById("id_estado").style.border= "1px solid red";
				document.getElementById("id_municipio").style.border= "";
				document.getElementById("id_municipio").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "municipios/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_municipio").html(data);
					}
				});
			}else{
				document.getElementById("id_estado").style.border= "";
				document.getElementById("id_municipio").style.border= "";
				var dataString = 'id_estado='+id_estado;
				$.ajax({
					type: "POST",
					url: "municipios/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_municipio").html(data);
					}
				});
				var dataString = 'id_estado='+id_estado+'&tipo=coordenadas';
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "mapas/ajax.php",
					data: dataString,
					success: function(data) { 
						zoom=8;
						myMap(data,zoom);
					}
				});

			}
		}
		function locationMunicipio() {
			var id_estado = document.getElementById("id_estado").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_municipio = id_municipio.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("id_municipio").value=id_municipio;
			if(id_municipio == ""){
				document.getElementById("id_municipio").style.border= "1px solid red";
				document.getElementById("id_localidad").style.border= "";
				document.getElementById("id_localidad").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
			}else{
				document.getElementById("id_municipio").style.border= "";
				document.getElementById("id_localidad").style.border= "";
				var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio;
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
				var dataString = 'id_municipio='+id_municipio+'&tipo=coordenadas';
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "mapas/ajax.php",
					data: dataString,
					success: function(data) { 
						zoom=14;
						myMap(data,zoom);
					}
				});
			}
		}
		function locationLocalidad() {
			var id_localidad = document.getElementById("id_localidad").value;
			var dataString = 'id_localidad='+id_localidad+'&tipo=coordenadas';
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "mapas/ajax.php",
				data: dataString,
				success: function(data) { 
					zoom=14;
					myMap(data,zoom);
				}
			});
		}
	</script>

	<script type="text/javascript">
		$( function() {
				$( "#fecha_emision" ).datepicker({ 
					changeMonth: true,
					changeYear: true,
					showButtonPanel: true, 
					dateFormat: 'yy-mm-dd', 
					onSelect: function (date) { 
						document.getElementById("fecha_emision").style.border= "";
					}
				});
				$('#hora_emision').timepicker({ 
					timeFormat: 'H:i:s',
					showDuration: true,
					interval: 15,
					scrollDefault: "now",
					onSelect: function (date) { 
						document.getElementById("hora_emision").style.border= "";
					}
				}); 
			});
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Representante</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input maxlength="350" max="350" class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $zona_importanteDatos['nombre'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="tipo" >
				<?php
				$select[$zona_importanteDatos['tipo']] = "selected";
				?>
				<option value="">Seleccione</option>
				<option <?= $select[0] ?> value="0">Amigo</option>
				<option <?= $select[1] ?> value="1">Hostil</option>
				<option <?= $select[2] ?> value="2">Neutro</option>
				<option <?= $select[3] ?> value="3">Interés</option>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $zona_importanteDatos['observaciones'] ?></textarea> <br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Dirección</label><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Sección<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_seccion_ine" >
				<?php
				echo secciones_ine($zona_importanteDatos['id_seccion_ine']);
				?>
			</select><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
			<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >
				<?php
				echo paises($zona_importanteDatos['id_pais']);
				?>
			</select>
		</div>
		
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
			<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);" disabled="disabled" >  
				<?php
				echo estados($zona_importanteDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
			<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
			<select   name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">  
				<?php
				echo municipios($zona_importanteDatos['id_municipio'],$zona_importanteDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
			<select   name="id_localidad" id="id_localidad" class='myselect' onchange="locationLocalidad(this)">  
				<?php
				echo localidades($zona_importanteDatos['id_localidad'],$zona_importanteDatos['id_municipio'],$zona_importanteDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm">
			<div id="localidades_asignadas_seccion"></div>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
			<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="calle" autocomplete="off" id="calle" value="<?= $zona_importanteDatos['calle'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Ext.</label><br>
			<input class="inputlogin" type="text" name="num_ext" autocomplete="off"  id="num_ext" value="<?= $zona_importanteDatos['num_ext'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Int.</label><br>
			<input class="inputlogin" type="text" name="num_int" autocomplete="off"  id="num_int" value="<?= $zona_importanteDatos['num_int'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="colonia" autocomplete="off"  id="colonia" value="<?= $zona_importanteDatos['colonia'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div> 


		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="codigo_postal" autocomplete="off"  id="codigo_postal" value="<?= $zona_importanteDatos['codigo_postal'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname"><br></label><br>
			<input type="button" value="Generar Mapa Dirección" onclick="generar_mapa()">
		</div>


		<div class="sucForm" style="width: 100%">

			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="latitud" autocomplete="off"  id="latitud" value="<?= $zona_importanteDatos['latitud'] ?>" placeholder="" maxlength="120" onkeypress="" /><br>
			</div>

			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="longitud" autocomplete="off"  id="longitud" value="<?= $zona_importanteDatos['longitud'] ?>" placeholder="" maxlength="120" onkeypress=" " /><br>
			</div>

			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname"><br></label><br>
				<input type="button" value="Generar Mapa Coordenadas" onclick="generar_mapa_coordenadas()">
			</div>

			<input type="hidden" name="latitud_r" id="latitud_r" value="" placeholder="latitud">
			<input type="hidden" name="longitud_r" id="longitud_r" value="" placeholder="longitud">
			
			<br><br>
		</div>

		<div id="mapa">
			<div id="googleMap" style="width:100%;height:400px;"></div>
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
		</div>


		<div class="sucForm" style="width: 100%" >
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
		<?php
			if ($id==""){
				?>
				localize();
				<?php
			}
		?>
		function error(errorCode){
			if(errorCode.code == 1){
				//alert("Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else if (errorCode.code==2){
				//alert("Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else{
				//alert("Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
		}
		function localize(){
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(myMap,error);
			}
		}
	</script>