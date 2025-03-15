<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_grupos.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/tipos_nombramientos.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$id_seccion_ine_grupo = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	if($id_seccion_ine_grupo!=""){
		$id_seccion_ine_grupo;
		$seccion_ine_grupoDatos = seccion_ine_grupoDatos($id_seccion_ine_grupo);
		$nombre_completo = $seccion_ine_grupoDatos['nombre'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_grupo,'secciones_ine_grupos','seccionesIneGrupos','index');
		if($redirectSecurity!=""){
			die;
		}
	}


	$permiso='insert';
	$claveF= clave('secciones_ine_ciudadanos_grupos');
	$seccion_ine_ciudadano_grupoDatos['clave']=$claveF['clave'];

	$seccion_ine_ciudadano_grupoDatos['fecha'] = date("Y-m-d");
	$seccion_ine_ciudadano_grupoDatos['hora'] = date("H:i:s");
	$seccion_ine_ciudadano_grupoDatos['status'] = 1;
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="seccionesIneCiudadanosGruposTotales/index.php";
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

			var id_seccion_ine_grupo = '<?= $id_seccion_ine_grupo ?>'; 
			if(id_seccion_ine_grupo == ""){
				document.getElementById("id_seccion_ine_grupo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
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

			var id_tipo_nombramiento = document.getElementById("id_tipo_nombramiento").value;  
			if(id_tipo_nombramiento == ""){
				document.getElementById("id_tipo_nombramiento").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Nombramiento requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_inicio = document.getElementById("fecha_inicio").value; 
			if(fecha_inicio == ""){
				document.getElementById("fecha_inicio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Inicio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if(!fechaValida(fecha_inicio)){ 
				document.getElementById("fecha_inicio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Inicio Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_final = document.getElementById("fecha_final").value; 
			if(fecha_final == ""){
				document.getElementById("fecha_final").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Final requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if(!fechaValida(fecha_final)){ 
				document.getElementById("fecha_final").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Final Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var clave_elector = document.getElementById("clave_elector").value; 
			if(clave_elector == ""){
				document.getElementById("clave_elector").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave Elector requerido");
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
			var seccion_ine_ciudadano_grupo = []; 
			var data = {    
					'id_seccion_ine_grupo' : id_seccion_ine_grupo, 
					'clave' : clave,
					'folio' : folio,
					'fecha' : fecha,
					'hora' : hora,
					'clave_elector' : clave_elector,
					'correo_electronico' :correo_electronico,
					'whatsapp' : whatsapp,
					'telefono' : telefono,
					'celular' : celular,
					'observaciones' : observaciones,
					'status' : status,
					'id_tipo_nombramiento' : id_tipo_nombramiento,
					'fecha_inicio' : fecha_inicio,
					'fecha_final' : fecha_final,
				}
			seccion_ine_ciudadano_grupo.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosGruposTotales/db_add.php",
				data: {seccion_ine_ciudadano_grupo: seccion_ine_ciudadano_grupo},
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
						urlink="seccionesIneCiudadanosGruposTotales/index.php";
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
					<font style="font-size: 25px;">Crear Miembro</font>
				</label><br> 
				<h2><?= $seccion_ine_grupoDatos['nombre']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a miembro.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>