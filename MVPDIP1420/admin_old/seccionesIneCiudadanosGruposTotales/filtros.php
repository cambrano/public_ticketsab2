<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/status.php";
	@session_start();
	unset($_SESSION['searchTable']);
?>
	<script type="text/javascript">
		function searchTable(value){
			var clave = document.getElementById("clave").value;
			var folio = document.getElementById("folio").value;
			var clave_elector = document.getElementById("clave_elector").value;
			var nombre = document.getElementById("nombre").value;
			var apellido_paterno = document.getElementById("apellido_paterno").value;
			var apellido_materno = document.getElementById("apellido_materno").value;
			var id_tipo_nombramiento = document.getElementById("id_tipo_nombramiento").value;
			var status = document.getElementById("status").value;
			if(status=='x'){
				status=0;
			}
			var searchTable = [];
			var data = {
					'clave' : clave,
					'folio' : folio,
					'clave_elector' : clave_elector,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'id_tipo_nombramiento' : id_tipo_nombramiento,
					'status' : status,
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosGruposTotales/table.php",
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
		<label class="labelForm" id="labeltemaname">Folio</label><br>
		<input data-column="0" id="folio" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave Elector</label><br>
		<input data-column="0" id="clave_elector" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>
	<div class="sucForm" style="width:100%"></div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre(s)</label><br>
		<input data-column="0" id="nombre" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
		<input data-column="0" id="apellido_paterno" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
		<input data-column="0" id="apellido_materno" autocomplete="off" type="text" onkeyup ="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Nombramiento</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_nombramiento" onchange="searchTable();">
		<?php
		echo tipos_nombramientos();
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