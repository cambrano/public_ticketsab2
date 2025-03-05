<?php
	@session_start(); 
	if($_GET['refresh']==1){
		setcookie("subPage", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
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
		setcookie("qr", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
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


	$modulosPermiso = modulosPermiso('operatividad','',$_COOKIE["id_usuario"]);
	

	if($_COOKIE["id_usuario"]==1){
		
	}

	if($modulosPermiso['correos_mailing'] || $modulosPermiso['all'] ){
		$correos_mailing1 = true;
	}

	if($modulosPermiso['titulos_personales'] || $modulosPermiso['all'] ){
		$titulos_personales = true;
	}

	if($modulosPermiso['ubicaciones'] || $modulosPermiso['all'] ){
		$ubicaciones = true;
	}

	if($modulosPermiso['campañas_mailing'] || $modulosPermiso['all'] ){
		$campanas_mailing1 = true;
	}

	if($modulosPermiso['dependencias'] || $modulosPermiso['all'] ){
		$dependencias = true;
	}

	if($modulosPermiso['directorios'] || $modulosPermiso['all'] ){
		$directorios = true;
	}

	if($modulosPermiso['tipos_equipos'] || $modulosPermiso['all'] ){
		$tipos_equipos = true;
	}

	if($modulosPermiso['sistemas_operativos'] || $modulosPermiso['all'] ){
		$sistemas_operativos = true;
	}

	if($modulosPermiso['softwares'] || $modulosPermiso['all'] ){
		$softwares = true;
	}

	if($modulosPermiso['equipos'] || $modulosPermiso['all'] ){
		$equipos = true;
	}
	if($modulosPermiso['responsables_equipos'] || $modulosPermiso['all'] ){
		$responsables_equipos = true;
	}

	if($modulosPermiso['tipos_gastos'] || $modulosPermiso['all'] ){
		$tipos_gastos = true;
	}

	if($modulosPermiso['tipos_gastos_asignados'] || $modulosPermiso['all'] ){
		$tipos_gastos_asignados = true;
	}

	if($modulosPermiso['control_gastos_ingresos'] || $modulosPermiso['all'] ){
		$control_gastos_ingresos = true;
	}



?>
	<title>Perfiles Personas</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#modulo_dependencias").click(function(event) { 
				document.getElementById("modulo_dependencias").style.pointerEvents = "none";
				urlink="dependencias/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_dependencias").style.pointerEvents = "auto";
			});
			$("#modulo_titulos_personales").click(function(event) { 
				document.getElementById("modulo_titulos_personales").style.pointerEvents = "none";
				urlink="titulosPersonales/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_titulos_personales").style.pointerEvents = "auto";
			});
			$("#modulo_ubicaciones").click(function(event) { 
				document.getElementById("modulo_ubicaciones").style.pointerEvents = "none";
				urlink="ubicaciones/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_ubicaciones").style.pointerEvents = "auto";
			});
			$("#modulo_directorios").click(function(event) { 
				document.getElementById("modulo_directorios").style.pointerEvents = "none";
				urlink="directorios/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+'?refresh=1');
				document.getElementById("modulo_directorios").style.pointerEvents = "auto";
			});

			$("#modulo_tipos_equipos").click(function(event) { 
				document.getElementById("modulo_tipos_equipos").style.pointerEvents = "none";
				urlink="tiposEquipos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_tipos_equipos").style.pointerEvents = "auto";
			});
			$("#modulo_responsables_equipos").click(function(event) { 
				document.getElementById("modulo_responsables_equipos").style.pointerEvents = "none";
				urlink="responsablesEquipos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_responsables_equipos").style.pointerEvents = "auto";
			});
			$("#modulo_sistemas_operativos").click(function(event) { 
				document.getElementById("modulo_sistemas_operativos").style.pointerEvents = "none";
				urlink="sistemasOperativos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_sistemas_operativos").style.pointerEvents = "auto";
			});
			$("#modulo_softwares").click(function(event) { 
				document.getElementById("modulo_softwares").style.pointerEvents = "none";
				urlink="softwares/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink);
				document.getElementById("modulo_softwares").style.pointerEvents = "auto";
			});

			$("#modulo_equipos").click(function(event) { 
				document.getElementById("modulo_equipos").style.pointerEvents = "none";
				urlink="equipos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+'?refresh=1');
				document.getElementById("modulo_equipos").style.pointerEvents = "auto";
			});

			$("#modulo_tipos_gastos").click(function(event) { 
				document.getElementById("modulo_tipos_gastos").style.pointerEvents = "none";
				urlink="tiposGastos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+'?refresh=1');
				document.getElementById("modulo_tipos_gastos").style.pointerEvents = "auto";
			});

			$("#modulo_tipos_gastos_asignados").click(function(event) { 
				document.getElementById("modulo_tipos_gastos_asignados").style.pointerEvents = "none";
				urlink="tiposGastosAsignados/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+'?refresh=1');
				document.getElementById("modulo_tipos_gastos_asignados").style.pointerEvents = "auto";
			});
			$("#modulo_control_gastos_ingresos").click(function(event) { 
				document.getElementById("modulo_control_gastos_ingresos").style.pointerEvents = "none";
				urlink="controlGastosIngresos/index.php";
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(urlink+'?refresh=1');
				document.getElementById("modulo_control_gastos_ingresos").style.pointerEvents = "auto";
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
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if(empty($modulosPermiso)){
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
			if(
				$dependencias ||
				$titulos_personales ||
				$ubicaciones ||
				$responsables_equipos ||
				$tipos_equipos ||
				$sistemas_operativos ||
				$softwares ||
				$tipos_gastos ||
				$tipos_gastos_asignados
			){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
					<font style="font-size: 20px;">Configuración</font>
				</label>
				<br>
				<?php
			}
			if(	$dependencias ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_dependencias" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/pub/configuracion/dependencias.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Dependencias <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$titulos_personales ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_titulos_personales" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Minus-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Titulos <br>Personales
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$ubicaciones ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_ubicaciones" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Bank-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Ubicaciones <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$responsables_equipos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_responsables_equipos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Bank-2-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Responsables <br>Equipos<br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$tipos_equipos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_equipos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Checklist-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>Equipos
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$sistemas_operativos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_sistemas_operativos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Tablet-Chart-icon_2.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Sistemas <br>Operativos
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$softwares ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_softwares" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/claves_2.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Softwares <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$tipos_gastos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_gastos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/auditoria_usuarios.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Gastos
							<br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$tipos_gastos_asignados ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_tipos_gastos_asignados" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/reportes/status.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Tipos <br>
							Gastos Asignados
							<br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	
				$dependencias ||
				$titulos_personales ||
				$ubicaciones ||
				$responsables_equipos ||
				$tipos_equipos ||
				$sistemas_operativos ||
				$softwares ||
				$tipos_gastos ||
				$tipos_gastos_asignados
			){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}

			if(
				$directorios ||
				$equipos ||
				$control_gastos_ingresos
			){
				?>
				<label class="tituloForm" style="text-align: center;width: 100%;border-top: 1px solid black;padding-top: 22px">
					<font style="font-size: 20px;">Operatividad</font>
				</label>
				<br>
				<?php
			}
			if(	$directorios ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_directorios" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Addressbook-3-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Directorio <br>Institucional
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$equipos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_equipos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Coding-Html-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Equipos <br><br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	$control_gastos_ingresos ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_control_gastos_ingresos" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Analytics-icon.png" width="24%">
						</div>
						<div class="moduloDetalle">
							Control <br>
							Gastos e Ingresos
							<br>
						</div>
					</div>
				</div> 
				<?php
			}
			if(	
				$directorios ||
				$equipos ||
				$control_gastos_ingresos
			){
				?>
				<div style="width: 100%;display: table;padding: 0" >
					<hr>
				</div>
				<?php
			}
		?>
	</div>