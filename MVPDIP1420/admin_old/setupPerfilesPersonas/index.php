<?php
	@session_start(); 
	$_SESSION['Paguinasub']='setupPerfilesPersonas/index.php'; 
	include '../functions/security.php'; 
	include '../functions/usuario_permisos.php';

	$modulosPermiso = modulosPermiso('perfiles','',$_COOKIE['id_usuario']);
	if($modulosPermiso['tipos_actividades'] || $modulosPermiso['all'] ){
		$tipos_actividades = true;
	}
	if($modulosPermiso['redes_sociales'] || $modulosPermiso['all'] ){
		$redes_sociales = true;
	}
	if($modulosPermiso['servidores_correos'] || $modulosPermiso['all'] ){
		$servidores_correos = true;
	}
	if($modulosPermiso['identidades'] || $modulosPermiso['all'] ){
		$identidades = true;
	}
	if($modulosPermiso['documentos_oficiales'] || $modulosPermiso['all'] ){
		$documentos_oficiales = true;
	}
	if($modulosPermiso['correos_electronicos'] || $modulosPermiso['all'] ){
		$correos_electronicos = true;
	}
	if($modulosPermiso['cuentas_redes_sociales'] || $modulosPermiso['all'] ){
		$cuentas_redes_sociales = true;
	}
	if($modulosPermiso['cuentas_actividades'] || $modulosPermiso['all'] ){
		$cuentas_actividades = true;
	}
	?>
	<title>Perfiles Personas</title>
	<script type='text/javascript'>
		$(document).ready(function() {

			$('#modulo_redes_sociales').click(function(event) { 
				urlink='redesSociales/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
			});


			$('#modulo_tipos_actividades').click(function(event) { 
				urlink='tiposActividades/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
			});


			$('#modulo_servidores_correos').click(function(event) { 
				urlink='servidoresCorreos/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				$('#homebody').load(urlink);
			});

			$('#modulo_identidades').click(function(event) { 
				urlink='identidades/index.php';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				////
				$('#homebody').load(urlink);
			});

			$('#modulo_correos_electronicos').click(function(event) { 
				urlink='correosElectronicos/index.php?reset=x';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				////
				$('#homebody').load(urlink);
			});

			$('#modulo_cuentas_redes_sociales').click(function(event) { 
				urlink='cuentasRedesSociales/index.php?reset=x';
				dataString = 'urlink='+urlink; 
				$.ajax({
					type: 'POST',
					url: 'functions/backarray.php',
					data: dataString,
					success: function(data) {}
				});
				////
				$('#homebody').load(urlink);
			});


		});
	</script>
	<style type='text/css'>
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
	
	<div style='display: table;width: 100%;text-align: left; color:black; padding: 25px;' id='bodymanager'> 
		<div id='mensaje' class='mensajeSolo' ></div>
		<?php
			if(empty($modulosPermiso)){
				?>
				<script type='text/javascript'>
					document.getElementById('mensaje').classList.add('mensajeError');
					$('#mensaje').html('No tiene permiso');
					$('#homebody').load('home.php');
				</script>
				<?php
				die;
			}
		?>
		<label class='tituloForm' style='text-align: center;width: 100%;border-bottom: 1px solid black'>
			<font style='font-size: 20px;'>Perfiles Personas</font>
		</label>
		<br>
		<?php
			if(	$tipos_actividades ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_tipos_actividades' >
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Analytics-icon.png' width='24%'>
						</div>
						<div class='moduloDetalle'>
							Tipos  <br> Actividades
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if( $redes_sociales ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_redes_sociales'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Coding-Html-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Redes <br> Sociales
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if( $servidores_correos ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_servidores_correos'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Mail-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Servidores <br> Correos
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		 
		<?php
		if( $tipos_actividades || $redes_sociales || $servidores_correos ){
			?>
			<div style='width: 100%;display: table;padding: 0' >
				<hr>
			</div>
			<?php
		}
		?>
		<?php
			if(	$identidades || $documentos_oficiales ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_identidades'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Addressbook-3-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Identidades<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if( $correos_electronicos ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_correos_electronicos'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Mail-at-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Correos<br>
							Eléctronicos
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if( $cuentas_redes_sociales	|| $cuentas_actividades ){
				?>
				<div class='moduloP' >
					<div class='modulo' id='modulo_cuentas_redes_sociales'>
						<div class='moduloImagen' style='width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px'>
							<img src='images/modulos/Add-icon.png' width='24%'></div>
						<div class='moduloDetalle'>
							Redes<br>
							Sociales
						</div>
					</div>
				</div> 
				<?php
			}
		?>
	</div>