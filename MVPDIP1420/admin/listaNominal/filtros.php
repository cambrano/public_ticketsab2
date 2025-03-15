<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	
	$searchTable = $_COOKIE["searchTableLN"];
	$searchTable = json_decode($searchTable,true);
	
	$searchOpcionesLN = $_COOKIE["searchOpcionesLN"];
	$searchOpcionesLN = json_decode($searchOpcionesLN,true);
?>
	<script type="text/javascript">
		document.addEventListener("keyup", function(event) {
			if (event.key === 'Enter') {	
				searchTable();
			}
		});
		function borrarCamposLista(){
			document.getElementById("id_seccion_ine").value = '';
			$('#id_seccion_ine').val('');
			$('#id_seccion_ine').select2().trigger('change');
			document.getElementById("clave_elector").value = '';
			document.getElementById("curp").value = '';
			document.getElementById("nombre").value = '';
			document.getElementById("apellido_paterno").value = '';
			document.getElementById("apellido_materno").value = '';
			document.getElementById("militante_partido").value = '';
			document.getElementById("id_localidad").value = '';
			document.getElementById("manzana").value = '';
			$('#id_localidad').val('');
			$('#id_localidad').select2().trigger('change');
			document.getElementById("id_municipio").value = '';
			$('#id_municipio').val('');
			$('#id_municipio').select2().trigger('change');
			$('#id_distrito_local').val('');
			$('#id_distrito_local').select2().trigger('change');
			$('#id_distrito_federal').val('');
			$('#id_distrito_federal').select2().trigger('change');
			$('#tipo_ciudadano').val('');
			$('#tipo_ciudadano').select2().trigger('change');
			
		}
		function searchTable(){
			var check = false;
			var espacios_invalidos= /\s+/g;
			var clave_elector = document.getElementById("clave_elector").value;
			clave_elector = clave_elector.trim();
			clave_electorx = clave_elector.replace(espacios_invalidos, '');
			if(clave_electorx != ""){
				var check = true;
			}
			var curp = document.getElementById("curp").value;
			curp = curp.trim();
			curpx = curp.replace(espacios_invalidos, '');
			if(curpx != ""){
				var check = true;
			}
			var nombre = document.getElementById("nombre").value;
			nombre = nombre.trim();
			nombrex = nombre.replace(espacios_invalidos, '');
			if(nombrex != ""){
				var check = true;
			}
			var apellido_paterno = document.getElementById("apellido_paterno").value;
			apellido_paterno = apellido_paterno.trim();
			apellido_paternox = apellido_paterno.replace(espacios_invalidos, '');
			if(apellido_paternox != ""){
				var check = true;
			}
			var apellido_materno = document.getElementById("apellido_materno").value;
			apellido_materno = apellido_materno.trim();
			apellido_maternox = apellido_materno.replace(espacios_invalidos, '');
			if(apellido_maternox != ""){
				var check = true;
			}
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;
			id_seccion_ine = id_seccion_ine.trim();
			id_seccion_inex = id_seccion_ine.replace(espacios_invalidos, '');
			if(id_seccion_inex != ""){
				var check = true;
			}
			var id_municipio = document.getElementById("id_municipio").value;
			id_municipiox = id_municipio.replace(espacios_invalidos, '');
			if(id_municipiox != ""){
				var check = true;
			}
			var id_localidad = document.getElementById("id_localidad").value;
			id_localidadx = id_localidad.replace(espacios_invalidos, '');
			if(id_localidadx != ""){
				var check = true;
			}
			var militante_partido = document.getElementById("militante_partido").value;
			militante_partido = militante_partido.trim();
			militante_partidox = militante_partido.replace(espacios_invalidos, '');
			if(militante_partidox != ""){
				var check = true;
			}

			var padrones_especificos = document.getElementById("padrones_especificos").value;
			padrones_especificosx = padrones_especificos.replace(espacios_invalidos, '');
			if(padrones_especificosx != ""){
				var check = true;
			}

			var tipo_ciudadano = document.getElementById("tipo_ciudadano").value;
			tipo_ciudadanox = curp.replace(espacios_invalidos, '');
			if(tipo_ciudadanox != ""){
				var check = true;
			}

			var manzana = document.getElementById("manzana").value;
			manzana = manzana.trim();
			manzanax = manzana.replace(espacios_invalidos, '');
			if(manzanax != ""){
				var check = true;
			}


			var id_distrito_local = document.getElementById("id_distrito_local").value;
			id_distrito_localx = id_distrito_local.replace(espacios_invalidos, '');
			if(id_distrito_localx != ""){
				var check = true;
			}

			var id_distrito_federal = document.getElementById("id_distrito_federal").value;
			id_distrito_federalx = id_distrito_federal.replace(espacios_invalidos, '');
			if(id_distrito_federalx != ""){
				var check = true;
			}

			//opciones de busqueda
			var tipo_mapa = document.getElementById("tipo_mapa").value;
			var tipo_limite = document.getElementById("tipo_limite").value;
			var tipo_tabla = document.getElementById("tipo_tabla").checked;
			if(tipo_tabla==true){
				tipo_tabla=1
			}else{
				tipo_tabla=0
			}
			var tipo_tabla_responsive = document.getElementById("tipo_tabla_responsive").checked;
			if(tipo_tabla_responsive==true){
				tipo_tabla_responsive=1
			}else{
				tipo_tabla_responsive=0
			}
			if(tipo_tabla==0 && tipo_mapa=='sin_mapa'){
				$("#dataTable").html('');
				$("#mapaLoad").html('Debe seleccionar algun filtro de mostrar mapa o mostrar tabla.');
				document.cookie = "searchTableLN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
				return false;
			}

			if(tipo_mapa=='sin_mapa'){
				$("#mapaLoad").html('');
			}

			if(tipo_tabla==0){
				$("#dataTable").html('');
			}

			if(check==false){
				$("#dataTable").html('');
				$("#mapaLoad").html('Debe seleccionar algun filtro.');
				document.cookie = "searchTableLN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
				return false;
			}
			solo_mapa = 0;
			solo_table = 0;
			if(tipo_mapa=='sin_mapa'){
				if(tipo_limite=='x'){
					$("#dataTable").html('');
					$("#mapaLoad").html('Debe seleccionar limite maximo es 2000.');
					document.cookie = "searchTableLN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
					return false;
				}
				if(tipo_tabla==0){
					$("#dataTable").html('');
					$("#mapaLoad").html('Debe seleccionar en opción tabla, mostrar.');
					document.cookie = "searchTableLN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
					return false;
				}
				solo_table = 1;
			}
			if(tipo_mapa == 'mapa_coordenadas'){
				if(tipo_tabla==0){
					solo_mapa = 1;
				}else{
					if(tipo_limite=='x'){
						$("#dataTable").html('');
						$("#mapaLoad").html('Debe seleccionar limite maximo es 2000.');
						document.cookie = "searchTableLN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
						return false;
					}
					solo_table = 1;
				}
			}
			if(tipo_mapa == 'mapa_calor'){
				if(tipo_tabla==0){
					solo_mapa = 1;
				}else{
					if(tipo_limite=='x'){
						$("#dataTable").html('');
						$("#mapaLoad").html('Debe seleccionar quitar la opcion de mostrar tabla.');
						document.cookie = "searchTableLN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
						return false;
					}
					solo_table = 1;
				}
			}

			var searchTable = [];
			var data = {
				'clave_elector' : clave_elector,
				'curp' : curp,
				'nombre' : nombre,
				'apellido_paterno' : apellido_paterno,
				'apellido_materno' : apellido_materno,
				'id_seccion_ine' : id_seccion_ine,
				'id_municipio' : id_municipio,
				'id_localidad' : id_localidad,
				'militante_partido' : militante_partido,
				'padrones_especificos' : padrones_especificos,
				'tipo_ciudadano' : tipo_ciudadano,
				'manzana' : manzana,
				'id_distrito_local' : id_distrito_local,
				'id_distrito_federal' : id_distrito_federal,
			}
			searchTable.push(data);

			searchOpciones = [];
			var data = {
				'tipo_tabla_responsive' : tipo_tabla_responsive,
				'tipo_tabla' :tipo_tabla,
				'tipo_limite' : tipo_limite,
				'tipo_mapa' : tipo_mapa,
			}
			searchOpciones.push(data);
			if(solo_table==1){
				$.ajax({
					type: "POST",
					url: "listaNominal/table.php",
					data: {searchTable: searchTable,searchOpciones:searchOpciones},
					async: true,
					success: function(data) {
						$("#dataTable").html(data);
					}
				});
			}
			var mapa = [];
			var data = {   
				'clave_elector' : clave_elector,
				'curp' : curp,
				'nombre' : nombre,
				'apellido_paterno' : apellido_paterno,
				'apellido_materno' : apellido_materno,
				'id_seccion_ine' : id_seccion_ine,
				'id_municipio' : id_municipio,
				'id_localidad' : id_localidad,
				'militante_partido' : militante_partido,
				'padrones_especificos' : padrones_especificos,
				'tipo_ciudadano' : tipo_ciudadano,
				'manzana' : manzana,
				'id_distrito_local' : id_distrito_local,
				'id_distrito_federal' : id_distrito_federal,
			}
			mapa.push(data);
			if(tipo_mapa=='mapa_calor'){
				url = "listaNominal/mapaCalor.php";
			}else{
				url = "listaNominal/mapa.php";
			}
			if(solo_mapa==1){
				$.ajax({
					type: "POST",
					url: url,
					data: {searchTable: searchTable,mapa:mapa,searchOpciones:searchOpciones},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			}
			url = "listaNominal/saveData.php";
			$.ajax({
				type: "POST",
				url: url,
				data: {searchTable: searchTable,searchOpciones:searchOpciones},
				async: true,
				success: function(data) {
				}
			});
		}
		function locationMunicipio() {
			var id_estado = '<?= $id_estado ?>';
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
				var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio+'&tipo=municipio_array';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
			}
			//searchTable();
		}
	</script>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros Especificos</label>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave Elector</label><br>
		<input data-column="1" id="clave_elector" autocomplete="off" type="text" value="<?= $searchTable['clave_elector'] ?>"> <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">C.U.R.P</label><br>
		<input data-column="1" id="curp" autocomplete="off" type="text" value="<?= $searchTable['curp'] ?>"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre</label><br>
		<input data-column="1" id="nombre" autocomplete="off" type="text"  value="<?= $searchTable['nombre'] ?>" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
		<input data-column="1" id="apellido_paterno" autocomplete="off" type="text" value="<?= $searchTable['apellido_paterno'] ?>" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
		<input data-column="1" id="apellido_materno" autocomplete="off" type="text" value="<?= $searchTable['apellido_materno'] ?>" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Militante Partido</label><br>
		<input data-column="1" id="militante_partido" autocomplete="off" type="text" value="<?= $searchTable['militante_partido'] ?>" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Padrones</label><br>
		<select class="myselect" id="padrones_especificos" >
			<option selected="selected" value="">Seleccione</option>
			<option value="1">Bienestar 65+ Mar - Abr 2023</option>
			<option value="2">Bienestar Benito Juárez Educación Básica Ene - Feb 2023</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo de ciudadano</label><br>
		<select class="myselect" id="tipo_ciudadano" >
			<option selected="selected" value="">Seleccione</option>
			<option value="1">Local</option>
			<option value="2">Foráneos</option>
		</select>
	</div>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros de Ubicación</label>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sección</label><br>
		<select class="myselect" id="id_seccion_ine" >
			<?php
			echo secciones_ine($searchTable['id_seccion_ine'],'','','','');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Manzana</label><br>
		<input data-column="1" id="manzana" autocomplete="off" type="text" value="<?= $searchTable['manzana'] ?>"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Municipio</label><br>
		<select name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">
			<?php
			echo municipios($searchTable['id_municipio'],$id_estado,'');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Localidad</label><br>
		<select name="id_localidad" id="id_localidad" class='myselect' >  
			<?php
			echo localidades($searchTable['id_localidad'],$searchTable['id_municipio']);
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Distrito Local</label><br>
		<select name="id_distrito_local" id="id_distrito_local" class='myselect' >
			<?php
			echo distritos_locales($searchTable['id_distrito_local']);
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Distrito Federal</label><br>
		<select name="id_distrito_federal" id="id_distrito_federal" class='myselect'>
			<?php
			echo distritos_federales($searchTable['id_distrito_federal']);
			?>
		</select>
	</div>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Tipos de Búsqueda</label>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo de mapa</label><br>
		<?php
			$tipo_mapaSelect[$searchOpcionesLN['tipo_mapa']]='selected';
		?>
		<select name="tipo_mapa" id="tipo_mapa" class='myselect'>
			<option <?= $tipo_mapaSelect['sin_mapa'] ?> value="sin_mapa" >Sin Mapa</option>
			<option <?= $tipo_mapaSelect['mapa_coordenadas'] ?> value="mapa_coordenadas" >Mapa Coordenadas</option>
			<option <?= $tipo_mapaSelect['mapa_calor'] ?> value="mapa_calor" >Mapa de Calor</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo de limite</label><br>
		<?php
			$tipo_limiteSelect[$searchOpcionesLN['tipo_limite']]='selected';
		?>
		<select name="tipo_limite" id="tipo_limite" class='myselect' >
			<option <?= $tipo_limiteSelect['10'] ?> value="10">Mostrar 10</option>
			<option <?= $tipo_limiteSelect['20'] ?> value="20">Mostrar 20</option>
			<option <?= $tipo_limiteSelect['30'] ?> value="30">Mostrar 30</option>
			<option <?= $tipo_limiteSelect['40'] ?> value="40">Mostrar 40</option>
			<option <?= $tipo_limiteSelect['50'] ?> value="50">Mostrar 50</option>
			<option <?= $tipo_limiteSelect['100'] ?> value="100">Mostrar 100</option>
			<option <?= $tipo_limiteSelect['200'] ?> value="200">Mostrar 200</option>
			<option <?= $tipo_limiteSelect['400'] ?> value="400">Mostrar 400</option>
			<option <?= $tipo_limiteSelect['500'] ?> value="500">Mostrar 500</option>
			<option <?= $tipo_limiteSelect['x'] ?> value="x" >Sin Limite</option>
		</select>
	</div>
	<div class="sucForm">
		<?php
		if($searchOpcionesLN['tipo_tabla']==1){
			$tipo_tablaCheck = 'checked';
		}
		if($searchOpcionesLN['tipo_tabla_responsive']==1){
			$tipo_tabla_responsiveCheck = 'checked';
		}
		?>
		<label class="labelForm" id="labeltemaname">Opción Tabla</label><br>
		<label style="font-weight: normal;" ><input <?= $tipo_tablaCheck ?> type="checkbox" id="tipo_tabla" value="tabla_mostrar"> Mostrar</label><br>
		<label style="font-weight: normal;" ><input <?= $tipo_tabla_responsiveCheck ?> type="checkbox" id="tipo_tabla_responsive" value="tabla_responsive"> Responsive</label><br>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm">
		<input type="button" onclick="searchTable()" value="Buscar Registros">
		<input type="button" onclick="borrarCamposLista()" value="Borrar Campos">
	</div>
	<style>
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple {
			box-shadow: 0 0 10px #c5c5f2;
			-webkit-box-shadow: 0 0 10px #c5c5f2;
			-moz-box-shadow: 0 0 10px #c5c5f2;
			border: 1px solid #DDDDDD;
			width: 100%;
		}
		input[type=text] {
			height: 38px;
		}
		.select2-container--default .select2-selection--single {
			background-color: #fff;
			border: 1px solid #aaa;
			border-radius: 4px;
			height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 32px;
			position: absolute;
			top: 1px;
			right: 1px;
			width: 20px;
		}
		.bs-actionsbox .btn-group button {
			width: 48%;
			font-size: 12px;
		}
	</style>
	<script type="text/javascript">
		$(".myselect").select2();
		$('.selectpicker').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
		/*searchTable()*/
	</script>