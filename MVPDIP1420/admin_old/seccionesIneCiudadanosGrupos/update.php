<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_grupos.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/tipos_nombramientos.php";
	include __DIR__."/../functions/secciones_ine_grupos.php";
	include __DIR__."/../functions/status.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/plataformas.php";
	
	@session_start(); 
	$_SESSION['Paguinasub']="seccionesIneCiudadanosGrupos/update.php";  
	
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos_grupos','seccionesIneCiudadanosGrupos','index');
	if($redirectSecurity!=""){
		die;
	}

	$id_seccion_ine_ciudadano = $_SESSION['id_seccion_ine_ciudadano'];
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

	$seccion_ine_ciudadano_grupoDatos=seccion_ine_ciudadano_grupoDatos($id,$id_seccion_ine_ciudadano);
	$claveF= clave('secciones_ine_ciudadanos_grupos');
	$seccion_ine_ciudadano_grupoDatos=seccion_ine_ciudadano_grupoDatos($id);
	if($seccion_ine_ciudadano_grupoDatos['clave']==""){
		$seccion_ine_ciudadano_grupoDatos['clave']=$claveF['clave'];
	}

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('seccionesIneCiudadanosGrupos/index.php');
		}
		 

		function guardar() {
			var coma= /,/g;
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var espacios_invalidos= /\s+/g;

			var id = '<?= $id?>';
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_seccion_ine_ciudadano = '<?= $id_seccion_ine_ciudadano ?>'
			if(id_seccion_ine_ciudadano == ""){
				document.getElementById("id_seccion_ine_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave Elector requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
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

			var id_seccion_ine_grupo = document.getElementById("id_seccion_ine_grupo").value; 
			if(id_seccion_ine_grupo == ""){
				document.getElementById("id_seccion_ine_grupo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
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
					'id' : id,
					'id_seccion_ine_grupo' : id_seccion_ine_grupo, 
					'clave' : clave,
					'folio' : folio,
					'fecha' : fecha,
					'hora' : hora,
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
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
				url: "seccionesIneCiudadanosGrupos/db_edit.php",
				data: {seccion_ine_ciudadano_grupo: seccion_ine_ciudadano_grupo},
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
						$("#homebody").load('seccionesIneCiudadanosGrupos/index.php');
					}else{
						if(data==""){
							$("#homebody").load('seccionesIneCiudadanosGrupos/index.php');
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
					<font style="font-size: 25px;">Modificar Grupo</font>
				</label><br>
				<h2><?= $seccion_ine_ciudadanoDatos['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar programa apoyo.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>