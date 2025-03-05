<?php
	@session_start();
	include '../functions/tool_xhpzab.php';
	if($_GET['refresh']==1){
		$id = $_GET['id'];
			setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
			?>
			<script type="text/javascript">
				location.reload();
			</script>
		<?php
		die;
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/manzanas_ine.php";
	include __DIR__."/../functions/manzanas_ine_parametros.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/status.php";
	include "../functions/usuarios.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	echo $redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
	if($redirectSecurity!=""){
		die;
	}


	$seccion_ine_ciudadanoDatos=seccion_ine_ciudadanoDatos($id);
	if($seccion_ine_ciudadanoDatos['id_seccion_ine_ciudadano_compartido']!=""){
		$seccion_ine_ciudadanoDatos_compartido=seccion_ine_ciudadanoDatos($seccion_ine_ciudadanoDatos['id_seccion_ine_ciudadano_compartido']);
		;
		$option_relacionado ='<option selected="selected" value="'.$seccion_ine_ciudadanoDatos_compartido['id'].'">'.$seccion_ine_ciudadanoDatos_compartido['nombre_completo']." - Sección:".$seccion_ine_ciudadanoDatos_compartido['seccion'].'</option>';
	}
	//var_dump($seccion_ine_ciudadanoDatos);
	$usuarioDatos=usuarioDatos('','',$id);
	$seccion_ine_ciudadanoDatos['usuario'] = $usuarioDatos['usuario'];
	$seccion_ine_ciudadanoDatos['password'] = $seccion_ine_ciudadanoDatos['password1'] = $usuarioDatos['password'];

	if($seccion_ine_ciudadanoDatos['longitud']==''){
		$longitud=$seccion_ine_ciudadanoDatos['longitud'];
		$latitud=$seccion_ine_ciudadanoDatos['latitud'];
	}
	$zoom="18";


	$permiso="Update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			link="seccionesIneCiudadanos/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load('seccionesIneCiudadanos/index.php?refresh=1');
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var espacios_invalidos= /\s+/g;
			var id = '<?= $id ?>'; 
			var id_usuario = '<?= $usuarioDatos['id'] ?>'; 
			if(id_usuario == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Usuario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var clave = '<?= $seccion_ine_ciudadanoDatos['clave'] ?>'; 
			if(clave == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var clave_elector = document.getElementById("clave_elector").value; 
			clave_electorx = clave_elector.replace(espacios_invalidos, ''); 
			if(clave_electorx == ""){
				document.getElementById("clave_elector").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave de Elector requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "initial";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var curp = document.getElementById("curp").value;
			curpx = curp.replace(espacios_invalidos, ''); 
			if(curpx == ""){
				document.getElementById("curp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("C.U.R.P requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "initial";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var rfc = document.getElementById("rfc").value;
			rfc = rfc.replace(espacios_invalidos, '');
			var ocr = document.getElementById("ocr").value; 
			ocrx = ocr.replace(espacios_invalidos, ''); 
			if(ocrx == ""){
				document.getElementById("ocr").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("OCR requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "initial";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}else{
				if(ocr.length >= 18 && ocr.length <= 21){
					document.getElementById("ocr").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("OCR valido de 11 a 13 digitos");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("divDatosCiudadano").style.display = "initial";
					document.getElementById("divDatosPersonales").style.display = "none";
					document.getElementById("divDatosContacto").style.display = "none";
					document.getElementById("divDatosDireccionActual").style.display = "none";
					document.getElementById("divDatosDireccionIne").style.display = "none";
					document.getElementById("divObservaciones").style.display = "none";
					return false;
				}
			}
			var id_seccion_ine_ciudadano_compartido = document.getElementById("id_seccion_ine_ciudadano_compartido").value;
			if(id_seccion_ine_ciudadano_compartido == ""){
				/*
				document.getElementById("id_seccion_ine_ciudadano_compartido").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
				*/
			}
			var status_verificacion = document.getElementById("status_verificacion").value;
			status_verificacionx = status_verificacion.replace(espacios_invalidos, '');
			if(status_verificacionx == ""){
				document.getElementById("status_verificacion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Verificacón requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "initial";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var id_tipo_ciudadano = document.getElementById("id_tipo_ciudadano").value;
			if(id_tipo_ciudadano == ""){
				document.getElementById("id_tipo_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "initial";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var nombre = document.getElementById("nombre").value;
			nombrex = nombre.replace(espacios_invalidos, '');
			if(nombrex == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "initial";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var apellido_paterno = document.getElementById("apellido_paterno").value;
			apellido_paternox = apellido_paterno.replace(espacios_invalidos, '');
			if(apellido_paternox == ""){
				document.getElementById("apellido_paterno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Paterno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "initial";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var apellido_materno = document.getElementById("apellido_materno").value;
			apellido_maternox = apellido_materno.replace(espacios_invalidos, '');
			if(apellido_maternox == ""){
				document.getElementById("apellido_materno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Materno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "initial";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var sexo = document.getElementById("sexo").value; 
			if(sexo == ""){
				document.getElementById("sexo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Sexo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "initial";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var fecha_nacimiento = document.getElementById("fecha_nacimiento").value;
			fecha_nacimientox = fecha_nacimiento.replace(espacios_invalidos, '');
			if(fecha_nacimientox == ""){
				document.getElementById("fecha_nacimiento").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Nacimiento Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "initial";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			} else{
				if(!fechaValida(fecha_nacimientox)){ 
					document.getElementById("fecha_nacimiento").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha Nacimiento Válida requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("divDatosCiudadano").style.display = "none";
					document.getElementById("divDatosPersonales").style.display = "initial";
					document.getElementById("divDatosContacto").style.display = "none";
					document.getElementById("divDatosDireccionActual").style.display = "none";
					document.getElementById("divDatosDireccionIne").style.display = "none";
					document.getElementById("divObservaciones").style.display = "none";
					return false;
				}
			}
			var usuario = document.getElementById("usuario").value;
			usuario = usuario.replace(espacios_invalidos, ''); 
			if(usuario == ""){
				document.getElementById("usuario").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Usuario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var password = document.getElementById("password").value; 
			password = password.replace(espacios_invalidos, ''); 
			if(password == ""){
				document.getElementById("password").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Constraseña requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var password1 = document.getElementById("password1").value;
			password1 = password1.replace(espacios_invalidos, ''); 
			if(password1 == ""){
				document.getElementById("password1").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Constraseña requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if(password != password1){
				document.getElementById("password1").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Constraseña No Coinciden requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Status requerido");
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
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "initial";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}else{
				if(!validarEmail(correo_electronico)){
					document.getElementById("correo_electronico").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Correo Electronico Válido requerido");
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
			var whatsapp = document.getElementById("whatsapp").value;
			whatsapp = whatsapp.replace(espacios_invalidos, '');  
			if(whatsapp == ""){
				document.getElementById("whatsapp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Whatsapp requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "initial";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}else{
				//validadamos si es numero
				if(isNaN(whatsapp)){
					document.getElementById("whatsapp").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Whatsapp valido");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("divDatosCiudadano").style.display = "none";
					document.getElementById("divDatosPersonales").style.display = "none";
					document.getElementById("divDatosContacto").style.display = "initial";
					document.getElementById("divDatosDireccionActual").style.display = "none";
					document.getElementById("divDatosDireccionIne").style.display = "none";
					document.getElementById("divObservaciones").style.display = "none";
					return false;
				}else{
					if(whatsapp.length != '10' ){
						document.getElementById("whatsapp").focus(); 
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html("Whatsapp valido de 10 digitos");
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
			var telefono = document.getElementById("telefono").value;
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
			celular = celular.replace(espacios_invalidos, '');
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
			/*
			if(celular == ""){
				document.getElementById("celular").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Celular requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			*/
			var id_pais = document.getElementById("id_pais").value; 
			if(id_pais == ""){
				document.getElementById("id_pais").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Pais requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			//alert(codigo_postal);
			var id_estado = document.getElementById("id_estado").value; 
			if(id_estado == ""){
				document.getElementById("id_estado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			//alert(id_estado);
			var id_municipio = document.getElementById("id_municipio").value; 
			if(id_municipio == ""){
				document.getElementById("id_municipio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Municipio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "initial";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			//alert(id_municipio);
			var id_localidad = document.getElementById("id_localidad").value; 
			if(id_localidad == ""){
				document.getElementById("id_localidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Localidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "initial";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var calle = document.getElementById("calle").value;
			callex = calle.replace(espacios_invalidos, '');
			if(callex == ""){
				document.getElementById("calle").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Calle requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "initial";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var num_ext = document.getElementById("num_ext").value;
			//num_ext = num_ext.replace(espacios_invalidos, '');
			var num_int = document.getElementById("num_int").value;
			//num_int = num_int.replace(espacios_invalidos, '');
			//alert(calle);
			var colonia = document.getElementById("colonia").value; 
			coloniax = colonia.replace(espacios_invalidos, '');
			if(coloniax == ""){
				document.getElementById("colonia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Colonia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "initial";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var codigo_postal = document.getElementById("codigo_postal").value;
			codigo_postal = codigo_postal.replace(espacios_invalidos, '');
			if(codigo_postal == ""){
				document.getElementById("codigo_postal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Codigo Postal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "initial";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var latitud = document.getElementById("latitud").value;
			latitud = latitud.replace(espacios_invalidos, '');
			if(latitud == ""){
				document.getElementById("latitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Latitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "initial";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var longitud = document.getElementById("longitud").value;
			longitud = longitud.replace(espacios_invalidos, '');
			if(longitud == ""){
				document.getElementById("longitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Longitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "initial";
				document.getElementById("divDatosDireccionIne").style.display = "none";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var longitud_r = document.getElementById("longitud_r").value;
			var latitud_r = document.getElementById("latitud_r").value;
			var id_seccion_ine = document.getElementById("id_seccion_ine").value;
			if(id_seccion_ine == ""){
				document.getElementById("id_seccion_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Sección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var manzana = document.getElementById("manzana").value;
			manzanax = manzana.replace(espacios_invalidos, '');
			if(manzanax == ""){
				document.getElementById("manzana").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Manzana requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var vigencia = document.getElementById("vigencia").value;
			vigencia = vigencia.replace(espacios_invalidos, '');
			if(vigencia != ""){
				if(vigencia.length != 4){
					document.getElementById("vigencia").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Vigencia válida2");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("divDatosCiudadano").style.display = "none";
					document.getElementById("divDatosPersonales").style.display = "none";
					document.getElementById("divDatosContacto").style.display = "none";
					document.getElementById("divDatosDireccionActual").style.display = "none";
					document.getElementById("divDatosDireccionIne").style.display = "initial";
					document.getElementById("divObservaciones").style.display = "none";
					return false;
				}
				vigencia = parseInt(vigencia);
				if(Number.isInteger(vigencia)==false){
					document.getElementById("vigencia").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Vigencia válida1");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("divDatosCiudadano").style.display = "none";
					document.getElementById("divDatosPersonales").style.display = "none";
					document.getElementById("divDatosContacto").style.display = "none";
					document.getElementById("divDatosDireccionActual").style.display = "none";
					document.getElementById("divDatosDireccionIne").style.display = "initial";
					document.getElementById("divObservaciones").style.display = "none";
					return false;
				}
			}else{
				document.getElementById("vigencia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Vigencia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}

			var id_municipio_ine = document.getElementById("id_municipio_ine").value; 
			if(id_municipio_ine == ""){
				document.getElementById("id_municipio_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Municipio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			//alert(id_municipio);
			var id_localidad_ine = document.getElementById("id_localidad_ine").value; 
			if(id_localidad_ine == ""){
				document.getElementById("id_localidad_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Localidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var calle_ine = document.getElementById("calle_ine").value;
			calle_inex = calle_ine.replace(espacios_invalidos, '');
			if(calle_inex == ""){
				document.getElementById("calle_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Calle requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var num_ext_ine = document.getElementById("num_ext_ine").value;
			//num_ext_ine = num_ext_ine.replace(espacios_invalidos, '');
			var num_int_ine = document.getElementById("num_int_ine").value;
			//num_int_ine = num_int_ine.replace(espacios_invalidos, '');
			//alert(calle);
			var colonia_ine = document.getElementById("colonia_ine").value;
			colonia_inex = colonia_ine.replace(espacios_invalidos, '');
			if(colonia_inex == ""){
				document.getElementById("colonia_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Colonia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var codigo_postal_ine = document.getElementById("codigo_postal_ine").value;
			codigo_postal_ine = codigo_postal_ine.replace(espacios_invalidos, '');
			if(codigo_postal_ine == ""){
				document.getElementById("codigo_postal_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Codigo Postal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("mensaje").classList.add("mensajeError");
				document.getElementById("divDatosCiudadano").style.display = "none";
				document.getElementById("divDatosPersonales").style.display = "none";
				document.getElementById("divDatosContacto").style.display = "none";
				document.getElementById("divDatosDireccionActual").style.display = "none";
				document.getElementById("divDatosDireccionIne").style.display = "initial";
				document.getElementById("divObservaciones").style.display = "none";
				return false;
			}
			var observaciones = document.getElementById("observaciones").value;
			//observaciones = observaciones.replace(espacios_invalidos, '');

			var seccion_ine_ciudadano = []; 
			var data = {    
					'clave' : clave,
					'id' : id,
					'id_seccion_ine' : id_seccion_ine,
					'manzana' : manzana,
					'vigencia' : vigencia,
					'id_tipo_ciudadano' : id_tipo_ciudadano,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'sexo' : sexo,
					'fecha_nacimiento' : fecha_nacimiento,
					'correo_electronico' :correo_electronico,
					'whatsapp' : whatsapp,
					'telefono' : telefono,
					'celular' : celular,
					'observaciones' : observaciones,
					'clave_elector' : clave_elector,
					'curp' : curp,
					'rfc' : rfc,
					'ocr' : ocr,

					'id_pais' : id_pais,
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'calle' : calle,
					'colonia' : colonia, 
					'codigo_postal' : codigo_postal,
					'latitud' : latitud,
					'longitud' : longitud,
					'latitud_r' : latitud_r,
					'longitud_r' : longitud_r,

					'num_int' : num_int,
					'num_ext' : num_ext,

					'id_municipio_ine' : id_municipio_ine,
					'id_localidad_ine' : id_localidad_ine,
					'calle_ine' : calle_ine,
					'num_int_ine' : num_int_ine,
					'num_ext_ine' : num_ext_ine,
					'colonia_ine' : colonia_ine, 
					'codigo_postal_ine' : codigo_postal_ine,
					'id_seccion_ine_ciudadano_compartido' : id_seccion_ine_ciudadano_compartido
				}
			seccion_ine_ciudadano.push(data);
			var usuarios = [];
			var data = { 
					'id' : id_usuario,
					'usuario' : usuario,
					'password' : password,
					'status' : status,
				}
			usuarios.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/db_edit.php",
				data: {seccion_ine_ciudadano: seccion_ine_ciudadano,usuarios: usuarios},
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
						link="seccionesIneCiudadanos/index.php";
						dataString = 'urlink='+link; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load('seccionesIneCiudadanos/index.php');
					}else{
						if(data==""){
							link="seccionesIneCiudadanos/index.php";
							dataString = 'urlink='+link; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load('seccionesIneCiudadanos/index.php');
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
					<font style="font-size: 25px;">Modificar Amigo</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a amigo.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>