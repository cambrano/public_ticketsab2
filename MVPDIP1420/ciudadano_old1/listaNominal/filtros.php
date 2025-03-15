<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	unset($_SESSION['searchTable']);
?>
	<script type="text/javascript">
		function searchTable(){
			var clave_elector = document.getElementById("clave_elector").value;
			var curp = document.getElementById("curp").value;
			var nombre = document.getElementById("nombre").value;
			var apellido_paterno = document.getElementById("apellido_paterno").value;
			var apellido_materno = document.getElementById("apellido_materno").value;
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_localidad = document.getElementById("id_localidad").value;
			var militante_partido = document.getElementById("militante_partido").value;
			var searchTable = [];
			var data = {
				'clave_elector' : clave_elector,
				'curp' : curp,
				'nombre' : nombre,
				'apellido_paterno' : apellido_paterno,
				'apellido_materno' : apellido_materno,
				'id_seccion_ine' : id_seccion_ine,
				'id_municipio' : id_municipio,
				'id_localidad' : id_localidad,
				'militante_partido' : militante_partido,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "listaNominal/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
		function locationMunicipio() {
			var id_estado = '<?= $id_estado ?>';
			var id_municipio = document.getElementById("id_municipio").value;
			var id_municipio = id_municipio.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("id_municipio").value=id_municipio;
			if(id_municipio == ""){
				document.getElementById("id_municipio").style.border= "1px solid red";
				document.getElementById("id_localidad").style.border= "";
				document.getElementById("id_localidad").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
			}else{
				document.getElementById("id_municipio").style.border= "";
				document.getElementById("id_localidad").style.border= "";
				var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio+'&tipo=municipio_array';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
			}
			searchTable();
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sección</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('','','','','SIN');
			?>
		</select>
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
		<label class="labelForm" id="labeltemaname">Nombre</label><br>
		<input data-column="1" id="nombre" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
		<input data-column="1" id="apellido_paterno" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
		<input data-column="1" id="apellido_materno" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Militante Partido</label><br>
		<input data-column="1" id="militante_partido" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>

	
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Municipio</label><br>
		<select name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">
			<?php
			echo municipios($id_municipio,$id_estado,'');
			?>
		</select>
	</div>
 
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Localidad</label><br>
		<select name="id_localidad" id="id_localidad" class='myselect' onchange="searchTable();">  
			<?php
			echo localidades();
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
		$(".myselect").select2();
		$('.selectpicker').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>