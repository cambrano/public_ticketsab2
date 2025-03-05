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
			var fecha_1 = document.getElementById("fecha_1").value;
			var fecha_2 = document.getElementById("fecha_2").value;
			var id_usuario = document.getElementById("id_usuario").value;
			var id_tipo_ciudadano = document.getElementById("id_tipo_ciudadano").value;
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;
			var alerta = document.getElementById("alerta").value;
			var alerta_seccion = document.getElementById("alerta_seccion").value;
			var searchTable = [];
			var data = {
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'id_usuario' : id_usuario,
					'id_tipo_ciudadano' : id_tipo_ciudadano,
					'id_seccion_ine' : id_seccion_ine,
					'alerta' : alerta,
					'alerta_seccion' : alerta_seccion,
				}
			searchTable.push(data);
			var mapa = [];
			var data = {   
					'fecha_1' : fecha_1,
					'fecha_2' : fecha_2,
					'id_usuario' : id_usuario,
					'id_tipo_ciudadano' : id_tipo_ciudadano,
					'id_seccion_ine' : id_seccion_ine,
					'alerta' : alerta,
					'alerta_seccion' : alerta_seccion,
				}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "logUsuariosTrackingSecciones/table.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
			/*
			$.ajax({
				type: "POST",
				url: "logUsuariosTrackingSecciones/mapa.php",
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
		<label class="labelForm" id="labeltemaname">Usuario</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_usuario" onchange="searchTable();">
			<?php
			echo usuariosCiudadanos('','','','',array('casilla'=>1));
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipos Ciudadanos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_ciudadano" onchange="searchTable();">
			<?php
			echo tipos_ciudadanos($searchTable['id_tipo_ciudadano'],'SIN');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sección</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine($searchTable['id_seccion_ine'],$id_municipio,$id_distrito_local,$id_distrito_federal,'SIN');
			?>
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
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">En sección<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="alerta_seccion" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="0">SI</option>
			<option value="1">NO</option>
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