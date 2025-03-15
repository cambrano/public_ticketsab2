<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/cuentas_redes_sociales.php";
	include __DIR__."/../functions/redes_sociales.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/correos_electronicos.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/timemex.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$permiso="insert"; 
	$id_identidad = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_identidad!=""){
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
		$disbale_id_pricipal='disabled="disabled"';
	}


	$cuenta_red_socialDatos['id_identidad'] = $id_identidad;
 
	$cuenta_red_socialDatos['ip'] = $_SERVER['REMOTE_ADDR'];
	$cuenta_red_socialDatos['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

	$cuenta_red_socialDatos['fecha_emision'] = $fechaSF;
	$cuenta_red_socialDatos['hora_emision'] = $fechaSH;
	$cuenta_red_socialDatos['mac_address'] = $_COOKIE['mac_address'];

	if($id_identidad!=""){
		$identidadDatos = identidadDatos($id_identidad);
		$identidadDatos['estado'] = estadoNombre($identidadDatos['id_estado']);
		$identidadDatos['municipio'] = municipioNombre($identidadDatos['id_municipio']);
		$identidadDatos['localidad'] = localidadNombre($identidadDatos['id_localidad']);

		$longitud = $identidadDatos['longitud'];
		$latitud = $identidadDatos['latitud'];
		$position = true;
	}

	$claveF= clave('cuentas_redes_sociales');
	$cuenta_red_socialDatos['clave']=$claveF['clave'];

?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="cuentasRedesSociales/index.php";
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

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_identidad = document.getElementById("id_identidad").value; 
			if(id_identidad == ""){
				document.getElementById("id_identidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_emision = document.getElementById("fecha_emision").value; 
			if(fecha_emision == ""){
				document.getElementById("fecha_emision").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Emision requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var hora_emision = document.getElementById("hora_emision").value; 
			if(hora_emision == ""){
				document.getElementById("hora_emision").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Hora Emision requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_red_social = document.getElementById("id_red_social").value; 
			if(id_red_social == ""){
				document.getElementById("id_red_social").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Red Social requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var usuario = document.getElementById("usuario").value; 
			if(usuario == ""){
				document.getElementById("usuario").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Usuario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var password = document.getElementById("password").value; 
			if(password == ""){
				document.getElementById("password").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Password requerido");
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
				$("#mensaje").html("Correo Eléctronico requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var url = document.getElementById("url").value; 
			if(url == ""){
				document.getElementById("url").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("LINK requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var verificado = document.getElementById("verificado").value; 
			if(verificado == ""){
				document.getElementById("verificado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Verificado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var user_agent = document.getElementById("user_agent").value; 
			if(user_agent == ""){
				document.getElementById("user_agent").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("User Agent requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var mac_address = document.getElementById("mac_address").value; 
			if(mac_address == ""){
				document.getElementById("mac_address").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("MAC ADDRESS requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var ip = document.getElementById("ip").value; 
			if(ip == ""){
				document.getElementById("ip").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("IP requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var latitud_script = document.getElementById("latitud_script1").value;
			if(latitud_script == ""){
				document.getElementById("latitud_script1").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Latitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var longitud_script = document.getElementById("longitud_script1").value; 
			if(longitud_script == ""){
				document.getElementById("longitud_script1").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Longitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var precision_script = document.getElementById("precision_script1").value; 
			if(precision_script == ""){
				document.getElementById("precision_script").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Precision requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var loc_script = document.getElementById("loc_script1").value; 
			if(loc_script == ""){
				document.getElementById("loc_script").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Location requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var cuenta_red_social = []; 
			var data = {
					'clave' : clave,
					'id_identidad' : id_identidad,
					'hora_emision' : hora_emision,
					'fecha_emision' : fecha_emision,
					'id_red_social' : id_red_social,
					'usuario' : usuario,
					'password' : password,
					'telefono' : telefono,
					'correo_electronico' : correo_electronico,
					'url' : url,
					'verificado' : verificado,
					'user_agent' : user_agent,
					'mac_address' : mac_address,
					'ip' : ip,
					'latitud_script' : latitud_script,
					'longitud_script' : longitud_script,
					'precision_script' : precision_script,
					'loc_script' : loc_script,
				}
			cuenta_red_social.push(data);


			$.ajax({
				type: "POST",
				url: "cuentasRedesSociales/db_add.php",
				data: {cuenta_red_social: cuenta_red_social},
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
						urlink="cuentasRedesSociales/index.php";
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
	<script type="text/javascript">
		localize();
		function localize(){
			if(navigator.geolocation){
				navigator.geolocation.getCurrentPosition(mapa,error);
			}
		}
		function mapa(pos) {
			/************************ Aqui están las variables que te interesan***********************************/
			//$("#mensaje").html('x');
			var latitud = pos.coords.latitude;
			var longitud = pos.coords.longitude;
			var precision = pos.coords.accuracy;
			var loc = latitud+','+longitud; 
			var location = []; 
			var data = {
					'latitud_script' : latitud,
					'longitud_script' : longitud,
					'precision_script' : precision,
					'loc_script' : loc, 
				}
			location.push(data);
			document.getElementById("latitud_script").value = latitud;
			document.getElementById("longitud_script").value = longitud;
			document.getElementById("precision_script").value = precision;
			document.getElementById("loc_script").value = loc;
		}
		function error(errorCode){
			if(errorCode.code == 1){
				//alert("Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else if (errorCode.code==2){
				//alert("Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Posicion no disponible,Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
			else{
				//alert("Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.");
				document.getElementById("mensaje").innerHTML = "Ha ocurrido un error,Debes activar tu geolocation para poder trabajar mejor con usted.";
				document.getElementById("mensaje").style.borderBottom= "1px solid red";
				document.getElementById("mensaje").style.color = "Red";
			}
		}
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Crear Cuentas Red Social</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a cuenta red social.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>