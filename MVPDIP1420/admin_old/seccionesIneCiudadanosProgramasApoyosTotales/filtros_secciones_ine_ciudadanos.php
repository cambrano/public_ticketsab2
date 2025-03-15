<?php
	include __DIR__."/../functions/security.php";
	@session_start();
	unset($_SESSION['searchTable']);
?>
	<script type="text/javascript">

		function asignarClaveElector(valor){
			document.getElementById("clave_elector").value = valor;
			buscar_clave_electoral();
		}

		function searchTable(){
			$(".loader").fadeIn(10);
			var espacios_invalidos= /\s+/g;
			var estatus = 0;
			var clave_elector = document.getElementById("lista_clave_elector").value;
			clave_elector = clave_elector.replace(espacios_invalidos, '');
			if(clave_elector != ""){
				estatus = 1;
			}
			var curp = document.getElementById("lista_curp").value;
			curp = curp.replace(espacios_invalidos, '');
			if(curp != ""){
				estatus = 1;
			}
			var nombre = document.getElementById("lista_nombre").value;
			nombre = nombre.trim();
			if(nombre != ""){
				estatus = 1;
			}
			var apellido_paterno = document.getElementById("lista_apellido_paterno").value;
			apellido_paterno = apellido_paterno.trim();
			if(apellido_paterno != ""){
				estatus = 1;
			}
			var apellido_materno = document.getElementById("lista_apellido_materno").value;
			apellido_materno = apellido_materno.trim();
			if(apellido_materno != ""){
				estatus = 1;
			}
			if(estatus == 0){
				return false;
			}
			var searchTable = [];
			var data = {
				'curp' : curp,
				'clave_elector' : clave_elector,
				'nombre' : nombre,
				'apellido_paterno' : apellido_paterno,
				'apellido_materno' : apellido_materno,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosProgramasApoyosTotales/table_secciones_ine_ciudadanos.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave Elector</label><br>
		<input data-column="1" id="lista_clave_elector" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">C.U.R.P</label><br>
		<input data-column="1" id="lista_curp" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre</label><br>
		<input data-column="1" id="lista_nombre" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
		<input data-column="1" id="lista_apellido_paterno" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
		<input data-column="1" id="lista_apellido_materno" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm">
		<input type="button" onclick="searchTable()" value="Buscar Registros">
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