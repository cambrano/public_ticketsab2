<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(){
			document.getElementById("botonEmpleados").disabled = true;
			var clave = document.getElementById("clave").value;
			var id_dependencia = document.getElementById("id_dependencia").value;
			var id_sub_dependencia = document.getElementById("id_sub_dependencia").value;
			var apellido_paterno = document.getElementById("apellido_paterno").value;
			var apellido_materno = document.getElementById("apellido_materno").value;
			var nombre = document.getElementById("nombre").value;
			var searchTable = [];
			var data = {
				'clave' : clave,
				'id_dependencia' : id_dependencia,
				'id_sub_dependencia' : id_sub_dependencia,
				'apellido_paterno' : apellido_paterno,
				'apellido_materno' : apellido_materno,
				'nombre' : nombre,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "directorios/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					document.getElementById("botonEmpleados").disabled = false;
					$("#dataTable").html(data);
				}
			});
		}
		function subdependencias(valor){
			id_dependencia = valor;
			if(id_dependencia == ""){
				var dataString = 'id_dependencia=x&tipo=list_select';
				$.ajax({
					type: "POST",
					url: "subDependencias/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_sub_dependencia").html(data);
						$("#id_sub_dependencia").selectpicker('refresh');
					}
				});
			}else{
				var dataString = 'id_dependencia='+id_dependencia+'&tipo=list_select';
				$.ajax({
					type: "POST",
					url: "subDependencias/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_sub_dependencia").html(data);
						$("#id_sub_dependencia").selectpicker('refresh');
					}
				});
			}
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave</label><br>
		<input data-column="1" id="clave" autocomplete="off" type="text"> <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Dependencias</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_dependencia"  onchange="subdependencias(this.value)">
			<?php
			echo dependencias('','');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sub Dependencias</label><br>
		<select class="selectpicker"  data-size="5" data-actions-box="true" title="Seleccione" id="id_sub_dependencia" >
			
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
		<input data-column="1" id="apellido_paterno" autocomplete="off" type="text"> <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
		<input data-column="1" id="apellido_materno" autocomplete="off" type="text"> <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre</label><br>
		<input data-column="1" id="nombre" autocomplete="off" type="text"> <br>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm">
		<input type="button" id="botonEmpleados" onclick="searchTable()" value="Buscar Empleado">
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
		$('.selectpicker').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>