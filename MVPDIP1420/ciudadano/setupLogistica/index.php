<?php
	@session_start(); 
	include '../functions/switch_operaciones.php';
?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			 

			$("#modulo_secciones_ine_ciudadanos").click(function(event) { 
				urlink="seccionesIneCiudadanos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				$("#homebody").load(urlink);
			});

			$("#modulo_casillas_votos_2021").click(function(event) { 
				urlink="casillasVotos2021/index.php";
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


		$registro = switch_operacionesPermisos('registro');
		$entrega = switch_operacionesPermisos('entrega');
		$recibido = switch_operacionesPermisos('recibido');
		$evaluacion = switch_operacionesPermisos('evaluacion');
		$usuarios = switch_operacionesPermisos('usuarios');

	?>
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(
				switch_operacionesPermisos('registro') == false &&
				switch_operacionesPermisos('entrega') == false &&
				switch_operacionesPermisos('recibido') == false &&
				switch_operacionesPermisos('evaluacion') == false &&
				switch_operacionesPermisos('usuarios') == false 
			){
				?>
				<script type="text/javascript">
					document.getElementById("mensaje").classList.add("mensajeError");
					$("#mensaje").html("No tiene permiso");
					$("#homebody").load('home.php');
				</script>
				<?php
				die;
			}
		?>
		
		<?php
			if($registro || $entrega || $recibido || $evaluacion ){
				?>
				<label class="tituloForm">
					<font style="font-size: 20px;">Campaña</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$registro == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Speaker-desk-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Ciudadanos <br>
							Secciones
						</div>
					</div>
				</div> 
				<?
			}
		?>
	</div>