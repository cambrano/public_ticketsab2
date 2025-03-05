<?php
		@session_start();
		if($_COOKIE["id_usuario"]!=1){
			?>
			<script type="text/javascript">
				urlink="home.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				$("#homebody").load(urlink);
			</script>
			<?php
			die;
		}
		include __DIR__."/../functions/security.php";
		include __DIR__."/../functions/paquetes_sistema.php";
		$paqueteSistemaDatos=paqueteSistemaDatos();
		unset($paqueteSistemaDatos['fechaR']);
		unset($paqueteSistemaDatos['monto']);
		unset($paqueteSistemaDatos['descuento']);
		unset($paqueteSistemaDatos['porcentaje']);
		unset($paqueteSistemaDatos['monto_total']);
		unset($paqueteSistemaDatos['codigo_plataforma']);

	?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="setupmanagerpanel/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			<?php
				foreach ($paqueteSistemaDatos as $key => $value) {
					?>
						var <?= $key ?> = document.getElementById("<?= $key ?>").value; 
						if(<?= $key ?> == ""){
							document.getElementById("<?= $key ?>").focus(); 
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html("<?= $key ?> requerido");
							document.getElementById("mensaje").classList.add("mensajeError");
							return false;
						}
					<?php
				}
			?>
			var paquete_sistema = []; 
			var data = { 
					<?php
						foreach ($paqueteSistemaDatos as $key => $value) {
							?>
								'<?= $key ?>' : <?= $key ?>,
							<?php
						}
					?>
				}
			paquete_sistema.push(data);
			//console.log(paquete_sistema);
			$.ajax({
				type: "POST",
				url: "paquete_sistema/db_edit.php",
				data: {paquete_sistema: paquete_sistema},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("&nbsp;");
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink = "setupmanagerpanel/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						document.getElementById("mensaje").classList.add("mensajeError");
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html(data);
					}
				}
			});
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Paquete Sistema</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;"></font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>