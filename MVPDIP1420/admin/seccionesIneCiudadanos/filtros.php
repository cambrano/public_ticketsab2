<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	$searchTable = $_COOKIE["searchTableSIC"];
	$searchTable = json_decode($searchTable,true);
	
	$searchOpciones = $_COOKIE["searchOpcionesSIC"];
	$searchOpciones = json_decode($searchOpciones,true);
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_nacimiento_1" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			});
			$( "#fecha_nacimiento_2" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			}); 
		});
		document.addEventListener("keyup", function(event) {
			if (event.key === 'Enter') {	
				searchTable();
			}
		});
		function borrarCamposLista(){
			$("#id_tipo_ciudadano").val('default').selectpicker("refresh");
			$("#plataforma").val('default').selectpicker("refresh");
			$("#id_tipo_categoria_ciudadano").val('default').selectpicker("refresh");
			document.getElementById("clave").value = '';
			document.getElementById("folio").value = '';
			document.getElementById("clave_elector").value = '';
			document.getElementById("curp").value = '';
			$("#sexo").val('default').selectpicker("refresh");
			$("#medio_registro").val('default').selectpicker("refresh");
			$("#distancia_alert").val('default').selectpicker("refresh");
			$("#status_verificacion").val('default').selectpicker("refresh");
			$("#info_vigente").val('default').selectpicker("refresh");
			document.getElementById("nombre").value = '';
			document.getElementById("apellido_paterno").value = '';
			document.getElementById("apellido_materno").value = '';
			$("#relacion").val('default').selectpicker("refresh");
			document.getElementById("fecha_nacimiento_1").value = '';
			document.getElementById("fecha_nacimiento_2").value = '';
			$("#fecha_nacimiento_dia").val('default').selectpicker("refresh");
			$("#fecha_nacimiento_mes").val('default').selectpicker("refresh");
			$("#fecha_nacimiento_edad").val('default').selectpicker("refresh");
			$("#documentos_oficiales").val('default').selectpicker("refresh");
			$("#vigencia_documentos_oficiales").val('default').selectpicker("refresh");
			$("#programas_apoyos").val('default').selectpicker("refresh");
			$("#id_programa_apoyo").val('default').selectpicker("refresh");
			$("#num_seguimiento").val('default').selectpicker("refresh");
			$("#id_seccion_ine").val('default').selectpicker("refresh");
			$("#tipo_seccion").val('default').selectpicker("refresh");
			$("#id_distrito_local").val('default').selectpicker("refresh");
			$("#id_distrito_federal").val('default').selectpicker("refresh");
			$("#id_cuartel").val('default').selectpicker("refresh");
			
			document.getElementById("id_municipio").value = '';
			$('#id_municipio').select2().trigger('change');

			$("#id_localidad").val('default').selectpicker("refresh");
			$("#solo_padre").val('default').selectpicker("refresh");
			$("#id_partido_legado").val('default').selectpicker("refresh");
			$("#id_seccion_ine_grupo").val('default').selectpicker("refresh");
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
		function searchTable(value){
			var id_municipio_input = document.getElementById("id_municipio");
			var id_municipio_array = [];
			for (var i = 0; i < id_municipio_input.length; i++) {
				if (id_municipio_input.options[i].selected){
					id_municipio_array.push(id_municipio_input.options[i].value);
				}
			}
			id_municipio = id_municipio_array.join(",");
			var id_localidad_input = document.getElementById("id_localidad");
			var id_localidad_array = [];
			for (var i = 0; i < id_localidad_input.length; i++) {
				if (id_localidad_input.options[i].selected){
					id_localidad_array.push(id_localidad_input.options[i].value);
				}
			}
			id_localidad = id_localidad_array.join(",");

			var plataforma = document.getElementById("plataforma").value;



			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");

			var id_cuartel_input = document.getElementById("id_cuartel");
			var id_cuartel_array = [];
			for (var i = 0; i < id_cuartel_input.length; i++) {
				if (id_cuartel_input.options[i].selected){
					id_cuartel_array.push(id_cuartel_input.options[i].value);
				}
			}
			id_cuartel = id_cuartel_array.join(",");

			/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
			var clave = document.getElementById("clave").value;
			clave = clave.trim();
			var sexo = document.getElementById("sexo").value;
			var nombre = document.getElementById("nombre").value;
			nombre = nombre.trim();
			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			apellido_paterno = apellido_paterno.trim();
			var apellido_materno = document.getElementById("apellido_materno").value; 
			apellido_materno = apellido_materno.trim();
			var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
			fecha_nacimiento_1 = fecha_nacimiento_1.trim();
			var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;
			fecha_nacimiento_2 = fecha_nacimiento_2.trim();

			var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
			fecha_nacimiento_dia = fecha_nacimiento_dia.trim();
			var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
			fecha_nacimiento_mes = fecha_nacimiento_mes.trim();
			var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;
			fecha_nacimiento_edad = fecha_nacimiento_edad.trim();

			var status_verificacion = document.getElementById("status_verificacion").value;
			
			/*
			if(value!=""){
				var id_seccion_ine_ciudadano_compartido_input = value;
			}else{
				id_seccion_ine_ciudadano_compartido_input = null;
			}

			var id_seccion_ine_ciudadano_compartido_input = document.getElementById("id_seccion_ine_ciudadano_compartido");
			var id_seccion_ine_ciudadano_compartido_array = [];
			for (var i = 0; i < id_seccion_ine_ciudadano_compartido_input.length; i++) {
				if (id_seccion_ine_ciudadano_compartido_input.options[i].selected){
					id_seccion_ine_ciudadano_compartido_array.push(id_seccion_ine_ciudadano_compartido_input.options[i].value);
				}
			}
			id_seccion_ine_ciudadano_compartido = id_seccion_ine_ciudadano_compartido_array.join(",");
			*/
			var id_seccion_ine_ciudadano_compartido = document.getElementById("id_seccion_ine_ciudadano_compartido").value;

			var id_tipo_ciudadano_input = document.getElementById("id_tipo_ciudadano");
			var id_tipo_ciudadano_array = [];
			for (var i = 0; i < id_tipo_ciudadano_input.length; i++) {
				if (id_tipo_ciudadano_input.options[i].selected){
					id_tipo_ciudadano_array.push(id_tipo_ciudadano_input.options[i].value);
				}
			}
			id_tipo_ciudadano = id_tipo_ciudadano_array.join(",");

			var id_tipo_categoria_ciudadano_input = document.getElementById("id_tipo_categoria_ciudadano");
			var id_tipo_categoria_ciudadano_array = [];
			for (var i = 0; i < id_tipo_categoria_ciudadano_input.length; i++) {
				if (id_tipo_categoria_ciudadano_input.options[i].selected){
					id_tipo_categoria_ciudadano_array.push(id_tipo_categoria_ciudadano_input.options[i].value);
				}
			}
			id_tipo_categoria_ciudadano = id_tipo_categoria_ciudadano_array.join(",");

			var medio_registro_input = document.getElementById("medio_registro");
			var medio_registro_array = [];
			for (var i = 0; i < medio_registro_input.length; i++) {
				if (medio_registro_input.options[i].selected){
					medio_registro_array.push(medio_registro_input.options[i].value);
				}
			}
			medio_registro = medio_registro_array.join(",");

			var distancia_alert = document.getElementById("distancia_alert").value;

			var relacion = document.getElementById("relacion").value;

			var solo_padre = document.getElementById("solo_padre").value;
			var folio = document.getElementById("folio").value;
			folio = folio.trim();

			var num_seguimiento = document.getElementById("num_seguimiento").value;

			var clave_elector = document.getElementById("clave_elector").value;
			clave_elector = clave_elector.trim();
			var curp = document.getElementById("curp").value;
			curp = curp.trim();


			var documentos_oficiales = document.getElementById("documentos_oficiales").value;
			var vigencia_documentos_oficiales = document.getElementById("vigencia_documentos_oficiales").value;
			var info_vigente = document.getElementById("info_vigente").value;

			
			var programas_apoyos = document.getElementById("programas_apoyos").value;

			var id_partido_legado_input = document.getElementById("id_partido_legado");
			var id_partido_legado_array = [];
			for (var i = 0; i < id_partido_legado_input.length; i++) {
				if (id_partido_legado_input.options[i].selected){
					id_partido_legado_array.push(id_partido_legado_input.options[i].value);
				}
			}
			id_partido_legado = id_partido_legado_array.join(",");

			var id_distrito_local_input = document.getElementById("id_distrito_local");
			var id_distrito_local_array = [];
			for (var i = 0; i < id_distrito_local_input.length; i++) {
				if (id_distrito_local_input.options[i].selected){
					id_distrito_local_array.push(id_distrito_local_input.options[i].value);
				}
			}
			id_distrito_local = id_distrito_local_array.join(",");
			var id_distrito_federal_input = document.getElementById("id_distrito_federal");
			var id_distrito_federal_array = [];
			for (var i = 0; i < id_distrito_federal_input.length; i++) {
				if (id_distrito_federal_input.options[i].selected){
					id_distrito_federal_array.push(id_distrito_federal_input.options[i].value);
				}
			}
			id_distrito_federal = id_distrito_federal_array.join(",");

			var id_programa_apoyo_input = document.getElementById("id_programa_apoyo");
			var id_programa_apoyo_array = [];
			for (var i = 0; i < id_programa_apoyo_input.length; i++) {
				if (id_programa_apoyo_input.options[i].selected){
					id_programa_apoyo_array.push(id_programa_apoyo_input.options[i].value);
				}
			}
			id_programa_apoyo = id_programa_apoyo_array.join(",");

			var id_seccion_ine_grupo_input = document.getElementById("id_seccion_ine_grupo");
			var id_seccion_ine_grupo_array = [];
			for (var i = 0; i < id_seccion_ine_grupo_input.length; i++) {
				if (id_seccion_ine_grupo_input.options[i].selected){
					id_seccion_ine_grupo_array.push(id_seccion_ine_grupo_input.options[i].value);
				}
			}
			id_seccion_ine_grupo = id_seccion_ine_grupo_array.join(",");

			var tipo_seccion_input = document.getElementById("tipo_seccion");
			var tipo_seccion_array = [];
			for (var i = 0; i < tipo_seccion_input.length; i++) {
				if (tipo_seccion_input.options[i].selected){
					tipo_seccion_array.push(tipo_seccion_input.options[i].value);
				}
			}
			tipo_seccion = tipo_seccion_array.join(",");

			//opciones de busqueda
			var tipo_mapa = document.getElementById("tipo_mapa").value;
			var tipo_limite = document.getElementById("tipo_limite").value;
			var tipo_tabla_responsive = document.getElementById("tipo_tabla_responsive").checked;
			if(tipo_tabla_responsive==true){
				tipo_tabla_responsive=1
			}else{
				tipo_tabla_responsive=0
			}

			if(tipo_limite != 'x'){
				if(tipo_mapa=='sin_mapa' && tipo_limite >1000){
					$("#mapaLoad").html('Debe seleccionar algún tipo de mapa');
					document.getElementById("btn_descargarExcel").style.opacity= "0.6";
					document.getElementById("btn_descargarExcel").style.cursor= "not-allowed";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "none";
					return false;
				}else{
					document.getElementById("btn_descargarExcel").style.opacity= "1";
					document.getElementById("btn_descargarExcel").style.cursor= "pointer";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "initial";
				}
			}else{
				if(tipo_mapa=='sin_mapa'){
					document.getElementById("btn_descargarExcel").style.opacity= "0.6";
					document.getElementById("btn_descargarExcel").style.cursor= "not-allowed";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "none";
					$("#mapaLoad").html('Debe seleccionar algún tipo de mapa');
					return false;
				}else{
					document.getElementById("btn_descargarExcel").style.opacity= "0.6";
					document.getElementById("btn_descargarExcel").style.cursor= "not-allowed";
					document.getElementById("btn_descargarExcel").style.pointerEvents= "none";
				}
			}

			if(tipo_limite=="x" && tipo_mapa=='mapa_coordenadas' ){
				$("#mapaLoad").html('No puede seleccionar tipo de mapa Mapa de coordenadas y tipo de limite Sin limite al mismo tiempo.');
				document.getElementById("btn_descargarExcel").style.opacity= "0.6";
				document.getElementById("btn_descargarExcel").style.cursor= "not-allowed";
				document.getElementById("btn_descargarExcel").style.pointerEvents= "none";
				return false;
			}


			$("#dataTable").html("");
			var searchTable = [];
			var data = {
					'id_seccion_ine' : id_seccion_ine, 
					'clave' : clave, 
					'plataforma' : plataforma,
					'sexo' : sexo,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'fecha_nacimiento_1' : fecha_nacimiento_1,
					'fecha_nacimiento_2' : fecha_nacimiento_2,
					'fecha_nacimiento_dia' : fecha_nacimiento_dia,
					'fecha_nacimiento_mes' : fecha_nacimiento_mes,
					'fecha_nacimiento_edad' : fecha_nacimiento_edad,
					'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
					'id_tipo_ciudadano' : id_tipo_ciudadano,
					'medio_registro' : medio_registro,
					'distancia_alert' : distancia_alert,
					'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
					'status_verificacion' : status_verificacion,
					'relacion' : relacion,
					'solo_padre' : solo_padre,
					'id_cuartel' : id_cuartel,
					'folio' : folio,
					'num_seguimiento' : num_seguimiento,
					'clave_elector' : clave_elector, 
					'documentos_oficiales' :documentos_oficiales,
					'vigencia_documentos_oficiales' :vigencia_documentos_oficiales,
					'programas_apoyos' :programas_apoyos,
					'id_localidad' :id_localidad,
					'id_municipio' :id_municipio,
					'id_partido_legado' :id_partido_legado,
					'id_distrito_local' :id_distrito_local,
					'id_distrito_federal' :id_distrito_federal,
					'id_programa_apoyo' :id_programa_apoyo,
					'id_seccion_ine_grupo' :id_seccion_ine_grupo,
					'tipo_seccion' : tipo_seccion,
					'info_vigente' : info_vigente,
					'curp' : curp, 
				}
			searchTable.push(data);

			searchOpciones = [];
			var data = {
				'tipo_tabla_responsive' : tipo_tabla_responsive,
				'tipo_limite' : tipo_limite,
				'tipo_mapa' : tipo_mapa,
			}
			searchOpciones.push(data);
			if(tipo_limite != 'x'){
				$.ajax({
					type: "POST",
					url: "seccionesIneCiudadanos/table.php",
					data: {searchTable: searchTable,searchOpciones:searchOpciones},
					async: true,
					success: function(data) {
						$("#dataTable").html(data);
					}
				});
			}
			var mapa = [];
			var data = {   
					'id_seccion_ine' : id_seccion_ine, 
					'clave' : clave, 
					'plataforma' : plataforma,
					'sexo' : sexo,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'fecha_nacimiento_1' : fecha_nacimiento_1,
					'fecha_nacimiento_2' : fecha_nacimiento_2,
					'fecha_nacimiento_dia' : fecha_nacimiento_dia,
					'fecha_nacimiento_mes' : fecha_nacimiento_mes,
					'fecha_nacimiento_edad' : fecha_nacimiento_edad,
					'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido,
					'id_tipo_ciudadano' : id_tipo_ciudadano,
					'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
					'medio_registro' : medio_registro,
					'distancia_alert' : distancia_alert,
					'status_verificacion' : status_verificacion,
					'relacion' : relacion,
					'solo_padre' : solo_padre,
					'id_cuartel' : id_cuartel,
					'folio' : folio,
					'num_seguimiento' : num_seguimiento,
					'clave_elector' : clave_elector, 
					'documentos_oficiales' :documentos_oficiales,
					'vigencia_documentos_oficiales' :vigencia_documentos_oficiales,
					'programas_apoyos' :programas_apoyos,
					'id_localidad' :id_localidad,
					'id_municipio' :id_municipio,
					'id_partido_legado' :id_partido_legado,
					'id_distrito_local' :id_distrito_local,
					'id_distrito_federal' :id_distrito_federal,
					'id_programa_apoyo' :id_programa_apoyo,
					'id_seccion_ine_grupo' :id_seccion_ine_grupo,
					'tipo_seccion' : tipo_seccion,
					'info_vigente' : info_vigente,
					'curp' : curp, 
				}
			mapa.push(data);
			if(tipo_mapa=='mapa_calor'){
				url = "seccionesIneCiudadanos/mapaCalor.php";
				var mapa_search1 = 1;
			}
			if(tipo_mapa=='mapa_coordenadas'){
				url = "seccionesIneCiudadanos/mapa.php";
				var mapa_search1 = 1;
			}
			if(tipo_mapa=='mapa_tipos_ciudadanos'){
				url = "seccionesIneCiudadanos/mapa_tipos_ciudadanos.php";
				var mapa_search1 = 1;
			}
			if(tipo_limite=="x" && tipo_mapa=='mapa_calor' ){
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
		}
	</script>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros Ciudadanos</label>
	</div>
	<?php
	if($moduloAccionPermisos['captura']!=true){
		?>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Centro Captura</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="plataforma" >
				<?php
				if(validar_codigo_plataforma($codigo_plataforma)==true){
					echo plataformas($searchTable['id_tipo_ciudadano'],'');
				}
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipos Ciudadanos</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_ciudadano" >
				<?php
				echo tipos_ciudadanos($searchTable['id_tipo_ciudadano'],'');
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Categorías</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_categoria_ciudadano" >
				<?php
				echo tipos_categorias_ciudadanos($searchTable['id_tipo_categoria_ciudadano'],'SIN');
				?>
				<option value="0">Sin Categoría</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">clave</label><br>
			<input data-column="0" id="clave" autocomplete="off" type="text" value="<?= $searchTable['clave'] ?>"> <br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio</label><br>
			<input data-column="0" id="folio" autocomplete="off" type="text" value="<?= $searchTable['folio'] ?>"> <br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">clave elector</label><br>
			<input data-column="0" id="clave_elector" autocomplete="off" type="text" value="<?= $searchTable['clave_elector'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">C.U.R.P</label><br>
			<input data-column="0" id="curp" autocomplete="off" type="text" value="<?= $searchTable['curp'] ?>"> <br>
		</div>
		
		
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Sexo</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="sexo" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_sexo[$searchTable['sexo']] = "selected";
				?>
				<option <?= $selected_sexo['Mujer'] ?> value="Mujer">Mujer</option>
				<option <?= $selected_sexo['Hombre'] ?> value="Hombre">Hombre</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Medio Registro</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="medio_registro" >
					<option selected="selected" value="">Seleccione</option>
					<?php
						$selected_mregistro[$searchTable['medio_registro']] = "selected";
					?>
					<option <?= $selected_mregistro['1'] ?> value="1">Ciudadano</option>
					<option <?= $selected_mregistro['2'] ?> value="2">Sistema</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Distancia Alerta</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="distancia_alert" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_dalert[$searchTable['distancia_alert']] = "selected";
				?>
				<option <?= $selected_dalert['1'] ?> value="1">SI</option>
				<option <?= $selected_dalert['0'] ?> value="0">NO</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Verificación</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="status_verificacion" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_sverificacion[$searchTable['status_verificacion']] = "selected";
				?>
				<option <?= $selected_sverificacion['0'] ?> value="0">No Encontrado</option>
				<option <?= $selected_sverificacion['1'] ?> value="1">Encontrado</option>
				<option <?= $selected_sverificacion['2'] ?> value="2">Verificado</option>
				<option <?= $selected_sverificacion['3'] ?> value="3">Por Validar</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Info Vigente</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="info_vigente" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_ivigente[$searchTable['info_vigente']] = "selected";
				?>
				<option <?= $selected_ivigente['1'] ?> value="1">Vencidos</option>
				<option <?= $selected_ivigente['x'] ?> value="x">No Vencido</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre</label><br>
			<input data-column="1" id="nombre" autocomplete="off" type="text" value="<?= $searchTable['nombre'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
			<input data-column="1" id="apellido_paterno" autocomplete="off" type="text" value="<?= $searchTable['apellido_paterno'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
			<input data-column="1" id="apellido_materno" autocomplete="off" type="text" value="<?= $searchTable['apellido_materno'] ?>"> <br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Relacionado</label><br>
			<select id='id_seccion_ine_ciudadano_compartido' style='width: 100%;' >
				<option value=''>Buscar Relacionado</option>
			</select>
		</div>
		<script>
			$(document).ready(function(){
				$("#id_seccion_ine_ciudadano_compartido").select2({ 
					language: {
						errorLoading:function(){ 
							return "" 
						},
						inputTooLong:function(e){
							var n=e.input.length-e.maximum,r="Por favor, elimine "+n+" car";return r+=1==n?"ácter":"acteres"
						},
						inputTooShort:function(e){
							var n=e.minimum-e.input.length,r="Por favor, introduzca minimo "+n+" car";return r+=1==n?"ácter":"acteres"
						},
						loadingMore:function(){
							return"Cargando más resultados…"
						},
						maximumSelected:function(e){
							var n="Sólo puede seleccionar "+e.maximum+" elemento";return 1!=e.maximum&&(n+="s"),n
						},
						noResults:function(){
							return"No se encontraron resultados"
						},
						searching:function(){
							return"Buscando…"
						},
						removeAllItems:function(){
							return"Eliminar todos los elementos"
						}
					},
					ajax: {
						url: "seccionesIneCiudadanos/search.php",
						type: "post",
						dataType: 'json',
						delay: 250,
						data: function (params) {
							return {
								search: params.term // search term
							};
						},
						processResults: function (response) {
							return {
								results: response
							};
						},
						cache: true
					}
				});
				//$('#id_seccion_ine_ciudadano_compartido').on("change", function(e) { 
				//	var val = $(this).val();
				//	//searchTable(val);
				//});
			});
		</script>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Relación</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="relacion" >
				<?php
					$selected_relacion[$searchTable['relacion']] = "selected";
				?>
				<option selected="selected" value="">Seleccione</option>
				<option <?= $selected_relacion['1'] ?> value="1">Con Padres</option>
				<option <?= $selected_relacion['2'] ?> value="2">Sin Padres</option>
			</select>
		</div>
		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento(1)</label><br>
			<input id="fecha_nacimiento_1" autocomplete="off" type="text" value="<?= $searchTable['fecha_nacimiento_1'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento(2)</label><br>
			<input id="fecha_nacimiento_2" autocomplete="off" type="text" value="<?= $searchTable['fecha_nacimiento_2'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Día</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_dia" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_fecha_nacimiento_dia[$searchTable['fecha_nacimiento_dia']] = "selected";
				?>
				<option <?= $selected_fecha_nacimiento_dia['01'] ?> value="01" >01</option>
				<option <?= $selected_fecha_nacimiento_dia['02'] ?> value="02" >02</option>
				<option <?= $selected_fecha_nacimiento_dia['03'] ?> value="03" >03</option>
				<option <?= $selected_fecha_nacimiento_dia['04'] ?> value="04" >04</option>
				<option <?= $selected_fecha_nacimiento_dia['05'] ?> value="05" >05</option>
				<option <?= $selected_fecha_nacimiento_dia['06'] ?> value="06" >06</option>
				<option <?= $selected_fecha_nacimiento_dia['07'] ?> value="07" >07</option>
				<option <?= $selected_fecha_nacimiento_dia['08'] ?> value="08" >08</option>
				<option <?= $selected_fecha_nacimiento_dia['09'] ?> value="09" >09</option>
				<option <?= $selected_fecha_nacimiento_dia['10'] ?> value="10" >10</option>
				<option <?= $selected_fecha_nacimiento_dia['11'] ?> value="11" >11</option>
				<option <?= $selected_fecha_nacimiento_dia['12'] ?> value="12" >12</option>
				<option <?= $selected_fecha_nacimiento_dia['13'] ?> value="13" >13</option>
				<option <?= $selected_fecha_nacimiento_dia['14'] ?> value="14" >14</option>
				<option <?= $selected_fecha_nacimiento_dia['15'] ?> value="15" >15</option>
				<option <?= $selected_fecha_nacimiento_dia['16'] ?> value="16" >16</option>
				<option <?= $selected_fecha_nacimiento_dia['17'] ?> value="17" >17</option>
				<option <?= $selected_fecha_nacimiento_dia['18'] ?> value="18" >18</option>
				<option <?= $selected_fecha_nacimiento_dia['19'] ?> value="19" >19</option>
				<option <?= $selected_fecha_nacimiento_dia['20'] ?> value="20" >20</option>
				<option <?= $selected_fecha_nacimiento_dia['21'] ?> value="21" >21</option>
				<option <?= $selected_fecha_nacimiento_dia['22'] ?> value="22" >22</option>
				<option <?= $selected_fecha_nacimiento_dia['23'] ?> value="23" >23</option>
				<option <?= $selected_fecha_nacimiento_dia['24'] ?> value="24" >24</option>
				<option <?= $selected_fecha_nacimiento_dia['25'] ?> value="25" >25</option>
				<option <?= $selected_fecha_nacimiento_dia['26'] ?> value="26" >26</option>
				<option <?= $selected_fecha_nacimiento_dia['27'] ?> value="27" >27</option>
				<option <?= $selected_fecha_nacimiento_dia['28'] ?> value="28" >28</option>
				<option <?= $selected_fecha_nacimiento_dia['29'] ?> value="29" >29</option>
				<option <?= $selected_fecha_nacimiento_dia['30'] ?> value="30" >30</option>
				<option <?= $selected_fecha_nacimiento_dia['31'] ?> value="31" >31</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Mes</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_mes" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_fecha_nacimiento_mes[$searchTable['fecha_nacimiento_mes']] = "selected";
				?>
				<option <?= $selected_fecha_nacimiento_mes['01'] ?> value="01" >Enero</option>
				<option <?= $selected_fecha_nacimiento_mes['02'] ?> value="02" >Febrero</option>
				<option <?= $selected_fecha_nacimiento_mes['03'] ?> value="03" >Marzo</option>
				<option <?= $selected_fecha_nacimiento_mes['04'] ?> value="04" >Abril</option>
				<option <?= $selected_fecha_nacimiento_mes['05'] ?> value="05" >Mayo</option>
				<option <?= $selected_fecha_nacimiento_mes['06'] ?> value="06" >Junio</option>
				<option <?= $selected_fecha_nacimiento_mes['07'] ?> value="07" >Julio</option>
				<option <?= $selected_fecha_nacimiento_mes['08'] ?> value="08" >Agosto</option>
				<option <?= $selected_fecha_nacimiento_mes['09'] ?> value="09" >Septiembre</option>
				<option <?= $selected_fecha_nacimiento_mes['10'] ?> value="10" >Octubre</option>
				<option <?= $selected_fecha_nacimiento_mes['11'] ?> value="11" >Noviembre</option>
				<option <?= $selected_fecha_nacimiento_mes['12'] ?> value="12" >Diciembre</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Edad</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_edad" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_fecha_nacimiento_edad[$searchTable['fecha_nacimiento_edad']] = "selected";
				?>
				<option <?= $selected_fecha_nacimiento_edad['18'] ?> value='18' >18</option>
				<option <?= $selected_fecha_nacimiento_edad['19'] ?> value='19' >19</option>
				<option <?= $selected_fecha_nacimiento_edad['20'] ?> value='20' >20</option>
				<option <?= $selected_fecha_nacimiento_edad['21'] ?> value='21' >21</option>
				<option <?= $selected_fecha_nacimiento_edad['22'] ?> value='22' >22</option>
				<option <?= $selected_fecha_nacimiento_edad['23'] ?> value='23' >23</option>
				<option <?= $selected_fecha_nacimiento_edad['24'] ?> value='24' >24</option>
				<option <?= $selected_fecha_nacimiento_edad['25'] ?> value='25' >25</option>
				<option <?= $selected_fecha_nacimiento_edad['26'] ?> value='26' >26</option>
				<option <?= $selected_fecha_nacimiento_edad['27'] ?> value='27' >27</option>
				<option <?= $selected_fecha_nacimiento_edad['28'] ?> value='28' >28</option>
				<option <?= $selected_fecha_nacimiento_edad['29'] ?> value='29' >29</option>
				<option <?= $selected_fecha_nacimiento_edad['30'] ?> value='30' >30</option>
				<option <?= $selected_fecha_nacimiento_edad['31'] ?> value='31' >31</option>
				<option <?= $selected_fecha_nacimiento_edad['32'] ?> value='32' >32</option>
				<option <?= $selected_fecha_nacimiento_edad['33'] ?> value='33' >33</option>
				<option <?= $selected_fecha_nacimiento_edad['34'] ?> value='34' >34</option>
				<option <?= $selected_fecha_nacimiento_edad['35'] ?> value='35' >35</option>
				<option <?= $selected_fecha_nacimiento_edad['36'] ?> value='36' >36</option>
				<option <?= $selected_fecha_nacimiento_edad['37'] ?> value='37' >37</option>
				<option <?= $selected_fecha_nacimiento_edad['38'] ?> value='38' >38</option>
				<option <?= $selected_fecha_nacimiento_edad['39'] ?> value='39' >39</option>
				<option <?= $selected_fecha_nacimiento_edad['40'] ?> value='40' >40</option>
				<option <?= $selected_fecha_nacimiento_edad['41'] ?> value='41' >41</option>
				<option <?= $selected_fecha_nacimiento_edad['42'] ?> value='42' >42</option>
				<option <?= $selected_fecha_nacimiento_edad['43'] ?> value='43' >43</option>
				<option <?= $selected_fecha_nacimiento_edad['44'] ?> value='44' >44</option>
				<option <?= $selected_fecha_nacimiento_edad['45'] ?> value='45' >45</option>
				<option <?= $selected_fecha_nacimiento_edad['46'] ?> value='46' >46</option>
				<option <?= $selected_fecha_nacimiento_edad['47'] ?> value='47' >47</option>
				<option <?= $selected_fecha_nacimiento_edad['48'] ?> value='48' >48</option>
				<option <?= $selected_fecha_nacimiento_edad['49'] ?> value='49' >49</option>
				<option <?= $selected_fecha_nacimiento_edad['50'] ?> value='50' >50</option>
				<option <?= $selected_fecha_nacimiento_edad['51'] ?> value='51' >51</option>
				<option <?= $selected_fecha_nacimiento_edad['52'] ?> value='52' >52</option>
				<option <?= $selected_fecha_nacimiento_edad['53'] ?> value='53' >53</option>
				<option <?= $selected_fecha_nacimiento_edad['54'] ?> value='54' >54</option>
				<option <?= $selected_fecha_nacimiento_edad['55'] ?> value='55' >55</option>
				<option <?= $selected_fecha_nacimiento_edad['56'] ?> value='56' >56</option>
				<option <?= $selected_fecha_nacimiento_edad['57'] ?> value='57' >57</option>
				<option <?= $selected_fecha_nacimiento_edad['58'] ?> value='58' >58</option>
				<option <?= $selected_fecha_nacimiento_edad['59'] ?> value='59' >59</option>
				<option <?= $selected_fecha_nacimiento_edad['60'] ?> value='60' >60</option>
				<option <?= $selected_fecha_nacimiento_edad['61'] ?> value='61' >61</option>
				<option <?= $selected_fecha_nacimiento_edad['62'] ?> value='62' >62</option>
				<option <?= $selected_fecha_nacimiento_edad['63'] ?> value='63' >63</option>
				<option <?= $selected_fecha_nacimiento_edad['64'] ?> value='64' >64</option>
				<option <?= $selected_fecha_nacimiento_edad['65'] ?> value='65' >65</option>
				<option <?= $selected_fecha_nacimiento_edad['66'] ?> value='66' >66</option>
				<option <?= $selected_fecha_nacimiento_edad['67'] ?> value='67' >67</option>
				<option <?= $selected_fecha_nacimiento_edad['68'] ?> value='68' >68</option>
				<option <?= $selected_fecha_nacimiento_edad['69'] ?> value='69' >69</option>
				<option <?= $selected_fecha_nacimiento_edad['70'] ?> value='70' >70</option>
				<option <?= $selected_fecha_nacimiento_edad['71'] ?> value='71' >71</option>
				<option <?= $selected_fecha_nacimiento_edad['72'] ?> value='72' >72</option>
				<option <?= $selected_fecha_nacimiento_edad['73'] ?> value='73' >73</option>
				<option <?= $selected_fecha_nacimiento_edad['74'] ?> value='74' >74</option>
				<option <?= $selected_fecha_nacimiento_edad['75'] ?> value='75' >75</option>
				<option <?= $selected_fecha_nacimiento_edad['76'] ?> value='76' >76</option>
				<option <?= $selected_fecha_nacimiento_edad['77'] ?> value='77' >77</option>
				<option <?= $selected_fecha_nacimiento_edad['78'] ?> value='78' >78</option>
				<option <?= $selected_fecha_nacimiento_edad['79'] ?> value='79' >79</option>
				<option <?= $selected_fecha_nacimiento_edad['80'] ?> value='80' >80</option>
				<option <?= $selected_fecha_nacimiento_edad['81'] ?> value='81' >81</option>
				<option <?= $selected_fecha_nacimiento_edad['82'] ?> value='82' >82</option>
				<option <?= $selected_fecha_nacimiento_edad['83'] ?> value='83' >83</option>
				<option <?= $selected_fecha_nacimiento_edad['84'] ?> value='84' >84</option>
				<option <?= $selected_fecha_nacimiento_edad['85'] ?> value='85' >85</option>
				<option <?= $selected_fecha_nacimiento_edad['86'] ?> value='86' >86</option>
				<option <?= $selected_fecha_nacimiento_edad['87'] ?> value='87' >87</option>
				<option <?= $selected_fecha_nacimiento_edad['88'] ?> value='88' >88</option>
				<option <?= $selected_fecha_nacimiento_edad['89'] ?> value='89' >89</option>
				<option <?= $selected_fecha_nacimiento_edad['90'] ?> value='90' >90</option>
				<option <?= $selected_fecha_nacimiento_edad['91'] ?> value='91' >91</option>
				<option <?= $selected_fecha_nacimiento_edad['92'] ?> value='92' >92</option>
				<option <?= $selected_fecha_nacimiento_edad['93'] ?> value='93' >93</option>
				<option <?= $selected_fecha_nacimiento_edad['94'] ?> value='94' >94</option>
				<option <?= $selected_fecha_nacimiento_edad['95'] ?> value='95' >95</option>
				<option <?= $selected_fecha_nacimiento_edad['96'] ?> value='96' >96</option>
				<option <?= $selected_fecha_nacimiento_edad['97'] ?> value='97' >97</option>
				<option <?= $selected_fecha_nacimiento_edad['98'] ?> value='98' >98</option>
				<option <?= $selected_fecha_nacimiento_edad['99'] ?> value='99' >99</option>
				<option <?= $selected_fecha_nacimiento_edad['100'] ?> value='100' >100</option>
				<option <?= $selected_fecha_nacimiento_edad['101'] ?> value='101' >101</option>
				<option <?= $selected_fecha_nacimiento_edad['102'] ?> value='102' >102</option>
				<option <?= $selected_fecha_nacimiento_edad['103'] ?> value='103' >103</option>
				<option <?= $selected_fecha_nacimiento_edad['104'] ?> value='104' >104</option>
				<option <?= $selected_fecha_nacimiento_edad['105'] ?> value='105' >105</option>
				<option <?= $selected_fecha_nacimiento_edad['106'] ?> value='106' >106</option>
				<option <?= $selected_fecha_nacimiento_edad['107'] ?> value='107' >107</option>
				<option <?= $selected_fecha_nacimiento_edad['108'] ?> value='108' >108</option>
				<option <?= $selected_fecha_nacimiento_edad['109'] ?> value='109' >109</option>
				<option <?= $selected_fecha_nacimiento_edad['110'] ?> value='110' >110</option>
				<option <?= $selected_fecha_nacimiento_edad['111'] ?> value='111' >111</option>
				<option <?= $selected_fecha_nacimiento_edad['112'] ?> value='112' >112</option>
				<option <?= $selected_fecha_nacimiento_edad['113'] ?> value='113' >113</option>
				<option <?= $selected_fecha_nacimiento_edad['114'] ?> value='114' >114</option>
				<option <?= $selected_fecha_nacimiento_edad['115'] ?> value='115' >115</option>
				<option <?= $selected_fecha_nacimiento_edad['116'] ?> value='116' >116</option>
				<option <?= $selected_fecha_nacimiento_edad['117'] ?> value='117' >117</option>
				<option <?= $selected_fecha_nacimiento_edad['118'] ?> value='118' >118</option>
				<option <?= $selected_fecha_nacimiento_edad['119'] ?> value='119' >119</option>
				<option <?= $selected_fecha_nacimiento_edad['120'] ?> value='120' >120</option>
				<option <?= $selected_fecha_nacimiento_edad['121'] ?> value='121' >121</option>
				<option <?= $selected_fecha_nacimiento_edad['122'] ?> value='122' >122</option>
				<option <?= $selected_fecha_nacimiento_edad['123'] ?> value='123' >123</option>
				<option <?= $selected_fecha_nacimiento_edad['124'] ?> value='124' >124</option>
				<option <?= $selected_fecha_nacimiento_edad['125'] ?> value='125' >125</option>
				<option <?= $selected_fecha_nacimiento_edad['126'] ?> value='126' >126</option>
				<option <?= $selected_fecha_nacimiento_edad['127'] ?> value='127' >127</option>
				<option <?= $selected_fecha_nacimiento_edad['128'] ?> value='128' >128</option>
				<option <?= $selected_fecha_nacimiento_edad['129'] ?> value='129' >129</option>
				<option <?= $selected_fecha_nacimiento_edad['130'] ?> value='130' >130</option>
			</select>
		</div>

		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Doc Oficiales</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="documentos_oficiales" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_doc_oficiales[$searchTable['documentos_oficiales']] = "selected";
				?>
				<option <?= $selected_doc_oficiales['1'] ?> value="1">SI</option>
				<option <?= $selected_doc_oficiales['0'] ?> value="0">NO</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Vig Doc Oficiales</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="vigencia_documentos_oficiales" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_vig_doc_oficiales[$searchTable['vigencia_documentos_oficiales']] = "selected";
				?>
				<option <?= $selected_vig_doc_oficiales['1'] ?> value="1">Vencidos</option>
				<option <?= $selected_vig_doc_oficiales['0'] ?> value="0">Ningun Vencidos</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="programas_apoyos" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_prog_apoyos[$searchTable['programas_apoyos']] = "selected";
				?>
				<option <?= $selected_prog_apoyos['1'] ?> value="1">Con</option>
				<option <?= $selected_prog_apoyos['0'] ?> value="0">Sin</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_programa_apoyo" >
				<?php
				echo programas_apoyos($searchTable['id_programa_apoyo'],'SIN');
				?>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">No. Seguimiento</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="num_seguimiento" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_num_seguimiento[$searchTable['num_seguimiento']] = "selected";
				?>
				<option <?= $selected_num_seguimiento['1'] ?> value="1">1</option>
				<option <?= $selected_num_seguimiento['2'] ?> value="2">2</option>
				<option <?= $selected_num_seguimiento['3'] ?> value="3">3</option>
				<option <?= $selected_num_seguimiento['4'] ?> value="4">4</option>
				<option <?= $selected_num_seguimiento['5'] ?> value="5">5</option>
				<option <?= $selected_num_seguimiento['6'] ?> value="6">6</option>
				<option <?= $selected_num_seguimiento['7'] ?> value="7">7</option>
				<option <?= $selected_num_seguimiento['8'] ?> value="8">8</option>
				<option <?= $selected_num_seguimiento['9'] ?> value="9">9</option>
				<option <?= $selected_num_seguimiento['10'] ?> value="10">10</option>
			</select>
		</div>

		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Secciones</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" >
				<?php
				echo secciones_ine($searchTable['id_seccion_ine'],'','','','SIN');
				?>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Sección</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_seccion" >
				<option value="">SELECCIONE</option>
				<?php
					$selected_tipo_seccion[$searchTable['tipo_seccion']] = "selected";
				?>
				<option <?= $selected_tipo_seccion['1'] ?> value="1">Urbana</option>
				<option <?= $selected_tipo_seccion['0'] ?> value="0">Rural</option>
			</select>
		</div>

		<?php
		if($tipo_uso_plataforma=='municipio'){
			//$display_municipio = 'style="display: none"';
			//$display_distrito_local = 'style="display: none"';
			//$display_distrito_federal = 'style="display: none"';
		}elseif($tipo_uso_plataforma=='distrito_local'){
			//$display_municipio = 'style="display: none"';
			//$display_distrito_federal = 'style="display: none"';
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			//$display_municipio = 'style="display: none"';
			//$display_distrito_local = 'style="display: none"';
		}
		?>

		<div class="sucForm" <?= $display_distrito_local ?>>
			<label class="labelForm" id="labeltemaname">Distritos Locales</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_local" >
				<?php
				echo distritos_locales($searchTable['id_distrito_local'],'SIN');
				?>
			</select>
		</div>

		<div class="sucForm" <?= $display_distrito_federal ?>>
			<label class="labelForm" id="labeltemaname">Distritos Federales</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_federal" >
				<?php
				echo distritos_federales($searchTable['id_distrito_federal'],'SIN');
				?>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Zonas</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_cuartel" >
				<?php
				echo cuarteles($searchTable['id_cuartel'],'SIN');
				?>
			</select>
		</div>
		<?php
		$display_municipio='';
		if($searchTable['id_municipio']==''){
			$id_municipioL = $id_municipio;
		}else{
			$id_municipioL = $searchTable['id_municipio'];
		}
		?>
		<div class="sucForm" <?= $display_municipio ?>>
			<label class="labelForm" id="labeltemaname">Municipio</label><br>
			<select name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">
				<?php
				echo municipios($id_municipioL,$id_estado,'');
				?>
			</select>
		</div>

		<div class="sucForm" <?= $display_municipio ?>>
			<label class="labelForm" id="labeltemaname">Localidad</label><br>
			<select name="id_localidad" id="id_localidad" class='myselect' >  
				<?php
				echo localidades($searchTable['id_localidad'],$searchTable['id_municipio']);
				?>
			</select>
		</div>

		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Solo Padres</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="solo_padre" >
				<option selected="selected" value="">Seleccione</option>

				<?php
					$selected_solo_padre[$searchTable['solo_padre']] = "selected";
				?>
				<option <?= $selected_solo_padre['1'] ?> value="1">Son Padres</option>
				<option <?= $selected_solo_padre['2'] ?> value="0">No Son Padres</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Militante</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_partido_legado" >
				<?php
				echo partidos_legados($searchTable['id_partido_legado'],'SIN');
				?>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Grupos Interes</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine_grupo" >
				<?php
				echo secciones_ine_grupos($searchTable['id_seccion_ine_grupo'],'',$id_distrito_local,$id_distrito_federal,$id_municipio,'SIN');
				?>
			</select>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipos de Búsqueda</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo de mapa</label><br>
			<?php
				$tipo_mapaSelect[$searchOpciones['tipo_mapa']]='selected';
			?>
			<select name="tipo_mapa" id="tipo_mapa" class='myselect'>
				<option <?= $tipo_mapaSelect['sin_mapa'] ?> value="sin_mapa" >Sin Mapa</option>
				<option <?= $tipo_mapaSelect['mapa_coordenadas'] ?> value="mapa_coordenadas" >Mapa Coordenadas</option>
				<option <?= $tipo_mapaSelect['mapa_calor'] ?> value="mapa_calor" >Mapa de Calor</option>
				<option <?= $tipo_mapaSelect['mapa_tipos_ciudadanos'] ?> value="mapa_tipos_ciudadanos" >Mapa Tipo Ciudadano</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo de limite</label><br>
			<?php
				$tipo_limiteSelect[$searchOpciones['tipo_limite']]='selected';
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
				<option <?= $tipo_limiteSelect['1000'] ?> value="1000">Mostrar 1000</option>
				<option <?= $tipo_limiteSelect['2000'] ?> value="2000">Mostrar 2000</option>
				<option <?= $tipo_limiteSelect['3000'] ?> value="3000">Mostrar 3000</option>
				<option <?= $tipo_limiteSelect['4000'] ?> value="4000">Mostrar 4000</option>
				<option <?= $tipo_limiteSelect['5000'] ?> value="5000">Mostrar 5000</option>
				<option <?= $tipo_limiteSelect['10000'] ?> value="10000">Mostrar 10000</option>
				<option <?= $tipo_limiteSelect['x'] ?> value="x" >Sin Limite</option>
			</select>
		</div>
		<div class="sucForm">
			<?php
			if($searchOpciones['tipo_tabla_responsive']==1){
				$tipo_tabla_responsiveCheck = 'checked';
			}
			?>
			<label class="labelForm" id="labeltemaname">Opción Tabla</label><br>
			<label style="font-weight: normal;" ><input <?= $tipo_tabla_responsiveCheck ?> type="checkbox" id="tipo_tabla_responsive" value="tabla_responsive"> Responsive</label><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<input style="width: 49%;" type="button" onclick="searchTable()" value="Buscar">
			<input style="width: 50%;" type="button" onclick="borrarCamposLista()" value="Borrar Campos">
		</div>
		<?php
	}else{
		?>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Centro Captura</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="plataforma" >
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipos Ciudadanos</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_ciudadano" >
				<?php
				echo tipos_ciudadanos($searchTable['id_tipo_ciudadano'],'');
				?>
			</select><br>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Categorías</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_categoria_ciudadano" >
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">clave</label><br>
			<input data-column="0" id="clave" autocomplete="off" type="text" value="<?= $searchTable['clave'] ?>"> <br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio</label><br>
			<input data-column="0" id="folio" autocomplete="off" type="text" value="<?= $searchTable['folio'] ?>"> <br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">clave elector</label><br>
			<input data-column="0" id="clave_elector" autocomplete="off" type="text" value="<?= $searchTable['clave_elector'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">C.U.R.P</label><br>
			<input data-column="0" id="curp" autocomplete="off" type="text" value="<?= $searchTable['curp'] ?>"> <br>
		</div>
		
		
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Sexo</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="sexo" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Medio Registro</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="medio_registro" >
					<option selected="selected" value="">Seleccione</option>
			</select><br>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Distancia Alerta</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="distancia_alert" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Verificación</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="status_verificacion" >
				<option selected="selected" value="">Seleccione</option>
				<?php
					$selected_sverificacion[$searchTable['status_verificacion']] = "selected";
				?>
				<option <?= $selected_sverificacion['0'] ?> value="0">No Encontrado</option>
				<option <?= $selected_sverificacion['1'] ?> value="1">Encontrado</option>
				<option <?= $selected_sverificacion['2'] ?> value="2">Verificado</option>
				<option <?= $selected_sverificacion['3'] ?> value="3">Por Validar</option>
			</select>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Info Vigente</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="info_vigente" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre</label><br>
			<input data-column="1" id="nombre" autocomplete="off" type="text" value="<?= $searchTable['nombre'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
			<input data-column="1" id="apellido_paterno" autocomplete="off" type="text" value="<?= $searchTable['apellido_paterno'] ?>"> <br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
			<input data-column="1" id="apellido_materno" autocomplete="off" type="text" value="<?= $searchTable['apellido_materno'] ?>"> <br>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Relacionado</label><br>
			<select id='id_seccion_ine_ciudadano_compartido' style='width: 100%;' >
				<option value=''>Buscar Relacionado</option>
			</select>
		</div> 
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Relación</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="relacion" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>
		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento(1)</label><br>
			<input id="fecha_nacimiento_1" autocomplete="off" type="text" value="<?= $searchTable['fecha_nacimiento_1'] ?>"> <br>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento(2)</label><br>
			<input id="fecha_nacimiento_2" autocomplete="off" type="text" value="<?= $searchTable['fecha_nacimiento_2'] ?>"> <br>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Día</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_dia" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Mes</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_mes" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Edad</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_edad" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>

		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Doc Oficiales</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="documentos_oficiales" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Vig Doc Oficiales</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="vigencia_documentos_oficiales" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="programas_apoyos" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_programa_apoyo" >
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">No. Seguimiento</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="num_seguimiento" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>

		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Secciones</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" >
				<?php
				echo secciones_ine($searchTable['id_seccion_ine'],'','','','SIN');
				?>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Tipo Sección</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_seccion" >
				<option value="">SELECCIONE</option>
			</select>
		</div>

		<?php
		if($tipo_uso_plataforma=='municipio'){
			//$display_municipio = 'style="display: none"';
			//$display_distrito_local = 'style="display: none"';
			//$display_distrito_federal = 'style="display: none"';
		}elseif($tipo_uso_plataforma=='distrito_local'){
			//$display_municipio = 'style="display: none"';
			//$display_distrito_federal = 'style="display: none"';
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			//$display_municipio = 'style="display: none"';
			//$display_distrito_local = 'style="display: none"';
		}
		?>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Distritos Locales</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_local" >
				<?php
				echo distritos_locales($searchTable['id_distrito_local'],'SIN');
				?>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Distritos Federales</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_federal" >
				<?php
				echo distritos_federales($searchTable['id_distrito_federal'],'SIN');
				?>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Zonas</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_cuartel" >
				<?php
				echo cuarteles($searchTable['id_cuartel'],'SIN');
				?>
			</select>
		</div>
		<?php
		$display_municipio='';
		if($searchTable['id_municipio']==''){
			$id_municipioL = $id_municipio;
		}else{
			$id_municipioL = $searchTable['id_municipio'];
		}
		?>
		<div class="sucForm" <?= $display_municipio ?>>
			<label class="labelForm" id="labeltemaname">Municipio</label><br>
			<select name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">
				<?php
				echo municipios($id_municipioL,$id_estado,'');
				?>
			</select>
		</div>

		<div class="sucForm" <?= $display_municipio ?>>
			<label class="labelForm" id="labeltemaname">Localidad</label><br>
			<select name="id_localidad" id="id_localidad" class='myselect' >  
				<?php
				echo localidades($searchTable['id_localidad'],$searchTable['id_municipio']);
				?>
			</select>
		</div>

		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Solo Padres</label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="solo_padre" >
				<option selected="selected" value="">Seleccione</option>
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Militante</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_partido_legado" >
			</select>
		</div>

		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Grupos Interes</label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine_grupo" >
			</select>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipos de Búsqueda</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo de mapa</label><br>
			<?php
				$tipo_mapaSelect[$searchOpciones['tipo_mapa']]='selected';
			?>
			<select name="tipo_mapa" id="tipo_mapa" class='myselect'>
				<option <?= $tipo_mapaSelect['sin_mapa'] ?> value="sin_mapa" >Sin Mapa</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo de limite</label><br>
			<?php
				$tipo_limiteSelect[$searchOpciones['tipo_limite']]='selected';
			?>
			<select name="tipo_limite" id="tipo_limite" class='myselect' >
				<option <?= $tipo_limiteSelect['10'] ?> value="10">Mostrar 10</option>
			</select>
		</div>
		<div class="sucForm">
			<?php
			if($searchOpciones['tipo_tabla_responsive']==1){
				$tipo_tabla_responsiveCheck = 'checked';
			}
			?>
			<label class="labelForm" id="labeltemaname">Opción Tabla</label><br>
			<label style="font-weight: normal;" ><input <?= $tipo_tabla_responsiveCheck ?> type="checkbox" id="tipo_tabla_responsive" value="tabla_responsive"> Responsive</label><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<input style="width: 49%;" type="button" onclick="searchTable()" value="Buscar">
			<input style="width: 50%;" type="button" onclick="borrarCamposLista()" value="Borrar Campos">
		</div>
		<?php
	}
	?>
	

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
	</script>