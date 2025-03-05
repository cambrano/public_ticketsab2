<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/api_whatsapp.php";
	include __DIR__."/../functions/status.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$api_whatsappDatos=api_whatsappDatos();
	if($api_whatsappDatos['id']!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_whatsapp',$_COOKIE["id_usuario"]);
	?>
	<title>Api Mailing</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="setupLogistica/index.php";
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

			var api_whatsapp = document.getElementById("api_whatsapp").value; 
			if(api_whatsapp == ""){
				document.getElementById("api_whatsapp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("API Whatsapp requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var code = document.getElementById("code").value; 
			if(code == ""){
				document.getElementById("code").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Code requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var url = document.getElementById("url").value; 
			if(url == ""){
				document.getElementById("url").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Url Mailing requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var account_sid = document.getElementById("account_sid").value; 
			if(account_sid == ""){
				document.getElementById("account_sid").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Account SID requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var auth_token = document.getElementById("auth_token").value; 
			if(auth_token == ""){
				document.getElementById("auth_token").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Auth Token requerido");
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

			var statusCallback_plantillas = document.getElementById("statusCallback_plantillas").value; 
			if(statusCallback_plantillas == ""){
				document.getElementById("statusCallback_plantillas").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Status Call Back Plantillas requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var statusCallback_replys = document.getElementById("statusCallback_replys").value; 
			if(statusCallback_replys == ""){
				document.getElementById("statusCallback_replys").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Status Call Back Replys requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var tiempo_espera_segundos = document.getElementById("tiempo_espera_segundos").value; 
			if(tiempo_espera_segundos == ""){
				document.getElementById("tiempo_espera_segundos").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Descripción requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var mensajes_a_enviar = document.getElementById("mensajes_a_enviar").value; 
			if(mensajes_a_enviar == ""){
				document.getElementById("mensajes_a_enviar").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Mensajes a Enviar requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var tiempo_script = document.getElementById("tiempo_script").value; 
			if(tiempo_script == ""){
				document.getElementById("tiempo_script").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tiempo Script requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var status_proveedor = document.getElementById("status_proveedor").value; 
			if(status_proveedor == ""){
				document.getElementById("status_proveedor").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus Proveedor requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var mensaje_proveedor = document.getElementById("mensaje_proveedor").value; 

			var api_whatsapp_data = []; 
			var data = {
					'nombre' : nombre,
					'api_whatsapp' : api_whatsapp,
					'code' : code,
					'url' : url,
					'account_sid' : account_sid,
					'auth_token' : auth_token,
					'de' : de,
					'statusCallback_plantillas' : statusCallback_plantillas,
					'statusCallback_replys' : statusCallback_replys,
					'tiempo_espera_segundos' : tiempo_espera_segundos,
					'mensajes_a_enviar' : mensajes_a_enviar,
					'tiempo_script' : tiempo_script,
					'status' : status,
					'status_proveedor' : status_proveedor,
					'mensaje_proveedor' : mensaje_proveedor,
				}
			api_whatsapp_data.push(data);
			$.ajax({
				type: "POST",
				url: "apiWhatsapp/db_add_update.php",
				data: {api_whatsapp_data: api_whatsapp_data},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="apiWhatsapp/index.php";
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
							urlink="apiWhatsapp/index.php";
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
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> <br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if(empty($moduloAccionPermisos)){
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
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Api Whatsapp</font>
				</label><br>
				<label class="tiempo_espera_segundosForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para api whatsapp.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
