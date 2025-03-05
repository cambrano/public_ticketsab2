<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/ubicaciones.php";
	include __DIR__."/../functions/titulos_personales.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/sub_dependencias.php";
	include __DIR__."/../functions/sexos.php";
	@session_start(); 
	$claveF= clave('directorios');
	$directorioDatos['clave']=$claveF['clave'];
	$permiso="insert";
	$directorioDatos['id_sub_dependencia'] = 'x';
	$directorioDatos['fecha_nacimiento']='2003-01-17';
	$directorioDatos['correo_electronico']='x@x.com';
	?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="directorios/index.php";
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

			var clave = document.getElementById("clave").value; 
			clave = clave.trim();
			clavex = clave.replace(espacios_invalidos, '');
			if(clavex == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_ubicacion = document.getElementById("id_ubicacion").value; 
			id_ubicacion = id_ubicacion.trim();
			id_ubicacionx = id_ubicacion.replace(espacios_invalidos, '');
			if(id_ubicacionx == ""){
				document.getElementById("id_ubicacion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Ubicación requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var id_dependencia = document.getElementById("id_dependencia").value; 
			id_dependencia = id_dependencia.trim();
			id_dependenciax = id_dependencia.replace(espacios_invalidos, '');
			if(id_dependenciax == ""){
				document.getElementById("id_dependencia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Dependencia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_sub_dependencia = document.getElementById("id_sub_dependencia").value; 
			id_sub_dependencia = id_sub_dependencia.trim();
			id_sub_dependenciax = id_sub_dependencia.replace(espacios_invalidos, '');
			if(id_sub_dependenciax == ""){
				document.getElementById("id_sub_dependencia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Sub Dependencia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var area = document.getElementById("area").value; 
			area = area.trim();
			areax = area.replace(espacios_invalidos, '');
			if(areax == ""){
				document.getElementById("area").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Área requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var puesto = document.getElementById("puesto").value; 
			puesto = puesto.trim();
			puestox = puesto.replace(espacios_invalidos, '');
			if(puestox == ""){
				document.getElementById("puesto").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Área requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var id_titulo_personal = document.getElementById("id_titulo_personal").value; 
			id_titulo_personal = id_titulo_personal.trim();
			id_titulo_personalx = id_titulo_personal.replace(espacios_invalidos, '');
			if(id_titulo_personalx == ""){
				document.getElementById("id_titulo_personal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Titulo Personal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var nombre = document.getElementById("nombre").value; 
			nombre = nombre.trim();
			nombrex = nombre.replace(espacios_invalidos, '');
			if(nombrex == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			apellido_paterno = apellido_paterno.trim();
			apellido_paternox = apellido_paterno.replace(espacios_invalidos, '');
			if(apellido_paternox == ""){
				document.getElementById("apellido_paterno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Paterno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var apellido_materno = document.getElementById("apellido_materno").value; 
			apellido_materno = apellido_materno.trim();
			apellido_maternox = apellido_materno.replace(espacios_invalidos, '');
			if(apellido_maternox == ""){
				document.getElementById("apellido_materno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Materno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_sexo = document.getElementById("id_sexo").value; 
			id_sexo = id_sexo.trim();
			id_sexox = id_sexo.replace(espacios_invalidos, '');
			if(id_sexox == ""){
				document.getElementById("id_sexo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Materno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_nacimiento = document.getElementById("fecha_nacimiento").value;
			fecha_nacimiento = fecha_nacimiento.trim();
			fecha_nacimientox = fecha_nacimiento.replace(espacios_invalidos, '');
			if(fecha_nacimientox == ""){
				document.getElementById("fecha_nacimiento").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Nacimiento Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(fecha_nacimientox)){ 
					document.getElementById("fecha_nacimiento").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha Nacimiento Válida requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}

			var correo_electronico = document.getElementById("correo_electronico").value;
			correo_electronico = correo_electronico.trim();
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
			whatsapp = whatsapp.trim();
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
			telefono = telefono.trim();

			var telefono_ext = document.getElementById("telefono_ext").value;
			telefono_ext = telefono_ext.trim();
			//telefono = telefono.replace(espacios_invalidos, '');
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
			celular = celular.trim();
			celular = celular.replace(espacios_invalidos, '');
			/*
			if(celular!=''){
				if(isNaN(celular)){
					document.getElementById("celular").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Celular valido");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("divDatosCiudadano").style.display = "none";
					document.getElementById("divDatosPersonales").style.display = "none";
					document.getElementById("divDatosContacto").style.display = "initial";
					document.getElementById("divDatosDireccionActual").style.display = "none";
					document.getElementById("divDatosDireccionIne").style.display = "none";
					document.getElementById("divObservaciones").style.display = "none";
					return false;
				}else{
					if(celular.length != '10' ){
						document.getElementById("celular").focus(); 
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html("Celular valido de 10 digitos");
						document.getElementById("mensaje").classList.add("mensajeError");
						document.getElementById("divDatosCiudadano").style.display = "none";
						document.getElementById("divDatosPersonales").style.display = "none";
						document.getElementById("divDatosContacto").style.display = "initial";
						document.getElementById("divDatosDireccionActual").style.display = "none";
						document.getElementById("divDatosDireccionIne").style.display = "none";
						document.getElementById("divObservaciones").style.display = "none";
						return false;
					}
				}
			}
			*/
			var observaciones = document.getElementById("observaciones").value;
			observaciones = observaciones.trim();

			

			var directorio = [];
			var data = {
					'clave' : clave,
					'id_ubicacion' : id_ubicacion,
					'id_dependencia' : id_dependencia,
					'id_sub_dependencia' : id_sub_dependencia,
					'area' : area,
					'puesto' : puesto,
					'id_titulo_personal' : id_titulo_personal,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'id_sexo' : id_sexo,
					'fecha_nacimiento' : fecha_nacimiento,
					'correo_electronico' : correo_electronico,
					'whatsapp' : whatsapp,
					'telefono' : telefono,
					'telefono_ext' : telefono_ext,
					'celular' : celular,
					'observaciones' : observaciones,
				}
			directorio.push(data);

			$.ajax({
				type: "POST",
				url: "directorios/db_add.php",
				data: {directorio: directorio},
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
						urlink="directorios/index.php";
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
					<font style="font-size: 25px;">Crear Directorio Institucional</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de nombrea a titulo personal.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>