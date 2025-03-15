<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	@session_start();
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_nacimiento_1" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			});
			$( "#fecha_nacimiento_2" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			}); 
		});
		function searchTable(value){
			var correos_electronicos = document.getElementById("correos_electronicos").value;
			var cuentas_redes_sociales = document.getElementById("cuentas_redes_sociales").value;
			var documentos_oficiales = document.getElementById("documentos_oficiales").value;
			var tipo = document.getElementById("tipo").value;

			var clave = document.getElementById("clave").value;
			var sexo = document.getElementById("sexo").value;
			var nombre_identidad = document.getElementById("nombre_identidad").value;
			var clave_elector = document.getElementById("clave_elector").value;
			var curp = document.getElementById("curp").value;
			var rfc = document.getElementById("rfc").value;
			var id_estado = document.getElementById("id_estado").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_localidad = document.getElementById("id_localidad").value;

			var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
			var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;


			if(value==1){
				var dataString = 'id_estado='+id_estado;
				$.ajax({
					type: "POST",
					url: "municipios/ajax.php",
					data: dataString,
					success: function(data) {
						//$("#id_municipio").html(data); 
						$('#id_municipio').append(data);
						$("#id_municipio").selectpicker("refresh");
					}
				});
				var dataString = 'id_estado='+id_estado;
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						//$("#id_localidad").html(data);
						$('#id_localidad').append(data);
						$("#id_localidad").selectpicker("refresh");
					}
				});
				var id_municipio =""; 
				var id_localidad =""; 
			}
			if(value==2){
				var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio;
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						//$("#id_localidad").html(data);
						$('#id_localidad').append(data);
						$("#id_localidad").selectpicker("refresh");
					}
				});
				var id_localidad =""; 
			} 

			var searchTable = [];
			var data = {
					'correos_electronicos' : correos_electronicos,
					'cuentas_redes_sociales' : cuentas_redes_sociales,
					'documentos_oficiales' : documentos_oficiales,
					'tipo' : tipo,

					'clave' : clave, 
					'sexo' : sexo,
					'nombre_identidad' : nombre_identidad, 
					'clave_elector' : clave_elector,  
					'curp' : curp,
					'rfc' : rfc,

					'id_estado' : id_estado,  
					'id_municipio' : id_municipio,  
					'id_localidad' : id_localidad,

					'fecha_nacimiento_1' : fecha_nacimiento_1,  
					'fecha_nacimiento_2' : fecha_nacimiento_2,
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "identidades/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">C.Eléctronicos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="correos_electronicos" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="si">SI</option>
			<option value="no">NO</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">R.Sociales</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="cuentas_redes_sociales" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="si">SI</option>
			<option value="no">NO</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Documentos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="documentos_oficiales" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="si">SI</option>
			<option value="no">NO</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="real">Real</option>
			<option value="falso">Falso</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">clave</label><br>
		<input data-column="0" id="clave" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sexo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="sexo" onchange="searchTable();">
			<option selected="selected" value="">Seleccione</option>
			<option value="Mujer">Mujer</option>
			<option value="Hombre">Hombre</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre Completo</label><br>
		<input data-column="1" id="nombre_identidad" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Nacimiento(1)</label><br>
		<input id="fecha_nacimiento_1" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Nacimiento(2)</label><br>
		<input id="fecha_nacimiento_2" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave Elector</label><br>
		<input data-column="1" id="clave_elector" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">C.U.R.P</label><br>
		<input data-column="1" id="curp" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">R.F.C</label><br>
		<input data-column="1" id="rfc" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>


	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>


	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Estado</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_estado" onchange="searchTable(1);">
			<?php
			echo estados('','','');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Municipio</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_municipio" onchange="searchTable(2);">
			<?php
			echo municipios('','','');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Localidad</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_localidad" onchange="searchTable(3);">
			<?php
			echo localidades('','','','');
			?>
		</select>
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
		$(".myselect").selectpicker({});
		$('select').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>