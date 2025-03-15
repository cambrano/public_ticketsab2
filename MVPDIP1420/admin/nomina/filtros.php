<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(){
			var nombre = document.getElementById("nombre").value;
			var primer_apellido = document.getElementById("primer_apellido").value;
			var segundo_apellido = document.getElementById("segundo_apellido").value;
			var tipo_integrante_sujeto_obligado = document.getElementById("tipo_integrante_sujeto_obligado").value;
			var denominacion_puesto = document.getElementById("denominacion_puesto").value;
			var area_adscripcion = document.getElementById("area_adscripcion").value;
			var areas_responsables = document.getElementById("areas_responsables").value;
			
			
			
			var searchTable = [];
			var data = {
				'nombre' : nombre,
				'primer_apellido' : primer_apellido,
				'segundo_apellido' : segundo_apellido,
				'tipo_integrante_sujeto_obligado' : tipo_integrante_sujeto_obligado,
				'denominacion_puesto' : denominacion_puesto,
				'area_adscripcion' : area_adscripcion,
				'areas_responsables' : areas_responsables
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "nomina/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre</label><br>
		<input data-column="1" id="nombre" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Primer Apellido</label><br>
		<input data-column="1" id="primer_apellido" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Segundo Apellido</label><br>
		<input data-column="1" id="segundo_apellido" autocomplete="off" type="text" onkeyup="searchTable();" > <br>
	</div>
	<div class="sucForm" style="width:100%"></div>
	
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Denominación Puesto<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="denominacion_puesto" onchange="searchTable();">
			<?php
			$sql = "SELECT denominacion_puesto FROM nomina GROUP BY denominacion_puesto ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				echo "<option ".$select[$sel]." value='".$row['denominacion_puesto']."' >".$row['denominacion_puesto']."</option> ";
			} 
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Área Adscripción<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="area_adscripcion" onchange="searchTable();">
			<?php
			$sql = "SELECT area_adscripcion FROM nomina GROUP BY area_adscripcion ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				echo "<option ".$select[$sel]." value='".$row['area_adscripcion']."' >".$row['area_adscripcion']."</option> ";
			} 
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Área Responsables<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="areas_responsables" onchange="searchTable();">
			<?php
			$sql = "SELECT areas_responsables FROM nomina GROUP BY areas_responsables ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				echo "<option ".$select[$sel]." value='".$row['areas_responsables']."' >".$row['areas_responsables']."</option> ";
			} 
			?>
		</select><br>
	</div>
	
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Integrante Sujeto Obligado<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_integrante_sujeto_obligado" onchange="searchTable();">
			<?php
			$sql = "SELECT tipo_integrante_sujeto_obligado FROM nomina GROUP BY tipo_integrante_sujeto_obligado ";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				echo "<option ".$select[$sel]." value='".$row['tipo_integrante_sujeto_obligado']."' >".$row['tipo_integrante_sujeto_obligado']."</option> ";
			} 
			?>
		</select><br>
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