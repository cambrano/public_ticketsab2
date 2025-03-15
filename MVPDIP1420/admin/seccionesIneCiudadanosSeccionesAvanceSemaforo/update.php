<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_secciones_avance_semaforo.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/status.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id_seccion_ine = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id_seccion_ine), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}

	$seccion_ine_ciudadano_seccion_avance_semaforoDatos = seccion_ine_ciudadano_seccion_avance_semaforoDatos('',$id_seccion_ine);
	$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	echo $redirectSecurity=redirectSecurity($id_seccion_ine,'secciones_ine','seccionesIneCiudadanosSeccionesAvanceSemaforo','index');
	if($redirectSecurity!=""){
		die;
	}
	if($seccion_ine_ciudadano_seccion_avance_semaforoDatos['id']==""){
		$seccion_ine_ciudadano_seccion_avance_semaforoDatos['status'] = 1;
	}

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="seccionesIneCiudadanosSeccionesAvanceSemaforo/index.php";
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
			var coma= /,/g;
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var id = '<?= $seccion_ine_ciudadano_seccion_avance_semaforoDatos['id']?>';

			var id_seccion_ine = '<?= $id_seccion_ine?>';
			if(id_seccion_ine == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Sección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var rojo_rango_inicial = document.getElementById("rojo_rango_inicial").value; 
			if(rojo_rango_inicial == ""){
				document.getElementById("rojo_rango_inicial").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("rojo_rango_inicial requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var rojo_rango_final = document.getElementById("rojo_rango_final").value; 
			if(rojo_rango_final == ""){
				document.getElementById("rojo_rango_final").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas rojo_rango_final requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var amarillo_rango_inicial = document.getElementById("amarillo_rango_inicial").value; 
			if(amarillo_rango_inicial == ""){
				document.getElementById("amarillo_rango_inicial").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas amarillo_rango_inicial requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var amarillo_rango_final = document.getElementById("amarillo_rango_final").value; 
			if(amarillo_rango_final == ""){
				document.getElementById("amarillo_rango_final").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("amarillo_rango_final requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var verde_rango_inicial = document.getElementById("verde_rango_inicial").value; 
			if(verde_rango_inicial == ""){
				document.getElementById("verde_rango_inicial").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("verde_rango_inicial requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var verde_rango_final = document.getElementById("verde_rango_final").value; 
			if(verde_rango_final == ""){
				document.getElementById("verde_rango_final").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("verde_rango_final requerido");
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

			var seccion_ine_ciudadano_seccion_avance_semaforo = [];
			var data = {
					'id' : id,
					'id_seccion_ine' : id_seccion_ine,

					'rojo_rango_inicial' : rojo_rango_inicial,
					'rojo_rango_final' : rojo_rango_final,

					'amarillo_rango_inicial' : amarillo_rango_inicial,
					'amarillo_rango_final' : amarillo_rango_final,
					
					'verde_rango_inicial' : verde_rango_inicial,
					'verde_rango_final' : verde_rango_final,

					'status' : status,
				}
				seccion_ine_ciudadano_seccion_avance_semaforo.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosSeccionesAvanceSemaforo/db_edit.php",
				data: {seccion_ine_ciudadano_seccion_avance_semaforo: seccion_ine_ciudadano_seccion_avance_semaforo},
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
						urlink="seccionesIneCiudadanosSeccionesAvanceSemaforo/index.php";
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
							urlink="seccionesIneCiudadanosSeccionesAvanceSemaforo/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
						}else{
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
							
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
					<font style="font-size: 25px;">Semáforo en sección : <?= $seccion_ineDatos['numero'] ?></font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para semáforo en sección.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>