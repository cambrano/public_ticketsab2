<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','distritos_federales',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			$("#homebody").load('home.php');
		</script>
		<?php
		die;
	}
	$api_maps="AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI";
	//$api_maps="AIzaSyD_TgaVmoOnFwxJ8hhPOlE_pJehZiuin4Y";
	
	$zoom="8";
	if($distrito_federalDatos['longitud']=="" || $distrito_federalDatos['latitud']=="" ){
	}else{
		$longitud=$distrito_federalDatos['longitud'];
		$latitud=$distrito_federalDatos['latitud'];
	}
?>
	<script type="text/javascript">
		function myMapFunctions(){
			myMap();
			myMapLimites();
		}
		function myMap(coordenadas=null,zoomCoordenada=null) {
			tipo_update="<?= $id ?>";
			if(coordenadas==null && zoomCoordenada==null){
				latitud=19.4978;
				longitud= -99.1269;
				zoom=8;
			}
			if(coordenadas==null && tipo_update != null){
				latitud=<?= $latitud ?>;
				longitud=<?= $longitud ?>;
				zoom=<?= $zoom ?>;
			}
			if(coordenadas != null ){
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
			var myLatlng = new google.maps.LatLng( latitud,longitud); 
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
			var map = new google.maps.Map(document.getElementById("googleMap"), myOptions); 
			marker = new google.maps.Marker({ 
				position: myLatlng,
				draggable: true,
				icon: pinImage,
			});
			/*	fijo	*/
			/*
			var myLatlng = new google.maps.LatLng(19.4978,-99.1269); 
			var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/blue-dot.png');
			marker1 = new google.maps.Marker({ 
				position: myLatlng,
				draggable: false,
				icon: pinImage,
			});
			marker1.setMap(map);
			*/
			/*	fijo	*/
			<?php
			foreach ($distritos_federales_parametrosDatosMapa as $key => $value) {
				echo "distritos_area".$key." = [";
				foreach ($value as $keyT => $valueT) {
					echo "{ lat: ".$valueT['latitud'].", lng: ".$valueT['longitud']." },";
				}
				echo "];";
				?>
				distritos<?= $key ?> = new google.maps.Polygon({
					paths: distritos_area<?= $key ?>,
					strokeColor: "#FF0000",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#b3ecb8",
					fillOpacity: 0.35,
				});
				distritos<?= $key ?>.setMap(map);
				<?php
			}
			?>

			google.maps.event.addListener(marker, "dragend", function() { getCoords(marker); });
			marker.setMap(map); 
			getCoords(marker); 
		}
		function getCoords(marker){ 
			document.getElementById("latitud").value = marker.getPosition().lat(); 
			document.getElementById("longitud").value = marker.getPosition().lng(); 
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Distrito Federal</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $distrito_federalDatos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Número<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="numero" autocomplete="off"  id="numero" value="<?= $distrito_federalDatos['numero'] ?>" placeholder="" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Marcador</label><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
			<input type="text" name="latitud" id="latitud" value="<?= $distrito_federalDatos['latitud'] ?>" placeholder="latitud" onkeypress="return CheckNumeric()" ><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
			<input type="text" name="longitud" id="longitud" value="<?= $distrito_federalDatos['longitud'] ?>" placeholder="longitud" onkeypress="return CheckNumeric()" >
		</div>
		<div id="mapa">
			<div id="googleMap" style="width:100%;height:400px;"></div>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Limites</label>
		</div>


		<script type="text/javascript">
			function generar_limite(){
				var latitud_limite = document.getElementById("latitud_limite").value; 
				if(latitud_limite == ""){
					document.getElementById("latitud_limite").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje_limit").html("Latitud Limite requerido");
					document.getElementById("mensaje_limit").classList.add("mensajeError");
					return false;
				}

				var longitud_limite = document.getElementById("longitud_limite").value; 
				if(longitud_limite == ""){
					document.getElementById("longitud_limite").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje_limit").html("Longitud Limite requerido");
					document.getElementById("mensaje_limit").classList.add("mensajeError");
					return false;
				}

				var orden_limite = document.getElementById("orden_limite").value; 
				if(orden_limite == ""){
					document.getElementById("orden_limite").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje_limit").html("Orden Limite requerido");
					document.getElementById("mensaje_limit").classList.add("mensajeError");
					return false;
				}
				orden_siguiente = parseInt(orden_limite) + 1;

				var numero_limite = document.getElementById("numero_limite").value; 
				var id_limite = document.getElementById("id_limite").value; 

				var seccion_limite = []; 
				var data = {    
						'longitud' : longitud_limite,
						'latitud' : latitud_limite,
						'orden' : orden_limite,
						'numero' : numero_limite,
						'id' : id_limite,
					}
				seccion_limite.push(data);
				$.ajax({
					type: "POST",
					url: "distritosFederales/form_limite.php",
					data: {seccion_limite: seccion_limite},
					success: function(data) {
						$("#limites").html("");
						$("#limites").html(data);
						document.getElementById("numero_limite").value = ""; 
						document.getElementById("id_limite").value = "";
						document.getElementById("orden_limite").value = orden_siguiente; 
					}
				});

			}

			function editarLimite(value){
				var num = value;
				var dataString = 'num='+num;  
				var ruta = "distritosFederales/form_ajax.php";
				$.ajax({
					url: ruta,
					type: "POST",
					data: dataString, 
					success: function(data){ 
						$("#form_limite").html(data);
						//$("#logo").html("");
					}
				});
			}

			function eliminarLimite(value){
				var numero = value;
				var dataString = 'numero='+numero+'&delete=1';  
				var ruta = "distritosFederales/form_limite.php";
				$.ajax({
					url: ruta,
					type: "POST",
					data: dataString, 
					success: function(data){ 
						$("#limites").html(data);
						//$("#logo").html("");
					}
				});
			}
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#mensaje_limit").click(function(event) { 
					document.getElementById("mensaje_limit").classList.remove("mensajeSucces");
					document.getElementById("mensaje_limit").classList.remove("mensajeError");
					$("#mensaje_limit").html("&nbsp");
				});
			});
		</script>

		<div id="form_limite">
			<div id="mensaje_limit"></div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Orden<font color="#FF0004">*</font></label><br>
				<input type="hidden" name="numero" id="numero_limite" value="" placeholder="numero" autocomplete="off">
				<input type="hidden" name="id" id="id_limite" value="" placeholder="id" autocomplete="off">
				<input type="text" name="latitud" id="orden_limite" value="" placeholder="orden" autocomplete="off" onkeypress="return CheckNumeric()" ><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
				<input type="text" name="latitud" id="latitud_limite" value="<?= $distrito_federalDatos['latitud'] ?>" placeholder="latitud" autocomplete="off" onkeypress="return CheckNumeric()" ><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
				<input type="text" name="longitud" id="longitud_limite" value="<?= $distrito_federalDatos['longitud'] ?>" placeholder="longitud" autocomplete="off" onkeypress="return CheckNumeric()" >
			</div>
			<div class="sucForm" style="width:100%">
				<input type="button" value="Generar Limite" onclick="generar_limite()">
			</div>
		</div>
		<div id="limites" class="sucForm" style="width:100%;">
			<?php
				include "form_limite.php";
			?>
		</div>


		<div class="sucForm" style="width: 100%" >
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<?php
			}
			?>
			<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuDtGMwgHfy9Nb07ARmHlsT-Zen228uK4&callback=myMapFunctions"></script>