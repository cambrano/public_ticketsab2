<?php
	include __DIR__."/../functions/security.php"; 
	@session_start(); 
?>
	<script type="text/javascript">
		 
		function searchTable(value){
			var id_municipio_input = document.getElementById("id_municipio");
			var id_municipio_array = [];
			var id_municipio_array_table = [];
			for (var i = 0; i < id_municipio_input.length; i++) {
				if (id_municipio_input.options[i].selected){
					id_municipio_array.push(id_municipio_input.options[i].value);
					id_municipio_array_table.push("(^" + id_municipio_input.options[i].value + ")");
				}
			}
			id_municipio = id_municipio_array.join(",");

			var partido_ganador_id_input = document.getElementById("partido_ganador_id");
			var partido_ganador_id_array = [];
			var partido_ganador_id_array_table = [];
			for (var i = 0; i < partido_ganador_id_input.length; i++) {
				if (partido_ganador_id_input.options[i].selected){
					partido_ganador_id_array.push(partido_ganador_id_input.options[i].value);
					partido_ganador_id_array_table.push("(^" + partido_ganador_id_input.options[i].value + ")");
				}
			}
			partido_ganador_id = partido_ganador_id_array.join(",");



			var searchTable = [];
			var data = {
					'id_municipio' : id_municipio,
					'partido_ganador_id' : partido_ganador_id,
				}
			searchTable.push(data);
			var mapa = [];
			var data = {   
					'id_municipio' : id_municipio,
					'partido_ganador_id' : partido_ganador_id,
				}
			mapa.push(data);

			id_municipio = id_municipio_array.join("|");
			if(id_municipio==""){
				$('#municipios_reportes-tabla').DataTable().column(0).search(id_municipio).draw();
			}else{
				$('#municipios_reportes-tabla').DataTable().column(0).search(id_municipio_array_table.join('|'), true, false).draw();
			}

			partido_ganador_id = partido_ganador_id_array.join("|");
			if(partido_ganador_id==""){
				$('#municipios_reportes-tabla').DataTable().column(1).search(partido_ganador_id).draw();
			}else{
				$('#municipios_reportes-tabla').DataTable().column(1).search(partido_ganador_id_array_table.join('|'), true, false).draw();
			}


			/*
			$.ajax({
				type: "POST",
				url: "municipiosReportes2021/table.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
			*/
			$.ajax({
				type: "POST",
				url: "municipiosReportes2021/mapa.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#mapaLoad").html(data);
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
		<label class="labelForm" id="labeltemaname">Municipio<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_municipio" onchange="searchTable();">
			<?php
			echo municipios('',$id_estado,'SIN');
			?>
		</select><br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Partidos Mayoría</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="partido_ganador_id" onchange="searchTable();">
			<?php
			echo partidos_2021('','SIN');
			?>
		</select><br>
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