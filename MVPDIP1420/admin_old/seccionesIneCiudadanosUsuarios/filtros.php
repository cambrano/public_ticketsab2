<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	unset($_SESSION['searchTable']);
?>
	<script type="text/javascript">
		function searchTable(){
			var clave = document.getElementById("clave").value;
			var clave_elector = document.getElementById("clave_elector").value;
			var nombre_completo = document.getElementById("nombre_completo").value;
			var usuario = document.getElementById("usuario").value;
			var searchTable = [];
			var data = {
				'clave' : clave,
				'clave_elector' : clave_elector,
				'nombre_completo' : nombre_completo,
				'usuario' : usuario,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosUsuarios/table.php",
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
		<input data-column="1" id="clave" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave Elector</label><br>
		<input data-column="1" id="clave_elector" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre Completo</label><br>
		<input data-column="1" id="nombre_completo" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Usuario</label><br>
		<input data-column="1" id="usuario" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
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