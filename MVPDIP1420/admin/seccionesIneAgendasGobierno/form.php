<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_agendas_gobierno',$_COOKIE["id_usuario"]);
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
	$zoom = 11;
	if($seccion_ine_agenda_gobiernoDatos['longitud']=="" || $seccion_ine_agenda_gobiernoDatos['latitud']=="" ){
		$zoom = 15;
	}else{
		$longitud=$seccion_ine_agenda_gobiernoDatos['longitud'];
		$latitud=$seccion_ine_agenda_gobiernoDatos['latitud'];
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
			document.getElementById("sumbmit").disabled = false;
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
						//actualizarNumerosMarcadores();
					}
				});
			}
		}
		function locationLocalidad() {
			return false;
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
					//actualizarNumerosMarcadores();
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
			<label class="labelForm" id="labeltemaname">Datos Generales</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave" autocomplete="off" <?= $claveF['input'] ?> id="clave" value="<?= $seccion_ine_agenda_gobiernoDatos['clave'] ?>" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="folio" autocomplete="off"  id="folio" value="<?= $seccion_ine_agenda_gobiernoDatos['folio'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Agenda Gobierno</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo de Agenda Gobierno<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_tipo_gira" >
				<?php
				echo tipos_giras($seccion_ine_agenda_gobiernoDatos['id_tipo_gira']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style=" ">
			<label class="labelForm" id="labeltemaname">Eje de Gobierno<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_eje_gobierno" >
				<?php
				echo ejes_gobierno($seccion_ine_agenda_gobiernoDatos['id_eje_gobierno']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input maxlength="350" max="350" class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $seccion_ine_agenda_gobiernoDatos['nombre'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Organismo Externo<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="organo_externo" autocomplete="off"  id="organo_externo" value="<?= $seccion_ine_agenda_gobiernoDatos['organo_externo'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>
		
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Dependencia Coordinadora<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_dependencia">
				<?php
				echo dependencias($seccion_ine_agenda_gobiernoDatos['id_dependencia']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%;">
			<label class="labelForm" id="labeltemaname">Dependencia(s) colaborativa(s)</label><br>
		</div>
		<?php
		$ids_dependencias = explode(',', $seccion_ine_agenda_gobiernoDatos['ids_dependencias']);
		foreach ($dependenciasDatos as $key => $value) {
			if (in_array($value['id'], $ids_dependencias)) {
				$checked = 'checked="checked"';
			} else {
				$checked = '';
			}
			?>
			<div class="sucForm" style="padding:5px 20px 5px 20px;width:48%;float:left;">
			<table>
				<tr>
					<td style="padding:5px">
						<input <?= $checked ?> class="inputlogin" type="checkbox" name="chk_dp<?= $value['id'] ?>" autocomplete="off" id="chk_dp<?= $value['id'] ?>" value=""/>
					</td>
					<td>
						<label class="labelForm" for="chk_dp<?= $value['id'] ?>" style="letter-spacing:2px;text-transform:none;font-size:9px">
							<?= $value['nombre'] ?>
						</label>
					</td>
				</tr>
			</table>

				
				
			</div>
			<?php
		}
		?>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Logistica</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Num Asistentes<font color="#FF0004">*</font></label><br>
			<input maxlength="350" max="350" class="inputlogin" type="text" name="num_asistentes" autocomplete="off"  id="num_asistentes" value="<?= $seccion_ine_agenda_gobiernoDatos['num_asistentes'] ?>" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Num Beneficiarios<font color="#FF0004">*</font></label><br>
			<input maxlength="350" max="350" class="inputlogin" type="text" name="num_beneficiarios" autocomplete="off"  id="num_beneficiarios" value="<?= $seccion_ine_agenda_gobiernoDatos['num_beneficiarios'] ?>" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
		</div>
		

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Agenda</label>
		</div>
		

		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones<font color="#FF0004">*</font></label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $seccion_ine_agenda_gobiernoDatos['observaciones'] ?></textarea> <br>
		</div>
		
		<div id="mapa">
			<?php include "form_mapa.php"; ?>
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