<?php
	@session_start(); 
	include '../functions/security.php'; 
	include '../functions/usuario_permisos.php'; 
	include '../functions/paquetes_sistema.php'; 
	include 'functions/paquetes_sistema.php';


	setcookie("subPage", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));

	setcookie("paguinaId_1", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));

	setcookie("paguinaId_1", "", array('expires' => time() - (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None'));
?>
	<title>Configuración</title>
	<script type="text/javascript">
		<?php
		if(	$_COOKIE["id_usuario"]==1 ){
			?>
				$(document).ready(function() {
					$("#reiniciar_sistema").click(function(event) { 
						urlink="reiniciar_sistema/index.php";
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
					
					$("#paquete_sistema_user").click(function(event) { 
						urlink="paqueteSistema/index.php";
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
			<?php
		}
		?>
		$(document).ready(function() {
			$("#modulo_config").click(function(event) { 
				urlink="configuracion/index.php";
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
			$("#modulo_claves").click(function(event) { 
				urlink="claves/index.php";
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
			$("#modulo_claves_2").click(function(event) { 
				urlink="claves2/index.php";
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
			$("#modulo_auditoria_usuario").click(function(event) { 
				urlink="auditoriaUsuario/index.php";
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
			$("#modulo_administradores_sistema").click(function(event) { 
				urlink="adminSistema/index.php";
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
			$("#modulo_administradores_generales").click(function(event) { 
				urlink="adminGenerales/index.php";
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
			$("#correo_sistema").click(function(event) { 
				urlink="correoSistema/index.php";
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
			$("#importaciones_sistema").click(function(event) { 
				urlink="importacionesSistema/index.php";
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
			$("#color_configuracion").click(function(event) { 
				urlink="colorConfiguracion/index.php";
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
			$("#elecciones").click(function(event) { 
				urlink="elecciones/index.php";
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
	<div style="display: table;width: 100%;text-align: left; color:black; padding: 25px;" id="bodymanager"> 
		<label class="tituloForm" style="text-align: center;width: 100%;border-bottom: 1px solid black">
			<font style="font-size: 20px;">Configuración</font>
		</label>
		<br>
		<?php
			if(	$_COOKIE["id_usuario"]==1 ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="reiniciar_sistema" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Button-7-close-icon.png" width="24%"></div>
						<div class="moduloDetalle">
							Reiniciar<br><br>
						</div>
					</div>
				</div> 
				<div class="moduloP" >
					<div class="modulo" id="paquete_sistema_user" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Button-7-close-icon.png" width="24%"></div>
						<div class="moduloDetalle">
							Paquete<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(moduloPermiso('configuracion_inicial','configuracion',$_COOKIE["id_usuario"])==true){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_config">
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/configuracion_inicial.png" width="24%"></div>
						<div class="moduloDetalle">
							Configuración <br> Inicial
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(moduloPermiso('claves','configuracion',$_COOKIE["id_usuario"])==true){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_claves" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/claves.png" width="24%"></div>
						<div class="moduloDetalle">
							Claves <br> Automáticas
						</div>
					</div> 
				</div> 
				<div class="moduloP" >
					<div class="modulo" id="modulo_claves_2" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/claves_2.png" width="24%"></div>
						<div class="moduloDetalle">
							Claves <br> Automáticas 2
						</div>
					</div> 
				</div> 
				<?php
			}
		?>
		<?php
			if($_COOKIE["id_usuario"]==1){
			//if(moduloPermiso('auditoria_usuarios','configuracion',$_COOKIE["id_usuario"])==true){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_auditoria_usuario" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/auditoria_usuarios.png" width="24%"></div>
						<div class="moduloDetalle">
							Auditoria <br> Usuarios
						</div>
					</div>
				</div>
				<?php
			}
		?>
		<?php
			if(
				moduloPermiso('administrador_sistema','configuracion',$_COOKIE["id_usuario"])==true 
			){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_administradores_sistema" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/usuarios_admin.png" width="24%"></div>
						<div class="moduloDetalle">
							Administradores  <br> Sistema
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(
				moduloPermiso('empleados','configuracion',$_COOKIE["id_usuario"])==true || 
				moduloPermiso('empleados_permisos','configuracion',$_COOKIE["id_usuario"])==true
			){
				?>
				<div class="moduloP" >
					<div class="modulo" id="modulo_administradores_generales" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/usuarios_empleados.png" width="24%"></div>
						<div class="moduloDetalle">
							Empleados<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	moduloPermiso('correo_sistema','configuracion',$_COOKIE["id_usuario"])==true ){
				?>
				<div class="moduloP" style="display: none;" >
					<div class="modulo" id="correo_sistema" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/Mail-icon.png" width="24%"></div>
						<div class="moduloDetalle">
							Correo<br> Sistema<br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
		<?php
			if(	$_COOKIE["id_usuario"]==1 ){
			//if(	moduloPermiso('correo_sistema','configuracion',$_COOKIE["id_usuario"])==true ){
				?>
				<div class="moduloP" >
					<div class="modulo" id="importaciones_sistema" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulosV2/configuracion/importacion_rapida.png" width="24%"></div>
						<div class="moduloDetalle">
							Importación <br>Datos<br>
						</div>
					</div>
				</div> 
				<div class="moduloP" >
					<div class="modulo" id="color_configuracion" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/beach.png" width="24%"></div>
						<div class="moduloDetalle">
							Tipo <br>Color<br>
						</div>
					</div>
				</div> 
				<div class="moduloP" >
					<div class="modulo" id="elecciones" >
						<div class="moduloImagen" style="width: 100%;background-color: #DCDCDC;text-align: center;padding: 12px">
							<img src="images/modulos/beach.png" width="24%"></div>
						<div class="moduloDetalle">
							Elecciones<br><br>
						</div>
					</div>
				</div> 
				<?php
			}
		?>
	</div>