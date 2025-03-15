<?php
	include __DIR__."/../functions/security.php"; 
	@session_start(); 
?>
	<script type="text/javascript">
		function searchTable(value){
			var sexo = document.getElementById("sexo").value;
			var edad = document.getElementById("edad").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;
			var searchTable = [];
			var data = {
					'sexo' : sexo,
					'edad' : edad,
					'id_municipio' : id_municipio,
					'id_seccion_ine' : id_seccion_ine,
				}
			searchTable.push(data); 
			$.ajax({
				type: "POST",
				url: "encuestasSecciones/municipio/table_seccion.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>

	<style>
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
			height: 33px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: 32px;
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
	<div class="sucForm">
		<input type="hidden" name="id_seccion_ine" id = "id_seccion_ine" value="<?= $id_seccion_ine ?>" >
		<input type="hidden" name="id_municipio" id = "id_municipio" value="<?= $id_municipio ?>" >
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sexo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="sexo" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="Mujer">Mujer</option>
			<option value="Hombre">Hombre</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Edad</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="edad" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="1">18</option>
			<option value="2">19</option>
			<option value="3">20 - 24</option>
			<option value="4">25 - 29</option>
			<option value="5">30 - 34</option>
			<option value="6">35 - 39</option>
			<option value="7">40 - 44</option>
			<option value="8">45 - 49</option>
			<option value="9">50 - 54</option>
			<option value="10">55 - 59</option>
			<option value="11">60 - 64</option>
			<option value="12">65 Más</option>
		</select>
	</div>

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