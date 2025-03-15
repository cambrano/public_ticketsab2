<?php
	@session_start(); 
	$_SESSION['Paguinasub']="setupDiaD/index.php"; 
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include '../functions/security.php'; 
	include '../functions/usuario_permisos.php'; 
	include __DIR__."/../functions/notificaciones_sistema.php";
	$_SESSION['dia_d']=1;
?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modulo_secciones_ine_ciudadanos_check_2021").click(function(event) { 
				urlink="seccionesIneCiudadanosCheck2021/index.php";
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

			$("#modulo_municipios_reportes_2021").click(function(event) { 
				urlink="municipiosReportes2021/index.php";
				//urlink="seccionesIneReportes2021/municipio/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+"?refresh=1");
				//
			});

			$("#modulo_distritos_locales_reportes_2021").click(function(event) { 
				urlink="distritosLocalesReportes2021/index.php";
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

			$("#modulo_distritos_federales_reportes_2021").click(function(event) { 
				urlink="distritosFederalesReportes2021/index.php";
				//urlink="seccionesIneReportes2021/distrito_federal/index.php";
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

			$("#modulo_switch_operaciones").click(function(event) { 
				urlink="switchOperaciones/index.php";
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
		$casillas_votos_2021 = moduloPermiso('casillas_votos_2021','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$cartografias_municipios_2021 = moduloPermiso('cartografias_municipios_2021','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$distritos_locales_2021 = moduloPermiso('distritos_locales_2021','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$distritos_federales_2021 = moduloPermiso('distritos_federales_2021','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$secciones_ine_ciudadanos = moduloPermiso('secciones_ine_ciudadanos','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$secciones_ine_actividades = moduloPermiso('secciones_ine_actividades','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$swich_operaciones = moduloPermiso('swich_operaciones','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);

		

	?>
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(
				$casillas_votos_2021 == false && 
				$cartografias_municipios_2021 == false && 
				$distritos_locales_2021 == false && 
				$distritos_federales_2021 == false && 

				$secciones_ine_ciudadanos == false && 
				$secciones_ine_actividades == false && 
				$swich_operaciones == false 
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
			if( $casillas_votos_2021 || $cartografias_municipios_2021 || $distritos_locales_2021 || $distritos_federales_2021 ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-bottom: 1px solid black">
					<font style="font-size: 20px;">Dia D</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$casillas_votos_2021 == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_casillas_votos_2021" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Download-Computer-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Casillas <br>Votos <br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$cartografias_municipios_2021 == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_municipios_reportes_2021" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Application-Map-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Cartografía <br> Municipios
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$distritos_locales_2021 == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_distritos_locales_reportes_2021" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Tablet-Chart-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Distritos <br> Locales
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$distritos_federales_2021 == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_distritos_federales_reportes_2021" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Graph-Magnifier-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Distritos <br> Federales
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if($partidos_2021 || $casillas_votos_2021 || $cartografias_municipios_2021 || $distritos_locales_2021 || $distritos_federales_2021 ){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
		<?php
			if($tipos_ciudadanos || $secciones_ine_ciudadanos || $secciones_ine_actividades || $swich_operaciones ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-bottom: 1px solid black">
					<font style="font-size: 20px;">Ciudadanos</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$swich_operaciones == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_switch_operaciones" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Settings-2-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Switch <br>Operaciones
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$tipos_ciudadanos == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_ciudadanos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Files-2-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Ciudadanos
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$secciones_ine_ciudadanos == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos_check_2021" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Speaker-desk-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Ciudadanos <br>
							Check
						</div>
					</div>
				</div> 
				<?php
			}
		?>
	</div>