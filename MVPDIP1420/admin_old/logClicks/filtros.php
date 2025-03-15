<?php
	include __DIR__."/../functions/security.php"; 
	@session_start();
	unset($_SESSION['searchTable']);
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
		function CheckNumeric() {
			return event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode == 46;
		}
		function searchTable(value){
			var city = document.getElementById("city").value;
			var region = document.getElementById("region").value;
			var country = document.getElementById("country").value;
			var fecha_1 = document.getElementById("fecha_1").value;
			var fecha_2 = document.getElementById("fecha_2").value;
			var os = document.getElementById("os").value;
			var browser = document.getElementById("browser").value;
			var searchTable = [];
			var data = {
					'city' : city,
					'region' : region,
					'country' : country,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'os' : os,
					'browser' : browser,
				}
			searchTable.push(data);
			var mapa = [];
			var data = {   
					'city' : city,
					'region' : region,
					'country' : country,
					'os' : os,
					'browser' : browser,
				}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "logClicks/table.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
			$.ajax({
				type: "POST",
				url: "logClicks/mapa.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#mapaLoad").html(data);
				}
			});
		}
	</script> 

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Browser</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="browser" onchange="searchTable();">
			<?php
			echo filtrosSelect('browser');
			?>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">OS</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="os" onchange="searchTable();">
			<?php
			echo filtrosSelect('os');
			?>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">City</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="city" onchange="searchTable();">
			<?php
			echo filtrosSelect('city');
			?>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Región</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="region" onchange="searchTable();">
			<?php
			echo filtrosSelect('region');
			?>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Country</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="country" onchange="searchTable();">
			<?php
			echo filtrosSelect('country');
			?>
		</select>
	</div>
	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha (1)</label><br>
		<input data-column="5" id="fecha_1" autocomplete="off" type="text" onkeyup="searchTable();" > <br> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha (2)</label><br>
		<input data-column="5" id="fecha_2" autocomplete="off" type="text" onkeyup="searchTable();" > <br> 
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