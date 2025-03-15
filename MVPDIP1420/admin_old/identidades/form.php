<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','identidades',$_COOKIE["id_usuario"]);
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
	$longitud=$identidadDatos['longitud'];
	$latitud=$identidadDatos['latitud'];
	$zoom="18";
	if($longitud=="" || $longitud=="" ){
		$latitud="19.4978";
		$longitud="-99.1269";
		$zoom="5";
	}
?>
	<script type="text/javascript">
		function myMap(coordenadas=null,zoomCoordenada=null) {
			tipo_update="<?= $id ?>";
				if(coordenadas==null && zoomCoordenada==null){
					latitud=19.4978;
					longitud= -99.1269;
					zoom=5;
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
					dataString="?address="+codigo_postal+"+"+data+"+"+calle+"+"+colonia+"&key=<?=$api_maps?>"; 
					$.ajax({
						type: "GET", 
						url: "https://maps.googleapis.com/maps/api/geocode/json"+dataString,
						success: function(response){
							//console.log(response);
							//console.log(response.results);
							if(response.results[0]==null){
								alert('Error, favor de contactar a soporte');
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
			});
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Identidad</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave" autocomplete="off" <?= $claveF['input'] ?> id="clave" value="<?= $identidadDatos['clave'] ?>" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
			$select[$identidadDatos['tipo']] = 'selected="selected"';
			?>
			<select name="tipo" id="tipo" class='myselect'>  
				<option value="">Seleccione</option>
				<option <?= $select['falso'] ?> value="falso">Falso</option>
				<option <?= $select['real'] ?> value="real">Real</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Sexo<font color="#FF0004">*</font></label><br>
			<?php
			$select[$identidadDatos['sexo']] = 'selected="selected"';
			?>
			<select name="sexo" id="sexo" class='myselect'>  
				<option value="">Seleccione</option>
				<option <?= $select['Mujer'] ?> value="Mujer">Mujer</option>
				<option <?= $select['Hombre'] ?> value="Hombre">Hombre</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_nacimiento" autocomplete="off"  id="fecha_nacimiento" value="<?= $identidadDatos['fecha_nacimiento'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $identidadDatos['nombre'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_paterno" autocomplete="off"  id="apellido_paterno" value="<?= $identidadDatos['apellido_paterno'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="apellido_materno" autocomplete="off"  id="apellido_materno" value="<?= $identidadDatos['apellido_materno'] ?>" placeholder="" /><br>
		</div>


		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Identificación</label><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave Electoral<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave_elector" autocomplete="off"  id="clave_elector" value="<?= $identidadDatos['clave_elector'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">C.U.R.P<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="curp" autocomplete="off"  id="curp" value="<?= $identidadDatos['curp'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">R.F.C<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="rfc" autocomplete="off"  id="rfc" value="<?= $identidadDatos['rfc'] ?>" placeholder="" onblur="aMays(event, this)"/><br>
		</div> 

		

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Dirección</label><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
			<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >  
				<?php
				echo paises($identidadDatos['id_pais']);
				?>
			</select>
		</div>
		
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
			<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);">  
				<?php
				echo estados($identidadDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
			<select   name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">  
				<?php
				echo municipios($identidadDatos['id_municipio'],$identidadDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
			<select   name="id_localidad" id="id_localidad" class='myselect'>  
				<?php
				echo localidades($identidadDatos['id_localidad'],$identidadDatos['id_municipio'],$identidadDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="calle" autocomplete="off"  id="calle" value="<?= $identidadDatos['calle'] ?>" placeholder="" maxlength="120" /><br>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="colonia" autocomplete="off"  id="colonia" value="<?= $identidadDatos['colonia'] ?>" placeholder="" maxlength="120" /><br>
		</div> 
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="codigo_postal" autocomplete="off"  id="codigo_postal" value="<?= $identidadDatos['codigo_postal'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname"><br></label><br>
			<input type="hidden" name="latitud" id="latitud" value="<?= $identidadDatos['latitud'] ?>" placeholder="latitud">
			<input type="hidden" name="longitud" id="longitud" value="<?= $identidadDatos['longitud'] ?>" placeholder="longitud">
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
	</script>