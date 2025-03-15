<?php
	@session_start(); 
	include '../functions/security.php'; 
	include '../functions/usuario_permisos.php'; 
	include __DIR__."/../functions/notificaciones_sistema.php";
?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modulo_casillas_votos_2024_incidentes_reportes").click(function(event) { 
				urlink="casillasVotos2024IncidenciasReportes/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
			});
			$("#modulo_casillas_votos_2024_status_reportes").click(function(event) { 
				urlink="casillasVotos2024StatusReportes/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
			});
		});
	</script>
	<style type="text/css">
		.circulo {
			width: 2.5rem;
			height: 2.5rem;
			background: red;
			border-radius: 50%;
			display: flex;
			-webkit-box-shadow: 0px 0px 5px 1px rgba(0,0,0,.41);
			-moz-box-shadow: 0px 0px 5px 1px rgba(0,0,0,.41);
			box-shadow: 0px 0px 5px 1px rgba(0,0,0,.41);
			justify-content: center;
			align-items: center;
			text-align: center;
			margin:-1px -5px 0px auto;
			/*padding:5%;*/
			float: right;
		}

		.circulo > h2 {
			margin:10px auto 10px auto;
			font-family: sans-serif;
			color: white;
			font-size: 1rem;
			font-weight: bold;
			padding: 5%; 
		}
	</style>
	<?php
		$casillas_votos_2024 = moduloPermiso('casillas_votos_2024','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);

	?>
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(
				$casillas_votos_2024 == false
			){
				?>
				<script type="text/javascript">
					document.getElementById("mensaje").classList.add("mensajeError");
					$("#mensaje").html("No tiene permiso");
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
		?> 
		<?php
			if( $casillas_votos_2024 ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-bottom: 1px solid black">
					<font style="font-size: 20px;">Reportes</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$casillas_votos_2024 == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_casillas_votos_2024_incidentes_reportes" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/reportes/incidencias.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Casillas <br> Incidentes
						</div>
					</div>
				</div> 
				<div class="moduloP" >
					<div class="modulo" id="modulo_casillas_votos_2024_status_reportes" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/reportes/status.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Casillas <br> Status
						</div>
					</div>
				</div> 
				<?php
			}
		?>
	</div>