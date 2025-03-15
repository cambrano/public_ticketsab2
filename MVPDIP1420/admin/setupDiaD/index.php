<?php
	@session_start(); 
	if($_GET['refresh']==1){
		setcookie("subPage",1, array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("periodoInicial", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("periodoFinal", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchOpcionesSIC", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("PHPSESSID", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("ch201AB", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchTableSIC", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchTableLN", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("searchOpcionesLN", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_1", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_2", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_3", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("paguinaId_4", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		setcookie("AB32BA51", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		//setcookie("Paguinasub", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
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

	include '../functions/elecciones.php';
	include 'functions/elecciones.php';
	$elecciones = elecciones();
?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modulo_secciones_ine_ciudadanos_check_2024").click(function(event) { 
				urlink="seccionesIneCiudadanosCheck2024/index.php";
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

			$("#modulo_casillas_votos_2024").click(function(event) { 
				urlink="casillasVotos2024/index.php";
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

			$("#modulo_municipios_reportes_2024").click(function(event) { 
				document.getElementById("modulo_municipios_reportes_2024").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='municipio'){
						echo 'urlink="seccionesIneReportes2024/municipio/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="municipiosReportes2024/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_municipios_reportes_2024").style.pointerEvents = "auto";
			});
			$("#modulo_distritos_locales_reportes_2024").click(function(event) { 
				document.getElementById("modulo_distritos_locales_reportes_2024").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_local'){
						echo 'urlink="seccionesIneReportes2024/distrito_local/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosLocalesReportes2024/index.php";';
					}
				?>
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_locales_reportes_2024").style.pointerEvents = "auto";
			});

			$("#modulo_distritos_federales_reportes_2024").click(function(event) {
				document.getElementById("modulo_distritos_federales_reportes_2024").style.pointerEvents = "none";
				<?php
					if($tipo_uso_plataforma=='distrito_federal'){
						echo 'urlink="seccionesIneReportes2024/distrito_federal/index.php";';
					}elseif($tipo_uso_plataforma=='all'){
						echo 'urlink="distritosFederalesReportes2024/index.php";';
					}
				?>
				//urlink="seccionesIneReportes2024/distrito_federal/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				//location.reload();
				//$("#homebody").load(urlink);
				$("#homebody").load(urlink+"?refresh=1");
				document.getElementById("modulo_distritos_federales_reportes_2024").style.pointerEvents = "auto";
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

			$("#modulo_log_usuarios_tracking_secciones").click(function(event) { 
				urlink="logUsuariosTrackingSecciones/index.php";
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
		$cartografias_municipios_2024 = moduloPermiso('cartografias_municipios_2024','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$cartografias_distritos_locales_2024 = moduloPermiso('cartografias_distritos_locales_2024','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$cartografias_distritos_federales_2024 = moduloPermiso('cartografias_distritos_federales_2024','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$distritos_locales_2024 = moduloPermiso('distritos_locales_2024','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$distritos_federales_2024 = moduloPermiso('distritos_federales_2024','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$secciones_ine_ciudadanos = moduloPermiso('secciones_ine_ciudadanos','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$secciones_ine_actividades = moduloPermiso('secciones_ine_actividades','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);
		$swich_operaciones = moduloPermiso('swich_operaciones','sistema_unico_beneficiarios',$_COOKIE["id_usuario"]);

		

	?>
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(
				$casillas_votos_2024 == false && 
				$cartografias_municipios_2024 == false && 
				$distritos_locales_2024 == false && 
				$distritos_federales_2024 == false && 

				$secciones_ine_ciudadanos == false && 
				$secciones_ine_actividades == false && 
				$swich_operaciones == false 
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
			if( $casillas_votos_2024 || $cartografias_municipios_2024 || $distritos_locales_2024 || $distritos_federales_2024 ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-bottom: 1px solid black">
					<font style="font-size: 20px;">Dia D</font>
				</label>
				<br>
				<?php
			}
		?>
		<?php
			if(	$casillas_votos_2024 == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_casillas_votos_2024" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/2024/casillas_2024.png" width="24%">
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
			if(	$cartografias_municipios_2024){
				if($tipo_uso_plataforma=='municipio' || $tipo_uso_plataforma=='all'){
					if($elecciones['2024']['municipios_show']!=1){
						$display='style="display: none"';
					}else{
						$display = '';
					}
					?>
					<div class="moduloP" <?= $display ?> >
						<div class="modulo" id="modulo_municipios_reportes_2024" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulos/Vote_Gober.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Municipios <br> <?= $elecciones['2024']['municipios'] ?>
							</div>
						</div>
					</div> 
					<?php
				}
			}
		?>
		<?php
			if(	$cartografias_distritos_locales_2024){
				if($tipo_uso_plataforma=='distrito_local' || $tipo_uso_plataforma=='all'){
					if($elecciones['2024']['distritos_locales_show']!=1){
						$display='style="display: none"';
					}else{
						$display = '';
					}
					?>
					<div class="moduloP" <?= $display ?> >
						<div class="modulo" id="modulo_distritos_locales_reportes_2024" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2024/distritos_locales_2024.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Distritos <br> Locales <?= $elecciones['2024']['distritos_locales'] ?>
							</div>
						</div>
					</div> 
					<?php
				}
			}
		?>
		<?php
			if(	$cartografias_distritos_federales_2024){
				if($tipo_uso_plataforma=='distrito_federal' || $tipo_uso_plataforma=='all'){
					if($elecciones['2024']['distritos_federales_show']!=1){
						$display='style="display: none"';
					}else{
						$display = '';
					}
					?>
					<div class="moduloP" <?= $display ?> >
						<div class="modulo" id="modulo_distritos_federales_reportes_2024" >
							<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
								<img src="images/modulosV2/pub/2024/distritos_federales_2024.png" width="24%">
							</div>
							<div class="moduloDetalle">
								Distritos <br> Federales <?= $elecciones['2024']['distritos_federales'] ?>
							</div>
						</div>
					</div> 
					<?php
				}
			}
		?>
		<?php
			if($partidos_2024 || $casillas_votos_2024 || $cartografias_municipios_2024 || $distritos_locales_2024 || $distritos_federales_2024 ){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
		<?php
			if($tipos_ciudadanos || $secciones_ine_ciudadanos || $secciones_ine_actividades || $swich_operaciones || $casillas_votos_2024 ){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-bottom: 1px solid black">
					<font style="font-size: 20px;">Estructura & Ciudadanos</font>
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
			if(	$secciones_ine_ciudadanos == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_secciones_ine_ciudadanos_check_2024" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/dia/ciudadanos_check.png" width="24%">
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
		<?php
			if(	$casillas_votos_2024 == true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_log_usuarios_tracking_secciones" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/dia/estructura_secciones.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Estructura <br>
							Secciones
						</div>
					</div>
				</div> 
				<?php
			}
		?>
	</div>