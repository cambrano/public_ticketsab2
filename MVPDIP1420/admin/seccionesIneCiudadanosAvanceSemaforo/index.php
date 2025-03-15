<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_avance_semaforo.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/status.php";
	@session_start();
	$secciones_ine_ciudadanos_avance_semaforoDatos=secciones_ine_ciudadanos_avance_semaforoDatos();
	
	if($secciones_ine_ciudadanos_avance_semaforoDatos['id']!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_avance_semaforo',$_COOKIE["id_usuario"]);
	//var_dump($secciones_ine_ciudadanos_avance_semaforoDatos);
	?>
	<title>Switch Operaciones</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink = 'setupLogistica/index.php';
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
			var rojo_rango_inicial_unidad = document.getElementById("rojo_rango_inicial_unidad").value; 
			if(rojo_rango_inicial_unidad == ""){
				document.getElementById("rojo_rango_inicial_unidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("rojo_rango_inicial_unidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var rojo_rango_inicial_decimal = document.getElementById("rojo_rango_inicial_decimal").value; 
			if(rojo_rango_inicial_decimal == ""){
				document.getElementById("rojo_rango_inicial_decimal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("rojo_rango_inicial_decimal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var rojo_rango_final_unidad = document.getElementById("rojo_rango_final_unidad").value; 
			if(rojo_rango_final_unidad == ""){
				document.getElementById("rojo_rango_final_unidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas rojo_rango_final_unidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var rojo_rango_final_decimal = document.getElementById("rojo_rango_final_decimal").value; 
			if(rojo_rango_final_decimal == ""){
				document.getElementById("rojo_rango_final_decimal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas rojo_rango_final_decimal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var amarillo_rango_inicial_unidad = document.getElementById("amarillo_rango_inicial_unidad").value; 
			if(amarillo_rango_inicial_unidad == ""){
				document.getElementById("amarillo_rango_inicial_unidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas amarillo_rango_inicial_unidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var amarillo_rango_inicial_decimal = document.getElementById("amarillo_rango_inicial_decimal").value; 
			if(amarillo_rango_inicial_decimal == ""){
				document.getElementById("amarillo_rango_inicial_decimal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("amarillo_rango_inicial_decimal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var amarillo_rango_final_unidad = document.getElementById("amarillo_rango_final_unidad").value; 
			if(amarillo_rango_final_unidad == ""){
				document.getElementById("amarillo_rango_final_unidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("amarillo_rango_final_unidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var amarillo_rango_final_decimal = document.getElementById("amarillo_rango_final_decimal").value; 
			if(amarillo_rango_final_decimal == ""){
				document.getElementById("amarillo_rango_final_decimal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("amarillo_rango_final_decimal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var verde_rango_inicial_unidad = document.getElementById("verde_rango_inicial_unidad").value; 
			if(verde_rango_inicial_unidad == ""){
				document.getElementById("verde_rango_inicial_unidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("verde_rango_inicial_unidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var verde_rango_inicial_decimal = document.getElementById("verde_rango_inicial_decimal").value; 
			if(verde_rango_inicial_decimal == ""){
				document.getElementById("verde_rango_inicial_decimal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("verde_rango_inicial_decimal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var verde_rango_final_unidad = document.getElementById("verde_rango_final_unidad").value; 
			if(verde_rango_final_unidad == ""){
				document.getElementById("verde_rango_final_unidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("verde_rango_final_unidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var verde_rango_final_decimal = document.getElementById("verde_rango_final_decimal").value; 
			if(verde_rango_final_decimal == ""){
				document.getElementById("verde_rango_final_unidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("verde_rango_final_decimal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("status requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			
			var secciones_ine_ciudadanos_avance_semaforo = []; 
			var data = {
					'rojo_rango_inicial_unidad' : rojo_rango_inicial_unidad,
					'rojo_rango_inicial_decimal' : rojo_rango_inicial_decimal,
					'rojo_rango_final_unidad' : rojo_rango_final_unidad,
					'rojo_rango_final_decimal' : rojo_rango_final_decimal,

					'amarillo_rango_inicial_decimal' : amarillo_rango_inicial_decimal,
					'amarillo_rango_inicial_unidad' : amarillo_rango_inicial_unidad,
					'amarillo_rango_final_unidad' : amarillo_rango_final_unidad,
					'amarillo_rango_final_decimal' : amarillo_rango_final_decimal,
					
					'verde_rango_inicial_unidad' : verde_rango_inicial_unidad,
					'verde_rango_inicial_decimal' : verde_rango_inicial_decimal,
					'verde_rango_final_unidad' : verde_rango_final_unidad,
					'verde_rango_final_decimal' : verde_rango_final_decimal,
					'status' : status
				}
			secciones_ine_ciudadanos_avance_semaforo.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosAvanceSemaforo/db_add_update.php",
				data: {secciones_ine_ciudadanos_avance_semaforo: secciones_ine_ciudadanos_avance_semaforo},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="seccionesIneCiudadanosAvanceSemaforo/index.php";
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
							urlink="seccionesIneCiudadanosAvanceSemaforo/index.php";
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
					<font style="font-size: 25px;">Semáforo General en secciones</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para semáforo en secciones.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
