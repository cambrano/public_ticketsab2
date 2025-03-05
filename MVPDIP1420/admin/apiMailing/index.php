<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/api_mailing.php";
	include __DIR__."/../functions/status.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$api_mailingDatos=api_mailingDatos();
	if($api_mailingDatos['id']!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','api_mailing',$_COOKIE["id_usuario"]);
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
			var url_mailing = document.getElementById("url_mailing").value; 
			if(url_mailing == ""){
				document.getElementById("url_mailing").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Url Mailing requerido");
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

			var tiempo_espera_segundos = document.getElementById("tiempo_espera_segundos").value; 
			if(tiempo_espera_segundos == ""){
				document.getElementById("tiempo_espera_segundos").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Descripción requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var correos_a_enviar = document.getElementById("correos_a_enviar").value; 
			if(correos_a_enviar == ""){
				document.getElementById("correos_a_enviar").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Correos A Enviar requerido");
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

			var api_mailing = []; 
			var data = {
					'url_mailing' : url_mailing,
					'status' : status,
					'tiempo_espera_segundos' : tiempo_espera_segundos,
					'correos_a_enviar' : correos_a_enviar,
					'tiempo_script' : tiempo_script,
				}
			api_mailing.push(data);
			$.ajax({
				type: "POST",
				url: "apiMailing/db_add_update.php",
				data: {api_mailing: api_mailing},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="setupLogistica/index.php";
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
							urlink="setupLogistica/index.php";
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
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
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
					<font style="font-size: 25px;">Api Mailing</font>
				</label><br>
				<label class="tiempo_espera_segundosForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para api mailing.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
