<?php
	include __DIR__."/../../functions/security.php";
	@session_start(); 
?>
	<script type="text/javascript">
		function localidadesSeccionesIne(){
			document.getElementById("id_seccion_ine").value = '';
			var id_secciones_ine = document.getElementById("id_secciones_ine").value;
			var id_municipio = '<?= $id_municipio ?>';
			var tipo = 'id_secciones_ine_array'
			var dataString = 'id_secciones_ine='+id_secciones_ine+'&tipo='+tipo+'&id_municipio='+id_municipio;
			$.ajax({
				type: "POST",
				url: "localidadesSeccionesIne/ajax.php",
				data: dataString,
				success: function(data) {
					$('#id_seccion_ine').find('option').remove();
					$("#id_seccion_ine").append(data);
					$("#id_seccion_ine").selectpicker("refresh");
				}
			});
			searchTable();
		}
		function searchTable(value) {
			var id_distrito_local_input = document.getElementById("id_distrito_local");
			var id_distrito_local_array = [];
			var id_distrito_local_array_table = [];
			for (var i = 0; i < id_distrito_local_input.length; i++) {
				if (id_distrito_local_input.options[i].selected){
					id_distrito_local_array.push(id_distrito_local_input.options[i].value);
					id_distrito_local_array_table.push("(^" + id_distrito_local_input.options[i].value + "$)");
				}
			}
			id_distrito_local = id_distrito_local_array.join(","); 

			if(id_distrito_local==""){
				$('#secciones_reportes-tabla').DataTable().column(6).search(id_distrito_local).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+id_seccion_ine+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(6).search(id_distrito_local_array_table.join('|'), true, false).draw();
			}
			var id_distrito_federal_input = document.getElementById("id_distrito_federal");
			var id_distrito_federal_array = [];
			var id_distrito_federal_array_table = [];
			for (var i = 0; i < id_distrito_federal_input.length; i++) {
				if (id_distrito_federal_input.options[i].selected){
					id_distrito_federal_array.push(id_distrito_federal_input.options[i].value);
					id_distrito_federal_array_table.push("(^" + id_distrito_federal_input.options[i].value + "$)");
				}
			}
			id_distrito_federal = id_distrito_federal_array.join(","); 

			if(id_distrito_federal==""){
				$('#secciones_reportes-tabla').DataTable().column(7).search(id_distrito_federal).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+id_seccion_ine+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(7).search(id_distrito_federal_array_table.join('|'), true, false).draw();
			}

			// Declaración de las variables globales necesarias
			var id_seccion_ine_array = [];
			var id_seccion_ine_array_table = [];

			// Función para procesar un elemento select y agregar sus valores a los arreglos
			function procesarSelect(selectElement) {
				if (selectElement && selectElement.options) {
					for (var option of selectElement.selectedOptions) {
						var arrayValores = option.value.split(',');
						arrayValores.forEach(function (element) {
							id_seccion_ine_array.push(element);
							id_seccion_ine_array_table.push("(^" + element + "$)");
						});
					}
				} else {
					//console.warn("El elemento no es un select válido o no contiene opciones.");
				}
			}

			// Procesar las colonias
			var id_secciones_ine_colonia_input = document.getElementById("id_secciones_ine_colonia");
			procesarSelect(id_secciones_ine_colonia_input);

			// Procesar las localidades
			var id_secciones_ine_localidad_input = document.getElementById("id_secciones_ine_localidad");
			procesarSelect(id_secciones_ine_localidad_input);

			// Procesar la sección INE
			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			if (id_seccion_ine_input && id_seccion_ine_input.options) {
				for (var option of id_seccion_ine_input.selectedOptions) {
					id_seccion_ine_array.push(option.value);
					id_seccion_ine_array_table.push("(^" + option.value + "$)");
				}
			} else {
				//console.warn("El elemento 'id_seccion_ine' no es válido o no contiene opciones.");
			}

			// Eliminar duplicados en los arreglos
			id_seccion_ine_array = [...new Set(id_seccion_ine_array)];
			id_seccion_ine_array_table = [...new Set(id_seccion_ine_array_table)];

			// Unir los valores para aplicar filtros en la tabla
			var id_seccion_ine = id_seccion_ine_array.join(",");
			//console.log("ID Secciones INE:", id_seccion_ine);

			// Aplicar el filtro en DataTables
			if (id_seccion_ine === "") {
				$('#secciones_reportes-tabla').DataTable().column(0).search("").draw();
			} else {
				$('#secciones_reportes-tabla')
					.DataTable()
					.column(0)
					.search(id_seccion_ine_array_table.join('|'), true, false)
					.draw();
			}

			var partido_ganador_individual_input = document.getElementById("partido_ganador_individual");
			var partido_ganador_individual_array = [];
			var partido_ganador_individual_array_table = [];

			// Recorrer las opciones del select y procesar las seleccionadas
			if (partido_ganador_individual_input && partido_ganador_individual_input.options) {
				for (var option of partido_ganador_individual_input.selectedOptions) {
					if (option.value.trim() !== "") {
						partido_ganador_individual_array.push(option.value.trim());
						partido_ganador_individual_array_table.push("(^" + option.value.trim() + "$)");
					}
				}
			} else {
				//console.warn("El elemento 'partido_ganador_individual' no es válido o no contiene opciones.");
			}

			// Unir los valores seleccionados en una cadena
			var partido_ganador_individual = partido_ganador_individual_array.join(",");

			// Aplicar el filtro en DataTables
			if (partido_ganador_individual === "") {
				$('#secciones_reportes-tabla').DataTable().column(1).search("").draw();
			} else {
				$('#secciones_reportes-tabla')
					.DataTable()
					.column(1)
					.search(partido_ganador_individual_array_table.join('|'), true, false)
					.draw();
			}


			var partido_ganador_coalicion_input = document.getElementById("partido_ganador_coalicion");
			var partido_ganador_coalicion_array = [];
			var partido_ganador_coalicion_array_table = [];

			// Recorrer las opciones del select y procesar las seleccionadas
			if (partido_ganador_coalicion_input && partido_ganador_coalicion_input.options) {
				for (var option of partido_ganador_coalicion_input.selectedOptions) {
					if (option.value.trim() !== "") {
						partido_ganador_coalicion_array.push(option.value.trim());
						partido_ganador_coalicion_array_table.push("(^" + option.value.trim() + "$)");
					}
				}
			} else {
				console.warn("El elemento 'partido_ganador_coalicion' no es válido o no contiene opciones.");
			}

			// Unir los valores seleccionados en una cadena
			var partido_ganador_coalicion = partido_ganador_coalicion_array.join(",");

			// Aplicar el filtro en DataTables
			if (partido_ganador_coalicion === "") {
				$('#secciones_reportes-tabla').DataTable().column(3).search("").draw();
			} else {
				$('#secciones_reportes-tabla')
					.DataTable()
					.column(3)
					.search(partido_ganador_coalicion_array_table.join('|'), true, false)
					.draw();
			}


			
			var semaforo_individual_input = document.getElementById("semaforo_individual");
			var semaforo_individual_array = [];
			var semaforo_individual_array_table = [];

			// Recorrer las opciones del select y procesar las seleccionadas
			if (semaforo_individual_input && semaforo_individual_input.options) {
				for (var option of semaforo_individual_input.selectedOptions) {
					if (option.value.trim() !== "") {
						semaforo_individual_array.push(option.value.trim());
						semaforo_individual_array_table.push("(^" + option.value.trim() + "$)");
					}
				}
			} else {
				//console.warn("El elemento 'semaforo_individual' no es válido o no contiene opciones.");
			}

			// Unir los valores seleccionados en una cadena
			var semaforo_individual = semaforo_individual_array.join(",");

			// Aplicar el filtro en DataTables
			if (semaforo_individual === "") {
				$('#secciones_reportes-tabla').DataTable().column(2).search("").draw();
			} else {
				$('#secciones_reportes-tabla')
					.DataTable()
					.column(2)
					.search(semaforo_individual_array_table.join('|'), true, false)
					.draw();
			}

			var semaforo_coalicion_input = document.getElementById("semaforo_coalicion");
			var semaforo_coalicion_array = [];
			var semaforo_coalicion_array_table = [];

			// Recorrer las opciones del select y procesar las seleccionadas
			if (semaforo_coalicion_input && semaforo_coalicion_input.options) {
				for (var option of semaforo_coalicion_input.selectedOptions) {
					if (option.value.trim() !== "") {
						semaforo_coalicion_array.push(option.value.trim());
						semaforo_coalicion_array_table.push("(^" + option.value.trim() + "$)");
					}
				}
			} else {
				//console.warn("El elemento 'semaforo_coalicion' no es válido o no contiene opciones.");
			}

			// Unir los valores seleccionados en una cadena
			var semaforo_coalicion = semaforo_coalicion_array.join(",");
			// Aplicar el filtro en DataTables
			if (semaforo_coalicion === "") {
				$('#secciones_reportes-tabla').DataTable().column(4).search("").draw();
			} else {
				$('#secciones_reportes-tabla')
					.DataTable()
					.column(4)
					.search(semaforo_coalicion_array_table.join('|'), true, false)
					.draw();
			}

			var tipo_seccion_input = document.getElementById("tipo_seccion");
			var tipo_seccion_array = [];
			var tipo_seccion_array_table = [];

			// Recorrer las opciones del select y procesar las seleccionadas
			if (tipo_seccion_input && tipo_seccion_input.options) {
				for (var option of tipo_seccion_input.selectedOptions) {
					if (option.value.trim() !== "") {
						tipo_seccion_array.push(option.value.trim());
						tipo_seccion_array_table.push("(^" + option.value.trim() + "$)");
					}
				}
			} else {
				//console.warn("El elemento 'tipo_seccion' no es válido o no contiene opciones.");
			}

			// Unir los valores seleccionados en una cadena
			var tipo_seccion = tipo_seccion_array.join(",");

			// Aplicar el filtro en DataTables
			if (tipo_seccion === "") {
				$('#secciones_reportes-tabla').DataTable().column(9).search("").draw();
			} else {
				$('#secciones_reportes-tabla')
					.DataTable()
					.column(9)
					.search(tipo_seccion_array_table.join('|'), true, false)
					.draw();
			}
			var tipo_seccion_input = document.getElementById("tipo_seccion");
			if(tipo_seccion != ''){
				if(tipo_seccion=='Urbana'){
					tipo_seccion = 1;
				}else{
					tipo_seccion = 0;
				}
			}

			var prioridad_input = document.getElementById("prioridad");
			var prioridad_array = [];
			var prioridad_array_table = [];

			// Recorrer las opciones del select y procesar las seleccionadas
			if (prioridad_input && prioridad_input.options) {
				for (var option of prioridad_input.selectedOptions) {
					if (option.value.trim() !== "") {
						prioridad_array.push(option.value.trim());
						prioridad_array_table.push("(^" + option.value.trim() + "$)");
					}
				}
			} else {
				//console.warn("El elemento 'prioridad' no es válido o no contiene opciones.");
			}

			// Unir los valores seleccionados en una cadena
			var prioridad = prioridad_array.join(",");

			// Aplicar el filtro en DataTables
			if (prioridad === "") {
				$('#secciones_reportes-tabla').DataTable().column(13).search("").draw();
			} else {
				$('#secciones_reportes-tabla')
					.DataTable()
					.column(13)
					.search(prioridad_array_table.join('|'), true, false)
					.draw();
			}
			//! Buscador
			var searchTable = [];
			var data = {
					'id_seccion_ine' : id_seccion_ine,
					'id_municipio' : <?= $id_municipio ?>,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'partido_ganador_individual' : partido_ganador_individual,
					'partido_ganador_coalicion' : partido_ganador_coalicion,
					'semaforo_individual' : semaforo_individual,
					'semaforo_coalicion' : semaforo_coalicion,
					'tipo_seccion' : tipo_seccion,
					'prioridad' : prioridad,
				}
			searchTable.push(data);

			var secciones_ine_agendas_gobierno_input = document.getElementById("secciones_ine_agendas_gobierno");
			var secciones_ine_agendas_gobierno_array = [];
			var secciones_ine_agendas_gobierno_array_table = [];
			for (var i = 0; i < secciones_ine_agendas_gobierno_input.length; i++) {
				if (secciones_ine_agendas_gobierno_input.options[i].selected){
					secciones_ine_agendas_gobierno_array.push(secciones_ine_agendas_gobierno_input.options[i].value);
				}
			}
			secciones_ine_agendas_gobierno = secciones_ine_agendas_gobierno_array.join(",");

			var secciones_ine_actividades_input = document.getElementById("secciones_ine_actividades");
			var secciones_ine_actividades_array = [];
			var secciones_ine_actividades_array_table = [];
			for (var i = 0; i < secciones_ine_actividades_input.length; i++) {
				if (secciones_ine_actividades_input.options[i].selected){
					secciones_ine_actividades_array.push(secciones_ine_actividades_input.options[i].value);
				}
			}
			secciones_ine_actividades = secciones_ine_actividades_array.join(",");

			var tipo_semaforo = document.getElementById("tipo_semaforo").value;

			var mapa = [];
			var data = {   
					'id_seccion_ine' : id_seccion_ine,
					'id_municipio' : <?= $id_municipio ?>,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'partido_ganador_individual' : partido_ganador_individual,
					'partido_ganador_coalicion' : partido_ganador_coalicion,
					'semaforo_individual' : semaforo_individual,
					'semaforo_coalicion' : semaforo_coalicion,
					'tipo' : tipo_seccion,
					'prioridad' : prioridad,
					'secciones_ine_agendas_gobierno' : secciones_ine_agendas_gobierno,
					'secciones_ine_actividades' : secciones_ine_actividades,
					'tipo_semaforo' : tipo_semaforo
				}
			mapa.push(data);
			if(value != 'pagina'){
				document.getElementById("pagina_valor").value = 1
				$.ajax({
					type: "POST",
					url: "seccionesIneReportes2024MatrizRentabilidad/senador/mapa.php",
					data: {searchTable: searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			}

			


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
	<div class="sucForm" style="display:none">
		<label class="labelForm" id="labeltemaname">Municipio<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_municipio" onchange="searchTable();">
			<?php
			echo municipios('',$id_estado);
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Disitritos Locales<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_local" onchange="searchTable();">
			<?php
			echo seccion_ineDistritosLocales('',$id_municipio,'','','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Disitritos Federales<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_distrito_federal" onchange="searchTable();">
			<?php
			echo seccion_ineDistritosFederales('',$id_municipio,'','','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Colonias<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_secciones_ine_colonia" onchange="searchTable();">
			<?php
			echo secciones_ine_colonias('','',$id_municipio,'','','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm" >
		<label class="labelForm" id="labeltemaname">Localidades<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_secciones_ine_localidad" onchange="searchTable();">
			<?php
			echo localidades_secciones_ineIdSecciones('',$id_estado,$id_municipio,'','');
			?>
		</select><br>
	</div>
	<div class="sucForm"">
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('',$id_municipio,'','','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Partidos Mayoría</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="partido_ganador_individual" onchange="searchTable();">
			<option value="No Data">No Data</option>
			<?php
			echo partidos_2024Nombres('',$tipo.'','sin_coalicion','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Coalición Mayoría</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="partido_ganador_coalicion" onchange="searchTable();">
			<option value="No Data">No Data</option>
			<?php
			foreach ($partidos_rec[2] as $key => $value) {
				?>
				<option value="<?= $key ?>"><?= $key ?></option>
				<?php
			}
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Semáforo Individual</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="semaforo_individual" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="VERDE">Verde</option>
			<option value="AMARILLO">Amarillo</option>
			<option value="ROJO">Rojo</option>
			<option value="GRIS">Gris</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Semáforo Coalición</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="semaforo_coalicion" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="VERDE">Verde</option>
			<option value="AMARILLO">Amarillo</option>
			<option value="ROJO">Rojo</option>
			<option value="GRIS">Gris</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Sección</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_seccion" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="Urbana">Urbana</option>
			<option value="Rural">Rural</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Prioridad</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="prioridad" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="A">A</option>
			<option value="B">B</option>
			<option value="C">C</option>
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