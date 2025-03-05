<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_seccion_ine_ciudadanox!=""){
		$id_seccion_ine_ciudadanox;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadanox);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadanox,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$permiso='insert';
	$seccion_ine_ciudadano_seguimientoDatos['fecha'] = date("Y-m-d");
	$seccion_ine_ciudadano_seguimientoDatos['hora'] = date("H:i:s");
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			link="seccionesIneCiudadanosSeguimientos/index.php?cot=<?= $id_seccion_ine_ciudadano_x ?>"; 
			var link2="seccionesIneCiudadanosSeguimientos/index.php";
			dataString = 'urlink='+link2+'&id=<?= $id_seccion_ine_ciudadano_x ?>';  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link);
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var id_seccion_ine_ciudadano = '<?= $id_seccion_ine_ciudadano_x ?>'; 
			if(id_seccion_ine_ciudadano == ""){
				document.getElementById("id_seccion_ine_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_seccion_ine = '<?= $id_seccion_ine ?>'; 
			if(id_seccion_ine == ""){
				document.getElementById("id_seccion_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar la sección en el sistema requerido");
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

			var asunto = document.getElementById("asunto").value;
			if(asunto == ""){
				document.getElementById("asunto").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Asunto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			

			var observaciones = document.getElementById("observaciones").value;
			if(observaciones == ""){
				document.getElementById("observaciones").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Observaciones requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var longitud = document.getElementById("longitud").value;
			var latitud = document.getElementById("latitud").value;

			var longitud_r = document.getElementById("longitud_r").value;
			var latitud_r = document.getElementById("latitud_r").value;

			

			var seccion_ine_ciudadano_seguimiento = []; 
			var data = {    
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
					'id_seccion_ine' : id_seccion_ine,
					'fecha' : fecha,
					'hora' : hora,
					'observaciones' : observaciones,
					'latitud' : latitud,
					'longitud' : longitud,
					'latitud_r' : latitud_r,
					'longitud_r' : longitud_r,
					'asunto' : asunto,
				}
			seccion_ine_ciudadano_seguimiento.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosSeguimientos/db_add.php",
				data: {seccion_ine_ciudadano_seguimiento: seccion_ine_ciudadano_seguimiento},
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
						link="seccionesIneCiudadanosSeguimientos/index.php?cot=<?= $id_seccion_ine_ciudadano_x ?>"; 
						var link2="seccionesIneCiudadanosSeguimientos/index.php";
						dataString = 'urlink='+link2+'&id=<?= $id_seccion_ine_ciudadano_x ?>';  
						dataString = 'urlink='+link2;
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) {}
						});
						//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
						$("#homebody").load(link);
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
					<font style="font-size: 25px;">Crear Seguimiento</font>
				</label><br> 
				<h2><?= $seccion_ine_ciudadanoDatos['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a seguimiento.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>