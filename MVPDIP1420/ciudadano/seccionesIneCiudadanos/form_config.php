<?php
	include '../functions/switch_operaciones.php';
	$api_maps="AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI";
	//$api_maps="AIzaSyD_TgaVmoOnFwxJ8hhPOlE_pJehZiuin4Y";
	$longitud=$seccion_ine_ciudadanoDatos['longitud'];
	$latitud=$seccion_ine_ciudadanoDatos['latitud'];
	$zoom="18";
	$secciones_ineDatosMapa = secciones_ineDatosMapa();
	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa();
?>
	<script type="text/javascript">
		function myMap(coordenadas=null,zoomCoordenada=null) {
			tipo_update="<?= $id ?>";
			if(coordenadas==null && zoomCoordenada==null){
				latitud = "<?= $latitud ?>";
				longitud = "<?= $longitud ?>";
				zoom=15;
			}
			if(tipo_update != ""){
				latitud="<?= $latitud ?>";
				longitud="<?= $longitud ?>";
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
				$("#mensaje").html("Localidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
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
			$( "#fecha_nacimiento" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_nacimiento").style.border= "";
				}
			});
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
			$( '#monstar_contraseña' ).on( 'click', function() {
				if( $(this).is(':checked') ){
					// Hacer algo si el checkbox ha sido seleccionado
					$('#labelMostrar').html("Ocultar");
					document.getElementById("password").type = "text";
					document.getElementById("password1").type = "text";
				} else {
					// Hacer algo si el checkbox ha sido deseleccionado
					$('#labelMostrar').html("Mostrar");
					document.getElementById("password").type = "password";
					document.getElementById("password1").type = "password";
				}
			});
			$("#usuario").keyup(function(){              
			        var ta      =   $("#usuario");
			        letras      =   ta.val().replace(/ /g, "");
			        ta.val(letras)
			}); 
			$("#password").keyup(function(){              
			        var ta      =   $("#password");
			        letras      =   ta.val().replace(/ /g, "");
			        ta.val(letras)
			}); 
			$("#password1").keyup(function(){              
			        var ta      =   $("#password1");
			        letras      =   ta.val().replace(/ /g, "");
			        ta.val(letras)
			}); 
		});
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Mis datos</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave_elector" autocomplete="off"  id="clave_elector" value="<?= $seccion_ine_ciudadanoDatos['clave_elector'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Secciones<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_seccion_ine" >
				<?php
				echo secciones_ine($seccion_ine_ciudadanoDatos['id_seccion_ine']);
				?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select name="id_tipo_ciudadano" id="id_tipo_ciudadano" class='myselect'>  
				<?php echo tipos_ciudadanos($seccion_ine_ciudadanoDatos['id_tipo_ciudadano']) ?>
			</select>
		</div>

		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $seccion_ine_ciudadanoDatos['nombre'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_paterno" autocomplete="off"  id="apellido_paterno" value="<?= $seccion_ine_ciudadanoDatos['apellido_paterno'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_materno" autocomplete="off"  id="apellido_materno" value="<?= $seccion_ine_ciudadanoDatos['apellido_materno'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Sexo<font color="#FF0004">*</font></label><br>
			<?php
			$select[$seccion_ine_ciudadanoDatos['sexo']] = 'selected="selected"';
			?>
			<select name="sexo" id="sexo" class='myselect'>  
				<option value="">Seleccione</option>
				<option <?= $select['Mujer'] ?> value="Mujer">Mujer</option>
				<option <?= $select['Hombre'] ?> value="Hombre">Hombre</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_nacimiento" autocomplete="off"  id="fecha_nacimiento" value="<?= $seccion_ine_ciudadanoDatos['fecha_nacimiento'] ?>" placeholder="" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Usuario</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Usuario<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="usuario" autocomplete="off"  id="usuario" value="<?= $seccion_ine_ciudadanoDatos['usuario']  ?>" placeholder="" maxlength="45" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Password<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="password" autocomplete="off"  id="password" value="<?= $seccion_ine_ciudadanoDatos['password'] ?>" placeholder="" maxlength="10" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Repetir Password<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="password1" autocomplete="off"  id="password1" value="<?= $seccion_ine_ciudadanoDatos['password'] ?>" placeholder="" maxlength="10" />
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labelMostrar">Mostrar</label><input type="checkbox"  id="monstar_contraseña" value="1"><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<?php
				if(!empty($id)){

					echo '<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>';
					echo '<select id="status" class="myselect" name="status" >';
					echo statusGeneralForm($usuarioDatos['status']);
					echo '</select><br><br>';
				} 
			?> 
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Contacto</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Correo Eletrónico</label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $seccion_ine_ciudadanoDatos['correo_electronico'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Whatsapp<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $seccion_ine_ciudadanoDatos['whatsapp'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono</label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $seccion_ine_ciudadanoDatos['telefono'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Celular</label><br>
			<input class="inputlogin" type="text" name="celular" autocomplete="off"  id="celular" value="<?= $seccion_ine_ciudadanoDatos['celular'] ?>" placeholder="" /><br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Observaciones</label>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $seccion_ine_ciudadanoDatos['observaciones'] ?></textarea> <br>
		</div>
		<div class="sucFormTitulo" style="display: none;">
			<label class="labelForm" id="labeltemaname">Identificación</label><br>
		</div>

		

		<div class="sucForm" style="display: none;">
			<label class="labelForm" id="labeltemaname">C.U.R.P<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="curp" autocomplete="off"  id="curp" value="<?= $seccion_ine_ciudadanoDatos['curp'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
		</div>

		<div class="sucForm" style="display: none;">
			<label class="labelForm" id="labeltemaname">R.F.C<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="rfc" autocomplete="off"  id="rfc" value="<?= $seccion_ine_ciudadanoDatos['rfc'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
		</div> 

		

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Dirección</label><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
			<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >
				<?php
				echo paises($seccion_ine_ciudadanoDatos['id_pais']);
				?>
			</select>
		</div>
		
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
			<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);" disabled="disabled" >  
				<?php
				echo estados($seccion_ine_ciudadanoDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px; display: none;">
			<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
			<select   name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">  
				<?php
				echo municipios($seccion_ine_ciudadanoDatos['id_municipio'],$seccion_ine_ciudadanoDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
			<select   name="id_localidad" id="id_localidad" class='myselect'>  
				<?php
				echo localidades($seccion_ine_ciudadanoDatos['id_localidad'],$seccion_ine_ciudadanoDatos['id_municipio'],$seccion_ine_ciudadanoDatos['id_estado']);
				?>
			</select>
		</div>
		
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
			<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="calle" autocomplete="off" id="calle" value="<?= $seccion_ine_ciudadanoDatos['calle'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Ext.</label><br>
			<input class="inputlogin" type="text" name="num_ext" autocomplete="off"  id="num_ext" value="<?= $seccion_ine_ciudadanoDatos['num_ext'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Int.</label><br>
			<input class="inputlogin" type="text" name="num_int" autocomplete="off"  id="num_int" value="<?= $seccion_ine_ciudadanoDatos['num_int'] ?>" placeholder="" maxlength="120" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="colonia" autocomplete="off"  id="colonia" value="<?= $seccion_ine_ciudadanoDatos['colonia'] ?>" placeholder="" maxlength="120" /><br>
		</div> 


		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="codigo_postal" autocomplete="off"  id="codigo_postal" value="<?= $seccion_ine_ciudadanoDatos['codigo_postal'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname"><br></label><br>
			<input type="hidden" name="latitud" id="latitud" value="<?= $seccion_ine_ciudadanoDatos['latitud'] ?>" placeholder="latitud">
			<input type="hidden" name="longitud" id="longitud" value="<?= $seccion_ine_ciudadanoDatos['longitud'] ?>" placeholder="longitud">
			<input type="hidden" name="latitud_r" id="latitud_r" value="" placeholder="latitud">
			<input type="hidden" name="longitud_r" id="longitud_r" value="" placeholder="longitud">
			<!--<input type="button" value="Generar Mapa" onclick="generar_mapa()">-->
			<br><br>
		</div>
		
		<!--
		<div id="mapa">
			<div id="googleMap" style="width:100%;height:400px;"></div>
			<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>
		</div>-->


		<div class="sucForm" style="width: 100%" >
			<br> 
			<input style="width: 100%;margin-bottom: 10px" type="button" id="sumbmit" onclick="guardar()" value="Guardar">
			<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input style="width: 100%;margin-bottom: 10px" type="button" value="Cancelar" onclick="cerrar()">
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