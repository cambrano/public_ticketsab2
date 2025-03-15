<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	unset($_SESSION['searchTable']);
?>
	<script type="text/javascript">
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
			var clave = document.getElementById("clave").value;
			var folio = document.getElementById("folio").value;
			var nombre = document.getElementById("nombre").value;
			var cedula = document.getElementById("cedula").value;
			var numero_contrato = document.getElementById("numero_contrato").value;

			var tipo_input = document.getElementById("tipo");
			var tipo_array = [];
			for (var i = 0; i < tipo_input.length; i++) {
				if (tipo_input.options[i].selected){
					tipo_array.push("'"+tipo_input.options[i].value+"'");
				}
			}
			tipo = tipo_array.join(",");

			var id_tipo_infraestructura_input = document.getElementById("id_tipo_infraestructura");
			var id_tipo_infraestructura_array = [];
			for (var i = 0; i < id_tipo_infraestructura_input.length; i++) {
				if (id_tipo_infraestructura_input.options[i].selected){
					id_tipo_infraestructura_array.push(id_tipo_infraestructura_input.options[i].value);
				}
			}
			id_tipo_infraestructura = id_tipo_infraestructura_array.join(",");


			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");

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

			var searchTable = [];
			var data = {
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'tipo' : tipo,
					'id_tipo_infraestructura' : id_tipo_infraestructura,
					'id_seccion_ine' :id_seccion_ine,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'cedula' : cedula,
					'numero_contrato' : numero_contrato
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneActividades/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});

			var mapa = [];
			var data = {   
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'tipo' : tipo,
					'id_tipo_infraestructura' : id_tipo_infraestructura,
					'id_seccion_ine' :id_seccion_ine,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'cedula' : cedula,
					'numero_contrato' : numero_contrato
				}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneActividades/mapa.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#mapaLoad").html(data);
				}
			});
		}
	</script>


	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros Acciones u Obras</label>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">clave</label><br>
		<input data-column="0" id="clave" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Folio</label><br>
		<input data-column="0" id="folio" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Número de Contrato</label><br>
		<input data-column="0" id="numero_contrato" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Cédula</label><br>
		<input data-column="0" id="cedula" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>

	<div class="sucForm" style="width: 100%">
		<label class="labelForm" id="labeltemaname">Nombre</label><br>
		<input data-column="0" id="nombre" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipos</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo" onchange="searchTable();">
				<!--<option value="candidato">Candidado</option>-->
				<!--<option value="visita">Visita</option>-->
				<option value="apoyo">Apoyo</option>
				<option value="obra">Obra</option>
				<option value="accion">Accion</option>
		</select><br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipos Infraestructuras<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_infraestructura" onchange="searchTable();">
			<?php
			echo tipos_infraestructuras('','SIN');
			?>
		</select><br>
	</div>

	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('',$id_municipio,$id_distrito_local,$id_distrito_federal,'SIN');
			?>
		</select><br>
	</div>
	<?php
	if($tipo_uso_plataforma=='municipio'){
		$display_municipio = 'style="display: none"';
		$display_distrito_local = 'style="display: none"';
		$display_distrito_federal = 'style="display: none"';
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$display_municipio = 'style="display: none"';
		$display_distrito_federal = 'style="display: none"';
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$display_municipio = 'style="display: none"';
		$display_distrito_local = 'style="display: none"';
	}
	?>
	<div class="sucForm" <?= $display_distrito_local ?>>
		<label class="labelForm" id="labeltemaname">Distritos Locales</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_local" onchange="searchTable();">
			<?php
			echo distritos_locales('','SIN');
			?>
		</select>
	</div>

	<div class="sucForm" <?= $display_distrito_federal ?>>
		<label class="labelForm" id="labeltemaname">Distritos Federales</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_federal" onchange="searchTable();">
			<?php
			echo distritos_federales('','SIN');
			?>
		</select>
	</div>

	<div class="sucForm" <?= $display_municipio ?>>
		<label class="labelForm" id="labeltemaname">Municipio</label><br>
		<select name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">
			<?php
			echo municipios($id_municipio,$id_estado,'');
			?>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Localidad</label><br>
		<select name="id_localidad" id="id_localidad" class='myselect' onchange="searchTable();">  
			<?php
			echo localidades();
			?>
		</select>
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
	</script>