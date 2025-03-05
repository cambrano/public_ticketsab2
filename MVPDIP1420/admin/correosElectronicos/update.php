<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php"; 
	include __DIR__."/../functions/correos_electronicos.php";
	include __DIR__."/../functions/servidores_correos.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id),time()+(60*60*24*650),"/",false);
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'correos_electronicos','correosElectronicos','index');
	if($redirectSecurity!=""){
		die;
	}
	@session_start();
	$id_identidad = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_identidad!=""){
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
		$disbale_id_pricipal='disabled="disabled"';
	}else{
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$claveF= clave('correos_electronicos');
	$correo_electronicoDatos=correo_electronicoDatos($id);
	if($correo_electronicoDatos['clave']==""){
		$correo_electronicoDatos['clave']=$claveF['clave'];
	}
	$identidadDatos = identidadDatos($correo_electronicoDatos['id_identidad']);
	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="correosElectronicos/index.php";
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
			
			var id = '<?= $id ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_identidad = document.getElementById("id_identidad").value; 
			if(id_identidad == ""){
				document.getElementById("id_identidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_emision = document.getElementById("fecha_emision").value; 
			if(fecha_emision == ""){
				document.getElementById("fecha_emision").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Emision requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var hora_emision = document.getElementById("hora_emision").value; 
			if(hora_emision == ""){
				document.getElementById("hora_emision").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Hora Emision requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_servidor_correo = document.getElementById("id_servidor_correo").value;
			if(id_servidor_correo == ""){
				document.getElementById("id_servidor_correo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Servidor de Correo requerido");
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
				$("#mensaje").html("Password requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var telefono = document.getElementById("telefono").value; 
			if(telefono == ""){
				document.getElementById("telefono").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Teléfono requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var correo_electronico_recuperacion = document.getElementById("correo_electronico_recuperacion").value; 
			if(correo_electronico_recuperacion == ""){
				document.getElementById("correo_electronico_recuperacion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Correo Recuperación requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var correo_electronico = []; 
			var data = {    
					'id' : id,
					'clave' : clave,
					'id_identidad' : id_identidad,
					'hora_emision' : hora_emision,
					'fecha_emision' : fecha_emision,
					'id_servidor_correo' : id_servidor_correo,
					'usuario' : usuario,
					'password' : password,
					'telefono' : telefono,
					'correo_electronico_recuperacion' : correo_electronico_recuperacion,
				}
			correo_electronico.push(data);

			$.ajax({
				type: "POST",
				url: "correosElectronicos/db_edit.php",
				data: {correo_electronico: correo_electronico},
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
						urlink="correosElectronicos/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						if(data==""){
							urlink="correosElectronicos/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
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
					<font style="font-size: 25px;">Modificar Correo Eléctronico</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a correo eléctronico.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>