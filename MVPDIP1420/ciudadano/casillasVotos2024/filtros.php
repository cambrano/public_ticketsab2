<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(){
			var clave = document.getElementById("clave").value;
			var id_municipio = document.getElementById("id_municipio").value;
			var id_distrito_local = document.getElementById("id_distrito_local").value;
			var id_distrito_federal = document.getElementById("id_distrito_federal").value;
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;
			var searchTable = [];
			var data = {
				'clave' : clave,
				'id_municipio' : id_municipio,
				'id_distrito_local' : id_distrito_local,
				'id_distrito_federal' : id_distrito_federal,
				'id_seccion_ine' : id_seccion_ine,
			}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2024/table.php",
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
		<label class="labelForm" id="labeltemaname">Municipio<br></label><br>
		<select class="myselect" id="id_municipio" onchange="searchTable();">
			<?php
			echo municipios('',31);
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Distrito Local<br></label><br>
		<select class="myselect" id="id_distrito_local" onchange="searchTable();">
			<?php
			echo distritos_locales();
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Distrito Federal<br></label><br>
		<select class="myselect" id="id_distrito_federal" onchange="searchTable();">
			<?php
			echo distritos_federales();
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="myselect" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine();
			?>
		</select><br>
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>