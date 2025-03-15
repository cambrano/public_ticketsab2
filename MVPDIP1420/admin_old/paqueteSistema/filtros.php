<?php
		@session_start();
		include "../functions/error.php"; 
		include "../functions/security.php";
		include 'functions/security.php'; 
		unset($_SESSION['fechaR']);
		unset($_SESSION['id_usuario']);
		unset($_SESSION['tabla']);
		unset($_SESSION['operacion']); 
?>
		<script type="text/javascript">
			function searchTable(){
				var fechaR = document.getElementById("fechaR").value;
				
				dataString='fechaR='+fechaR;
				
				$.ajax({
					type: "POST",
					url: "auditoriaUsuario/table.php",
					data: dataString,
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
		<label class="labelForm" id="labeltemaname">Fecha</label><br>
		<input data-column="0" id="fechaR" autocomplete="off" type="text" onkeyup ="searchTable();" ><br>