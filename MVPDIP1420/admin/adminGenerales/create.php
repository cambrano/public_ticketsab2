<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/claves.php";	 
	include __DIR__."/../functions/empleados_grupos.php";
	@session_start(); 
	$claveF= clave('empleados');
	$empleadoDatos['clave']=$claveF['clave'];
	$permiso="insert";
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="adminGenerales/index.php";
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
			var espacios_invalidos= /\s+/g;

			var clave = document.getElementById("clave").value; 
			clavex = clave.replace(espacios_invalidos, '');
			if(clavex == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var id_empleado_grupo = document.getElementById("id_empleado_grupo").value; 
			id_empleado_grupox = id_empleado_grupo.replace(espacios_invalidos, '');
			if(id_empleado_grupox == ""){
				document.getElementById("id_empleado_grupo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Empleado Grupo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var nombre = document.getElementById("nombre").value; 
			nombrex = nombre.replace(espacios_invalidos, '');
			if(nombrex == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			apellido_paternox = apellido_paterno.replace(espacios_invalidos, '');
			if(apellido_paternox == ""){
				document.getElementById("apellido_paterno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Paterno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			

			var apellido_materno = document.getElementById("apellido_materno").value;
			apellido_maternox = apellido_materno.replace(espacios_invalidos, '');
			if(apellido_maternox == ""){
				document.getElementById("apellido_materno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Materno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var correo_electronico = document.getElementById("correo_electronico").value;
			correo_electronicox = correo_electronico.replace(espacios_invalidos, '');
			if(correo_electronicox == ""){
				document.getElementById("correo_electronico").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Correo Electrónico Válido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else{
				if(!validarEmail(correo_electronico)){
					document.getElementById("correo_electronico").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Correo Electrónico Válido requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}
			var telefono = document.getElementById("telefono").value; 
			var usuario = document.getElementById("usuario").value;
			usuariox = usuario.replace(espacios_invalidos, '');
			if(usuariox == ""){
				document.getElementById("usuario").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Usuario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var password = document.getElementById("password").value;
			passwordx = password.replace(espacios_invalidos, '');
			if(passwordx == ""){
				document.getElementById("password").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Constraseña requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var password1 = document.getElementById("password1").value; 
			password1x = password1.replace(espacios_invalidos, '');
			if(password1x == ""){
				document.getElementById("password1").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Constraseña requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			if(password != password1){
				document.getElementById("password1").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Constraseña No Coinciden requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var empleados = [];
			var data = { 
					'clave' : clave,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'correo_electronico' : correo_electronico,
					'telefono' : telefono,
					'id_empleado_grupo' : id_empleado_grupo,
				}
			empleados.push(data);
			var usuarios = [];
			var data = { 
					'usuario' : usuario,
					'password' : password,
					'clave' : clave,
				}
			usuarios.push(data);
			$.ajax({
				type: "POST",
				url: "adminGenerales/db_add.php",
				data: {empleados: empleados,usuarios: usuarios},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("&nbsp;");
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="adminGenerales/index.php";
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
						$("#mensaje").html(data);
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
					document.getElementById("password1").type = "text";
				} else {
					// Hacer algo si el checkbox ha sido deseleccionado
					$('#labelMostrar').html("Mostrar");
					document.getElementById("password").type = "password";
					document.getElementById("password1").type = "password";
				}
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Crear Empleado</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a empleado.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>