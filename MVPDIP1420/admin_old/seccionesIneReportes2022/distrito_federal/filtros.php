<?php
	include __DIR__."/../../functions/security.php"; 
	@session_start(); 
?>
	<script type="text/javascript">
		 
		function searchTable(value){
			let alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			var id_seccion_ine_array_table = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					id_seccion_ine_array_table.push("(^" + id_seccion_ine_input.options[i].value + ")");
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");


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

			var tipo_seccion_input = document.getElementById("tipo_seccion");
			var tipo_seccion_array = [];
			var tipo_seccion_array_table = [];
			for (var i = 0; i < tipo_seccion_input.length; i++) {
				if (tipo_seccion_input.options[i].selected){
					tipo_seccion_array.push(tipo_seccion_input.options[i].value);
					tipo_seccion_array_table.push("(^" + tipo_seccion_input.options[i].value + ")");
				}
			}
			tipo_seccion = tipo_seccion_array.join(",");

			if(tipo_seccion==""){
				$('#secciones_reportes-tabla').DataTable().column(6).search(tipo_seccion).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+tipo_seccion+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(6).search(tipo_seccion_array_table.join('|'), true, false).draw();
			}

			if(tipo_seccion != ''){
				if(tipo_seccion=='Urbana'){
					tipo_seccion = 1;
				}else{
					tipo_seccion = 0;
				}
			}

			var id_distrito_federal = "<?= $id_distrito_federal ?>";
			var searchTable = [];
			var data = {
					'tipo_seccion' : tipo_seccion,
					'id_seccion_ine' : id_seccion_ine,
					'partido_ganador_id' : partido_ganador_id,
					'id_distrito_federal' : id_distrito_federal,
				}
			searchTable.push(data);
			var mapa = [];
			var data = {  
					'tipo_seccion' : tipo_seccion, 
					'id_seccion_ine' : id_seccion_ine,
					'partido_ganador_id' : partido_ganador_id,
					'id_distrito_federal' : id_distrito_federal,
				}
			mapa.push(data);


			if(id_seccion_ine==""){
				$('#secciones_reportes-tabla').DataTable().column(0).search(id_seccion_ine).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+id_seccion_ine+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(0).search(id_seccion_ine_array_table.join('|'), true, false).draw();
			}

			if(partido_ganador_id==""){
				$('#secciones_reportes-tabla').DataTable().column(1).search(partido_ganador_id).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(1).search("(^"+partido_ganador_id+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(1).search(partido_ganador_id_array_table.join('|'), true, false).draw();
			}


			/*
			$.ajax({
				type: "POST",
				url: "seccionesIneReportes2022/table.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
			*/
			$.ajax({
				type: "POST",
				url: "seccionesIneReportes2022/distrito_federal/mapa.php",
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
	<div style="width: 100%;">
		<button class="btn btn-info"  onClick="verMasDistritoFederalMatrizRentabilidad2022()">
			<span class="btnImage">Matriz Rentabilidad</span>
			<span class="btnText">Matriz Rentabilidad</span>
		</button>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('','','',$id_distrito_federal,'SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Partidos Mayoría</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="partido_ganador_id" onchange="searchTable();">
			<?php
			echo partidos_2022('',2,'SIN');
			?>
		</select><br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Sección</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_seccion" onchange="searchTable();">
			<option value="">SELECCIONE</option>
			<option value="Urbana">Urbana</option>
			<option value="Rural">Rural</option>
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