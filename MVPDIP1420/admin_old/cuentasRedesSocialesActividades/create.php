<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/cuentas_redes_sociales.php";
	include __DIR__."/../functions/redes_sociales.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/tipos_actividades.php";
	include __DIR__."/../functions/timemex.php";

	@session_start();
	$_SESSION['Paguinasub']="cuentasRedesSocialesActividades/create.php";  
	$permiso="insert"; 

	
	if($_SESSION['reset']!="x"){
		$id_cuenta_red_social = $_SESSION['id_cuenta_red_social']; 
		if($id_cuenta_red_social!=""){
			echo $redirectSecurity=redirectSecurity($id_cuenta_red_social,'cuentas_redes_sociales','cuentasRedesSociales','index');
			if($redirectSecurity!=""){
				die;
			}
		}else{
			echo $redirectSecurity=redirectSecurity($id_cuenta_red_social,'cuentas_redes_sociales','cuentasRedesSociales','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}
	

	if($_SESSION['reset']!="x"){
		$disbale_id_pricipal='disabled="disabled"';
	}
	$id_cuenta_red_social;
	$cuenta_red_social_actividadDatos['ip'] = $_SERVER['REMOTE_ADDR'];
	$cuenta_red_social_actividadDatos['user_agent'] = $_SERVER['HTTP_USER_AGENT'];


	$cuenta_red_socialDatos = cuenta_red_socialDatos($id_cuenta_red_social);


	$cuenta_red_social_actividadDatos['id_identidad'] = $cuenta_red_socialDatos['id_identidad'];
	$cuenta_red_social_actividadDatos['id_red_social'] = $cuenta_red_socialDatos['id_red_social'];

	$cuenta_red_social_actividadDatos['fecha_emision'] = $fechaSF;
	$cuenta_red_social_actividadDatos['hora_emision'] = $fechaSH;
	$cuenta_red_social_actividadDatos['mac_address'] = $_COOKIE['mac_address'];

	include __DIR__."/../functions/claves.php";
	$claveF= clave('cuentas_redes_sociales_actividades');
	$cuenta_red_social_actividadDatos['clave']=$claveF['clave'];

	//var_dump($cuenta_red_social_actividadDatos);

?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('cuentasRedesSocialesActividades/index.php');
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var id_cuenta_red_social = '<?= $id_cuenta_red_social ?>'; 
			if(id_cuenta_red_social == ""){
				document.getElementById("id_cuenta_red_social").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerido");
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

			var id_red_social = document.getElementById("id_red_social").value; 
			if(id_red_social == ""){
				document.getElementById("id_red_social").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_emision = document.getElementById("fecha_emision").value; 
			if(fecha_emision == ""){
				document.getElementById("fecha_emision").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Emisión requerido");
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

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_tipo_actividad = document.getElementById("id_tipo_actividad").value; 
			if(id_tipo_actividad == ""){
				document.getElementById("id_tipo_actividad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var url = document.getElementById("url").value; 
			if(url == ""){
				document.getElementById("url").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("URL requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var detalle = document.getElementById("detalle").value; 
			if(detalle == ""){
				document.getElementById("detalle").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Detalle requerido");
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

			var latitud_script = document.getElementById("latitud_script").value; 
			if(latitud_script == ""){
				document.getElementById("latitud_script").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Latitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var longitud_script = document.getElementById("longitud_script").value; 
			if(longitud_script == ""){
				document.getElementById("longitud_script").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Longitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var precision_script = document.getElementById("precision_script").value; 
			if(precision_script == ""){
				document.getElementById("precision_script").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Precision requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var loc_script = document.getElementById("loc_script").value; 
			if(loc_script == ""){
				document.getElementById("loc_script").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Location requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var cuenta_red_social_actividad = []; 
			var data = {    
					'id_cuenta_red_social' : id_cuenta_red_social,
					'id_identidad' : id_identidad,
					'id_red_social' : id_red_social,
					'hora_emision' : hora_emision,
					'fecha_emision' : fecha_emision,
					'clave' : clave,
					'id_tipo_actividad' : id_tipo_actividad,
					'url' : url,
					'detalle' : detalle,
					'user_agent' : user_agent,
					'mac_address' : mac_address,
					'ip' : ip,
					'latitud_script' : latitud_script,
					'longitud_script' : longitud_script,
					'precision_script' : precision_script,
					'loc_script' : loc_script,
				}
			cuenta_red_social_actividad.push(data);


			$.ajax({
				type: "POST",
				url: "cuentasRedesSocialesActividades/db_add.php",
				data: {cuenta_red_social_actividad: cuenta_red_social_actividad},
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
						$("#homebody").load('cuentasRedesSocialesActividades//index.php');
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
					<font style="font-size: 25px;">Crear Actividad Red Social</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a actividad red social.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>