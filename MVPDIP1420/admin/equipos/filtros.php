<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(){
			document.getElementById("botonEquipos").disabled = true;
			var id_tipo_equipo = document.getElementById("id_tipo_equipo").value;
			var clave = document.getElementById("clave").value;
			var folio = document.getElementById("folio").value;
			var procesador = document.getElementById("procesador").value;
			var ram = document.getElementById("ram").value;
			var modelo = document.getElementById("modelo").value;
			var marca = document.getElementById("marca").value;
			var searchTable = [];
			var data = {
				'id_tipo_equipo' : id_tipo_equipo,
				'clave' : clave,
				'folio' : folio,
				'procesador' : procesador,
				'ram' : ram,
				'modelo' : modelo,
				'marca' : marca,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "equipos/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					document.getElementById("botonEquipos").disabled = false;
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm" style="display:none">
		<label class="labelForm" id="labeltemaname">Sistema Operativo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_sistema_operativo">
			<?php
			echo sistemas_operativos();
			?>
		</select>
	</div>
	<div class="sucForm" style="display:none">
		<label class="labelForm" id="labeltemaname">Software</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_software">
			<?php
			echo softwares();
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Equipo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_equipo">
			<?php
			echo tipos_equipos();
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave</label><br>
		<input data-column="1" id="clave" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Folio</label><br>
		<input data-column="1" id="folio" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Procesador</label><br>
		<input data-column="1" id="procesador" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Memoria RAM(GB)</label><br>
		<input data-column="1" id="ram" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Modelo</label><br>
		<input data-column="1" id="modelo" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Marca</label><br>
		<input data-column="1" id="marca" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm">
		<input type="button" id="botonEquipos" onclick="searchTable()" value="Buscar Equipo">
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