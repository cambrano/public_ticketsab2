<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/correos_mailing.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'correos_mailing','correosMailing','index');
	if($redirectSecurity!=""){
		die;
	}
	$correo_mailingDatos=correo_mailingDatos($id);

	$permiso="update";
	?>
	<title>Update</title>
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

			var id = '<?= $id?>';
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

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
			var dataString = '&id='+id+'&nombre='+nombre+'&servidor='+servidor+'&puerto='+puerto+'&cifrado='+cifrado+'&usuario='+usuario+'&password='+password+'&correo_electronico='+correo_electronico+'&de='+de;

			$.ajax({
				type: "POST",
				url: "correosMailing/db_edit.php",
				data: dataString,
				success: function(data) {
					//alert(data.length);
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#mensaje").html("Correo Comprobado");
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
							$("#mensaje").html("Correo No Comprobado");
						}
						
					}
				}
			});
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
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
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Correo Mailing</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a correo mailing.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>