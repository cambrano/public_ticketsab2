<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function asignarClaveElector(valor){
			document.getElementById("buscardorAmigo").style.display='none';
			document.getElementById("clave_elector").value = valor;
			buscar_clave_electoral();
		}
		function borrarCamposLista(){
			document.getElementById("lista_id_seccion_ine").value = '';
			$('#lista_id_seccion_ine').val('');
			$('#lista_id_seccion_ine').select2().trigger('change');
			document.getElementById("lista_clave_elector").value = '';
			document.getElementById("lista_curp").value = '';
			document.getElementById("lista_nombre").value = '';
			document.getElementById("lista_apellido_paterno").value = '';
			document.getElementById("lista_apellido_materno").value = '';
			document.getElementById("lista_fecha_nacimiento_ano").value = '';
			document.getElementById("lista_fecha_nacimiento_mes").value = '';
			document.getElementById("lista_fecha_nacimiento_dia").value = '';
		}
		function searchTable(){
			document.getElementById("botonListaNominal").disabled = true;
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
			var id_seccion_ine = document.getElementById("lista_id_seccion_ine").value;
			if(id_seccion_ine != ""){
				estatus = 1;
			}
			var fecha_nacimiento_ano = document.getElementById("lista_fecha_nacimiento_ano").value;
			fecha_nacimiento_ano = fecha_nacimiento_ano.trim();
			if(fecha_nacimiento_ano != ""){
				estatus = 1;
			}

			var fecha_nacimiento_mes = document.getElementById("lista_fecha_nacimiento_mes").value;
			if(fecha_nacimiento_mes != ""){
				estatus = 1;
			}
			var fecha_nacimiento_dia = document.getElementById("lista_fecha_nacimiento_dia").value;
			if(fecha_nacimiento_dia != ""){
				estatus = 1;
			}
			if(estatus == 0){
				document.getElementById("botonListaNominal").disabled = false;
				return false;
			}
			$(".loader").fadeIn(10);
			var searchTable = [];
			var data = {
				'clave_elector' : clave_elector,
				'curp' : curp,
				'nombre' : nombre,
				'apellido_paterno' : apellido_paterno,
				'apellido_materno' : apellido_materno,
				'id_seccion_ine' : id_seccion_ine,
				'fecha_nacimiento_ano' : fecha_nacimiento_ano,
				'fecha_nacimiento_mes' : fecha_nacimiento_mes,
				'fecha_nacimiento_dia' : fecha_nacimiento_dia,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/table_lista_nominal.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					document.getElementById("botonListaNominal").disabled = false;
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sección</label><br>
		<select class="myselect" id="lista_id_seccion_ine" >
			<?php
			echo secciones_ine('','','','','');
			?>
		</select>
	</div>
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
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Año Nacimiento</label><br>
		<input data-column="1" id="lista_fecha_nacimiento_ano" autocomplete="off" type="text" onkeypress="return CheckNumeric()"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Mes Nacimiento</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="lista_fecha_nacimiento_mes" >
			<option selected="selected" value="">Seleccione</option>
			<option value="01" >Enero</option>
			<option value="02" >Febrero</option>
			<option value="03" >Marzo</option>
			<option value="04" >Abril</option>
			<option value="05" >Mayo</option>
			<option value="06" >Junio</option>
			<option value="07" >Julio</option>
			<option value="08" >Agosto</option>
			<option value="09" >Septiembre</option>
			<option value="10" >Octubre</option>
			<option value="11" >Noviembre</option>
			<option value="12" >Diciembre</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Día Nacimiento</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="lista_fecha_nacimiento_dia" >
			<option selected="selected" value="">Seleccione</option>
			<option value="01" >01</option>
			<option value="02" >02</option>
			<option value="03" >03</option>
			<option value="04" >04</option>
			<option value="05" >05</option>
			<option value="06" >06</option>
			<option value="07" >07</option>
			<option value="08" >08</option>
			<option value="09" >09</option>
			<option value="10" >10</option>
			<option value="11" >11</option>
			<option value="12" >12</option>
			<option value="13" >13</option>
			<option value="14" >14</option>
			<option value="15" >15</option>
			<option value="16" >16</option>
			<option value="17" >17</option>
			<option value="18" >18</option>
			<option value="19" >19</option>
			<option value="20" >20</option>
			<option value="21" >21</option>
			<option value="22" >22</option>
			<option value="23" >23</option>
			<option value="24" >24</option>
			<option value="25" >25</option>
			<option value="26" >26</option>
			<option value="27" >27</option>
			<option value="28" >28</option>
			<option value="29" >29</option>
			<option value="30" >30</option>
			<option value="31" >31</option>
		</select>
	</div>
	<div class="sucForm" style="width: 100%"></div>
	<div class="sucForm">
		<input type="button" id="botonListaNominal" onclick="searchTable()" value="Buscar Registros">
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