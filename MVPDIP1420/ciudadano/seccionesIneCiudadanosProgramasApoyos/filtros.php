<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/programas_apoyos.php";
	@session_start();
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_1" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			});
			$( "#fecha_2" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			});
		});
		function searchTable(value){
			var clave = document.getElementById("clave").value;
			var folio = document.getElementById("folio").value;
			var id_programa_apoyo = document.getElementById("id_programa_apoyo").value;

			var fecha_1 = document.getElementById("fecha_1").value;
			var fecha_2 = document.getElementById("fecha_2").value;

			var searchTable = [];
			var data = {
					'clave' : clave,
					'folio' : folio,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'id_programa_apoyo' : id_programa_apoyo,
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosProgramasApoyos/table.php",
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
		<input data-column="0" id="clave" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Folio</label><br>
		<input data-column="0" id="folio" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>

	 

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_programa_apoyo" onchange="searchTable();">
			<?php
			echo programas_apoyos();
			?>
		</select><br>
	</div>

	

	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Emision(1)</label><br>
		<input id="fecha_1" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Emision(2)</label><br>
		<input id="fecha_2" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
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
		$(".myselect").selectpicker({
		});
		$('select').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>