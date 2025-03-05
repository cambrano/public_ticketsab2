<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	@session_start(); 
	$permiso="insert";
	?>
	<title>Correo Venta</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="correosMailing/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var servidor = document.getElementById("servidor").value; 
			if(servidor == ""){
				document.getElementById("servidor").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Servidor requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var puerto = document.getElementById("puerto").value; 
			if(puerto == ""){
				document.getElementById("puerto").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Puerto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var cifrado = document.getElementById("cifrado").value; 
			if(cifrado == ""){
				document.getElementById("cifrado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Cifrado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var de = document.getElementById("de").value; 
			if(de == ""){
				document.getElementById("de").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("De requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var usuario = document.getElementById("usuario").value; 
			if(usuario == ""){
				document.getElementById("usuario").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Usuario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var password = document.getElementById("password").value; 
			if(password == ""){
				document.getElementById("password").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Contraseña requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			
			var correo_electronico = document.getElementById("correo_electronico").value; 
				if(correo_electronico == ""){
					document.getElementById("correo_electronico").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Correo Electronico Valido requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}else{
					/*
					if(!validarEmail(correo_electronico)){
						document.getElementById("correo_electronico").focus(); 
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html("Correo Electronico Valido requerido");
						document.getElementById("mensaje").classList.add("mensajeError");
						return false;
					}
					*/
				}
			var dataString = '&nombre='+nombre+'&servidor='+servidor+'&puerto='+puerto+'&cifrado='+cifrado+'&usuario='+usuario+'&password='+password+'&correo_electronico='+correo_electronico+'&de='+de;

			$.ajax({
				type: "POST",
				url: "correosMailing/db_add.php",
				data: dataString,
				success: function(data) {
					//alert(data.length);
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#mensajeInfo").html("Correo Comprobado");
						$("#homebody").load('correosMailing/index.php');
						urlink="correosMailing/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						if(data=="SINCAMBIOS"){
							urlink="correosMailing/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
						}else{
							document.getElementById("mensaje").classList.add("mensajeError");
							document.getElementById("sumbmit").disabled = false; 
							$("#mensaje").html("ERROR "+data);
							$("#mensajeInfo").html("Correo No Comprobado");
						}
						
					}
				}
			});
		}
		$( function() {
			$( '#monstar_contraseña' ).on( 'click', function() {
				if( $(this).is(':checked') ){
					// Hacer algo si el checkbox ha sido seleccionado
					$('#labelMostrar').html("Ocultar");
					document.getElementById("password").type = "text"; 
				} else {
					// Hacer algo si el checkbox ha sido deseleccionado
					$('#labelMostrar').html("Mostrar");
					document.getElementById("password").type = "password"; 
				}
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager" style="display: table;"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if(!$correosMailing['status']){
				echo '<div id="mensajeInfo" class="mensajeInfo">Correo No Comprobado</div>';
			}else{
				echo '<div id="mensajeInfo" class="mensajeInfo">Correo Comprobado</div>';
			}
		?>
		<div class="bodyform">
			<div class= "bodyheader">
				<br><br>
				<label class="tituloForm">
					<font style="font-size: 25px;">Configurar Correo mailing</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Formulario para configurar Correo mailing </font><br>
					<font style="font-size: 10px;">
						<strong>
							<br>
							Configure la cuentra de correo para que el sistema pueda enviar correos correctamente.<br>
						</strong>
					</font>
				</label>
				<font style="font-size: 15px;"><strong></strong></font>
			</div>
		</div> 
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>