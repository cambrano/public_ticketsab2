<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(){
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;
			var semaforo_general = document.getElementById("semaforo_general").value;
			var searchTable = [];
			var data = {
				'id_seccion_ine' : id_seccion_ine,
				'semaforo_general' : semaforo_general,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosSeccionesAvanceSemaforo/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sección</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange ="searchTable();">
			<?php
			echo secciones_ine('',$id_municipio,$id_distrito_local,$id_distrito_federal,'');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Con Semáforo General</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="semaforo_general" onchange ="searchTable();">
			<option value="">Seleccione</option>
			<option value="1">Activo</option>
			<option value="0">No Activo</option>
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
		$('.selectpicker').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>