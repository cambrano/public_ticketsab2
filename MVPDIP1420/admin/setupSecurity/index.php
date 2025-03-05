<?php
	@session_start();
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
				//location.reload();
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
				//location.reload();
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
				/*location.reload();*/
				$("#homebody").load(urlink+"?refresh=1");
			});
			$("#modulo_zonas_importantes").click(function(event) { 
				urlink="zonasImportantes/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				/*location.reload();*/
				$("#homebody").load(urlink+"?refresh=1");
			});

			$("#modulo_ips_bloqueados").click(function(event) { 
				urlink="ipsBloqueados/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				/*location.reload();*/
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
		$modulosPermiso = modulosPermiso('security','',$_COOKIE["id_usuario"]);
		if($modulosPermiso['tracking_sesion'] || $modulosPermiso['all'] ){
			$tracking_sesion = true;
		}
		if($modulosPermiso['tracking_page'] || $modulosPermiso['all'] ){
			$tracking_page = true;
		}
		if($modulosPermiso['tracking_usuarios'] || $modulosPermiso['all'] ){
			$tracking_usuarios = true;
		}
		if($modulosPermiso['zonas_importantes'] || $modulosPermiso['all'] ){
			$zonas_importantes = true;
		}
		if($modulosPermiso['ips_bloqueados'] || $modulosPermiso['all'] ){
			$ips_bloqueados = true;
		}

	?>
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(
				$tracking_page == false && 
				$tracking_sesion == false && 
				$tracking_usuarios == false && 
				$zonas_importantes == false &&
				$ips_bloqueados == false 
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
			if( $tracking_page || $tracking_sesion || $tracking_usuarios || $zonas_importantes || $ips_bloqueados ){
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
							<img src="images/modulosV2/seguridad/inicio_session.png" width="24%">
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
							<img src="images/modulosV2/seguridad/paginas.png" width="24%">
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
							<img src="images/modulosV2/seguridad/usuarios.png" width="24%">
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
			if(	$zonas_importantes == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_zonas_importantes" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/seguridad/zonas_importantes.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Zonas<br>Importantes
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$ips_bloqueados == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_ips_bloqueados" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/seguridad/ips_bloqueados.png" width="24%">
						</div>
						<div class="moduloDetalle">
							IP<br>Bloqueadas
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if($tracking_sesion || $tracking_page || $tracking_usuarios || $zonas_importantes || $ips_bloqueados ){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
	</div>