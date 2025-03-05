<?php
	include __DIR__."/../functions/security.php";
	include '../functions/switch_operaciones.php';
	include "../functions/configuracion.php";
	@session_start();
	$configuracionDatos=configuracionDatos();
	if($configuracionDatos['id']!=""){
		$permiso="Update";
		$imagen_configuracion="../../ops/imagen.php?id_img=logo_principal.png";
	}else{
		$permiso="Insert";
		$imagen_configuracion="../../ops/imagen.php?id_img=logo_principal.png";
		$configuracionDatos['nombre'] ="";
	}
	?>
	<title>Configuracion Inicial</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('setupmanagerpanel/index.php');
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
			 
			var slogan = document.getElementById("slogan").value; 
			if(slogan == ""){
				document.getElementById("slogan").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Slogan requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var url_base = document.getElementById("url_base").value; 
			if(url_base == ""){
				document.getElementById("url_base").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("URL Base requerido");
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
			var correo_electronico = document.getElementById("correo_electronico").value; 
			if(correo_electronico == ""){
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
			var nombre_completo_representante = document.getElementById("nombre_completo_representante").value; 
			if(nombre_completo_representante == ""){
				document.getElementById("nombre_completo_representante").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre Representante requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var imagen = document.getElementById("imagen").value; 

			formData = new FormData($("#form")[0]); 
			formData.append('nombre', nombre);
			formData.append('telefono', telefono);
			formData.append('correo_electronico', correo_electronico);
			formData.append('nombre_completo_representante', nombre_completo_representante);
			formData.append('slogan', slogan);
			formData.append('imagen', imagen);
			formData.append('url_base', url_base);
			ruta = "configuracion/db_add_update.php";
			$.ajax({
				url: ruta,
				type: "POST",
				data: formData, 
				contentType: false,
				processData: false,
				success: function(data){ 
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("form").style.border="";
						document.getElementById("mensaje").classList.add("mensajeSucces");
						//$("#homebody").load('setupmanagerpanel/index');
						location.reload();
					}else{
						if(data==""){
							document.getElementById("sumbmit").disabled = true;
							$("#homebody").load('setupmanagerpanel/index.php');
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
	<div class="bodymanager" id="bodymanager" style="display: table;"> 
		<div class="submenux" onclick="subConfiguracion()">Configuración</div> / 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Configurar Inicial</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para configurar el sistema .</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
			</div>
		</div> 
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
