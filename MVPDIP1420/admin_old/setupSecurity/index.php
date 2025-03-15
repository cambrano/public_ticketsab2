<?php
	@session_start(); 
	$_SESSION['Paguinasub']="setupSecurity/index.php"; 
	include '../functions/security.php'; 
	include '../functions/usuario_permisos.php'; 
?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modulo_log_sesiones").click(function(event) { 
				urlink="logSesiones/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+"?refresh=1");
			});
			$("#modulo_log_clicks").click(function(event) { 
				urlink="logClicks/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+"?refresh=1");
			});
			$("#modulo_log_usuarios_tracking").click(function(event) { 
				urlink="logUsuariosTracking/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+"?refresh=1");
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
		$tracking_page = moduloPermiso('tracking_page','security',$_COOKIE["id_usuario"]);
		$tracking_sesion = moduloPermiso('tracking_sesion','security',$_COOKIE["id_usuario"]);
		$tracking_usuarios = moduloPermiso('tracking_usuarios','security',$_COOKIE["id_usuario"]);

	?>
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(
				$tracking_page == false && 
				$tracking_sesion == false && 
				$tracking_usuarios == false
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
			if( $tracking_page || $tracking_sesion || $tracking_usuarios ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-bottom: 1px solid black">
					<font style="font-size: 20px;">Seguridad Georeferenciada</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$tracking_sesion == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_log_sesiones" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Laptop-Signal-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Inicios<br>Sesiones
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$tracking_page == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_log_clicks" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Database-Cloud-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Páginas<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$tracking_usuarios == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_log_usuarios_tracking" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Street-View-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Usuarios<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if($tracking_sesion || $tracking_page || $tracking_usuarios ){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
	</div>