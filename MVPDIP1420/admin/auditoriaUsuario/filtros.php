<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/log_usuarios.php";
	include "../functions/usuarios.php"; 
	@session_start();
?>
	<script type="text/javascript">
		function searchTable(){
			var fechaR = document.getElementById("fechaR").value;
			var id_usuario = document.getElementById("id_usuario").value;
			var tabla = document.getElementById("tabla").value;
			var operacion = document.getElementById("operacion").value;

			dataString='fechaR='+fechaR+'&id_usuario='+id_usuario+'&tabla='+tabla+'&operacion='+operacion;
			var searchTable = [];
			var data = {   
					'fechaR' : fechaR, 
					'id_usuario' : id_usuario, 
					'tabla' : tabla, 
					'operacion' : operacion,
				}
			searchTable.push(data);
			$.ajax({
				type: "POST",
				url: "auditoriaUsuario/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
		$( function() {
			$( "#fechaR" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			}); 
		});
	</script>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha</label><br>
		<input data-column="0" id="fechaR" autocomplete="off" type="text" onkeyup ="searchTable();" ><br>
	</div>

	<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;">
		<label class="labelForm" id="labeltemaname">Agente</label><br>
		<select id="id_usuario" class="myselect" onchange ="searchTable();" >
		<?php
			echo usuariosSelect('','3','xyz');
		?>
		</select><br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Modulos</label><br>
		<select data-column="2" class="myselect" id="tabla" onchange ="searchTable();" >
			<?php
			echo modulosLog();
			?>
		</select><br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Operaciones</label><br>
		<select data-column="3" class="myselect" id="operacion"  onchange ="searchTable();" >
			<?php
			echo operaciones();
			?>
		</select><br>
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>