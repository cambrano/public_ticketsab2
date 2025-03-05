<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones.php";
	include __DIR__."/../functions/modulos.php";
	include __DIR__."/../functions/permisos.php";
	@session_start();
	
?>
	<script type="text/javascript">
		function secciones(id_seccion){
			var dataString = 'id_seccion='+id_seccion;
			$.ajax({
				type: "POST",
				url: "modulos/ajax.php",
				data: dataString,
				success: function(data) {
					//$("#id_modulo").html(data);
					$('#id_modulo').append(data);
					$("#id_modulo").selectpicker("refresh");
				}
			});
			var id_seccion = id_seccion;
			var id_modulo = document.getElementById("id_modulo").value;
			var id_permiso = document.getElementById("id_permiso").value; 
		}
		function modulos(id_modulo){
			var dataString = 'id_modulo='+id_modulo+'&tipo=select';
			$.ajax({
				type: "POST",
				url: "permisos/ajax.php",
				data: dataString,
				success: function(data) {
					//$("#id_permiso").html(data);
					$('#id_permiso').append(data);
					$("#id_permiso").selectpicker("refresh");
				}
			});
		}
		function searchTable(value){
			var id_seccion = document.getElementById("id_seccion").value;
			var dataString = 'id_seccion='+id_seccion;
			if(value==1){
				$.ajax({
					type: "POST",
					url: "modulos/ajax.php",
					data: dataString,
					success: function(data) {
						//$("#id_modulo").html(data);
						$('#id_modulo').append(data);
						$("#id_modulo").selectpicker("refresh");

					}
				});
			}

			var id_modulo = document.getElementById("id_modulo").value;
			var dataString = 'id_modulo='+id_modulo+'&tipo=select';
			if(value==2){
				$.ajax({
					type: "POST",
					url: "permisos/ajax.php",
					data: dataString,
					success: function(data) {
						//$("#id_permiso").html(data);
						$('#id_permiso').append(data);
						$("#id_permiso").selectpicker("refresh");
					}
				});
			}

			var id_permiso = document.getElementById("id_permiso").value;
			var searchTable = [];
			var data = {   
					'id_seccion' : id_seccion, 
					'id_modulo' : id_modulo, 
					'id_permiso' : id_permiso, 
				}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "usuarioPermisos/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Seccion</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion" onchange="searchTable(1);">
		<?php
			echo secciones();
		?>
		</select><br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Modulos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_modulo" onchange="searchTable(2);">
		<?php
			//echo modulos();
		?>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Permisos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_permiso" >
		<?php
			//echo permisos();
		?>
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
		$(".myselect").selectpicker({});
		$('select').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>