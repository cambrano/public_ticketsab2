<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2021',$_COOKIE["id_usuario"]);
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
	//$id_seccion_ine = $casilla_voto_2021Datos['id_seccion_ine'];
	//$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	//$api_maps="AIzaSyD_TgaVmoOnFwxJ8hhPOlE_pJehZiuin4Y";
	//$longitud=$casilla_voto_2021Datos['longitud'];
	//$latitud=$casilla_voto_2021Datos['latitud'];

	//$longitud=$seccion_ineDatos['longitud'];
	//$latitud=$seccion_ineDatos['latitud'];
	$zoom="18";
	if($casilla_voto_2021Datos['longitud']=="" || $casilla_voto_2021Datos['latitud']=="" ){
		$zoom="5";
	}
	$partido_2021PrincipalDatos = partido_2021PrincipalDatos();
	$secciones_ineDatosMapa = secciones_ineDatosMapa();
	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();


?>
	<script type="text/javascript">
		function myMap(coordenadas=null,zoomCoordenada=null) {
			tipo_update="<?= $id ?>";
			if(coordenadas==null && zoomCoordenada==null){
				latitud=19.4978;
				longitud= -99.1269;
				zoom=15;
			}
			if(tipo_update != null){
				latitud=<?= $latitud ?>;
				longitud=<?= $longitud ?>;
				zoom=<?= $zoom ?>;
			}
			if(coordenadas != null && zoomCoordenada ==null){
				var latitud = coordenadas.coords.latitude;
				var longitud = coordenadas.coords.longitude;
				zoom=15;
			}

			if(coordenadas != null && zoomCoordenada != null){
				latitud=coordenadas.lat;
				longitud=coordenadas.lng;
				zoom=zoomCoordenada;
			}

			var myLatlng = new google.maps.LatLng( latitud,longitud); 
			var myOptions = {
				zoom: zoom,
				center: myLatlng,
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

			<?php
			foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
				$secciones_ineDatosMapa[$key]['numero'];
				$secciones_ineDatosMapa[$key]['latitud'];
				$secciones_ineDatosMapa[$key]['longitud'];
				$div = '<div class="divMapaSeccion">
							<h4>Sección: '.$secciones_ineDatosMapa[$key]['numero'].'</h4>
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
				secciones_area<?= $key ?>.setMap(map);
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
		}
		function getCoords(marker){ 
			document.getElementById("latitud").value=marker.getPosition().lat(); 
			document.getElementById("longitud").value=marker.getPosition().lng(); 
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


			var dataString = 'id_pais='+id_pais+'&id_estado='+id_estado+'&id_municipio='+id_municipio+'&id_localidad='+id_localidad+'&tipo=datos_formulario';
			$.ajax({
				type: "POST",
				url: "mapas/ajax.php",
				data: dataString,
				success: function(data) { 
					dataString="?address="+codigo_postal+"+"+data+"+"+calle+"+"+num_ext+"+"+num_int+"+"+colonia+"&key=<?=$api_maps?>"; 
					$.ajax({
						type: "GET", 
						url: "https://maps.googleapis.com/maps/api/geocode/json"+dataString,
						success: function(response){
							//console.log(response);
							//console.log(response.results);
							if(response.results[0]==null){
								alert('Error, favor de contactar a soporte, posibles errores no ponga la palabra calle no ponga el signo # gracias.');
							}else{
								//console.log(response.status);
								var location=response.results[0].geometry.location;
								zoom=18;
								myMap(location,zoom);
							}
						},
						error: function(response){
							//console.log(response);
							$("#mensaje").html("Error al Generar El mapa");
						}
					});
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
	<script type="text/javascript">
		function votos_validos(){
			var votos = 0;
			<?php
			foreach ($partidos_2021Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				if(votos_partido_<?= $value['id'] ?>!=""){
					votos = parseInt(votos_partido_<?= $value['id'] ?>) + votos;
				}else{
					document.getElementById("votos_partido_<?= $value['id'] ?>").value = 0;
				}
				<?php
			}
			?>
			var votos_validos = document.getElementById("votos_validos").value=votos.toLocaleString("ja-JP"); 
			votos_totales();
		}

		function votos_totales(){
			var votos = 0;
			var votos_nulos = document.getElementById("votos_nulos").value;
			if(votos_nulos==""){
				votos_nulos = 0;
				document.getElementById("votos_nulos").value =0;
			}
			votos = parseInt(votos_nulos) + votos;
			var votos_can_nreg = document.getElementById("votos_can_nreg").value;
			if(votos_can_nreg==""){
				votos_can_nreg = 0;
				document.getElementById("votos_can_nreg").value =0;
			}
			votos = parseInt(votos_can_nreg) + votos;
			<?php
			foreach ($partidos_2021Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				if(votos_partido_<?= $value['id'] ?>!=""){
					votos = parseInt(votos_partido_<?= $value['id'] ?>) + votos;
				}else{
					document.getElementById("votos_partido_<?= $value['id'] ?>").value = 0;
				}
				<?php
			}
			?>
			var votos_totales = document.getElementById("votos_totales").value=votos.toLocaleString("ja-JP"); 
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Casilla Voto 2021</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $casilla_voto_2021Datos['clave'] ?>" placeholder="" maxlength="120" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<?php
			$selecTipoSeccion[$casilla_voto_2021Datos['tipo_seccion']] = 'selected="selected"';
			?>
			<label class="labelForm" id="labeltemaname">Tipo Sección<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="tipo_seccion" >
				<option <?= $selecTipoSeccion ?> value="">Seleccione</option>
				<option <?= $selecTipoSeccion['Urbana'] ?> value="Urbana">Urbana</option>
				<option <?= $selecTipoSeccion['Rural'] ?> value="Rural">Rural</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Secciones<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_seccion_ine" >
				<?php
				echo secciones_ine($casilla_voto_2021Datos['id_seccion_ine']);
				?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Casilla<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_tipo_casilla">
				<?php
				echo tipos_casillas($casilla_voto_2021Datos['id_tipo_casilla']);
				?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Código<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="codigo" autocomplete="off"  id="codigo" value="<?= $casilla_voto_2021Datos['codigo'] ?>" placeholder="Código" onblur="aMays(event, this)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Lista Nominal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="lista_nominal" autocomplete="off"  id="lista_nominal" value="<?= $casilla_voto_2021Datos['lista_nominal'] ?>" placeholder="Lista Nominal" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="status" autocomplete="off"  id="status" value="<?= $casilla_voto_2021Datos['status'] ?>" placeholder="estatus" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Votos</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Totales<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" style="width: 100%" name="votos_totales" autocomplete="off"  id="votos_totales" value="<?= $votos_totales ?>" placeholder="Votos Totales" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Válidos<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" disabled="disabled" type="text" style="width: 100%" name="votos_validos" autocomplete="off"  id="votos_validos" value="<?= $votos_validos ?>" placeholder="Votos Válidos" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Nulos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="votos_nulos" autocomplete="off"  id="votos_nulos" value="<?= $casilla_voto_2021Datos['votos_nulos'] ?>" placeholder="Votos Nulos" onkeypress="return CheckNumeric()" onchange="votos_totales()"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos CAN NREG<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="votos_can_nreg" autocomplete="off"  id="votos_can_nreg" value="<?= $casilla_voto_2021Datos['votos_can_nreg'] ?>" placeholder="Votos CAN NREG" onkeypress="return CheckNumeric()" onchange="votos_totales()"/><br>
		</div>

		<style type="text/css">
			.mobile_mode{
				width: 30%;
				background-color: #f4f4f2
			}
			@media screen and (max-width: 930px) {
				.mobile_mode{
					width: 49%;
					background-color: #f4f4f2
				}
			}
			@media screen and (max-width: 820px) {
				.mobile_mode{
					width: 100%;
					background-color: #f4f4f2
				}
			}
		</style>

		<div class="sucForm" style="width: 100%;">
			<?php
			foreach ($partidos_2021Datos as $key => $value) {
				$nombre_corto = strtr($value['nombre_corto'], "_", " ");

				?>
				<div class="sucForm mobile_mode">
					<div class="sucForm" style="width:25%">
						
						<img style="width:80% " src="images/logos_partidos/<?= $value['logo'] ?>">
					</div>

					<div class="sucForm" style="width: 60%">
						<label class="labelForm" id="labeltemaname"><?= $nombre_corto ?><font color="#FF0004">*</font></label><br>
						<input class="inputlogin" type="text" style="width: 100%" name="votos_partido_<?= $value['id'] ?>" autocomplete="off"  id="votos_partido_<?= $value['id'] ?>" value="<?= $value['votos'] ?>" placeholder="Número Votos" onkeypress="return CheckNumeric()" onchange="votos_validos()"/><br>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Dirección</label><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
			<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >
				<?php
				echo paises($casilla_voto_2021Datos['id_pais']);
				?>
			</select>
		</div>
		
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
			<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);" disabled="disabled" >  
				<?php
				echo estados($casilla_voto_2021Datos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
			<select   name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">  
				<?php
				echo municipios($casilla_voto_2021Datos['id_municipio'],$casilla_voto_2021Datos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
			<select   name="id_localidad" id="id_localidad" class='myselect'>  
				<?php
				echo localidades($casilla_voto_2021Datos['id_localidad'],$casilla_voto_2021Datos['id_municipio'],$casilla_voto_2021Datos['id_estado']);
				?>
			</select>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
			<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="calle" autocomplete="off" id="calle" value="<?= $casilla_voto_2021Datos['calle'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Ext.</label><br>
			<input class="inputlogin" type="text" name="num_ext" autocomplete="off"  id="num_ext" value="<?= $casilla_voto_2021Datos['num_ext'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Int.</label><br>
			<input class="inputlogin" type="text" name="num_int" autocomplete="off"  id="num_int" value="<?= $casilla_voto_2021Datos['num_int'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="colonia" autocomplete="off"  id="colonia" value="<?= $casilla_voto_2021Datos['colonia'] ?>" placeholder="" maxlength="120" /><br>
		</div> 


		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="codigo_postal" autocomplete="off"  id="codigo_postal" value="<?= $casilla_voto_2021Datos['codigo_postal'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Referencia</label><br>
			<textarea id="referencia" style="width: 99%;height: 150px"><?= $casilla_voto_2021Datos['referencia'] ?></textarea> <br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Latitud</label><br>
			<input type="text" name="latitud" id="latitud_m" value="<?= $casilla_voto_2021Datos['latitud'] ?>" placeholder="latitud">
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Longitud</label><br>
			<input type="text" name="longitud" id="longitud_m" value="<?= $casilla_voto_2021Datos['longitud'] ?>" placeholder="longitud">
		</div>

		<div class="sucForm" style="display: none">
			<label class="labelForm" id="labeltemaname"><br></label><br>
			<input type="button" value="Generar Mapa" onclick="generar_mapa()">
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