<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php"; 
	include __DIR__."/../functions/cuentas_redes_sociales_actividades.php";
	include __DIR__."/../functions/redes_sociales.php";
	include __DIR__."/../functions/tipos_actividades.php";
	include __DIR__."/../functions/identidades.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id),time()+(60*60*24*650),"/",false);
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	$id;
	echo $redirectSecurity=redirectSecurity($id,'cuentas_redes_sociales_actividades','cuentasRedesSocialesActividades','index');
	if($redirectSecurity!=""){
		die;
	}
	$id_cuenta_red_social = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_cuenta_red_social!=""){
		$disbale_id_pricipal='disabled="disabled"';
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


	$claveF= clave('cuentas_redes_sociales_actividades');
	$cuenta_red_social_actividadDatos=cuenta_red_social_actividadDatos($id);
	if($cuenta_red_social_actividadDatos['clave']==""){
		$cuenta_red_social_actividadDatos['clave']=$claveF['clave'];
	}


	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="cuentasRedesSocialesActividades/index.php";
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
			
			var id = '<?= $id ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

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

			var cuenta_red_social_actividad = []; 
			var data = {    
					'id' : id,
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
				}
			cuenta_red_social_actividad.push(data);

			$.ajax({
				type: "POST",
				url: "cuentasRedesSocialesActividades/db_edit.php",
				data: {cuenta_red_social_actividad: cuenta_red_social_actividad},
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
						urlink="cuentasRedesSocialesActividades/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						if(data==""){
							urlink="cuentasRedesSocialesActividades/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
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
					<font style="font-size: 25px;">Modificar Actividad Red Social</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a actividad red social.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>