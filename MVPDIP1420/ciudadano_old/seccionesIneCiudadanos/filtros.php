<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	unset($_SESSION['searchTable']);
?>
	<script type="text/javascript">
		function searchTable(value){
			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");

			/*var id_seccion_ine = document.getElementById("id_seccion_ine").value;*/
			var sexo = document.getElementById("sexo").value;
			var nombre_completo = document.getElementById("nombre_completo").value; 
			var clave_elector = document.getElementById("clave_elector").value;
			var programas_apoyos = document.getElementById("programas_apoyos").value;
			var id_programa_apoyo_input = document.getElementById("id_programa_apoyo");
			var id_programa_apoyo_array = [];
			for (var i = 0; i < id_programa_apoyo_input.length; i++) {
				if (id_programa_apoyo_input.options[i].selected){
					id_programa_apoyo_array.push(id_programa_apoyo_input.options[i].value);
				}
			}
			id_programa_apoyo = id_programa_apoyo_array.join(",");


			var searchTable = [];
			var data = {
					'id_seccion_ine' : id_seccion_ine, 
					'sexo' : sexo,
					'nombre_completo' : nombre_completo,
					'clave_elector' : clave_elector, 
					'programas_apoyos' :programas_apoyos,
					'id_programa_apoyo' :id_programa_apoyo,
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros Ciudadanos</label>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">clave elector</label><br>
		<input data-column="0" id="clave_elector" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>
	
	
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sexo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="sexo" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="Mujer">Mujer</option>
			<option value="Hombre">Hombre</option>
		</select>
	</div>
	<div class="sucForm" style="width: 100%">
		<label class="labelForm" id="labeltemaname">Ciudadano</label><br>
		<input data-column="1" id="nombre_completo" autocomplete="off" type="text" onchange="searchTable();" > <br>
	</div>

	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>


	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="programas_apoyos" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="1">Con</option>
			<option value="0">Sin</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_programa_apoyo" onchange="searchTable();">
			<?php
			echo programas_apoyos('','SIN');
			?>
		</select>
	</div>

	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('','','','','SIN');
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