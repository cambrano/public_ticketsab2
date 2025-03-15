<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<style type="text/css">
		.semaforo_red{
			display: inline-block;
		    min-width: 10px;
		    padding: 3px 7px;
		    font-size: 12px;
		    font-weight: 700;
		    line-height: 1;
		    color: #fff;
		    text-align: center;
		    white-space: nowrap;
		    vertical-align: middle;
		    background-color: red;
		    border-radius: 10px;
		}
		.semaforo_yellow{
			display: inline-block;
		    min-width: 10px;
		    padding: 3px 7px;
		    font-size: 12px;
		    font-weight: 700;
		    line-height: 1;
		    color: #191919;
		    text-align: center;
		    white-space: nowrap;
		    vertical-align: middle;
		    background-color: yellow;
		    border-radius: 10px;
		}
		.semaforo_green{
			display: inline-block;
		    min-width: 10px;
		    padding: 3px 7px;
		    font-size: 12px;
		    font-weight: 700;
		    line-height: 1;
		    color: #fff;
		    text-align: center;
		    white-space: nowrap;
		    vertical-align: middle;
		    background-color: green;
		    border-radius: 10px;
		}
	</style>
	<script type="text/javascript">
		function searchTable(){
			var codigo = document.getElementById("codigo").value;
			var tipo_candidato = document.getElementById("tipo_candidato").value;


			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");
			var status = document.getElementById("status").value;
			var semaforo = document.getElementById("semaforo").value;

			var searchTable = [];
			var data = {
				'codigo' : codigo,
				'tipo_candidato' : tipo_candidato,
				'id_seccion_ine' : id_seccion_ine,
				'status' : status,
				'semaforo' : semaforo,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2024IncidenciasReportes/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Código</label><br>
		<input data-column="1" id="codigo" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm" style="<?= $mostrar_all == 1 ? '' :'display: none;'  ?>">
		<label class="labelForm" id="labeltemaname">Tipo Candidato</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_candidato" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="0">Municipal</option>
			<option value="1">Distrito Local</option>
			<option value="2">Distrito Federal</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('',$id_municipio,$id_distrito_local,$id_distrito_federal,'SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Semáforo</label><br>
		<!--<select class="myselect" id="status" >-->
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" id="semaforo" onchange="searchTable();">
			<option <?= $selecTipoSeccion ?> value="">Seleccione</option> 
			<option data-content="<span class='semaforo_green'>Verde</span>" <?= $selecTipoSeccion['1'] ?> value="1" >Verde</option>
			<option data-content="<span class='semaforo_yellow'>Amarillo</span>" <?= $selecTipoSeccion['2'] ?> value="2" >Amarillo</option>
			<option data-content="<span class='semaforo_red'>Rojo</span>" <?= $selecTipoSeccion['3'] ?> value="3" >Rojo</option>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Estatus<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Tipo Selección" id="status" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="1">Atendido</option> 
			<option value="0">Pendiente</option> 
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
		$('select').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>