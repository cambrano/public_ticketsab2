<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function asignarFolio(valor){
			let [folio, clave] = valor.split(',');
			document.getElementById("buscardorEquipo").style.display='none';
			document.getElementById("folio").value = folio;
			document.getElementById("clave").value = clave;
			buscarFolio(clave);
		}
		function buscarFolio(value){
			var dataString = 'clave='+value;
			$.ajax({
				type: "POST",
				url: "equiposDirectorios/search_equipo.php",
				data: dataString,
				dataType: 'json',
				success: function(data) {
					//console.log(data);
					//console.table(data);
					//$("#id").html(data.id);
					document.getElementById("id_equipo").value = data.id_equipo;
					$("#busqueda_clave").html(data.clave);
					$("#busqueda_folio").html(data.folio);
					$("#busqueda_ubicacion").html(data.ubicacion);
					$("#busqueda_tipo_equipo").html(data.tipo_equipo);
					$("#busqueda_marca").html(data.marca);
					$("#busqueda_modelo").html(data.modelo);
					$("#busqueda_ram").html(data.ram);
					$("#busqueda_procesador").html(data.procesador);
				}
			});
		}
		function borrarCamposLista(){
			document.getElementById("lista_id_sistema_operativo").value = '';
			$('#lista_id_sistema_operativo').val('');
			$('#lista_id_sistema_operativo').select2().trigger('change');
			
			document.getElementById("lista_id_software").value = '';
			$('#lista_id_software').val('');
			$('#lista_id_software').select2().trigger('change');

			document.getElementById("lista_id_tipo_equipo").value = '';
			$('#lista_id_tipo_equipo').val('');
			$('#lista_id_tipo_equipo').select2().trigger('change');

			document.getElementById("lista_clave").value = '';
			document.getElementById("lista_folio").value = '';
			document.getElementById("lista_procesador").value = '';
			document.getElementById("lista_ram").value = '';
			document.getElementById("lista_modelo").value = '';
			document.getElementById("lista_marca").value = '';
		}
		function searchTable(){
			document.getElementById("botonEquipos").disabled = true;
			var espacios_invalidos= /\s+/g;
			var estatus = 0;
			/*
			var lista_id_sistema_operativo = document.getElementById("lista_id_sistema_operativo").value;
			lista_id_sistema_operativo = lista_id_sistema_operativo.replace(espacios_invalidos, '');
			if(lista_id_sistema_operativo != ""){
				estatus = 1;
			}
			var lista_id_software = document.getElementById("lista_id_software").value;
			lista_id_software = lista_id_software.replace(espacios_invalidos, '');
			if(lista_id_software != ""){
				estatus = 1;
			}
			*/
			var lista_id_tipo_equipo = document.getElementById("lista_id_tipo_equipo").value;
			lista_id_tipo_equipo = lista_id_tipo_equipo.trim();
			if(lista_id_tipo_equipo != ""){
				estatus = 1;
			}
			var lista_clave = document.getElementById("lista_clave").value;
			lista_clave = lista_clave.trim();
			if(lista_clave != ""){
				estatus = 1;
			}
			var lista_folio = document.getElementById("lista_folio").value;
			lista_folio = lista_folio.trim();
			if(lista_folio != ""){
				estatus = 1;
			}
			var lista_procesador = document.getElementById("lista_procesador").value;
			if(lista_procesador != ""){
				estatus = 1;
			}
			var lista_ram = document.getElementById("lista_ram").value;
			lista_ram = lista_ram.trim();
			if(lista_ram != ""){
				estatus = 1;
			}

			var lista_modelo = document.getElementById("lista_modelo").value;
			if(lista_modelo != ""){
				estatus = 1;
			}
			var lista_marca = document.getElementById("lista_marca").value;
			if(lista_marca != ""){
				estatus = 1;
			}
			$(".loader").fadeIn(10);
			var searchTable = [];
			var data = {
				//'id_sistema_operativo' : lista_id_sistema_operativo,
				//'id_software' : lista_id_software,
				'id_tipo_equipo' : lista_id_tipo_equipo,
				'clave' : lista_clave,
				'folio' : lista_folio,
				'procesador' : lista_procesador,
				'ram' : lista_ram,
				'modelo' : lista_modelo,
				'marca' : lista_marca,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "equiposDirectorios/table_equipos.php",
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
		<select class="myselect" id="lista_id_sistema_operativo" >
			<?php
			echo sistemas_operativos();
			?>
		</select>
	</div>
	<div class="sucForm" style="display:none">
		<label class="labelForm" id="labeltemaname">Software</label><br>
		<select class="myselect" id="lista_id_software" >
			<?php
			echo softwares();
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Equipo</label><br>
		<select class="myselect" id="lista_id_tipo_equipo" >
			<?php
			echo tipos_equipos();
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave</label><br>
		<input data-column="1" id="lista_clave" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Folio</label><br>
		<input data-column="1" id="lista_folio" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Procesador</label><br>
		<input data-column="1" id="lista_procesador" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Memoria RAM(GB)</label><br>
		<input data-column="1" id="lista_ram" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Modelo</label><br>
		<input data-column="1" id="lista_modelo" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Marca</label><br>
		<input data-column="1" id="lista_marca" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm">
		<input type="button" id="botonEquipos" onclick="searchTable()" value="Buscar Equipo">
		<input type="button" onclick="borrarCamposLista()" value="Borrar Campos">
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