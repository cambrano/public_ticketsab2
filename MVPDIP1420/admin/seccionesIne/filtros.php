<?php
	include __DIR__."/../functions/security.php"; 
	@session_start(); 
?>
	<script type="text/javascript">
		 
		function searchTable(value){
			var clave = document.getElementById("clave").value;
			var numero = document.getElementById("numero").value;
			var searchTable = [];
			var data = {
					'clave' : clave,
					'numero' : numero,
				}
			searchTable.push(data);
			var mapa = [];
			var data = {   
					'clave' : clave,
					'numero' : numero,
				}
			mapa.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIne/table.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
			$.ajax({
				type: "POST",
				url: "seccionesIne/mapa.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#mapaLoad").html(data);
				}
			});
		}
	</script> 
 

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave</label><br>
		<input data-column="5" id="clave" autocomplete="off" type="text" onkeyup="searchTable();" > <br> 
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Número</label><br>
		<input data-column="5" id="numero" autocomplete="off" type="text" onkeyup="searchTable();" > <br> 
	</div>

	 

	

	
	<script type="text/javascript">
		$(".myselect").select2();
	</script>