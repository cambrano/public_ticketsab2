<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include '../functions/tool_xhpzab.php';
	include "../functions/usuarios.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_permisos.php";
	include __DIR__."/../functions/status.php";
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos','seccionesIneCiudadanosUsuarios','index');
	if($redirectSecurity!=""){
		die;
	}

	$usuarioDatos=usuarioDatos('','',$id);
	$seccion_ine_ciudadano_permisosDatos=seccion_ine_ciudadano_permisosDatos('',$id);
	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="seccionesIneCiudadanosUsuarios/index.php";
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
			
			var id_usuario = '<?= $usuarioDatos['id'] ?>'; 
			if(id_usuario == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Usuario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id = '<?= $usuarioDatos['id_seccion_ine_ciudadano'] ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var usuario = document.getElementById("usuario").value;
			usuario = usuario.replace(espacios_invalidos, ''); 
			if(usuario == ""){
				document.getElementById("usuario").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Usuario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var password = document.getElementById("password").value; 
			password = password.replace(espacios_invalidos, ''); 
			if(password == ""){
				document.getElementById("password").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Constraseña requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var password1 = document.getElementById("password1").value;
			password1 = password1.replace(espacios_invalidos, ''); 
			if(password1 == ""){
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
			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Status requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var usuarios = [];
			var data = { 
					'id' : id_usuario,
					'usuario' : usuario,
					'password' : password,
					'status' : status,
				}
			usuarios.push(data);

			var id_seccion_ine_ciudadano_permiso = '<?= $seccion_ine_ciudadano_permisosDatos['id'] ?>'
			var entrega = document.getElementById("entrega").checked;
			if(entrega){
				entrega = 1;
			}else{
				entrega = 0;
			}
			var recibe = document.getElementById("recibe").checked;
			if(recibe){
				recibe = 1;
			}else{
				recibe = 0;
			}
			var casilla = document.getElementById("casilla").checked;
			if(casilla){
				casilla = 1;
			}else{
				casilla = 0;
			}
			var seccion_ine_ciudadano = []; 
			var data = {    
					'id' : id,
				}
			seccion_ine_ciudadano.push(data);
			var usuarios_permisos = [];
			var data = { 
					'id' : id_seccion_ine_ciudadano_permiso,
					'id_usuario' : id_usuario,
					'id_seccion_ine_ciudadano' : id,
					'entrega' : entrega,
					'recibe' : recibe,
					'casilla' : casilla,
				}
			usuarios_permisos.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosUsuarios/db_edit.php",
				data: {seccion_ine_ciudadano: seccion_ine_ciudadano,usuarios: usuarios,usuarios_permisos:usuarios_permisos},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="seccionesIneCiudadanosUsuarios/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink+'?refresh=1');
					}else{
						if(data==""){
							urlink="seccionesIneCiudadanosUsuarios/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink+'?refresh=1');
						}else{
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
							document.getElementById("sumbmit").disabled = false;
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
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Usuario Ciudadano</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar usuario.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>