<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/api_sms.php";
	include __DIR__."/../functions/status.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$api_smsDatos=api_smsDatos();
	if($api_smsDatos['id']!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_sms',$_COOKIE["id_usuario"]);
	//var_dump($apiSMSDatos);
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

			var url = document.getElementById("url").value; 
			if(url == ""){
				document.getElementById("url").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Url Mailing requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var modo = document.getElementById("modo").value; 
			if(modo == ""){
				document.getElementById("modo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Modo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var key_sandbox = document.getElementById("key_sandbox").value; 
			if(key_sandbox == ""){
				document.getElementById("key_sandbox").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Key Sandbox requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var key_produccion = document.getElementById("key_produccion").value; 
			if(key_produccion == ""){
				document.getElementById("key_produccion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Key Producción requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var max_caracteres = document.getElementById("max_caracteres").value; 
			if(max_caracteres == ""){
				document.getElementById("max_caracteres").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Max Caracateres requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var max_caracteres_especiales = document.getElementById("max_caracteres_especiales").value; 
			if(max_caracteres_especiales == ""){
				document.getElementById("max_caracteres_especiales").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Max Caracateres Especiales requerido");
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

			var api_sms = []; 
			var data = {
					'nombre' : nombre,
					'url' : url,
					'modo' : modo,
					'key_sandbox' : key_sandbox,
					'key_produccion' : key_produccion,
					'max_caracteres' : max_caracteres,
					'max_caracteres_especiales' : max_caracteres_especiales,
					'tiempo_espera_segundos' : tiempo_espera_segundos,
					'mensajes_a_enviar' : mensajes_a_enviar,
					'tiempo_script' : tiempo_script,
					'status' : status,
					'status_proveedor' : status_proveedor,
					'mensaje_proveedor' : mensaje_proveedor,
				}
			api_sms.push(data);
			$.ajax({
				type: "POST",
				url: "apiSMS/db_add_update.php",
				data: {api_sms: api_sms},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="apiSMS/index.php";
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
							urlink="apiSMS/index.php";
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
					<font style="font-size: 25px;">Api SMS</font>
				</label><br>
				<label class="tiempo_espera_segundosForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para api sms.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
