<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/status.php";
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(value){
			var clave = document.getElementById("clave").value;
			var id_partido_legado = document.getElementById("id_partido_legado").value;
			var status = document.getElementById("status").value;
			if(status=='x'){
				status=0;
			}
			var searchTable = [];
			var data = {
					'clave' : clave,
					'id_partido_legado' : id_partido_legado,
					'status' : status,
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "militantesPartidos/table.php",
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
		<label class="labelForm" id="labeltemaname">Partidos Legado</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_partido_legado" onchange="searchTable();">
			<?php
			echo partidos_legados();
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Estatus</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="status" onchange="searchTable();">
		<?php
			echo statusGeneral();
		?>
		</select><br>
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