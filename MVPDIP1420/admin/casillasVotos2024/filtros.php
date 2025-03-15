<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(){
			var clave = document.getElementById("clave").value;
			var id_municipio_input = document.getElementById("id_municipio");
			var id_municipio_array = [];
			for (var i = 0; i < id_municipio_input.length; i++) {
				if (id_municipio_input.options[i].selected){
					id_municipio_array.push(id_municipio_input.options[i].value);
				}
			}
			id_municipio = id_municipio_array.join(",");
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

			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");
			var tipo = document.getElementById("tipo").value;

			var tipo_seccion = document.getElementById("tipo_seccion").value;

			var status_data = document.getElementById("status_data").value;

			var check_in = document.getElementById("check_in").value;

			var searchTable = [];
			var data = {
				'tipo_seccion' : tipo_seccion,
				'clave' : clave,
				'id_municipio' : id_municipio,
				'id_distrito_local' : id_distrito_local,
				'id_distrito_federal' : id_distrito_federal,
				'id_seccion_ine' : id_seccion_ine,
				'tipo' : tipo,
				'tipo_seccion' : tipo_seccion,
				'status_data' : status_data,
				'check_in' : check_in,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2024/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave</label><br>
		<input data-column="1" id="clave" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm" style="<?= $mostrar_all == '' ? '' :'display: none;'  ?>">
		<label class="labelForm" id="labeltemaname">Tipo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="0">Municipio</option>
			<option value="1">Distrito Local</option>
			<option value="2">Distrito Federal</option>
		</select>
	</div>
	<div class="sucForm" style="<?= $mostrar_all == '' ? '' :'display: none;'  ?>">
		<label class="labelForm" id="labeltemaname">Municipios<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_municipio" onchange="searchTable();">
			<?php
			echo municipios($id_municipio,$id_estado,'SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm" style="<?= $mostrar_all == '' ? '' :'display: none;'  ?>">
		<label class="labelForm" id="labeltemaname">Distritos Locales<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_local" onchange="searchTable();">
			<?php
			echo distritos_locales('','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm" style="<?= $mostrar_all == '' ? '' :'display: none;'  ?>">
		<label class="labelForm" id="labeltemaname">Distritos Federales<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_federal" onchange="searchTable();">
			<?php
			echo distritos_federales('','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm" style="width: 100%"><hr></div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Sección<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Tipo Selección" id="tipo_seccion" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="Urbana">Urbana</option> 
			<option value="Rural">Rural</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('',$id_municipio,$id_distrito_local,$id_distrito_federal,'SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Estatus<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Tipo Selección" id="status_data" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="x">Sin Estatus</option> 
			<option value="1">Abierto</option> 
			<option value="2">Cerrado Con Votantes</option>
			<option value="3">Cerrado</option>
			<option value="4">Inicio Conteo</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Check IN<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Check In" id="check_in" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="0">Sin Check IN</option> 
			<option value="1">Con Check IN</option>
		</select>
	</div>
	<style type="text/css">
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
		$('select').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>