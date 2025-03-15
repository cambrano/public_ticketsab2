<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redes_sociales.php";

	@session_start();
	unset($_SESSION['searchTable']);
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_emision_1" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			});
			$( "#fecha_emision_2" ).datepicker({ 
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
			var fecha_emision_1 = document.getElementById("fecha_emision_1").value;
			var fecha_emision_2 = document.getElementById("fecha_emision_2").value;

			var searchTable = [];
			var data = {
					'clave' : clave,
					'fecha_emision_1' : fecha_emision_1,
					'fecha_emision_2' : fecha_emision_2,
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "cuentasRedesSocialesActividades/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">clave</label><br>
		<input data-column="0" id="clave" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>

	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Emisión(1)</label><br>
		<input id="fecha_emision_1" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Emisión(2)</label><br>
		<input id="fecha_emision_2" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
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