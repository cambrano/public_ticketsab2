<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/plataformas.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$id_seccion_ine = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	validar_plataforma_vista($id_seccion_ine,'secciones_ine','seccionesIneCiudadanosSeccionesAvanceSemaforo','index',$codigo_plataforma);
	if($id_seccion_ine!=""){
		$id_seccion_ine;
		$seccion_ineDatos = seccion_ineDatos($id_seccion_ine);
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine,'secciones_ine','seccionesIneCiudadanosSeccionesAvanceSemaforo','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$tipo_ciudadano_seccion_avance_semaforoDatos['status'] = 1;
	$permiso='insert';
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="tiposCiudadanosSeccionesAvanceSemaforo/index.php";
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

			var id_seccion_ine = '<?= $id_seccion_ine?>';
			if(id_seccion_ine == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Sección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_tipo_ciudadano = document.getElementById("id_tipo_ciudadano").value; 
			if(id_tipo_ciudadano == ""){
				document.getElementById("id_tipo_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo ciudadano requerido");
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

			var tipo_ciudadano_seccion_avance_semaforo = [];
			var data = {
				'id_seccion_ine' : id_seccion_ine,
				'id_tipo_ciudadano' : id_tipo_ciudadano,

				'rojo_rango_inicial' : rojo_rango_inicial,
				'rojo_rango_final' : rojo_rango_final,

				'amarillo_rango_inicial' : amarillo_rango_inicial,
				'amarillo_rango_final' : amarillo_rango_final,
				
				'verde_rango_inicial' : verde_rango_inicial,
				'verde_rango_final' : verde_rango_final,

				'status' : status,
			}
			tipo_ciudadano_seccion_avance_semaforo.push(data);
			$.ajax({
				type: "POST",
				url: "tiposCiudadanosSeccionesAvanceSemaforo/db_add.php",
				data: {tipo_ciudadano_seccion_avance_semaforo: tipo_ciudadano_seccion_avance_semaforo},
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
						urlink="tiposCiudadanosSeccionesAvanceSemaforo/index.php";
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
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Crear Semáforo Tipo Ciudadano En Sección</font>
				</label><br> 
				<h2><?= $seccion_ineDatos['clave']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a semáforo tipo ciudadano en sección.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>