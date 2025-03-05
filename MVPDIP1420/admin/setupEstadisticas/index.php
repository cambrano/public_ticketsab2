<?php
	@session_start(); 
	include __DIR__."/../functions/notificaciones_sistema.php";
	include __DIR__."/../functions/paquetes_sistema.php";
	$capacidad_sistema = paquetesSistema("web");
?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modulo_tipos_casillas").click(function(event) { 
				urlink="tiposCasillas/index.php";
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
			$("#modulo_secciones_ine").click(function(event) { 
				urlink="seccionesIne/index.php";
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

			$("#modulo_partidos").click(function(event) { 
				urlink="partidos/index.php";
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

			$("#modulo_casillas_votos_2018").click(function(event) { 
				urlink="casillasVotos2018/index.php";
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

			$("#modulo_secciones_ine_reportes").click(function(event) { 
				urlink="seccionesIneReportes/index.php";
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
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<label class="tituloForm">
			<font style="font-size: 20px;">Estadisticas</font>
		</label>
		<br>
		<?php
			if(	moduloPermiso('tipos_casillas','estadisticas',$_COOKIE["id_usuario"])==true ){
				$otros_div=true;
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_casillas" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Files-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Casillas
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	moduloPermiso('secciones_ine','estadisticas',$_COOKIE["id_usuario"])==true ){
				$otros_div=true;
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_partidos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Shield-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Partidos <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	moduloPermiso('secciones_ine','estadisticas',$_COOKIE["id_usuario"])==true ){
				$otros_div=true;
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Bank-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Secciones <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	moduloPermiso('secciones_ine_ciudadanos','estadisticas',$_COOKIE["id_usuario"])==true ){
				$otros_div=true;
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
				<?php
			}
		?>
		
		<div style="width: 100%;display: table;padding: 0" >
			<hr>
		</div>
		<label class="tituloForm">
			<font style="font-size: 20px;">Reportes Votaciónes 2018</font>
		</label>
		<br>
		<?php
			if(	moduloPermiso('casillas_votos_2018','estadisticas',$_COOKIE["id_usuario"])==true ){
				$otros_div=true;
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_casillas_votos_2018" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Download-Computer-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							2018 <br>
							Casillas Votos <br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	moduloPermiso('secciones_ine_reportes','estadisticas',$_COOKIE["id_usuario"])==true ){
				$otros_div=true;
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_reportes" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Analytics-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Reporte<br>Secciones 2018<br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
	</div>