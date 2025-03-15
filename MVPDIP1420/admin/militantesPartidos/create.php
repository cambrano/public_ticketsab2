<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$id_seccion_ine_ciudadano = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	validar_plataforma_vista($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index',$codigo_plataforma);
	if($id_seccion_ine_ciudadano!=""){
		$id_seccion_ine_ciudadano;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		$id_seccion_ine = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}


	$permiso='insert';
	$claveF= clave('militantes_partidos');
	$militante_partidoDatos['clave']=$claveF['clave'];

	$militante_partidoDatos['fecha'] = date("Y-m-d");
	$militante_partidoDatos['hora'] = date("H:i:s");
	$militante_partidoDatos['status'] = 1;

	$militante_partidoDatos['correo_electronico'] = $seccion_ine_ciudadanoDatos['correo_electronico'];
	$militante_partidoDatos['whatsapp'] = $seccion_ine_ciudadanoDatos['whatsapp'];
	$militante_partidoDatos['telefono'] = $seccion_ine_ciudadanoDatos['telefono'];
	$militante_partidoDatos['celular'] = $seccion_ine_ciudadanoDatos['celular'];
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="militantesPartidos/index.php";
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
			var permiso = '<?= $permiso ?>'; 
			if(permiso == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Permiso requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var imagen = document.getElementById("imagen").value; 
			if(imagen == ""){
				/*
				document.getElementById("imagen").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Youtube Imagenes requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
				*/
			}

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var folio = document.getElementById("folio").value; 
			if(folio == ""){
				document.getElementById("folio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Folio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_seccion_ine_ciudadano = '<?= $id_seccion_ine_ciudadano ?>'; 
			if(id_seccion_ine_ciudadano == ""){
				document.getElementById("id_seccion_ine_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_partido_legado = document.getElementById("id_partido_legado").value;
			if(id_partido_legado == ""){
				document.getElementById("id_partido_legado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Partido Legado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha = document.getElementById("fecha").value; 
			if(fecha == ""){
				document.getElementById("fecha").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if(!fechaValida(fecha)){ 
				document.getElementById("fecha").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var hora = document.getElementById("hora").value; 
			if(hora == ""){
				document.getElementById("hora").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Hora requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var correo_electronico = document.getElementById("correo_electronico").value;
			correo_electronico = correo_electronico.replace(espacios_invalidos, '');  
			if(correo_electronico == ""){
				document.getElementById("correo_electronico").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Correo Electronico Válido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else{
				if(!validarEmail(correo_electronico)){
					document.getElementById("correo_electronico").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Correo Electronico Válido requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}
			var whatsapp = document.getElementById("whatsapp").value;
			whatsapp = whatsapp.replace(espacios_invalidos, '');  
			if(whatsapp == ""){
				document.getElementById("whatsapp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Whatsapp requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else{
				//validadamos si es numero
				if(isNaN(whatsapp)){
					document.getElementById("whatsapp").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Whatsapp valido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}else{
					if(whatsapp.length != '10' ){
						document.getElementById("whatsapp").focus(); 
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html("Whatsapp valido de 10 digitos");
						document.getElementById("mensaje").classList.add("mensajeError");
						return false;
					}
				}
			}
			var telefono = document.getElementById("telefono").value; 
			/*
			if(telefono == ""){
				document.getElementById("telefono").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Teléfono requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			*/
			var celular = document.getElementById("celular").value;
			celular = celular.replace(espacios_invalidos, '');
			if(celular!=''){
				if(isNaN(celular)){
					document.getElementById("celular").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Celular valido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}else{
					if(celular.length != '10' ){
						document.getElementById("celular").focus(); 
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html("Celular valido de 10 digitos");
						document.getElementById("mensaje").classList.add("mensajeError");
						return false;
					}
				}
			}

			var observaciones = document.getElementById("observaciones").value;
			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var formData = new FormData($("#form")[0]);
			formData.append('imagen', imagen);
			formData.append('id_seccion_ine_ciudadano' , id_seccion_ine_ciudadano);
			formData.append('id_partido_legado' , id_partido_legado);
			formData.append('clave' , clave);
			formData.append('folio' , folio);
			formData.append('fecha' , fecha);
			formData.append('hora' , hora);
			formData.append('correo_electronico' , correo_electronico);
			formData.append('whatsapp' , whatsapp);
			formData.append('telefono' , telefono);
			formData.append('celular' , celular);
			formData.append('observaciones' , observaciones);
			formData.append('status' , status);
			var ruta = "militantesPartidos/db_add.php";
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
						urlink="militantesPartidos/index.php";
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
							document.getElementById("mensaje").classList.add("mensajeError");
							urlink="militantesPartidos/index.php";
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
					<font style="font-size: 25px;">Crear Militante Partido</font>
				</label><br> 
				<h2><?= $seccion_ine_ciudadanoDatos['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a militante partido.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>