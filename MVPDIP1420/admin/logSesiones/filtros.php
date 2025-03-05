<?php
	include __DIR__."/../functions/security.php"; 
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
		function CheckNumeric() {
			return event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode == 46;
		}
		function searchTable(value){
			var alerta = document.getElementById("alerta").value;
			var tipo = document.getElementById("tipo").value;
			var tipo_intento = document.getElementById("tipo_intento").value;
			var fecha_1 = document.getElementById("fecha_1").value;
			var fecha_2 = document.getElementById("fecha_2").value;
			var searchTable = [];
			var data = {
					'alerta' :alerta,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'tipo_intento' : tipo_intento,
					'tipo' : tipo,
				}
			searchTable.push(data);
			var mapa = [];
			var data = {   
					'alerta' :alerta,
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'tipo_intento' : tipo_intento,
					'tipo' : tipo,
				}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "logSesiones/table.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
			/*
			$.ajax({
				type: "POST",
				url: "logSesiones/mapa.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#mapaLoad").html(data);
				}
			});
			*/
		}
	</script> 
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
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="alerta_maxima">Alerta Maxima</option>
			<option value="usuario">Usuario</option>
			<option value="ciudadano">ciudadano</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Intento</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_intento" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="no_existe">No Existe</option>
			<option value="sesion">Session</option>
			<option value="error_scaneo">Error Scaneo</option>
			<option value="error_http">Error HTTP</option>
			<option value="error_seguridad_id">Error Seguridad Id</option>
			<option value="usuario_status">Usuario Status</option>
			<option value="ciudadanos_cerrado">Ciudadanos Cerrado</option>
			<option value="error_seguridad_admin">Error Seguridad Admin</option>
			<option value="error_seguridad_plataforma">Plataforma Diferente</option>
			
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Alerta<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="alerta" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="x">Ninguno</option>
			<option value="0">Amigo</option>
			<option value="1">Hostil</option>
			<option value="2">Neutro</option>
			<option value="3">Interés</option>
		</select><br>
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