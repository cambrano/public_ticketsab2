<?php
	include __DIR__."/../functions/security.php"; 
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

			var semaforo_input = document.getElementById("semaforo");
			var semaforo_array = [];
			var semaforo_array_table = [];
			for (var i = 0; i < semaforo_input.length; i++) {
				if (semaforo_input.options[i].selected){
					semaforo_array.push(semaforo_input.options[i].value);
					semaforo_array_table.push("(^" + semaforo_input.options[i].value + ")");
				}
			}
			semaforo = semaforo_array.join(",");


			if(id_seccion_ine==""){
				$('#secciones_reportes-tabla').DataTable().column(0).search(id_seccion_ine).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+id_seccion_ine+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(0).search(id_seccion_ine_array_table.join('|'), true, false).draw();
			}

			if(semaforo==""){
				$('#secciones_reportes-tabla').DataTable().column(2).search(semaforo).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+id_seccion_ine+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(2).search(semaforo).draw();
			}

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
				$('#secciones_reportes-tabla').DataTable().column(7).search(tipo_seccion).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+tipo_seccion+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(7).search(tipo_seccion_array_table.join('|'), true, false).draw();
			}

			if(tipo_seccion != ''){
				if(tipo_seccion=='Urbana'){
					tipo_seccion = 1;
				}else{
					tipo_seccion = 0;
				}
			}


			var id_distrito_local = "<?= $id_distrito_local ?>";
			var searchTable = [];
			var data = {
					'tipo_seccion' : tipo_seccion,
					'id_seccion_ine' : id_seccion_ine, 
					'semaforo' : semaforo,
					'id_distrito_local' : id_distrito_local,
				}
			searchTable.push(data); 
			var mapa = [];
			var data = {   
					'tipo_seccion' : tipo_seccion,
					'id_seccion_ine' : id_seccion_ine,
					'semaforo' : semaforo,
					'id_distrito_local' : id_distrito_local,
				}
			mapa.push(data); 

			$.ajax({
				type: "POST",
				url: "seccionesIneReportes2021MatrizRentabilidad/distrito_local/mapa.php",
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
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('','',$id_distrito_local,'','SIN');
			?>
		</select><br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Semáforo<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="semaforo" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="verde">Verde</option>
			<option value="amarillo">Amarillo</option>
			<option value="rojo">Rojo</option>
			<option value="gris">Gris</option>
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