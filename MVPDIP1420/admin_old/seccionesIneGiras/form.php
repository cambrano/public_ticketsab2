<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_giras',$_COOKIE["id_usuario"]);
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
	$zoom = 11;
	if($seccion_ine_giraDatos['longitud']=="" || $seccion_ine_giraDatos['latitud']=="" ){
		$zoom = 15;
	}else{
		$longitud=$seccion_ine_giraDatos['longitud'];
		$latitud=$seccion_ine_giraDatos['latitud'];
	}
	
	
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
				$( "#fecha" ).datepicker({ 
					changeMonth: true,
					changeYear: true,
					showButtonPanel: true, 
					dateFormat: 'yy-mm-dd', 
					onSelect: function (date) { 
						document.getElementById("fecha").style.border= "";
					}
				});

				$('#hora').timepicker({ 
					timeFormat: 'H:i:s',
					showDuration: true,
					interval: 15,
					scrollDefault: "now",
					onSelect: function (date) { 
						document.getElementById("hora").style.border= "";
					}
				});

			});
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave" autocomplete="off" <?= $claveF['input'] ?> id="clave" value="<?= $seccion_ine_giraDatos['clave'] ?>" onkeyup="clave(this.value)" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="folio" autocomplete="off"  id="folio" value="<?= $seccion_ine_giraDatos['folio'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Giras</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input maxlength="350" max="350" class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $seccion_ine_giraDatos['nombre'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $seccion_ine_giraDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $seccion_ine_giraDatos['hora'] ?>" placeholder="" /><br>
		</div>
		<?php
			$slctTipo[$seccion_ine_giraDatos['tipo']] = 'selected="selected"';
		?>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="tipo" >
				<option value="">Seleccione</option>
				<!--<option <?= $slctTipo['candidato'] ?> value="candidato">Candidado</option>-->
				<!--<option <?= $slctTipo['visita'] ?> value="visita">Visita</option>-->
				<option <?= $slctTipo['junta'] ?> value="junta">Junta</option>
				<option <?= $slctTipo['visita'] ?> value="visita">Visita</option>
				<option <?= $slctTipo['caminata'] ?> value="caminata">Caminata</option>
			</select><br>
		</div>

		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $seccion_ine_giraDatos['observaciones'] ?></textarea> <br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Dirección</label><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Sección<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_seccion_ine" >
				<?php
				echo secciones_ine($seccion_ine_giraDatos['id_seccion_ine']);
				?>
			</select><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Pais<font color="#FF0004">*</font></label><br>
			<select   name="id_pais" id="id_pais" class='myselect' disabled="disabled" >
				<?php
				echo paises($seccion_ine_giraDatos['id_pais']);
				?>
			</select>
		</div>
		
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display: none;">
			<label class="labelForm" id="labeltemaname">Estado<font color="#FF0004">*</font></label><br>
			<select   name="id_estado" id="id_estado" class='myselect' onchange="locationEstado(this);" disabled="disabled" >  
				<?php
				echo estados($seccion_ine_giraDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
			<label class="labelForm" id="labeltemaname">Municipio<font color="#FF0004">*</font></label><br>
			<select   name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">  
				<?php
				echo municipios($seccion_ine_giraDatos['id_municipio'],$seccion_ine_giraDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Localidad<font color="#FF0004">*</font></label><br>
			<select   name="id_localidad" id="id_localidad" class='myselect' onchange="locationLocalidad(this)">  
				<?php
				echo localidades($seccion_ine_giraDatos['id_localidad'],$seccion_ine_giraDatos['id_municipio'],$seccion_ine_giraDatos['id_estado']);
				?>
			</select>
		</div>
		<div class="sucForm">
			<div id="localidades_asignadas_seccion"></div>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
			<label class="labelForm" id="labeltemaname">Calle<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="calle" autocomplete="off" id="calle" value="<?= $seccion_ine_giraDatos['calle'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Ext.</label><br>
			<input class="inputlogin" type="text" name="num_ext" autocomplete="off"  id="num_ext" value="<?= $seccion_ine_giraDatos['num_ext'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Num Int.</label><br>
			<input class="inputlogin" type="text" name="num_int" autocomplete="off"  id="num_int" value="<?= $seccion_ine_giraDatos['num_int'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Colonia<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="colonia" autocomplete="off"  id="colonia" value="<?= $seccion_ine_giraDatos['colonia'] ?>" placeholder="" maxlength="120" onkeyup="aMays(event, this)" /><br>
		</div> 


		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname">Código Postal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="codigo_postal" autocomplete="off"  id="codigo_postal" value="<?= $seccion_ine_giraDatos['codigo_postal'] ?>" placeholder="" maxlength="120" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
			<label class="labelForm" id="labeltemaname"><br></label><br>
			<input type="button" value="Generar Mapa Dirección" onclick="generar_mapa()">
		</div>


		<div class="sucForm" style="width: 100%">

			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="latitud" autocomplete="off"  id="latitud" value="<?= $seccion_ine_giraDatos['latitud'] ?>" placeholder="" maxlength="120" onkeypress="" /><br>
			</div>

			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="longitud" autocomplete="off"  id="longitud" value="<?= $seccion_ine_giraDatos['longitud'] ?>" placeholder="" maxlength="120" onkeypress=" " /><br>
			</div>

			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname"><br></label><br>
				<input type="button" value="Generar Mapa Coordenadas" onclick="generar_mapa_coordenadas()">
			</div>

			<input type="hidden" name="latitud_r" id="latitud_r" value="<?= $seccion_ine_giraDatos['latitud_r'] ?>" placeholder="latitud">
			<input type="hidden" name="longitud_r" id="longitud_r" value="<?= $seccion_ine_giraDatos['longitud_r'] ?>" placeholder="longitud">
			
			<br><br>
		</div>

		<div id="mapa">
			<?php include "punto_ajax.php"; ?>
		</div>
		<div class="sucForm" style="width: 100%">
			<input type="hidden" value="" id="key_punter" >
			<div class="sucForm" id="divAdd" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px">
				<label class="labelForm" id="labeltemaname"><br></label><br>
				<input type="button" value="Agregar Punto" id="puntero_opcion_add" onclick="agregar_punto()">
			</div>
			<div class="sucForm" id="divEdit" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;display:none">
				<label class="labelForm" id="labeltemaname"><br></label><br>
				<input type="button" value="Editar Punto" id="puntero_opcion_edit" onclick="editar_punto()">
			</div>
			<br><br>
		</div>
		<script>
			function deletePuntero(value){
				var espacios_invalidos= /\s+/g;
				var latitud = document.getElementById("latitud").value; 
				latitud = latitud.replace(espacios_invalidos, '');
				if(latitud == ""){
					document.getElementById("latitud").focus(); 
					document.getElementById("puntero_opcion_add").disabled = false;
					return false;
				}
				var longitud = document.getElementById("longitud").value; 
				longitud = longitud.replace(espacios_invalidos, '');
				if(longitud == ""){
					document.getElementById("longitud").focus(); 
					document.getElementById("puntero_opcion_add").disabled = false;
					return false;
				}
				var tipo =  'delete';
				var puntero = []; 
				var data = {  
						'key' : value,
						'tipo' : tipo,
						'latitud' : latitud,
						'longitud' : longitud,
					}
				puntero.push(data);
				console.log(puntero);
				$.ajax({
					type: "POST",
					url: "seccionesIneGiras/punto_ajax.php",
					data: {puntero: puntero},
					success: function(data) {
						document.getElementById("puntero_opcion_add").disabled = false;
						$("#mapa").html(data);
					}
				});
			}
			function editPuntero(value){
				document.getElementById("key_punter").value = value;
				var tipo =  'editPunto';
				var puntero = []; 
				var data = {  
						'key' : value,
						'tipo' : tipo,
					}
				puntero.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneGiras/punto_ajax.php",
					data: {puntero: puntero},
					success: function(data) {
						document.getElementById("divAdd").style.display = "none";
						document.getElementById("divEdit").style.display = "block";
						$("#mapa").html(data);
					}
				});

			}
			function editar_punto(){
				document.getElementById("puntero_opcion_edit").disabled = true;
				var espacios_invalidos= /\s+/g;
				
				var key_punter = document.getElementById("key_punter").value; 
				key_punter = key_punter.replace(espacios_invalidos, '');
				if(key_punter == ""){
					document.getElementById("key_punter").focus(); 
					document.getElementById("puntero_opcion_edit").disabled = false;
					return false;
				}
				var latitud = document.getElementById("latitud").value; 
				latitud = latitud.replace(espacios_invalidos, '');
				if(latitud == ""){
					document.getElementById("latitud").focus(); 
					document.getElementById("puntero_opcion_edit").disabled = false;
					return false;
				}
				var latitud = document.getElementById("latitud").value; 
				latitud = latitud.replace(espacios_invalidos, '');
				if(latitud == ""){
					document.getElementById("latitud").focus(); 
					document.getElementById("puntero_opcion_edit").disabled = false;
					return false;
				}
				var longitud = document.getElementById("longitud").value; 
				longitud = longitud.replace(espacios_invalidos, '');
				if(longitud == ""){
					document.getElementById("longitud").focus(); 
					document.getElementById("puntero_opcion_edit").disabled = false;
					return false;
				}
				var tipo =  'edit';
				var puntero = []; 
				var data = {  
						'tipo' : tipo,
						'latitud' : latitud,
						'longitud' : longitud,
						'key' : key_punter,
					}
				puntero.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneGiras/punto_ajax.php",
					data: {puntero: puntero},
					success: function(data) {
						document.getElementById("puntero_opcion_edit").disabled = false;
						$("#mapa").html(data);
						document.getElementById("key_punter").value = '';
						document.getElementById("divAdd").style.display = "block";
						document.getElementById("divEdit").style.display = "none";
					}
				});
			}
			function agregar_punto(){
				document.getElementById("puntero_opcion_add").disabled = true;
				var espacios_invalidos= /\s+/g;
				var latitud = document.getElementById("latitud").value; 
				latitud = latitud.replace(espacios_invalidos, '');
				if(latitud == ""){
					document.getElementById("latitud").focus(); 
					document.getElementById("puntero_opcion_add").disabled = false;
					return false;
				}
				var longitud = document.getElementById("longitud").value; 
				longitud = longitud.replace(espacios_invalidos, '');
				if(longitud == ""){
					document.getElementById("longitud").focus(); 
					document.getElementById("puntero_opcion_add").disabled = false;
					return false;
				}
				var tipo =  'add';
				var puntero = []; 
				var data = {  
						'tipo' : tipo,
						'latitud' : latitud,
						'longitud' : longitud,
					}
				puntero.push(data);
				$.ajax({
					type: "POST",
					url: "seccionesIneGiras/punto_ajax.php",
					data: {puntero: puntero},
					success: function(data) {
						document.getElementById("puntero_opcion_add").disabled = false;
						$("#mapa").html(data);
					}
				});
			}
		</script>

		



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